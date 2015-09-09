<?php

/**
 * search actions.
 *
 * @package    smint
 * @subpackage search
 * @author     Wolfgang Jochum, Spectralmind GmbH
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends sfActions {
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request) {
		$this -> formfileupload = new FileUploadForm();

		// check for parameters
		$queryParameters = array("existingUploadedFile", "uploadedFilename", "originalFilename", "metadataquery", );
		$this -> requestQueryParameters = $request -> extractParameters($queryParameters);

		$this -> getResponse() -> setHttpHeader('Access-Control-Allow-Origin', 'localhost');
	}

	public function executeUpload(sfWebRequest $request) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {

			$errormessage = 'The server was unable to handle that much POST data (' . $_SERVER['CONTENT_LENGTH'] . ' bytes) due to its current configuration : ' . ini_get('post_max_size');
			// return $this->jsonError($request, $errormessage);
			return $this -> renderPartial('search/error', array('error_message' => $errormessage, ));

		}

		$this -> formfileupload = new FileUploadForm();
		if ($request -> isMethod('post')) {
			$this -> formfileupload -> bind($request -> getParameter('fileupload'), $request -> getFiles('fileupload'));
			if ($this -> formfileupload -> isValid()) {

				$file = $this -> formfileupload -> getValue('mp3');
				$fileInfo = smintUploadFileHelper::saveFile($file);

				# possibly add generate waveform code HERE ???

				return $this -> renderPartial('search/fileupload', array('fileInfo' => $fileInfo, 'metadataquery' => $this -> formfileupload -> getValue('metadataquery'), ));
			} else {
				// if form validation failed
				$form_errors = array("Form error" => "following errors while validating form:");

				foreach ($this->formfileupload->getFormFieldSchema() as $name => $formField) {
					if ($formField -> hasError()) {
						$current_error = $formField -> getName() . ' - ' . (string)$formField -> getError();
						$form_errors[] = $current_error;
					}
				}

				if ($this -> formfileupload -> hasGlobalErrors()) {
					foreach ($this->formfileupload->getGlobalErrors() as $validator_error) {
						$form_errors[] = $validator_error -> getMessage();
					}
				}

				return $this -> renderPartial('search/error', array('error_message' => $form_errors, ));

			}
		}
	}

	/** Action that is called if file has been uploaded (metadata may have been entered)
	 *
	 */
	public function executeRelated(sfWebRequest $request) {
		try {
			// Load helpers
			$this -> getContext() -> getConfiguration() -> loadHelpers('Asset');
			$this -> getContext() -> getConfiguration() -> loadHelpers('Url');

			// get config
			$configFeaturevectortypeid = sfConfig::get('app_defaults_featurevectortypeid', 0);

			// get request params
			$reqparamtracknr = $request -> getParameter('tracknr');
			$filename = $request -> getParameter('uploadedFilename');
			$originalFilename = $request -> getParameter('originalFilename');
			$metadataquery = $request -> getParameter('metadataquery');

			// 2 possibilities: either tracknr is given or uploadedFilename
			if (isset($reqparamtracknr) && $reqparamtracknr != '' && $reqparamtracknr != 'undefined') {
				//get file via external key
				$tracknr = $reqparamtracknr;
				$fileForSearchSimilar = FilePeer::getFileByExternalKey($tracknr);

				//only if the file was found in the smafestore db
				if ($fileForSearchSimilar) {
					$originalFilename = $fileForSearchSimilar -> getUri();
					$filesavepath = $fileForSearchSimilar -> getUri();
				} else {
					throw new Exception("Could not find file in database with external_key: $tracknr");
				}
				$bUploadedFile = false;
			} else {
				$originalFilename = $request -> getParameter('originalFilename');
				$filesavepath = smintUploadFileHelper::getUploadPath() . $filename;
				$bUploadedFile = true;
			}

			// variables about segment selection
			//$reqparamStartSegment = '10000';
			// Start of segment in ms MOCKUP   10 to 20 seconds
			//$reqparamEndSegment = '20000';
			// end of segment in ms   MOCKUP
			$reqparamStartSegment = $request -> getParameter('segmentStart');
			// Start of segment in ms
			$reqparamEndSegment = $request -> getParameter('segmentEnd');
			$reqparamDuration = $request -> getParameter('duration');
			// end of segment in ms
			$aSegmSearch = array('enabled' => false, 'startSegment' => 0, 'endSegment' => 0);
			//$bSegmSearch =			FALSE;										// boolean: is this segmented search? To be set later.
			//$startSegment;														// int value, to be set later
			//$endSegment; 															// int value, to be set later

			$empty_livequery = false;
			$empty_metadatafilteredquery = false;
			$omit_empty_metadataquery = $request -> getParameter('omit_empty_metadataquery', false);

			///// check validity of parameters
			if (isset($reqparamStartSegment))
				$aSegmSearch['startSegment'] = intval($reqparamStartSegment);
			if (isset($reqparamEndSegment))
				$aSegmSearch['endSegment'] = intval($reqparamEndSegment);
			if (isset($reqparamDuration))
				$aSegmSearch['duration'] = intval($reqparamDuration);

			if (isset($reqparamStartSegment) && isset($reqparamEndSegment)) {
				mysfLog::log($this, "DEBUG: " . intval($reqparamStartSegment) . ", $reqparamEndSegment     " . $aSegmSearch['startSegment']);
			}

			// for selection, both start and end must be given and of type int and greater or equal 0
			// Note that the start and end times are set "inline"
			if ((isset($reqparamStartSegment) && $aSegmSearch['startSegment'] > 0) || (isset($reqparamEndSegment) && $aSegmSearch['endSegment'] > 0)) {
				mysfLog::log($this, "DEBUG: Using segmented search with start-end in ms:" . $aSegmSearch['startSegment'] . " - " . $aSegmSearch['endSegment']);
				$aSegmSearch['enabled'] = TRUE;
			} else {
				mysfLog::log($this, "DEBUG: NOT using segmented search");
				$aSegmSearch['enabled'] = false;
			}

			$this -> exec_result = smintSmafeHelper::queryFile($filesavepath, $aSegmSearch);

			$results = smintSmafeHelper::getResultsOnly($this -> exec_result["output"]);
			//			$results = smintSmafeHelper::getResultsWithUri($this -> exec_result["output"]);

			// If we are here in a "search similar" scenario (track is in db, so it will be returned as result again)
			// we have to filter out the seed song
			if (!$bUploadedFile) {
				// do filtering
				$track_id_seed = $fileForSearchSimilar -> getTrackId();
				$results_no_seed = array();
				foreach ($results as $key => $result) {
					if ($result[0] != $track_id_seed) {
						$results_no_seed[$key] = $result;
					}
				}
				$results = $results_no_seed;
			}

			if (count($results) == 0) {
				$empty_livequery = true;
			}

			// filter with metadata
			// select external_key,genre from file left join filedesc on file.external_key = filedesc.tracknr where filedesc.genre ilike '%metadata%' and track_id IN ('31469', '31465') limit 10
			if (is_string($metadataquery) && strlen($metadataquery) > 0) {
				$bMDsearch = true;
				$filtered_results = $this -> metadataFilter($results, $metadataquery);

				// omit metadata query if empty
				if ($omit_empty_metadataquery) {
					if (count($filtered_results) > 0) {
						// set results to filtered results if the metadata filter returns results
						$results = $filtered_results;
					} else {
						$empty_metadatafilteredquery = true;
					}
				} else {
					$results = $filtered_results;
				}
			} else {
				$bMDsearch = false;

			}

			// reduce to maxresults
			$this -> limit = $this -> getContext() -> getConfiguration() -> getSmintMaxQueryResults();
			$this -> results = array_slice($results, 0, $this -> limit);
			//$this -> results = array_slice($results, 0, 50);

			////// If this is segmented search, we perform re-ordering
			if ($aSegmSearch['enabled']) {
				// First, we get the length of the segments in samples
				mysfLog::log($this, "res " . print_r($this -> results, true));

				$aResultsEnhanced = array();

				foreach ($this->results as $key => $value) {

					// check for negative segmentnr (indicates non-segment server)
					if ($value[1] < 0) {
						//throw new Exception("Negative value for segment nr. Probably the smafe server is not configured for segmented search?");
						// Set dummy segments (whole track)
						array_push($aResultsEnhanced, array('track_id' => intval($value[0]), 'segmnr' => 0, 'dist' => doubleval($value[2]), 'start' => 0, 'end' => 0));
					} else {

						$fvsegm = FeaturevectorsegmentPeer::getInformationOnSegment($value[0], $value[1], $configFeaturevectortypeid);
						$iSamplef = $fvsegm -> getFile() -> getSamplef();

						array_push($aResultsEnhanced, array('track_id' => $fvsegm -> getTrackId(), 'segmnr' => $fvsegm -> getSegmentnr(), 'dist' => doubleval($value[2]), 'start' => intval(($fvsegm -> getStartsample() * 1000) / $iSamplef), 'end' => intval((($fvsegm -> getStartsample() + $fvsegm -> getLength()) * 1000) / $iSamplef)));
						//$value[3] = $value[1] * 262144; 	// this is correct for 44100 Hz samples audio
						//$value[4] = 262144; 						// this is correct for 44100 Hz samples audio
					}
				}

				//mysfLog::log($this, "DEBUG: " . print_r($aResultsEnhanced, true));

				$aSegmSearch['resultsegments'] = array();
				// Structure of $aSegmSearch['resultsegments']:
				// first dim:
				//		each element corresponds to element at same position in $this -> results (and later: ->files)
				// second dim:
				//		list of result segments for given track
				// third dim:
				// 		assoc array with start and end time for this result segment

				// INPUT for the aggregation algo:
				// - $aResultsEnhanced
				// - $this->results

				// OUTPUT
				// - $this -> results					array of array[0]...track id , [1] segm nr, [2] distance
				// - $aSegmSearch['resultsegments']		(see above)

				switch (sfConfig::get('app_defaults_segm_search_aggregation_algo', 0)) {
					case 0 :
						foreach ($aResultsEnhanced as $key => $value) {
							// new segment
							// conversion done from sample number to ms
							array_push($aSegmSearch['resultsegments'], array( array('start' => $value['start'], 'end' => $value['end'], 'dist' => $value['dist'])));
						}

						break;

					case 1 :
					// extract 1st column (track ids)
					// do the actual work
					//						$column = 0;
					//					$result_trackids = array_map('array_slice', $this->results, array_fill(0, count( $this->results), $column), array_fill(0, count( $this->results), 1));
						$result_trackids = array();
						foreach ($this->results as $key => $value) {
							array_push($result_trackids, $value[0]);
						}

						mysfLog::log($this, "DEBUG: " . print_r($result_trackids, true));

						$unique = array();
						foreach ($result_trackids as $key => $value) {
							if (!in_array($value, $unique))
								array_push($unique, $value);
						}

						//						$unique = array_unique($result_trackids);

						//mysfLog::log($this, "DEBUG: " . print_r($unique, true));

						foreach ($unique as $ukey => $uvalue) {
							// segments for $value
							$tmparray = array();
							foreach ($aResultsEnhanced as $key => $value) {
								if ($uvalue == $value['track_id']) {
									array_push($tmparray, array('start' => $value['start'], 'end' => $value['end'], 'dist' => $value['dist']));
								}
							}

							// Sort result segments ascending in start time
							$fCmpSegments = create_function('$a, $b', '
							
							if ($a[\'start\'] == $b[\'start\']) {
									return 0;
								} else if ($a[\'start\'] < $b[\'start\']) {
									return -1;
								} else {
									return 1;
								}
						
							');

							usort($tmparray, $fCmpSegments);

							// push segments to master array
							array_push($aSegmSearch['resultsegments'], $tmparray);
						}

						//mysfLog::log($this, "DEBUG: " . print_r($aSegmSearch['resultsegments'], true));

						$this -> results = $unique;

						break;

					default :
						throw new Exception("Bad value for app_defaults_segm_search_aggregation_algo: " . sfConfig::get('app_defaults_segm_search_aggregation_algo', 0));
						break;
				}
			} else { // segmented search enabled
				// nothing to do, normal search
			}// segmented search enabled

			// not used in _result.php
			//			$this -> distances = smintSmafeHelper::getDistances($this -> results);
			$this -> files = $this -> queryMetadataDatabase($this -> results);

			//mysfLog::log($this, "DEBUG: " . print_r($this -> files, true));

			// depending on whether it is an upload or not
			if ($bUploadedFile) {
				//tracknr is before underscore in filename
				$fileNameParts = explode("_", $filename);
				$tracknr = $fileNameParts[0];
				$uploadedFileURL = _compute_public_path($filename, 'uploads/mp3uploads', 'mp3', false);
				//url_for("getAudioFile/download") . "?uploaded=" . rawurlencode($filename);
				// the url for getting the file via PHP, but incomplete. Format parameter is missing and will be
				// appended in mp3 helper class
				$uploadedFilePHPUrl_incomplete = url_for("getAudioFile/download", true) . "?uploaded=" . rawurlencode(basename($filename));
				if ($aSegmSearch['enabled']) {
					$seedLabel = intval(($aSegmSearch['endSegment'] - $aSegmSearch['startSegment']) / 1000) . " seconds from $originalFilename";
					if ($bMDsearch)
						$seedlabel_topleft = "Seed track, results filtered by " . $metadataquery;
					else {
						$seedlabel_topleft = "Seed track";
					}
				} else {
					$seedLabel = $originalFilename;
					if ($bMDsearch)
						$seedlabel_topleft = "Seed track, results filtered by " . $metadataquery;
					else {
						$seedlabel_topleft = "Seed track";
					}
				}

			} else {
				$queryFileMetadata = FiledescPeer::retrieveByPk($tracknr);
				$fileInfoName = $queryFileMetadata -> getTitle() . " - " . $queryFileMetadata -> getPerformers();
				$uploadedFilePHPUrl_incomplete = url_for("getAudioFile/download", true) . "?tracknr=" . rawurlencode($tracknr);
				$uploadedFileURL = smintUploadFileHelper::getDirectFileUrl($fileForSearchSimilar -> getUri());
				$seedLabel = $fileInfoName;
				if ($bMDsearch)
					$seedlabel_topleft = "Acoustic similarity, filtered by " . $metadataquery;
				else {
					$seedlabel_topleft = "Acoustic similarity";
				}

			}

			return $this -> renderPartial('search/result', array('render' => 'related', 'id_prefix' => "id_prefix", 'tracknr' => $tracknr, 'uploadedFilePHPUrl_incomplete' => $uploadedFilePHPUrl_incomplete, 'uploadedFileURL' => $uploadedFileURL,
			//					'distances' => $this -> distances,
				'files' => $this -> files, 'queryTrackIsUploaded' => true, 'seedLabel' => $seedLabel, 'limit' => $this -> limit, 'metadataquery' => $metadataquery, 'empty_livequery' => $empty_livequery, 'empty_metadatafilteredquery' => $empty_metadatafilteredquery,
			// added by EP: we need segment info
				'aSegmSearch' => $aSegmSearch,
			// Label for top left of player
				'seedlabel_topleft' => $seedlabel_topleft));

		} catch (Exception $e) {
			// an error occoured while trying to query the file
			mysfLog::log($this, "STARTING the QUERY failed: " . $e -> getMessage());

			return $this -> renderPartial('search/error', array('error_message' => $e -> getMessage(), ));

		}
	}

	/** Action that is called if only metadata query is done
	 */
	public function executeMetadata(sfWebRequest $request) {
		$this -> getContext() -> getConfiguration() -> loadHelpers('Url');

		$files = NULL;

		// get request parameters
		$metadataquery = $request -> getParameter('metadataquery');
		$limit = $request -> getParameter('limit', $this -> getContext() -> getConfiguration() -> getSmintMaxQueryResults());
		$id_prefix = $request -> getParameter('id_prefix', '');
		$featurevectortypeid = sfConfig::get('app_defaults_featurevectortypeid', 0);
		$page = $request -> getParameter('page', 1);

		mysfLog::logRequest($this, $request);

		$relatedCriteria = $this -> buildQuery($metadataquery, $limit);
		mysfLog::log($this, $relatedCriteria -> toString());

		$filespager = new sfPropelPager('Filedesc');
		$filespager -> setPage($page);
		$filespager -> setCriteria($relatedCriteria);
		$filespager -> setMaxPerPage($limit);
		$filespager -> init();

		$seedLabel = null;
		$seedlabel_topleft = 'Search for ' . $metadataquery;

		$aSegmSearch['enabled'] = false;

		return $this -> renderPartial('search/result', array('render' => 'results', 'seedLabel' => $seedLabel, 'seedlabel_topleft' => $seedlabel_topleft, 'id_prefix' => $id_prefix, 'limit' => $limit, 'metadataquery' => $metadataquery, 'filespager' => $filespager, 'aSegmSearch' => $aSegmSearch));

	}

	private function queryMetadataDatabase($results) {
		$tracks = array();
		foreach ($results as $key => $value) {

			if (is_array($value))
				$trackid = $value[0];
			else {
				$trackid = $value;
			}

			$file = Filepeer::getFirstFileByTrackId($trackid);

			if ($file) {
				$tracks[$key] = FiledescPeer::retrieveByPk($file -> getExternalKey());
			}

			if (!$file || !$tracks[$key]) {
				//if the track was not found in the metadata database
				$track = new Filedesc();
				$track -> setTracknr($trackid);
				$track -> setTitle("No Track Information.");
				$tracks[$key] = $track;
			}
		}

		return $tracks;
	}

	/**
	 * filters results of the smafewrap result according to a metadataquery term
	 *
	 * @param array $results output of smafewrap as php array
	 * @param string $metadataquery query term as string
	 * @return array filtered $results output of smafewrap as php array filtered by the query term
	 * @author Wolfgang
	 */
	private function metadataFilter($results, $metadataquery) {
		// select external_key,genre from file left join filedesc on file.external_key = filedesc.tracknr where filedesc.genre ilike '%' and track_id IN ('31469', '31465') limit 10
		$columnnames = array("genre", "title", "performers");
		// prepare the array of results
		$results_trackids = array();
		foreach ($results as $key => $result) {
			$results_trackids[] = $result[0];
		};

		$criteria = new Criteria();
		$criteria -> addJoin(FilePeer::EXTERNAL_KEY, FiledescPeer::TRACKNR);
		$crit_InTracks = $criteria -> getNewCriterion(FilePeer::TRACK_ID, $results_trackids, Criteria::IN);

		// loop over columnnames that need to be searched
		if (count($columnnames) > 0) {
			//first create the criteria for one columnname
			$crit_Metadata = $criteria -> getNewCriterion(constant('FiledescPeer::' . strtoupper($columnnames[0])), '%' . $metadataquery . '%', Criteria::ILIKE);
			// for the rest of the columnnames -> i=1
			for ($i = 1; $i < count($columnnames); ++$i) {
				$crit_next_Metadata = $criteria -> getNewCriterion(constant('FiledescPeer::' . strtoupper($columnnames[$i])), '%' . $metadataquery . '%', Criteria::ILIKE);
				$crit_Metadata -> addOr($crit_next_Metadata);
			}
			$crit_InTracks -> addAnd($crit_Metadata);
		}
		$criteria -> add($crit_InTracks);
		//$criteria->setIgnoreCase(true); // used ILIKE instead
		$related = FilePeer::doSelect($criteria);

		$filtered_results = array();
		$track_results = array();
		foreach ($related as $key => $value) {
			$currentTrackId = $value -> getTrackId();
			$track_results[$currentTrackId] = $currentTrackId;
		}
		//TODO distances & sort order
		foreach ($results as $key => $result) {
			if (array_key_exists($result[0], $track_results)) {
				$filtered_results[$key] = $result;
			}
		}

		return $filtered_results;
	}

	/** This is executed when searched for related tracks and metadata */
	public function executeRelatedtrack(sfWebRequest $request) {
		$this -> getContext() -> getConfiguration() -> loadHelpers('Url');

		$empty_metadatafilteredquery = false;
		$distances = NULL;
		$files = NULL;

		// get request parameters
		$metadataquery = $request -> getParameter('metadataquery');
		$tracknr = $request -> getParameter('tracknr');
		$limit = $request -> getParameter('limit', $this -> getContext() -> getConfiguration() -> getSmintMaxQueryResults());
		$offset = $request -> getParameter('offset', 0);
		$id_prefix = $request -> getParameter('id_prefix', '') . smintTools::generateHtmlId($tracknr) . "_";
		$omit_empty_metadataquery = $request -> getParameter('omit_empty_metadataquery', false);

		$distancetypeid = sfConfig::get('app_defaults_distancetypeid', 0);
		$featurevectortypeid = sfConfig::get('app_defaults_featurevectortypeid', 0);

		mysfLog::logRequest($this, $request);

		//get file via external key
		$file = FilePeer::getFileByExternalKey($tracknr);

		//only if the file was found in the smafestore db
		if ($file) {
			// run query
			$fileTrackId = $file -> getTrackid();

			$relatedCriteria = $this -> buildRelatedQuery($fileTrackId, $featurevectortypeid, $distancetypeid, $limit, $offset, $metadataquery);
			$related = FilePeer::doSelect($relatedCriteria);

			// if omit empty metadataquery is true
			if ($omit_empty_metadataquery) {
				if (count($related) > 0) {
					// if the query returned results
					$distances = DistancePeer::doSelect($relatedCriteria);
				} else {
					// if the query returned no results -> retry without metadata
					$empty_metadatafilteredquery = true;
					$relatedCriteria = $this -> buildRelatedQuery($fileTrackId, $featurevectortypeid, $distancetypeid, $limit, $offset);
					$related = FilePeer::doSelect($relatedCriteria);
					$distances = DistancePeer::doSelect($relatedCriteria);
				}
			} else {
				$distances = DistancePeer::doSelect($relatedCriteria);
			}

		}

		$files = FiledescPeer::doSelect($relatedCriteria);
		// add duration

		//		$fileDownloadURL = url_for("getAudioFile/download") . "?tracknr=" . rawurlencode($tracknr);

		$queryFileMetadata = FiledescPeer::retrieveByPk($tracknr);
		$fileInfoName = $queryFileMetadata -> getTitle() . " - " . $queryFileMetadata -> getPerformers();
		$uploadedFilePHPUrl_incomplete = url_for("getAudioFile/download", true) . "?tracknr=" . rawurlencode($tracknr);
		$uploadedFileURL = smintUploadFileHelper::getDirectFileUrl($file -> getUri());

		return $this -> renderPartial('search/result', array('render' => 'related', 'uploadedFilePHPUrl_incomplete' => $uploadedFilePHPUrl_incomplete, 'uploadedFileURL' => $uploadedFileURL, 'seedLabel' => $fileInfoName, 'id_prefix' => $id_prefix, 'tracknr' => $tracknr, 'files' => $files, 'distances' => $distances, 'limit' => $limit, 'offset' => $offset, 'metadataquery' => $metadataquery, 'empty_metadatafilteredquery' => $empty_metadatafilteredquery, 'aSegmSearch' => $aSegmSearch));
	}

	/** redirects the user to the given url */
	public function executeRedirect(sfWebRequest $request) {
		$url = rawurldecode($request -> getParameter('url'));

		$this -> redirect($url);

		throw new sfException("redirect failed");
	}

	public function executeTutorial() {
		// only show the view, no busines logic here
	}

	private function buildRelatedQuery($fileTrackId, $featurevectortypeid, $distancetypeid, $limit = 0, $offset = 0, $metadataquery = '') {

		$relatedCriteria = $this -> buildQuery($metadataquery, $limit, $offset);

		$relatedCriteria -> addJoin(FeaturevectorPeer::FILE_ID, FilePeer::ID);
		$relatedCriteria -> addJoin(array(DistancePeer::TRACK_B_ID, DistancePeer::FEATUREVECTORTYPE_ID), array(FeaturevectorPeer::TRACK_ID, FeaturevectorPeer::FEATUREVECTORTYPE_ID));
		$relatedCriteria -> add(DistancePeer::TRACK_A_ID, $fileTrackId);
		$relatedCriteria -> add(DistancePeer::FEATUREVECTORTYPE_ID, $featurevectortypeid);
		$relatedCriteria -> add(DistancePeer::DISTANCETYPE_ID, $distancetypeid);

		// set order defaults to asc if a track is given -> otherwise the order makes no sense and cost awfully much query time
		$relatedCriteria -> addAscendingOrderByColumn(DistancePeer::VALUE);

		return $relatedCriteria;
	}

	private function buildQuery($metadataquery, $limit = 0, $offset = 0) {
		$relatedCriteria = new Criteria();
		$relatedCriteria -> addJoin(FiledescPeer::TRACKNR, FilePeer::EXTERNAL_KEY);

		if (strlen($metadataquery) > 0) {
			$relatedCriteria = $this -> addMetadataCriteria($metadataquery, $relatedCriteria);
		}

		if ($limit == 0)
			$relatedCriteria -> setLimit($this -> getContext() -> getConfiguration() -> getSmintMaxQueryResults());
		if ($limit > 0)
			$relatedCriteria -> setLimit($limit);
		if ($offset > 0)
			$relatedCriteria -> setOffset($offset);

		return $relatedCriteria;
	}

	private function addMetadataCriteria($metadataquery, $relatedCriteria, $columnnames = array("genre", "title", "performers")) {
		// add metadata query

		// loop over columnnames that need to be searched
		if (count($columnnames) > 0) {
			//first create the criteria for one columnname
			$crit_Metadata = $relatedCriteria -> getNewCriterion(constant('FiledescPeer::' . strtoupper($columnnames[0])), '%' . $metadataquery . '%', Criteria::ILIKE);
			// for the rest of the columnnames -> i=1
			for ($i = 1; $i < count($columnnames); ++$i) {
				$crit_next_Metadata = $relatedCriteria -> getNewCriterion(constant('FiledescPeer::' . strtoupper($columnnames[$i])), '%' . $metadataquery . '%', Criteria::ILIKE);
				$crit_Metadata -> addOr($crit_next_Metadata);
			}
		}
		//$relatedCriteria->setIgnoreCase(true);
		$relatedCriteria -> add($crit_Metadata);

		return $relatedCriteria;
	}

}
