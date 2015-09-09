<?php use_helper('JavascriptBase') ?>
<?php include_javascripts_for_form($formfileupload) ?>
<?php include_stylesheets_for_form($formfileupload) ?>
<div id="f1_upload_process"><?php echo image_tag('loading.gif') ?></div>
<div class="loadingIndicator"></div>
<div class="container">
  <div class="logo_container">
	<div class="spec_logo">
		<a href="http://www.spectralmind.com" target="_blank">
			<img src="<?php echo image_path('spec_logo_128_109.png')?>" alt="spectralmind audio intelligence" />
		</a>
	</div>
	<div class="search_logo">
		<a href="<?php echo url_for('search/index')?>">
			<img src="<?php echo image_path('search_logo_171_171_beta.png')?>" alt="SEARCH by Sound" />
		</a>
	</div>
	<div class="getty_logo">
		<a href="https://secure.gettyimages.co.uk/music" target="_blank">
			<img src="<?php echo image_path('getty_235x30.png')?>" alt="spectralmind audio intelligence" />
		</a>
	</div>
	<div id="userinfo_logout_container">
	<?php echo link_to('<img src="'.image_path('btn_help.png').'" alt="HELP" class="help" />','search/tutorial', array('popup' => true)) ?>
	<?php echo link_to('<img src="'.image_path('btn_logout.png').'" alt="Logout" class="logout" />','sfGuardAuth/signout') ?></div>
  </div>
  <div class="history_left"> <!-- end .sidebar1 --></div>

  <div class="search_content">
    <div class="query_content" id="query">
      <?php echo $formfileupload->renderFormTag('search/upload', array("id" => "uploadform", "target" => "upload_target" )) ?>

<!-- fileid info for uploadprogress - has to be before the file input field  -->
      <?php $fileid = md5(microtime() . rand()); //used to identify file for progress info ?>
      <input type="hidden" name="UPLOAD_IDENTIFIER" id="UPLOAD_IDENTIFIER" value="<?php echo $fileid;?>" />
      <div class="upload_button"><a href="#" class="upload_btn"><img class="mouseoverfunction_on_over" src="<?php echo image_path('buttons/buttons_single/btn_folder_30x30.png')?>" name="clear_meta" width="33" height="33" border="0" id="upload_button" /></a></div>
      <div class="clear_meta_button"><a href="#" onclick="clearMetadata();" class="clear_meta_btn"><img class="mouseoverfunction_on_over" src="<?php echo image_path('buttons/buttons_single/btn_clear_30x30.png')?>" name="clearmeta" width="33" height="33" border="0" id="clearmeta" /></a></div>
      <div class="clear_upload_button"><a href="#" onclick="clearFile();" class="clear_upload_btn"><img class="mouseoverfunction_on_over" src="<?php echo image_path('buttons/buttons_single/btn_clear_30x30.png')?>" name="clearfile" width="33" height="33" border="0" id="clearfile" /></a></div>

	    <div class="search_button">
        <input class="mouseoverfunction_on_over" id="submitButton" tabindex=3 name="submitButton" value="Upload" type="image" src="<?php echo image_path('/images/buttons/buttons_single/btn_search_93x33.png') ?>" />
  	    <div class="input_file_name_container"> <input tabindex=1 type="text" id="file_name" readonly="readonly" /> </div>      
  	    <div class="input_metadata_container"> <input tabindex=2 type="text" id="metadata_input" onKeyPress="return suppressEnter(event)"/> </div>      
        <div id="fileuploadform_container"><?php echo $formfileupload['mp3']->render(array('onchange' => 'updateFile();')) ?> </div>
      </div>

      <div id="upload_track_tite"><p class="indented_text grey_text">Upload your own track:</p></div>
      <div id="metadata_input_title"><p class="indented_text grey_text">Enter artist, title or genre:</p></div>

      <?php echo $formfileupload['metadataquery']->render(array('style'=>'visibility:hidden')) ?> 
      <?php echo $formfileupload['_csrf_token']; ?>
      <!-- Either p.result should be shown, or p.f1_upload_process, or none -->
      </form>    
    </div>
    
    <div class="seed_content" id="seed" style="display:none">    
     <!-- <p class="indented_text query_text">Showing results for</p>
      <div id="query_track_name" class="indented_text query_text">-->
          <!--  name of the mp3 file inserted by js 
      </div>-->
      <div id="query_track_player_div_container"> <div id="query_track_player_div"></div> </div>
      
    </div>
    <div class="result_content" id="result_element" style="display:none">    
      <!--<div class="result_content_header" id="result_header">
        <p class="indented_text yellow_text">Results of your search</p>
      </div>-->
    	<div class="result_content_list" id="result_list">
        <div>

          <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="flash_notice">
              <?php echo $sf_user->getFlash('notice') ?>
            </div>
          <?php endif; ?>

          <?php if ($sf_user->hasFlash('error')): ?>
            <div class="flash_error">
              <?php echo $sf_user->getFlash('error') ?>
            </div>
          <?php endif; ?>

            <div id="query_result"></div>        

        </div>
      </div>   
     <!-- <div class="result_content_footer" id="result_footer">
        <p id="result_content_footer_text" class="indented_text yellow_text"></p>
      </div>-->
      
    </div>
    

  <!-- end .content --></div>
  <div class="history_right"> <!-- end .sidebar2 -->
    
  </div>

  <p>&nbsp;</p>
  
  <!-- end .container --></div>



<?php 






  $relatedLink = url_for("search/related");
  $relatedTrackLink = url_for("search/relatedtrack");
  $metadataLink = url_for("search/metadata");
  $progressLink = url_for("ajax/fileuploadstatus");

  echo javascript_tag("
  $(function(){mouseoverImageChanger('mouseoverfunction_on_over');});
  
  
  function showWorkingIndicator() {
    $('#f1_upload_process').show();
	$('.loadingIndicator').show();
  }
  function hideWorkingIndicator() {
    $('#f1_upload_process').hide();
	$('.loadingIndicator').hide();
  }

  function clearMetadata(){
    $('#fileupload_metadataquery').val('');
    $('#metadata_input').val('');
    //clearResult();
    
    // requery
    $('#metadata_input').keyup(); //fire event, everything else is done there 
  }
  
  function clearFile(){
    $('#fileupload_mp3').val(''); // can't do that because of security issues in some browsers (IE)
    $('#fileupload_mp3').replaceWith($('#fileupload_mp3').clone(true));  // workaround
    updateFile();
  }
  function updateFile(){
   
 	var path = $('#fileupload_mp3').val();
	if(path.indexOf('fakepath') != -1) var file = path.substr(12);
	else file = path;
	$('#file_name').val(file);
  	
    relatedQuery = new relatedQueryObj();
    clearResult();
  }
  
  function clearResult(){
    $('#query_result').innerHTML='';
    $('#query_track_name').innerHTML='';
    $('#query_track_player_div').innerHTML='';
    //$('#result').innerHTML=''; // this element seems not to exist anymore!
    $('#result_element').hide();
    $('#seed').hide();
	
	// stop player
	if (typeof($('#jquery_jplayer_1').jPlayer)  != 'undefined') $('#jquery_jplayer_1').jPlayer('pause');
	if (typeof($('#jquery_jplayer_2').jPlayer)  != 'undefined') $('#jquery_jplayer_2').jPlayer('pause');
  }
  
  /** checks if the node with the seed player exists. If yes, it is detached.
   * @return true if the node was actively detached. False if no action has been performed
   */
  function clearSeedplayer() {
  	if ($('div#query_track_player_div>div#result_query_track').length > 0) {
		// only detach the player
		$('div#query_track_player_div>div#result_query_track').detach();
		return true;
	}
	return false;
  }
  
  function showError(errormessage) {
    errormessage = nl2br(errormessage, true); 
    var errorinfo = '<span class=\"emsg\" style=\"color: black; padding:5px;\">There was an error during file upload! ' + errormessage + '<\/span><br/><br/>'; 
    $('#query_result').html(errorinfo);
    $('#result_element').slideDown('slow');
  }  
  
  /** this function gets called if uploaded file has already been uploaded AND also if new file has been uploaded (via _fileupload.php)
   * Possible changes are:
   * - metadata
   * - different selection of range (introduced with segmented smint)
   */
  function smintSearch(uploadedFilename, originalFilename, metadataquery, queryTrackId, page, segmentStart, segmentEnd, duration){
    var metadataquery = (typeof(metadataquery) == 'undefined') ? relatedQueryObj.metadataquery : metadataquery ;
    var uploadedFilename = (typeof(uploadedFilename) == 'undefined') ? relatedQueryObj.uploadedFilename : uploadedFilename ;
    var originalFilename = (typeof(originalFilename) == 'undefined') ? relatedQueryObj.originalFilename : originalFilename ;
    var queryTrackId = (typeof(queryTrackId) == 'undefined') ? relatedQueryObj.queryTrackId : queryTrackId ;    
    var page = (typeof(page) == 'undefined') ? 1 : page ;
    var segmentStart = (typeof(segmentStart) == 'undefined') ? relatedQueryObj.segmentStart : segmentStart ;    
    var segmentEnd = (typeof(segmentEnd) == 'undefined') ? relatedQueryObj.segmentEnd : segmentEnd ;    
    
	var duration = 	(typeof(segmentEnd) == 'undefined') ? $('.fov-seek').attr('duration') : duration ;
	
    relatedQuery = new relatedQueryObj(uploadedFilename,originalFilename,metadataquery, uploadedFilename!='',  queryTrackId , segmentStart, segmentEnd, duration);
    var query_url = '';
    var moreParamsArray = {};
    moreParamsArray['page']=page;
    var moreParams = $.param(moreParamsArray);
    
    if (uploadedFilename == '' && (queryTrackId == '' || typeof(queryTrackId) == 'undefined')) {
      var query_url = relatedQuery.getQueryString('$metadataLink', moreParams);
    } else {
      var query_url = relatedQuery.getQueryString('$relatedLink', moreParams);
    }
	
	/*
	// empty upload your track input field if we have a search similar trac,
	if ( ! (queryTrackId == '' || queryTrackId == 'undefined' )) {
		$('#file_name').val('');
	}
	*/
	
	
	showWorkingIndicator();
	$('#result_element').slideUp('fast'); 
	
    $.ajax({
       url: query_url,
       dataType:'html',
       success: function(data) {
         if ( $('#file_name').val() != '' ) {
           $('#file_name').val('100 %' + ' - ' + relatedQuery.originalFilename) ;
         }
         $('#query_result').html(data);
       }
     })
  }   
  
// uses ajax to poll the uploadprogress.php page with the id
// deserializes the json string, and computes the percentage (integer)
// update the jQuery progress bar
// sets a timer for the next poll in xxxx ms
  function showUploadProgress(fileid, progresslink, start_time) {
    var start_time = (typeof start_time == 'undefined') ? new Date().getTime() : start_time;
    
    $.get(progresslink+'?fileid='+fileid, 
      function( output, status, xhrdata ) {
        var data = jQuery.parseJSON(xhrdata.getResponseHeader('X-Json'));

        // return value is false if the server does not support progress info 
        if (data==false) 
          {
            // add code that handles the missing package - http://pecl.php.net/package/uploadprogress
            // alert('upload progress meter not supported');
            $('#file_name').val('?? %' + ' - ' + $('#fileupload_mp3').val()) ;
            return;
          }

        // return value is null if the server does not have information on the progress (before and after the upload) 
        if (!data) 
          return;

        var percentage = Math.floor(100 * parseInt(data['bytes_uploaded']) / parseInt(data['bytes_total']));
        $('#file_name').val( percentage + ' %' + ' - ' + $('#fileupload_mp3').val() ) ;
        var call = 'showUploadProgress(\"' + fileid + '\",\"' + progresslink + '\",' + start_time +')';
        setTimeout(call, 1000);
        return;
      }
    );
    
    // try for 3 seconds to get info on upload progress 
    var elapsed = new Date().getTime() - start_time;
    if (elapsed < 3000) {
      var call = 'showUploadProgress(\"' + fileid + '\",\"' + progresslink + '\",' + start_time +')';
      setTimeout(call, 1000);
    }
  }   
  


  function fillInSearchHint(){
    $('#metadata_input').val('Enter a search term to get started.');    
	$('#metadata_input').select();
  }

  // add function to prevent upload if form is empty

    $('#uploadform').submit(function() {
         $('#fileupload_metadataquery').val($('#metadata_input').val());
         var metadataTermSelected = ( $('#fileupload_metadataquery').val() == '' ) ? false : true ; 
         var queryFileSelected = ( $('#fileupload_mp3').val() == '' ) ? false : true ;
		
         // check for empty search
        	if (!metadataTermSelected && !queryFileSelected && !relatedQuery.isFileUploaded && !(relatedQuery.queryTrackId != '')) {
			fillInSearchHint();
			 return false;         	
         } 
         
         showWorkingIndicator(); 
		 $('#result_element').slideUp('fast'); 
 		 $('#seed').slideUp('fast'); 
		
         // check if the files was already uploaded -> only change metadata and selection
         if ( relatedQuery.isFileUploaded ) {
           //$('#result_element').hide();
           smintSearch(relatedQuery.uploadedFilename,relatedQuery.originalFilename,$('#fileupload_metadataquery').val(), 'undefined', 'undefined', $('.fov-seek').attr('select_start'), $('.fov-seek').attr('select_end'));
           return false; 
         }

         // if no query file is selected query without upload
         if (!queryFileSelected) {
           //$('#result_element').hide();
           smintSearch('','',$('#fileupload_metadataquery').val());
           return false; 
         }
         
         // if a query file is selected and it was not uploaded -> checked before 
         if ( queryFileSelected ) {
         	//alert('if a query file is selected and it was not uploaded -> checked before');
             var fileid=\"$fileid\";
             var progresslink=\"$progressLink\";

             // wait until the actual submit happend (a second) before updating the upload progress
             var call = 'showUploadProgress(\"' + fileid + '\",\"' + progresslink + '\")';
             setTimeout(call,1000);  
             return true;
         } 
       });



// start search after defined period for metadata_input field 
  delayFunction = new delayFunctionObj(500);
  delayFunction2 = new delayFunctionObj(500);
  
  $(document).ready(function(){
	 
			
      $('#metadata_input').keyup(function(){
          // Retrieve the input field text and reset the count to zero
          
          var metadataTermSelected = ( $('#metadata_input').val() == '' ) ? false : true ; 
           var queryFileSelected = ( $('#fileupload_mp3').val() == '' ) ? false : true ;
           
            // check for empty search
        	if (!metadataTermSelected && !queryFileSelected && !relatedQuery.isFileUploaded && !(relatedQuery.queryTrackId != '')) {
        		clearResult();
				fillInSearchHint();
			 	return false;         	
         	} 
         
		 

		  // only if we really changed the text. It seems that the event itself is fired only if I move the cursor
		  if ( $('#metadata_input').val() != relatedQuery.metadataquery) {
		  
			  var call = 'smintSearch(\"' + relatedQuery.uploadedFilename + '\",\"' + relatedQuery.originalFilename + '\",\"' + $('#metadata_input').val() +'\", \"'+ relatedQuery.queryTrackId +'\", 1, \"' + relatedQuery.segmentStart + '\", \"' + relatedQuery.segmentEnd + '\", \"' + relatedQuery.duration + '\")'; 
    	      delayFunction.delayedFunctionCallWithTimeoutUpdate(call);
		  
		  } 
		  
      });
	  	
	$(document).on('selectionChanged','.fov-seek',function(){
		// check if selection is valid
		
	  	var select_start = $('.fov-seek').attr('select_start');
		var select_end = $('.fov-seek').attr('select_end');
		
		var old_select_start = relatedQuery.segmentStart;
		var old_select_end = relatedQuery.segmentEnd;
		
		
		if (    typeof(select_start) != 'undefined' && typeof(select_end) != 'undefined') {
//			if (select_start != 0 || select_end != 0) {
	
			// check if there really is some change 
			if (! ( old_select_start == select_start && old_select_end == select_end   )  ) {
	  			
				var call = 'smintSearch(\"' + relatedQuery.uploadedFilename + '\", \"' + relatedQuery.originalFilename + '\", \"' + $('#metadata_input').val() +'\", \"'+ relatedQuery.queryTrackId +'\", 1, \"' + select_start + '\", \"' + select_end + '\", \"' + $('.fov-seek').attr('duration') + '\")';
          		delayFunction2.delayedFunctionCallWithTimeoutUpdate(call);
				
			}
				
//			} // one value is != 0
		} // both values are defined
	});    
	 

  });


  ") ?>



<div>  
  <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
</div>
 
 
 
<!-- BEGIN: check if query parameters where given:  -->
<?php if (count( $requestQueryParameters ) > 0 ): ?>

    <!-- only if a parameter was given.  -->
    <!-- featurevectortypeid and distancetypeid are set in the action -->
    <?php if (array_key_exists("existingUploadedFile", $requestQueryParameters) || array_key_exists("uploadedFilename", $requestQueryParameters)): ?>
        <?php 
		include('wave.php');
          if (array_key_exists("existingUploadedFile", $requestQueryParameters)) {
            $existingUploadedFile = $requestQueryParameters["existingUploadedFile"];		
          }
          if (array_key_exists("uploadedFilename", $requestQueryParameters)) {
            $existingUploadedFile = $requestQueryParameters["uploadedFilename"];		
          }
          
          if (array_key_exists("originalFilename", $requestQueryParameters)) {
            $originalFilename = $requestQueryParameters["originalFilename"];
          } else {
            $originalFilename = $existingUploadedFile;
          }
          
          $existingUploadedFile = addslashes( $existingUploadedFile ); 
          $originalFilename = addslashes( $originalFilename ); 
          echo javascript_tag("smintSearch('$existingUploadedFile', '$originalFilename');");
          
        ?>
    <?php endif ?>
<?php endif ?>
<!-- > END: check if query parameters where given:  -->














