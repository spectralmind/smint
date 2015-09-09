<pre>
	
	There was a server error: <br>
	
	<?php print_r((array)$error_message) ?>
	
		
</pre>

<script language="javascript" type="text/javascript">
  var error_message = '<?php echo json_encode((array)$error_message) ?>';
  window.top.window.showError(error_message);
</script>


<?php require_once dirname(__FILE__).'/../../../templates/ga-tracking.php' ?>
