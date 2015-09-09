<?php

abstract Class smintTools {
	public static function implode_with_key($assoc, $inglue = '=', $outglue = '&') {
		$return = null;
		foreach ($assoc as $tk => $tv)
			$return .= $outglue . $tk . $inglue . $tv;
		return substr($return, strlen($outglue));
	}

	public static function generateHtmlId($inputString) {
		$result = ereg_replace("[^A-Za-z0-9]", "", $inputString);
		return $result;
	}

	/** converts a time given in milliseconds to a formatted string like
	 * mm.ss.hh
	 * where
	 * mm = minutes
	 * ss = seconds
	 * hh = hundredths
	 * Based on http://snippets.aktagon.com/snippets/122-How-to-format-number-of-seconds-as-duration-with-PHP
	 * @param $ms in ms
	 * @param $delimiter the Delimiter. Default = .
	 */
	public static function convertTimeMsToMM_SS_HH($ms, $delimiter = '.') {

		$hundredths = intval(($ms % 1000) / 10);
		$seconds = floor(($ms / 1000) % 60);
		$minutes = floor($ms / (1000 * 60));
		$hours = floor($ms / (1000 * 60 * 60));

		$hundredths = str_pad($hundredths, 2, "0", STR_PAD_LEFT);
		$seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT) . $delimiter;
		$minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT) . $delimiter;

		if ($hours > 0) {
			$hours = str_pad($hours, 2, "0", STR_PAD_LEFT) . $delimiter;
		} else {
			$hours = '';
		}

		return "$hours$minutes$seconds$hundredths";

	}

	/** checks if a string starts with a specified string */
	public static function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	/** checks if a string ends with a specified string */
	public static function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

	/** once you have extracted the basename from the full path
	 *  and want to separate the extension from the file name,
	 *  the following function will do it efficiently: */
	public static function splitFilename($filename) {
		$pos = strrpos($filename, '.');
		if ($pos === false) {// dot is not found in the filename
			return array($filename, '');
			// no extension
		} else {
			$basename = substr($filename, 0, $pos);
			$extension = substr($filename, $pos + 1);
			return array($basename, $extension);
		}
	}
	
	
	public static function getExtension($filename) {
		$a = self::splitFilename($filename);
		return $a[1];
	}
	

	/**
	 * converts the the PHP options array into a javscript array
	 * enhanced by EP: can deal with strings
	 *
	 * @param array
	 * @return string javascript arry equivalent
	 */
	public static function my_options_for_javascript($options) {
		$opts = array();
		foreach ($options as $key => $value) {
			if (is_array($value)) {
				$opts[] = "\n" . $key . ":" . "\n" . self::my_options_for_javascript($value) . "\n";
			} else {
				$opts[] = "\n" . $key . ":" . self::my_array_or_string_for_javascript($value);
				// should escape ''
			}
		}
		sort($opts);

		return '{' . join(', ', $opts) . '}';
	}

	/**
	 * converts the given PHP array or string to the corresponding javascript array or string.
	 * single quotes are escaped.
	 *
	 * @param option (typically from option array)
	 * @return string javascript string or array equivalent
	 */
	public static function my_array_or_string_for_javascript($option) {
		if (is_array($option)) {
			return "['" . join('\',\'', $option) . "']";
		} else if (is_string($option)) {
			if (strlen($option) == 0) {
				return "''";
			} else if ($option[0] != "'") {
				return "'" . preg_replace("/\r?\n/", "\\n", addslashes($option)) . "'";
			}
			// is_string
		}
		return $option;
	}

	// from http://snippets.jc21.com/snippets/php/truncate-a-long-string-and-add-ellipsis/
	/** truncates a string to a certain char length, stopping on a word if not specified otherwise.
	 */
	public static function truncate_for_output($string, $length, $stopanywhere = false) {
		if (strlen($string) > $length) {
			//limit hit!
			$string = substr($string, 0, ($length - 3));
			if ($stopanywhere) {
				//stop anywhere
				$string .= '...';
			} else {
				//stop on a word.
				$string = substr($string, 0, strrpos($string, ' ')) . '...';
			}
		}
		return $string;
	}

	////////////// drawing functions ///////////////////////
	/**
	 * findValues
	 */
	public static function findValues($byte1, $byte2) {
		$byte1 = hexdec(bin2hex($byte1));
		$byte2 = hexdec(bin2hex($byte2));
		return ($byte1 + ($byte2 * 256));
	}

	/**
	 * html2rgb
	 *
	 * Great function slightly modified as posted by Minux at
	 * http://forums.clantemplates.com/showthread.php?t=133805
	 */
	public static function html2rgb($input) {
		$input = ($input[0] == "#") ? substr($input, 1, 6) : substr($input, 0, 6);
		return array(hexdec(substr($input, 0, 2)), hexdec(substr($input, 2, 2)), hexdec(substr($input, 4, 2)));
	}

	/**
	 * Helper function for system execution.
	 * Performs some replacemens:
	 * - $SMAFEHOME
	 * Logs and throws exception if return value != 0
	 * DOES NOT WORK
	 */
	public static function mySmafeExec($cmd) {
		return false;

		/*
		 $smafehome = mysfConfig::get('app_live_settings_smafehome', "(app_live_settings_smafehome not defined!)");
		 // replace placeholder with smafe path
		 $exec_cmd = str_replace('$SMAFEHOME', $smafehome, $cmd);
		 $exec_cmd = str_replace('a', 'asdfasdfasdf', $exec_cmd);

		 // executing, redirecting stderr to stdout
		 exec($exec_cmd . " 2>&1", $exec_err, $exec_result);

		 if ($exec_result != 0) {
		 $result_array = array("command" => $exec_cmd, "result" => $exec_result, "stdout+stderr" => $exec_err);
		 $error_message = "ERROR when executing $exec_cmd:  " . print_r($result_array, true);
		 mysfLog::log(self::$context, $error_message);
		 throw new Exception($error_message);
		 }
		 *
		 */
	}

}
