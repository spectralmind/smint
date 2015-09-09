<?php

abstract Class smintSmafeHelper {

	private static $context = "smintSmafeHelper";

	/** Executes the command to start smafedidstd server and checks if error code is 0.
	 * If not 0, an exception is thrown
	 * Log file is automatically determined and assign to the server
	 */
	public static function restartServer($segmentMode = false) {
		// restart settings
		// $restart_repeatcount = 5;
		// $restart_waitperiod_seconds = 30;

		$exec_output = array();
		$exec_err = array();
		$server_running = false;

		if (!$segmentMode) {
			//    	$server_command = mysfConfig::get('app_live_settings_server_command',"") . $filesavepath = sfConfig::get('sf_upload_dir') . "/smafe_live_distd." . $current_date . ".log";
			$server_command = mysfConfig::get('app_live_settings_server_command', "");
			$current_date = date("_Ymd_His_");
		} else {
			$server_command = mysfConfig::get('app_live_settings_server_command_segmentedsearch', "");
			$current_date = date("_Ymd_His_") . "-segments-";
		}
		$server_command = $server_command . $filesavepath = sfConfig::get('sf_upload_dir') . "/smafe_live_distd." . $current_date . ".log";

		mysfLog::log(self::$context, "INFO: trying to start server - $server_command");

		//smintTools::mySmafeExec($server_command . "  &");

		exec($server_command . " &> /dev/null &", $exec_err, $exec_result);

		if ($exec_result <> 0) {
			exec($server_command . ' 2>&1', $exec_err, $exec_result);
			$result_array = array("output" => $exec_output, "result" => $exec_result, "error" => $exec_err);
			$error_message = "ERROR: while trying to start the server: " . print_r($result_array, true);
			mysfLog::log(self::$context, $error_message);
			throw new Exception($error_message);
		}

		// // wait until the server started
		// while ( $restart_repeatcount > 0 || $server_running ) {
		//   mysfLog::log(self::$context, "INFO: trying to connect to server. attempts left: $restart_repeatcount");
		//
		//   // check if the server is responding
		//   $query_result = self::$context->queryFile("", false);
		//   if ($query_result["result"] == 0) {
		//     $server_running = true;
		//     mysfLog::log(self::$context, "INFO: server is running");
		//   }
		//
		//   sleep($restart_waitperiod_seconds);
		//   --$restart_repeatcount;
		// }

	}

	public static function getResultsOnly($exec_output) {
		$results = array();
		foreach ($exec_output as $key => $value) {
			$result = explode(",", $value);
			if (is_array($result)) {
				if (count($result) == 3) {
					if (is_numeric($result[0])) {
						// the format is: id, segment, distance
						// if there are segments the id is not unique, but id.segment is unique
						$results[$result[0] . $result[1]] = $result;
					}
				}
			}
		}
		return $results;
	}

/*
	public static function getResultsWithUri($exec_output) {
		$results = array();
		foreach ($exec_output as $key => $value) {
			$result = explode(",", $value);
			if (is_array($result)) {
				if (count($result) == 3) {
					if (is_numeric($result[0])) {
						// the format is: id, segment, distance
						// if there are segments the id is not unique, but id.segment is unique
						$results[$result[0] . $result[1]] = $result;
						array_push($results[$result[0] . $result[1]], FilePeer::getFirstFileByTrackId($result[0]));
					}
				}
			}
		}
		return $results;
	}
 */

	public static function getDistances($results) {
		$distances = array();
		foreach ($results as $key => $value) {
			$newDistance = new Distance();
			$newDistance -> setValue($value[2]);
			$distances[$value[0] . $value[1]] = $newDistance;
		}
		return $distances;
	}

	/** Performs live query with Smafe
	 * Also tries to start the server if it seems to be down. In this case, an exception is thrown with explanation
	 * @param $filepath		The file to query (local file path)
	 * @param $aSegmSearch	Associative array with data about segmented search
	 * @param $autostart	if true, the server is tried to started if it seems to be down. Default = true
	 */
	public static function queryFile($filepath, $aSegmSearch, $autorestart = true) {

		// The command
		$exec_command;

		if ($aSegmSearch['enabled']) {
			// segmented search
			$command = mysfConfig::get('app_live_settings_command_segmentedsearch', "");
			$command = str_replace('$LIVEFILE', $filepath, $command);
			$command = str_replace('$BEGINTIME', smintTools::convertTimeMsToMM_SS_HH($aSegmSearch['startSegment']), $command);
			$command = str_replace('$ENDTIME', smintTools::convertTimeMsToMM_SS_HH($aSegmSearch['endSegment']), $command);

			$exec_command = $command;
		} else {
			// normal search
			$command = mysfConfig::get('app_live_settings_command', "");
			$command = str_replace('$LIVEFILE', $filepath, $command);

			$exec_command = $command;
		}

		$exec_output = array();
		$exec_err = array();

		mysfLog::log(self::$context, "INFO - executing live query: $exec_command");

		exec($exec_command, $exec_output, $exec_result);

		if ($exec_result <> 0) {

			if ($autorestart) {
				// try to start server
				try {
					mysfLog::log(self::$context, "INFO: Server seems not to be running. STARTING the SERVER.");
					self::restartServer($aSegmSearch['enabled']);
					throw new Exception("Server was restarted. Try again in a few minutes. Log: " . print_r($exec_output, true));

				} catch (Exception $e) {
					// restart failed
					mysfLog::log(self::$context, "STARTING the SERVER failed: " . $e -> getMessage());
					throw new Exception($e -> getMessage());
				}
			} else {
				// do not start the smafedistd server but return error message
				$result_array = array("command" => $exec_command, "result" => $exec_result, "error" => $exec_output);
				$error_message = "ERROR during live query. " . print_r($result_array, true);
				mysfLog::log(self::$context, $error_message);
				throw new Exception($error_message);
			}
		}

		$result_array = array("output" => $exec_output, "result" => $exec_result);

		return $result_array;

	}

}
