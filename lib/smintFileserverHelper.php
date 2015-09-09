<?php

abstract Class smintFileserverHelper {

	private static $context = "smintFileserverHelper";

	/**
	 * This function serves the given file of the given contenttype and obeys the range header.
	 * Side effects: all http headers are cleared from $response
	 * Based on
	 */
	public static function serve_file_resumable($response, $file, $contenttype = 'application/octet-stream') {

		// Avoid sending unexpected errors to the client - we should be serving a file,
		// we don't want to corrupt the data we send
		@error_reporting(0);

		// Make sure the files exists, otherwise we are wasting our time
		if (!file_exists($file)) {
			header("HTTP/1.1 404 Not Found");
			exit ;
		}

		// Get the 'Range' header if one was sent
		if (isset($_SERVER['HTTP_RANGE']))
			$range = $_SERVER['HTTP_RANGE'];
		// IIS/Some Apache versions
		else if ($apache = apache_request_headers()) {// Try Apache again
			$headers = array();
			foreach ($apache as $header => $val)
				$headers[strtolower($header)] = $val;
			if (isset($headers['range']))
				$range = $headers['range'];
			else
				$range = FALSE;
			// We can't get the header/there isn't one set
		} else
			$range = FALSE;
		// We can't get the header/there isn't one set

		// Get the data range requested (if any)
		$filesize = filesize($file);
		if ($range) {
			$partial = true;
			list($param, $range) = explode('=', $range);
			if (strtolower(trim($param)) != 'bytes') {// Bad request - range unit is not 'bytes'
				header("HTTP/1.1 400 Invalid Request");
				exit ;
			}
			$range = explode(',', $range);
			$range = explode('-', $range[0]);
			// We only deal with the first requested range
			if (count($range) != 2) {// Bad request - 'bytes' parameter is not valid
				header("HTTP/1.1 400 Invalid Request");
				exit ;
			}
			if ($range[0] === '') {// First number missing, return last $range[1] bytes
				$end = $filesize - 1;
				$start = $end - intval($range[0]);
			} else if ($range[1] === '') {// Second number missing, return from byte $range[0] to end
				$start = intval($range[0]);
				$end = $filesize - 1;
			} else {// Both numbers present, return specific range
				$start = intval($range[0]);
				$end = intval($range[1]);
				if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1))))
					$partial = false;
				// Invalid range/whole file specified, return whole file
			}
			$length = $end - $start;
			mysfLog::log(self::$context, "INFO: Fileserver: partial: $partial, start: $start, end: $end, length: $length");
		} else
			$partial = false;
			
		
		// No range requested

		// Send standard headers
		$response -> clearHttpHeaders();
		$response -> setContentType($contenttype);
		$response -> setHttpHeader("Content-Length", $filesize);
		$response -> setHttpHeader("Content-Disposition", "attachment; filename=" . urlencode(basename($file)) . "");
		$response -> setHttpHeader("Accept-Ranges", "bytes");
		$response -> setHttpHeader("Access-Control-Allow-Origin", "*");

		// if requested, send extra headers and part of file...
		if ($partial) {
			$response -> setStatusCode(206);
			$response -> setHttpHeader("Content-Range", "bytes $start-$end/$filesize");
			// check for full file (0-)
			if ($start == 0 && ($end = $filesize - 1)) {
				// the player often requests "0-", so basically the whole file. 
				// In this case we use readfile which is much faster than print(fread)
				mysfLog::log(self::$context, "DEBUG: About to send whole file (0- shortcut)");
				$response -> sendHttpHeaders();
				mysfLog::log(self::$context, "DEBUG: Headers sent (0- shortcut)");
				readfile($file);
				mysfLog::log(self::$context, "DEBUG: Sent whole file (0- shortcut)");
			} else {
				
				//method 1 (copied from net)
					mysfLog::log(self::$context, "DEBUG: Sending partial file with fread");
				if (!$fp = fopen($file, 'r')) {// Error out if we can't read the file
					$response -> setStatusCode(500);
					exit ;
				}
				if ($start)
					fseek($fp, $start);
				
				$response -> sendHttpHeaders();
				
				while ($length) {// Read in blocks of 8KB so we don't chew up memory on the server
					$read = ($length > 8192) ? 8192 : $length;
					$length -= $read;

					print(fread($fp, $read));
					mysfLog::log(self::$context, "DEBUG: bytes sent: $read");
				}
				fclose($fp);
				 
				// method 2, using file_get_contents
				//print(file_get_contents($file, NULL, NULL, $start, $length));
			}

		} else {
			$response -> sendHttpHeaders();
			readfile($file);
			// ...otherwise just send the whole file
		}

		// Exit here to avoid accidentally sending extra content on the end of the file
		//exit;
	}

	/**
	 * Performs the file conversion, ie, calls external tool, checks if it worked correctly, and returns the filename of the output file
	 * Note that the output files can be deleted safely. On the other hand this function also checks if the file has already been converted.
	 * If so, it will not be converted a second time.
	 * @param filename the filename including path (local)
	 * @param formatcode Currently these codes are supported:
	 * 				- ogg
	 * 				- mp3
	 * 				- waveform
	 *				- waveformpng 					
	 *
	 *
	 * 			To add another type:
	 * 				- Add command_XXX in app.yml. The command must follow the same conventions as command_mp3 and _ogg ($INfile, ...)
	 * 				- XXX is also used as the file suffix for the output file
	 */
	public static function doFileConversion($filename, $formatcode = "ogg") {

		$exec_output = array();
		$exec_err = array();

		$current_date = date("_Ymd_His_");
		// get command as configured in app.yml
		$exec_cmd_raw = mysfConfig::get('app_conversion_setting_command_' . $formatcode, "");
		// replace placeholder with real file name, infile
		$exec_cmd = str_replace('$INfile', escapeshellcmd($filename), $exec_cmd_raw);
		// replace outfile placeholder. outfile is : upload dir, basename of infile, and ogg suffix
		if ($formatcode === 'waveform') {
			// extra file ending for waveform, since waveform is not recognized by apache 
			$outfile = smintUploadFileHelper::getUploadPath() . basename($filename) . "." . $formatcode . ".txt";
		} else {
			$outfile = smintUploadFileHelper::getUploadPath() . basename($filename) . "." . $formatcode;	
		}
		$exec_cmd = str_replace('$OUTfile', escapeshellcmd($outfile), $exec_cmd);
		// check for pixels per second variable. If found, replace it with config param
		if (substr_count($exec_cmd, '$PIXELSPERSECOND') > 0) {
			$pixelspersecond = sfConfig::get('app_defaults_waveform_resolution');
			$exec_cmd = str_replace('$PIXELSPERSECOND', escapeshellcmd($pixelspersecond), $exec_cmd);
		}

		$exec_cmd_escaped = ($exec_cmd);

		// before conversion check if file exists already
		if (file_exists($outfile)) {
			mysfLog::log(self::$context, "INFO: file $outfile exists, using it.");
		} else {
			mysfLog::log(self::$context, "INFO: starting conversion. Command = $exec_cmd_escaped");

			// executing, redirecting stderr to stdout
			exec($exec_cmd_escaped . " 2>&1", $exec_err, $exec_result);

			if ($exec_result != 0 || !file_exists($outfile)) {
				$result_array = array("command" => $exec_cmd_escaped, "result" => $exec_result, "error" => $exec_err);
				$error_message = "ERROR during conversion. " . print_r($result_array, true);
				mysfLog::log(self::$context, $error_message);
				throw new Exception($error_message);
			}

			mysfLog::log(self::$context, "INFO: conversion done");
		}

		return $outfile;
	}

}
