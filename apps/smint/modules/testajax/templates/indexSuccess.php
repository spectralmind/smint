<?php use_helper('JavascriptBase') ?>


<style>
.ui-autocomplete-loading { background: white url('<?php echo  image_path("loader.gif") ?>') right center no-repeat; }
</style>
<?php $ajax_url=url_for("ajax/genre"); ?>
<?php echo javascript_tag('
  $(function() {

    $( "#tags" ).autocomplete({
      source: "'.$ajax_url .'",
      minLength: 1,
    });
  });
  ');
?>

<div id="search_form" style="background:white;">
  <div class="demo">

  <div class="ui-widget">
    <label for="tags">Tags: </label>
    <input id="tags" />
  </div>

  </div>
</div>
