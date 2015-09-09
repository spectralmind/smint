<?php

/**
 * Skeleton subclass for performing query and update operations on the 'featurevectorsegment' table.
 *
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Wed Aug 24 09:56:02 2011
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model.smafe
 */
class FeaturevectorsegmentPeer extends BaseFeaturevectorsegmentPeer {

	/** returns start sample and length of sample for given featurevectorsegment
	 *
	 */
	static public function getInformationOnSegment($track_id, $segmentnr, $featurevectortypeid) {

		// get id from file by uri
		$crit = new Criteria();
		$crit -> add(FeaturevectorsegmentPeer::TRACK_ID, $track_id);
		$crit -> add(FeaturevectorsegmentPeer::SEGMENTNR, $segmentnr);
		$crit -> add(FeaturevectorsegmentPeer::FEATUREVECTORTYPE_ID, $featurevectortypeid);
		$fvsegm = FeaturevectorsegmentPeer::doSelectOne($crit);
		return $fvsegm;
	}

} // FeaturevectorsegmentPeer