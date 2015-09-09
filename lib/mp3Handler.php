<?php

/**
 *
 */
abstract class MP3PlayerHelper {

	public static function mp3PlayerTag($options = array(), $options_html = array()) {

		$player = sfConfig::get('app_mp3player_defaultplayer', 'worldpress');

		switch ($player) {
			case 'flash-mp3-player' :
				$options = _parse_attributes($options);
				$options_html = _parse_attributes($options_html);
				$trackURI = urlencode(isset($options['trackURI']) ? $options['trackURI'] : '');
				$trackURInotencoded = isset($options['trackURI']) ? $options['trackURI'] : '';
				$flashplayerid = isset($options['flashplayerid']) ? $options['flashplayerid'] : '';
				$customSkin = isset($options['customSkin']) ? $options['customSkin'] : '';

				$playerConfig = sfConfig::get('app_mp3player_flash-mp3-player');
				$autoplay = isset($playerConfig['autoplay']) ? $playerConfig['autoplay'] : '1';
				$width = isset($playerConfig['width']) ? $playerConfig['width'] : '200';
				$img = isset($playerConfig['img']) ? image_path($playerConfig['img']) : 'nopreview.defined';
				$imgmouseover = isset($playerConfig['imgmouseover']) ? image_path($playerConfig['imgmouseover']) : 'nopreview.defined';

				$options_html['onClick'] = "javascript: swfobject.createSWF(
          { data:'" . public_path("/mp3player/player_mp3_maxi.swf") . "', width:'${width}', height:'15' }, 
          { flashvars:'mp3=${trackURI}&amp;showslider=1&amp;width=${width}&amp;height=15&amp;autoplay=${autoplay}'}, 
          '${flashplayerid}'); 
          return false;";

				$options_html['href'] = "#";
				$options_html['onmouseover'] = "this.src='${imgmouseover}'";
				$options_html['onmouseout'] = "this.src='${img}'";
				$options_html['alt'] = "no preview defined?";

				return image_tag($img, $options_html);

				break;

			case 'worldpress' :
				$options = _parse_attributes($options);
				$options_html = _parse_attributes($options_html);
				$trackURI = urlencode(isset($options['trackURI']) ? $options['trackURI'] : '');
				$trackURInotencoded = isset($options['trackURI']) ? $options['trackURI'] : '';
				$flashplayerid = isset($options['flashplayerid']) ? $options['flashplayerid'] : '';
				$customSkin = isset($options['customSkin']) ? $options['customSkin'] : '';

				$playerConfig = sfConfig::get('app_mp3player_worldpress');
				$img = isset($playerConfig['img']) ? image_path($playerConfig['img']) : 'nopreview.defined';
				$imgmouseover = isset($playerConfig['imgmouseover']) ? image_path($playerConfig['imgmouseover']) : 'nopreview.defined';

				$img = isset($options['customSkinImage']) ? image_path($options['customSkinImage']) : $img;
				$imgmouseover = isset($options['customSkinImageMouseover']) ? image_path($options['customSkinImageMouseover']) : $imgmouseover;

				$options_html['onClick'] = "javascript: if( isiPhone() ) document.getElementById('$flashplayerid').innerHTML='<audio controls=\"true\" autoplay=\"true\" width=\"150px\" height=\"24px\"> <source src=\"$trackURInotencoded\" type=\"audio/mp3\"> </audio>'; else AudioPlayer.embed('$flashplayerid', {soundFile: '$trackURI', $customSkin}); return false;";

				$options_html['href'] = "#";
				$options_html['onmouseover'] = "this.src='${imgmouseover}'";
				$options_html['onmouseout'] = "this.src='${img}'";
				$options_html['alt'] = "no preview defined?";

				return image_tag($img, $options_html);

				break;

			case 'smintplayer' :
			default :
				return "todo";

				break;
		}

	}

	/** Returns appropriate JS code for mp3 player config.
	 * @param $files Array of Filedesc instances
	 * @param $aSegmSearch Array with segmentation information
	 *
	 */
	public static function getMp3PlayerConfig($files = array(), $aSegmSearch = array()) {
		//needed for javascript_tag
		use_helper('JavascriptBase');

		$player = sfConfig::get('app_mp3player_defaultplayer', 'worldpress');

		switch ($player) {
			case 'flash-mp3-player' :
				return '';
				break;

			case 'worldpress' :
				$playerConfig = sfConfig::get('app_mp3player_worldpress');
				$autostart = isset($playerConfig['autostart']) ? $playerConfig['autostart'] : 'yes';
				$width = isset($playerConfig['width']) ? $playerConfig['width'] : '200';
				$skin = $playerConfig['skin'];
				return javascript_tag("AudioPlayer.setup('" . public_path('audio-player/player.swf') . "', 
                  {
                    width: '$width', 
                    autostart: '$autostart', 
                    animation: 'no', 
                    $skin,
                  });");
				break;
			case 'smintplayer' :
			default :
			// get configuration
				$playerConfig = sfConfig::get('app_mp3player_smintplayer');
				// build options array of array. First dimension is list of files, second is assoc array with values for the player
				$options = array();

				if (!isset($aSegmSearch['enabled'])) {
					$aSegmSearch['enabled'] = false;
				}

				foreach ($files as $i => $filedesc) :

					$filerec = FilePeer::getFileByExternalKey($filedesc -> getTracknr());

					if (!isset($filerec)) {
						// no recrod returned - maybe no metadata in the db?

						$tmparray = array('text' => "(No metadata available)",
						// mp3 file
							'audiofile_mp3' => url_for("getAudioFile/download", true) . "?fileid=" . rawurlencode($filedesc -> getTracknr()) . "&format=mp3",
						// ogg file
							'audiofile_ogg' => url_for("getAudioFile/download", true) . "?tracknr=" . rawurlencode($filedesc -> getTracknr()) . "&format=ogg",
						// waveform file
							'waveformfile' => url_for("getAudioFile/download", true) . "?tracknr=" . rawurlencode($filedesc -> getTracknr()) . "&format=waveform",
						// estimationed duration
							'duration' => isset($iDurationMs) ? $iDurationMs : 0,
						// segments array
							'segments' => $aSegmSearch['enabled'] ? $aSegmSearch['resultsegments'][$i] : array(),
						// function to call for "seach similar"
						// NOTE the function itself is inserted in _result.php
							'searchsimilarfunc' => 'smint_' . smintTools::generateHtmlId($filedesc -> getTracknr()) . '()',
						// backlink to getty music
							'backlinktext' => "License track from gettyimages music", 'backlinkurl' => url_for("search/redirect") . "?url=" . rawurlencode("http://www.gettyimages.at/music/download-songs/" . $filedesc -> getTracknr() . "?ref=spec"),
						// closing bracket
						);
					} else {

						$iBitrate = $filerec -> getBitrate();
						$sUri = $filerec -> getUri();

						// estimate duration
						if (file_exists($sUri)) {
							$iSize = filesize($sUri);
							if ($iSize) {
								// file size divided by bytes per second, times 1000 to get ms.
								// bitrate is k bits per second, so multiply by 1024 to get bits, and divide by 8 to get bytes
								$iDurationMs = intval($iSize / ($iBitrate * 1024 / 8) * 1000);
							} else {
								mysfLog::log($this, "DEBUG Could not read file size: $sUri");
								$iDurationMs = 0;
							}
						} else {
							mysfLog::log($this, "WARNING File does not exist: $sUri");
							$iDurationMs = 0;
						}

						$tmparray = array('text' => $filedesc -> getTitle() . ' | ' . $filedesc -> getPerformers() . ' | ' . $filedesc -> getGenre(),
						// mp3 file
						//						'audiofile_mp3' => url_for("getAudioFile/download", true)."?tracknr=".rawurlencode($filedesc->getTracknr())."&format=mp3",
							'audiofile_mp3' => smintUploadFileHelper::getDirectFileUrl($filerec -> getUri()),
						// ogg file
							'audiofile_ogg' => url_for("getAudioFile/download", true) . "?tracknr=" . rawurlencode($filedesc -> getTracknr()) . "&format=ogg",
						// waveform file
							'waveformfile' => url_for("getAudioFile/download", true) . "?tracknr=" . rawurlencode($filedesc -> getTracknr()) . "&format=waveform",
						// estimationed duration
							'duration' => isset($iDurationMs) ? $iDurationMs : 0,
						// segments array
							'segments' => $aSegmSearch['enabled'] ? $aSegmSearch['resultsegments'][$i] : array(),
						// function to call for "seach similar"
						// NOTE the function itself is inserted in _result.php
							'searchsimilarfunc' => 'smint_' . smintTools::generateHtmlId($filedesc -> getTracknr()) . '()',
						// backlink to getty music
							'backlinktext' => "License track from gettyimages music", 'backlinkurl' => url_for("search/redirect") . "?url=" . rawurlencode("http://www.gettyimages.at/music/download-songs/" . $filedesc -> getTracknr() . "?ref=spec"),
						// closing bracket
						);
					}

					$options[] = $tmparray;

				endforeach;

				// we cannot put all these fields into one array since the output format must be like
				// , A: XXXX, B: XXX
				// and not
				//, {A: XXXX, B: XXX}
				return ", playlist: " . smintTools::my_options_for_javascript($options) . ", waveform_resolution: " . intval(sfConfig::get('app_defaults_waveform_resolution')) . ', waveform_factor: ' . doubleval(sfConfig::get('app_defaults_waveform_factor'));

				break;
		}
	}

	/** Returns appropriate JS code for Smintplayer embedding for Seed song.
	 * <p>Note: this function returns more code that the counterpart "getMp3PlayerConfig"
	 * @param $uploadedFilePHPUrl_incomplete incomplete download url for file. format can be appended
	 * @param $uploadedFileURL url of original file uploaded (curretnly used as mp3 although this might not be true)
	 *
	 */
	public static function getMp3PlayerConfig4Seedsong($uploadedFilePHPUrl_incomplete, $uploadedFileURL) {
		//needed for javascript_tag
		use_helper('JavascriptBase');

		$player = sfConfig::get('app_mp3player_defaultplayer', 'worldpress');

		switch ($player) {
			case 'flash-mp3-player' :
				return 'not implemented';
				break;

			case 'worldpress' :
				return 'not implemented';
				break;

			case 'smintplayer' :
			default :
				if (smintTools::endsWith($uploadedFileURL, "mp3")) {
					$audiofile_mp3 = $uploadedFileURL;
					$audiofile_ogg = $uploadedFilePHPUrl_incomplete . "&format=ogg";
					$orig_ext_comma = "";
					$setmedia_orig_part = "";
				} elseif (smintTools::endsWith($uploadedFileURL, "ogg")) {
					$audiofile_mp3 = $uploadedFilePHPUrl_incomplete . "&format=mp3";
					$audiofile_ogg = $uploadedFileURL;
					$orig_ext_comma = "";
					$setmedia_orig_part = "";
				} else {
					$audiofile_mp3 = $uploadedFilePHPUrl_incomplete . "&format=mp3";
					$audiofile_ogg = $uploadedFilePHPUrl_incomplete . "&format=ogg";
					$audiofile_orig = $uploadedFileURL;
					$orig_ext = smintTools::getExtension($uploadedFileURL);
					$orig_ext_comma = "$orig_ext,";
					$setmedia_orig_part = "$orig_ext: $('#jquery_jplayer_2').jPlayer('option', 'playlist')[0].audiofile_orig,";
				}

				// get configuration
				$playerConfig = sfConfig::get('app_mp3player_smintplayer');
				// build options array of array. First dimension is list of files, second is assoc array with values for the player
				$options = array();

				if (!isset($aSegmSearch['enabled'])) {
					$aSegmSearch['enabled'] = false;
				}

				$protocol = isset($_SERVER['HTTPS']) && (strcasecmp('off', $_SERVER['HTTPS']) !== 0);
				$hostname = $_SERVER['SERVER_ADDR'];
				$port = $_SERVER['SERVER_PORT'];
				$server = $protocol . $hostname . ":" . $port;

				$tmparray = array('text' => '',
				// mp3
					'audiofile_mp3' => $audiofile_mp3,
				//'audiofile_mp3' => $uploadedFilePHPUrl_incomplete . "&format=mp3",
				//'audiofile_mp3' => "http://" . $server . "/smintcontent/" . rawurlencode(basename($filerec -> getUri())),
					'audiofile_ogg' => $audiofile_ogg,
				// orig file if not mp3 or oga
					'audiofile_orig' => (isset($audiofile_orig) && $audiofile_orig) ? $audiofile_orig : "not used",
				// waveform file as text file
				//'waveformfile' => $uploadedFilePHPUrl_incomplete . "&format=png",
				// waveform file as image
					'waveformimage' => $uploadedFilePHPUrl_incomplete . "&format=png",
				// duration (not used)
					'duration' => '',
				// the segment
					'segments' => $aSegmSearch['enabled'] ? $aSegmSearch['resultsegments'][$i] : array(), );

				// we cannot put all these fields into one array since the output format must be like
				// , A: XXXX, B: XXX
				// and not
				//, {A: XXXX, B: XXX}
				$options[] = $tmparray;
				$playlist_part = ", playlist: " . smintTools::my_options_for_javascript($options) . ", waveform_resolution: " . intval(sfConfig::get('app_defaults_waveform_resolution')) . ', waveform_factor: ' . doubleval(sfConfig::get('app_defaults_waveform_factor'));

				$general_part = "
				ready: function () {
					$(this).jPlayer('setMedia', {
						$setmedia_orig_part
						mp3: $('#jquery_jplayer_2').jPlayer('option', 'playlist')[0].audiofile_mp3,
						oga: $('#jquery_jplayer_2').jPlayer('option', 'playlist')[0].audiofile_ogg
					});
				},
				play: function() { // To avoid both jPlayers playing together.
					$(this).jPlayer('pauseOthers');
				},
				swfPath:  ' " . _compute_public_path('', 'smintplayer/js', '', false) . "'  ,
				supplied: '$orig_ext_comma mp3, oga',
				solution: 'flash, html', 
				cssSelectorAncestor: '#jp_container_2',
				wmode: 'window',
				preload: 'auto'
				" . $playlist_part;

				return $general_part;

				break;
		}
	}

	/*
	 public static function mp3PlayerConfig() {
	 //needed for javascript_tag
	 use_helper('JavascriptBase');

	 $player = sfConfig::get('app_mp3player_defaultplayer', 'worldpress');

	 switch ($player) {
	 case 'flash-mp3-player' :
	 return '';
	 break;

	 case 'worldpress' :
	 default :
	 $playerConfig = sfConfig::get('app_mp3player_worldpress');
	 $autostart = isset($playerConfig['autostart']) ? $playerConfig['autostart'] : 'yes';
	 $width = isset($playerConfig['width']) ? $playerConfig['width'] : '200';
	 $skin = $playerConfig['skin'];
	 return javascript_tag("AudioPlayer.setup('" . public_path('audio-player/player.swf') . "',
	 {
	 width: '$width',
	 autostart: '$autostart',
	 animation: 'no',
	 $skin,
	 });");
	 break;
	 }
	 }
	 */
}
