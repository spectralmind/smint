<!-- BEGIN _result -->
<?php use_helper('JavascriptBase')
?>

<?php
// init values

$columnnames = sfConfig::get('app_names_columnnames');
$showcolumns = mysfConfig::get('app_view_columns');
$showoptions = mysfConfig::get('app_view_options');

$tracknr = (isset($tracknr)) ? $tracknr : "NOTRACKNR";

$queryTrackIsUploaded = (isset($queryTrackIsUploaded)) ? $queryTrackIsUploaded : false;

$distancetypeid = sfConfig::get('app_defaults_distancetypeid', 0);
$featurevectortypeid = sfConfig::get('app_defaults_featurevectortypeid', 0);

// init rating system
$maxScore = 4;
$ratingColumnWidth = ($maxScore * 17 + 17) . 'px';

$queryString = "";
$original_queryString = "";
$seedLabel = (isset($seedLabel)) ? $seedLabel : "";
$metadataquery = (isset($metadataquery)) ? $metadataquery : "";
$empty_metadatafilteredquery = (isset($empty_metadatafilteredquery)) ? $empty_metadatafilteredquery : false;
?>

<!-- if the result contains md queries paginate  -->
<div id="paginate_controls">
	<?php if (isset($filespager)):
	?>
	<?php $files = $filespager->getResults()
	?>
	<!-- Paginate Page -->
	<?php if ($filespager->haveToPaginate()):
	?>
	<!--<div class="pagination">
		<a href="javascript: var page = <?php // echo $filespager->getFirstPage() ?>; smintSearch(relatedQuery.uploadedFilename,relatedQuery.originalFilename,$('#fileupload_metadataquery').val(),null, page);">|<</a>
		<a href="javascript: var page = <?php // echo $filespager->getPreviousPage() ?>; smintSearch(relatedQuery.uploadedFilename,relatedQuery.originalFilename,$('#fileupload_metadataquery').val(),null, page);"><</a>
		<?php // foreach ($filespager->getLinks() as $page):
		?>
		<?php // if ($page == $filespager->getPage()):
		?>
		<?php // echo $page
		?>
		<?php // else:?>
		<a href="javascript: var page = <?php // echo $page ?>; smintSearch(relatedQuery.uploadedFilename,relatedQuery.originalFilename,$('#fileupload_metadataquery').val(),null, page);">
			<?php // echo $page
		?></a>
		<?php // endif;?>
		<?php //endforeach;?>
		<a href="javascript: var page = <?php // echo $filespager->getNextPage() ?>; smintSearch(relatedQuery.uploadedFilename,relatedQuery.originalFilename,$('#fileupload_metadataquery').val(),null, page);">></a>
		<a href="javascript: var page = <?php // echo $filespager->getLastPage() ?>; smintSearch(relatedQuery.uploadedFilename,relatedQuery.originalFilename,$('#fileupload_metadataquery').val(),null, page);">>|</a>
	</div>-->
	<?php endif;?>
	<!-- END: Paginate Page -->
	<?php endif?>
</div>
<!-- END: if the result contains md queries paginate  -->
<!-- create hidden div to update query track in query form  - do not remove this div-->
<div>
<!--div style="visibility:hidden;position:fixed;"-->
	<div id="result_query_track">
		<?php if (isset( $uploadedFilePHPUrl_incomplete )):
		?>
		
		<?php /** BEGIN PLAYER - SEED SONG 
		 * 		
		 * IMPORTANT NOTE:
		 * 
		 * Javascript functions below expect the DOM to be in a certain state.
		 * More specifically, this structure is expected:
		 * 
		 * div#query_result, div, div#result_query_track
		 * div#query_result, div, div#result_query_track_name
		 * 
		 * 
		 * **/ 
		
		?>


<div id="jquery_jplayer_2" class="jp-jplayer"></div>
<!-- Overview -->
<div class="overview-container">
<!-- JPlayer-->
<div style="height:1px;"></div>
		<div id="jp_container_2" class="jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">			
					<ul class="jp-controls" >
						<li><a href="javascript:;" class="jp-previous" tabindex="1"  style="display:none;">previous</a></li>
						<li><a href="javascript:;" class="jp-play ov-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause ov-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-next" tabindex="1" style="display:none;">next</a></li> 
						<!--<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li> 
						<li><a href="javascript:;" class="ov-mute jp-mute " tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute ov-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max ov-volmax" tabindex="1" title="max volume">max volume</a></li>-->
					</ul>
				
					</div>
					<!--<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>-->
					
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
</div>
	

<!--End Jplayer-->

<div class="metadata"></div>
	<div class="overview-duration"></div>
	<div class='overview-progress'>	
			<div class='overview-play'>			
				<div class="overview-current"></div>					
			</div>
	<div class='overview-play-bar'></div>			
	<div id="segment-highlight"></div>	
	<div class='overview-seek'>
				
			<div id="overview-slidermin" class="ui-slider-handle"></div>
			<div id="overview-slidermax" class="ui-slider-handle"></div>
			
	</div>
	<div class="viewport"></div>
	</div>

	
	<div style="clear:both;"></div>
	<div class="placeholder"></div>
	<div class="fov-container">	
		<div class='fov-progress' >
			<div class="fov-time-min"></div>
			<div class="fov-time-max"></div>
			<div class="fov-current"></div>
				<div class='fov-seek scroll-pane' >
					<div id="waveform_status_msg">(loading waveform)</div>
					<div class="scroll-content" id="a" ></div>
					<div id="fov-slidermin" class="ui-slider-handle"></div>
					<div id="fov-slidermax" class="ui-slider-handle"></div>
					<div class='fov-play'>
					</div>		
				</div>
				<div class="go-to-play"></div>
				<div class="zoom-plus"></div>
				<div class="zoom-minus"></div>	
		</div>	
	</div>
	
</div>
<div class="wf-toggle"></div>
<script type="text/javascript">
//<![CDATA[
	
// check if player exists in #seed
	clearSeedplayer();
		
	$(document).ready(function(){ 
		$("#jquery_jplayer_2").jPlayer({

<?php
		echo MP3PlayerHelper::getMp3PlayerConfig4Seedsong($uploadedFilePHPUrl_incomplete, $uploadedFileURL);
		?>

		});
	initMain(2);	
	
	
<?php
	// Check if segment has been selected.
	if (isset($aSegmSearch) && is_array($aSegmSearch) && isset($aSegmSearch['enabled']) && $aSegmSearch['enabled'] ) {
		// yes, so pre-select this selection
		echo "
			// open waveform drawer;
			toggleWaveformdrawer(2, false);
			// set correct background after segment selection		
		";
		
		// set position
			if (isset($aSegmSearch['duration']) && isset($aSegmSearch['startSegment'])&& isset($aSegmSearch['endSegment']) &&  $aSegmSearch['endSegment'] != 0) {
				echo "posSegment(2, " . $aSegmSearch['startSegment']. ", " . $aSegmSearch['endSegment']. ", " . $aSegmSearch['duration']. "); ";
				 }
				 
	}
	
	?>
	
	});
	


//]]>
</script>




		
		<?php
		//echo MP3PlayerHelper::getMp3PlayerConfig4Seedsong($uploadedFileId, $uploadedFileURL);
		?>
	
		<?php /** END PLAYER - SEED SONG **/ ?>

	
		
		
		<?php
		// wordpress player 
		//echo MP3PlayerHelper::mp3PlayerTag(array('trackURI' => $uploadedFile, 'flashplayerid' => "query_track_player", 'customSkin' => "transparentpagebg:'yes',bg: '666666',leftbg : 'FBF315',lefticon: '424242',rightbg: 'FBF315',righticon:'424242',rightbghover:'928D0C', righticonhover:'ffffff',voltrack: 'cccccc',volslider:'424242',track:'ffffff',tracker:'cccccc'", 'customSkinImage' => "worldpress-seed-180.png", 'customSkinImageMouseover' => "worldpress-seed-180-mouseover.png", ), array());
		?>
		
		
		<?php endif   // if (isset( $uploadedFilePHPUrl_incomplete ?>
	</div>
		<?php
		/*
		$queryString = "$seedLabel";
		if (strlen($metadataquery) > 0) {
			// if metadata query term was given
			$queryString = (strlen($seedLabel) > 0) ? "$queryString & $metadataquery" : "$metadataquery";
		}
		if ($empty_metadatafilteredquery) {
			// if the metadata query returned emtpy results show that we skipped the metadata query
			$queryString = "$seedLabel";
			$original_queryString = "Search for: $queryString & $metadataquery";
		}
		$seedsongmetadata =  htmlentities("$queryString", ENT_COMPAT, "UTF-8");
		//dont forget utf-8 !!!
		 * */
		
		/*
		 * // Original search term
		echo " </p>";
		echo "<br>";
		echo htmlentities("$original_queryString", ENT_COMPAT, "UTF-8");
		//dont forget utf-8 !!!
		 * */
		?>
</div>
<!-- END: create hidden div to update query track in query form -->
<!-- javascript used to show result_element and seed.  -->
<?php
echo javascript_tag("
// fill seed div





/* Version 3: 
	If player node present in #seed, detach this player node.
	Then, detach the player node in the result area and attach it in the seed area
	*/

	var removedSeedPlayer = clearSeedplayer();			
			
		
	// move div#result_query_track to  div#query_track_player_div
	$('#result_query_track').detach().prependTo('#query_track_player_div');
	
	// top left label in player ('numresults')
	$('#jp_container_2 .jp-interface').append(\"<div class='numresults'>" . htmlentities(smintTools::truncate_for_output($seedlabel_topleft, 65, true), ENT_COMPAT, "UTF-8"). "</div>\");
	
	// set metadata for seed player
	insertMetadataToSeedplayer(\"" . htmlentities($seedLabel, ENT_COMPAT, "UTF-8") . "\");
	
	// empty $('#result_query_track_name').html()
	$('#result_query_track_name').html('');




/* Version 2: 
	If player node present in #seed, detach this player node.
	Then, detach the player node in the result area and attach it in the seed area
	*/
/*
	var removedSeedPlayer = clearSeedplayer();			
			
		
	// move div#result_query_track to  div#query_track_player_div
	$('#result_query_track').detach().prependTo('#query_track_player_div');
	
	// move #result_query_track_name to #query_track_name
	$('#query_track_name').html( $('#result_query_track_name').html() );
	
	// empty $('#result_query_track_name').html()
	$('#result_query_track_name').html('');

*/



/* Version 1: 
	If player node NOT present in #seed, move the player node from result area (added by ajax) to #seed area
	Otherwise: detach the player node in the result area
	Problem: if we uplaod another track, this track is never shown in seed area
if ($('div#query_track_player_div>div#result_query_track').length == 0) {
	//alert('node does not exist');
	// move div#result_query_track to  div#query_track_player_div
	$('div#result_query_track').detach().prependTo('div#query_track_player_div');
	// move #result_query_track_name to #query_track_name
	$('#query_track_name').html( $('#result_query_track_name').html() );
	// empty $('#result_query_track_name').html()
	$('#result_query_track_name').html('');
} else {
	//alert('node exists');
	// only detach the player
	$('div#query_result>div>div#result_query_track').detach()
	// empty $('#result_query_track_name').html()
	$('div#query_result>div>#result_query_track_name').html('');
}
*/


// slide down divs
$('#result_element').slideDown('slow');
if (!removedSeedPlayer) $('#seed').slideDown('slow');

// move paginate controls to the footer
$('#result_content_footer_text').html( $('#paginate_controls') );

")
?>
<!-- END: javascript used to show result_element and seed.  -->
<table class="smint_result_table full_width <?php echo $render ?>">
	<!-- error handling for empty result sets      -->
	<?php
		if (!isset($files)) {
			$files = array();
		}
	?>

	<?php if (count($files) == 0 ):
	?>
	<?php if (isset($empty_livequery) && $empty_livequery):
	?>
	<p class="indented_text error">
		There are no results from your uploaded file ! Sure you uploaded a proper mp3 file ?
	</p>
	<?php else:?>
	<p class="indented_text error">
		Your query does not return results. 
		
		<?php if (isset( $uploadedFilePHPUrl_incomplete )): ?>
		
		<a href="javascript: clearMetadata()">Try again without filter</a>.
		
		<?php endif?>
	</p>
	<?php endif?>
	
	<?php else: // if (count($files) == 0 ?>
	
	<!-- END: error handling for empty result sets      -->
	

	<?php /** BEGIN PLAYER - RESULT LIST **/ ?>
	
	<div id="jquery_jplayer_1" class="jp-jplayer" ></div>
	
	
		<!-- JPlayer(controls)-->
		<div id="jp_container_1" class="jp-audio reslist_container" style="margin:0px auto;">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface ">			
					<ul class="jp-controls" >
						<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
						<!--<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>-->
					</ul>			
					</div>
					<!--<div class="jp-volume-bar rs-volume">
						<div class="jp-volume-bar-value"></div>
					</div>-->			
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
	<!-- JPlayer(controls) end-->	
	
	
	
<!-- Playlist -->
<div id="listwrap" style="float:none">
</div>	



<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	  
				/*Load the first song into the player*/
			$("#jquery_jplayer_1").jPlayer({
				ready: function (event) {	
					//Load the first track
					$(this).jPlayer("setMedia", {
						mp3:$("#jquery_jplayer_1").jPlayer("option", "playlist")[0].audiofile_mp3,
						oga: $("#jquery_jplayer_1").jPlayer("option", "playlist")[0].audiofile_ogg
					});
				$(".song0").parent().parent().removeClass("item");	
				$(".song0").parent().parent().addClass("playing");	
				$("#jquery_jplayer_1").jPlayer("option", "cssSelector", {seekBar: ".jp-seek-bar0"});
				$("#jquery_jplayer_1").jPlayer("option", "cssSelector", {playBar: ".jp-play-bar0"});
						
				},	
				play: function() { // To avoid both jPlayers playing together.
					$(this).jPlayer("pauseOthers");
				},
				cssSelectorAncestor: "#jp_container_1",
				swfPath:  <?php echo "'" . _compute_public_path('', 'smintplayer/js', '', false) . "'" ?>  ,
				supplied: "mp3, oga",
				solution: "flash, html",
				wmode: "window",
				preload: "auto"
				
						<?php
			echo MP3PlayerHelper::getMp3PlayerConfig($files, $aSegmSearch);
		?>
					});
								
	initResults(1);
	
	/*Resultlist dropdown menu init and options*/
	$('.dropMenu').nmcDropDown({trigger: 'click'});
	});
//]]>
</script>

	<?php /** END  PLAYER - RESULT LIST **/ ?>
	
<!--
<?php
		echo MP3PlayerHelper::getMp3PlayerConfig($files, $aSegmSearch);
	?>
-->



<?php endif // else of results empty?

?> 



<!-- search similar functions -->

  <?php foreach ($files as $i => $file): ?>   
  	
  	  <?php $currentid =  smintTools::generateHtmlId($file->getTracknr()) ?>
  	  <?php $smintRequestParameters = array() ?>
      <?php $smintRequestParameters['tracknr'] =  $file->getTracknr() ?>
      <?php $smintRequestParameters['metadataquery'] =  $metadataquery ?>
      <?php 
      //$relatedUrlsmint =  url_for('search/relatedtrack').'?'.http_build_query($smintRequestParameters) 
      $relatedUrlsmint =  url_for('search/related').'?'.http_build_query($smintRequestParameters) 
      ?>

  	
  	  <?php 
                     $currentFileTracknr = $file->getTracknr(); 
                     $ajax_call = "\$.ajax({
                        type: 'POST',
                        url: '$relatedUrlsmint',
                        dataType:'html',
                        success: function(data) { \$('#query_result').html(data); },
                        beforeSend: function(data) { 
                          relatedQuery = new relatedQueryObj(relatedQuery.uploadedFilename, relatedQuery.originalFilename, \$('#metadata_input').val(),false, '$currentFileTracknr', 'undefined', 0, 0 );
                          //relatedQuery = new relatedQueryObj('','',\$('#metadata_input').val(),false, '$currentFileTracknr');
                          
                          // empty file upload input field
                          // we dont do that because it is confusing to click SEARCH again and then search with the empty field
                          // \$('#file_name').val('');
                          
                          showWorkingIndicator(); 
                          \$('#result_element').slideUp('fast');
                          \$('#seed').slideUp('fast');  
                         
                          },
                      })"; 
                      $js_string = "function smint_{$currentid}(){ $ajax_call } ";
                      echo javascript_tag($js_string); 
                   ?>
  	
  <?php endforeach; ?>


	
</table>
<script language="javascript" type="text/javascript">
	// hide upload progress indicator after all results are shown
	hideWorkingIndicator();
	// ready script to change image on mouseover
	$(function() {mouseoverImageChanger('mouseoverfunction_on_over');
	});

</script>



<?php require_once dirname(__FILE__).'/../../../templates/ga-tracking.php' ?>

<!-- END _result -->
