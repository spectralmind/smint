<?php

/**
 * getAudioFile actions.
 *
 * @package    sf_test1
 * @subpackage getAudioFile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class getAudioFileActions extends sfActions {
	/** provides the file as download/streaming
	 * Request params:
	 * - tracknr
	 * - fileid
	 * - uploaded
	 * 		( either tracknr or fileid or uploaded is required)
	 * - format		optional. One of: mp3, ogg. If format is given, the file is converted to the given format, using command line invocations that are configured in app.yml
	 *
	 *
	 */
	public function executeDownload(sfWebRequest $request) {

		// write session, so it won't lock other requests
		session_write_close();

		$reqparamUploaded = $this -> getRequestParameter('uploaded');
		if ($reqparamUploaded) {
			$filename = smintUploadFileHelper::getUploadPath() . $reqparamUploaded;
		} else {

			$tracknr = $this -> getRequestParameter('tracknr');
			if ($tracknr) {
				$file = FilePeer::getFileByExternalKey($tracknr);
				$filekey = $tracknr;
			} else {
				# check if file.id is given
				$fileid = $this -> getRequestParameter('fileid');
				$file = FilePeer::retrieveByPK($fileid);
				$filekey = $fileid;
			}

			//check if a file was found matching tracknr, else exit with 404
			if ($file) {
				$filename = $file -> getUri();
			} else {
				$this -> forward404("file not found in database.");
			}
		}

		//check if a tracknr is given, otherwise exit with 404
		if (!$filename)
			$this -> forward404("Invalid query! Provide tracknr, fileid or uploaded to get a file.");

		if (file_exists($filename)) {
			//check if file is inside the folder configured in smint/config/app.yml
			// or inside the upload folder
			$mp3Path = dirname(sfConfig::get('app_files_mp3path') . "/file");
			if (strpos($filename, $mp3Path) === false && strpos($filename, smintUploadFileHelper::getUploadPath()) === false)
				$this -> forward404("file: $filename is outside of the configured path (app.yml): $mp3Path. Accessing files outside the path is not allowed. Check app.yml and URI in file table.");

			// -----------------------------------------------
			// -------------- check for format --------------
			// if no format is given,
			$reqparamFormat = $this -> getRequestParameter('format');
			if ($reqparamFormat) {
				if (substr($filename, -3) === $reqparamFormat) {
					mysfLog::log($this, "INFO: format parameter equals original audio file suffix. No conversion.");
					if ($reqparamFormat === "mp3")
						$contenttype = "audio/mpeg";
					else
						$contenttype = "audio/$reqparamFormat";
				} else {
					// conversion
					if ($reqparamFormat === "ogg") {
						$filename = smintFileserverHelper::doFileConversion($filename, $reqparamFormat);
						$contenttype = "audio/ogg";
					} else if ($reqparamFormat === "mp3") {
						$filename = smintFileserverHelper::doFileConversion($filename, $reqparamFormat);
						$contenttype = "audio/mpeg";
					} else if ($reqparamFormat === "waveform") {
						$filename = smintFileserverHelper::doFileConversion($filename, $reqparamFormat);
						$contenttype = "application/txt";
					} else if ($reqparamFormat === "png") {
						$waveformfilename = smintFileserverHelper::doFileConversion($filename, "waveform");

						$wfdata = split(", ", file_get_contents($waveformfilename));
						//mysfLog::log($this, print_r($wfdata, true));

						// how much detail we want. Larger number means less detail
						// (basically, how many bytes/frames to skip processing)
						// the lower the number means longer processing time
						// EP: in SMINT we do not chagne this. To change the detail, edit your waveform_resolution 
						// config param in app.yml!
						define("DETAIL", 1);

						// get user vars from form
						$width = $this -> getRequestParameter('width') ? rawurldecode($this -> getRequestParameter('width')) : 640;
						$height = $this -> getRequestParameter('height') ? rawurldecode($this -> getRequestParameter('height')) : 100;
						$foreground = $this -> getRequestParameter('fg') ? rawurldecode($this -> getRequestParameter('fg')) : "#4D4D4D";
						$background = $this -> getRequestParameter('bg') ? rawurldecode($this -> getRequestParameter('bg')) : "#ffffff";
						$maxval = 0;
						$factor = sfConfig::get('app_defaults_waveform_factor', 0.8);

						// create original image width based on amount of detail
						$img = imagecreatetruecolor(sizeof($wfdata) / DETAIL, $height);

						// apply factor to the data
						//$wfdata_factor = array_map("this->pow_func", $wfdata);
						
						for ($i = 0; $i < sizeof($wfdata); $i += DETAIL) {
							$wfdata[$i] = pow(abs($wfdata[$i]), $factor);
						}

						$datamaxval = max(array_map("abs", $wfdata));
						
						$normconstant = (doubleval($height)) / doubleval($datamaxval);
						
						for ($i = 0; $i < sizeof($wfdata); $i += DETAIL) {
							$wfdata[$i] = $wfdata[$i] * $normconstant;
						}
/*
						for ($i = 0; $i < sizeof($wfdata); $i += DETAIL) {

							if (abs($wfdata[$i]) > $maxval) {
								$maxval = $wfdata[$i];
							}
						}
						if ($maxval < 40) {
							for ($i = 0; $i < sizeof($wfdata); $i += DETAIL) {
								$wfdata[$i] = pow(abs($wfdata[$i]), 1.4);
							}
						}
 * */

						// fill background of image
						if ($background == "") {
							imagesavealpha($img, true);
							$transparentColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
							imagefill($img, 0, 0, $transparentColor);
						} else {
							list($r, $g, $b) = smintTools::html2rgb($background);
							imagefilledrectangle($img, 0, 0, sizeof($wfdata) / DETAIL, $height, imagecolorallocate($img, $r, $g, $b));
						}

						// generate foreground color
						list($r, $g, $b) = smintTools::html2rgb($foreground);

						// loop through frames/bytes of wav data as genearted above
						for ($d = 0; $d < sizeof($wfdata); $d += DETAIL) {
							// relative value based on height of image being generated
							// data values can range between -127 and 128
							$v = $wfdata[$d];
							//$v = pow($v, sfConfig::get('app_defaults_waveform_factor', 0.8));
							// draw the line on the image using the $v value and centering it vertically on the canvas
							imageline($img, $d / DETAIL, ($height - $v) / 2, $d / DETAIL, ($height + $v) / 2, imagecolorallocate($img, $r, $g, $b));
						}

						// want it resized?
						if ($width != imagesx($img)) {
							// resample the image to the proportions defined in the form
							$rimg = imagecreatetruecolor($width, $height);
							// save alpha from original image
							imagesavealpha($rimg, true);
							imagealphablending($rimg, false);
							// copy to resized
							imagecopyresampled($rimg, $img, 0, 0, 0, 0, $width, $height, sizeof($wfdata) / DETAIL, $height);
							$img = $rimg;
						}

						$contenttype = "image/png";
						header("Content-type: $contenttype");

						imagepng($img);
						imagedestroy($img);

						// dont render view
						return sfView::NONE;
					} else {
						$error_message = "ERROR: Format $reqparamFormat not valid";
						mysfLog::log($this, $error_message);
						throw new Exception($error_message);
					}
				}
			} else {
				mysfLog::log($this, "INFO: format parameter not given, sending the original file ");
				// assuming file ending as content type. Just an approximation
				$contenttype = "audio/" . substr($filename, -3);
			}

			// do a redirect to file to have apache handle it
			$this -> redirect(smintUploadFileHelper::getDirectFileUrl_for_uploads($filename));

			/* alternate version of redirect (not using symf framework)
			 * does not work better :-(
			 *
			 $url = smintUploadFileHelper::getDirectFileUrl_for_uploads($filename);
			 $statusCode = 302;
			 // redirect
			 $response = $this->context->getResponse();
			 $response->clearHttpHeaders();
			 $response->setStatusCode($statusCode);
			 $response->setHttpHeader('Location', $url);
			 $response->send();

			 throw new sfStopException();
			 // we do not reach this part b/o the redirect!
			 *
			 */

			// ------------ delegate to helper for download
			// serves file, obeying the range header
			smintFileserverHelper::serve_file_resumable($this -> getResponse(), $filename, $contenttype);

			// dont render view
			return sfView::NONE;
			//return sfView::HEADER_ONLY;

		} else {
			$missingfile = ($filename) ? $filename : "No filename found!";
			$this -> forward404("file for tracknr:  not found: " . $missingfile);
		}
	}

}
