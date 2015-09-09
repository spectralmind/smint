<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
<!-- Browser detection css and js -->
	<?php use_stylesheet('browser-detection.css') ?>
	<?php use_javascript('browser-detection.js'); ?>
<!-- my stylesheets  -->
    <?php use_stylesheet('main.css') ?>
    <?php use_stylesheet('results.css') ?>
	
<!-- jquery css and js  -->
    <?php //use_stylesheet('jquery-ui.css') ?>
    <?php use_javascript('jquery.js'); ?>
    <?php use_javascript('jquery-ui.js'); ?>

<!-- jquery rating  -->
    <?php // use_stylesheet('rating.css') ?>
    <?php // use_javascript('jquery.rating.js'); ?>

<!-- mp3 player -->
    <?php // use_javascript('swfobject.js'); ?>
    <?php // use_javascript('audio-player.js'); ?>

<!-- smint js -->
    <?php use_javascript('smint.js'); ?>
    
<!-- smint player JS & CSS-->
    <?php 
    //use_javascript('http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js')
    // do not include since jquery has been included earlier 
    ?>
    <?php use_stylesheet('../smintplayer/css/jquery.ui.slider.css') ?>
    <?php use_stylesheet('../smintplayer/css/smintplayer.css') ?>
    <?php use_stylesheet('../smintplayer/css/custom.css') ?>
	<?php use_javascript('../smintplayer/js/smintplayer.js') ?>
	<?php use_javascript('../smintplayer/js/slider.js') ?>
	<?php use_javascript('../smintplayer/js/jquery.ui.touch-punch.min.js') ?>

<!-- css should be before js for performance reasons -->
    <?php echo get_stylesheets (); ?>

<!-- other js : should be at the end of all use_javascript calls -->
    <?php echo get_javascripts (); ?>
    
    
   
    <link rel="icon" type="image/png" href="<?php echo image_path("favicon.png") ?>" />



	<?php require_once dirname(__FILE__).'/ga-tracking.php' ?>

  </head>
  <body>
  <?php echo $sf_content ?>
  
  <!-- uservoice -->
  <script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/Od1dn0KYq4Ywg8G4EfG2oQ.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
  </script>


  </body>
</html>
