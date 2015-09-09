<?php use_helper('JavascriptBase') ?>
<?php 
// add escapes for quotes
  $originalFilename = str_replace("'", "\'", $fileInfo['originalFilename']);
  $filesavepath = str_replace("'", "\'", $fileInfo['filesavepath']);
  $uploadedfilename = str_replace("'", "\'", $fileInfo['filename']);
  $metadataquery = str_replace("'", "\'", $metadataquery);
  
// IMPORTANT NOTE: PHP Magic Quotes must be turned OFF  !!

echo javascript_tag("
 window.top.window.smintSearch('$uploadedfilename', '$originalFilename', '$metadataquery', 'undefined', 'undefined', 0, 0);
");


require_once dirname(__FILE__).'/../../../templates/ga-tracking.php';