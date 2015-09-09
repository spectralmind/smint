<?php

abstract Class smintUploadFileHelper {

	public static function saveFile($file) {
		$fileInfo = self::fileLog($file);
		$file -> save($fileInfo['filesavepath']);
		return $fileInfo;
	}

	public static function appendFileLog(&$fp, $file) {
		sfContext::getInstance() -> getConfiguration() -> loadHelpers('Url');

		$fileInfo = array();
		$fileInfo['fileID'] = self::nextFileLogId($fp);

		$fileInfo['originalFilename'] = $file -> getOriginalName();

		$filenameHash = sha1($file -> getOriginalName());
		$filenameWithId = $fileInfo['fileID'] . "_" . $filenameHash;
		//  -> depending on the apache/php version detection does not always work    $fileInfo['filename'] = $filenameWithId . $file->getExtension($file->getOriginalExtension("mp3"));
		// quick hack 4 demo
		//$fileInfo['filename'] = $filenameWithId . ".mp3";
		$fileInfo['filename'] = $filenameWithId . $file->getOriginalExtension("mp3");

		$fileInfo['filesavepath'] = self::getUploadPath() . $fileInfo['filename'];

		$user = sfContext::getInstance() -> getUser();
		$userid = $user -> getAttribute('userid');
		$username = $user -> getAttribute('name');
		$fileInfo['userid'] = $userid;
		$fileInfo['username'] = $username;

		$fileInfo['timestamp'] = date('Y-m-d H:i:s');
		$fileInfo['URL'] = url_for('search/index', true) . "?existingUploadedFile=" . $fileInfo['filename'] . "&originalFilename=" . $fileInfo['originalFilename'];

		$logEntry = implode(";", $fileInfo) . "\n";

		fwrite($fp, $logEntry);

		return $fileInfo;
	}

	public static function fileLog($file) {
		$uploadLogFile = self::getUploadPath() . "upload.log";

		$fp = fopen($uploadLogFile, "a+");

		if (flock($fp, LOCK_EX)) {// do an exclusive lock

			$fileInfo = self::appendFileLog($fp, $file);

			flock($fp, LOCK_UN);
			// release the lock
			fclose($fp);

			return $fileInfo;

		} else {
			fclose($fp);
			throw new Exception("Could not lock file $uploadLogFile for writing");
		}
	}

	public static function nextFileLogId(&$fp) {
		$lastLine = explode(";", self::readLastLine($fp));
		// uploaded files get negative id values, because for now we assume, that existing files only use positive ids
		$id = (int)$lastLine[0] - 1;
		return $id;
	}

	public static function getUploadPath() {
		return sfConfig::get('sf_upload_dir') . "/mp3uploads/";
	}

	/** Builds a direct file url from the original file Uri, via a alias "smintcontent"
	 * This is only a temporary workaround since file serving with PHP does not work
	 * as expected */
	public static function getDirectFileUrl($uri) {
		$protocol = isset($_SERVER['HTTPS']) && (strcasecmp('off', $_SERVER['HTTPS']) !== 0);
		$hostname = $_SERVER['SERVER_ADDR'];
		$port = $_SERVER['SERVER_PORT'];
		$server = $protocol . $hostname . ":" . $port;

		return "http://" . $server . "/smintcontent/" . rawurlencode(basename($uri));
	}
	
	/** Builds a public url for generated files, given the local filename
	 * This is only a temporary workaround since file serving with PHP does not work
	 * as expected */
	public static function getDirectFileUrl_for_uploads($uri) {
		sfContext::getInstance() -> getConfiguration() -> loadHelpers('Asset');
		return _compute_public_path(rawurlencode(basename($uri)), 'uploads/mp3uploads', '', false);
	}

	public static function readLastLine(&$f) {
		$line = '';
		$cursor = -1;
		fseek($f, $cursor, SEEK_END);
		$cursor = ftell($f);
		$char = fgetc($f);

		/**
		 * Trim trailing newline chars of the file
		 */
		while ($char === "\n" || $char === "\r") {
			fseek($f, $cursor--);
			$char = fgetc($f);
		}

		/**
		 * Read until the start of file or first newline char
		 */
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			/**
			 * Prepend the new char
			 */
			$line = $char . $line;
			fseek($f, $cursor--);
			$char = fgetc($f);
		}

		return $line;
	}

}
