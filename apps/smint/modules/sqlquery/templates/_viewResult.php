<?php echo "Query: $query\n\n" ?>



<?php if ( count($result) > 0 ) {
  $columnwidth = array();
  // set columnwidth headers
  foreach ($result[0] as $key => $value) {
    $columnwidth[$key] = strlen($key);
  }
  // set columnwidth entries
  foreach ($result as $key => $value) {
    foreach ($value as $ikey => $ivalue) {
      if ($columnwidth[$ikey] < strlen($ivalue)) {
        $columnwidth[$ikey] = strlen($ivalue);
      }  
    }
  }
  
  
  // headers
  foreach ($result[0] as $key => $value) {
    echo str_pad("$key", $columnwidth[$key], " ")." | ";
  }
  echo "\n";

  // line
  foreach ($result[0] as $key => $value) {
    echo str_pad("", $columnwidth[$key], "-")." | ";
  }
  echo "\n";
  
  
  // values
  foreach ($result as $key => $value) {
    foreach ($value as $ikey => $ivalue) {
      echo str_pad("$ivalue", $columnwidth[$ikey], " ")." | ";
    }
    echo "\n";
  }
} 
?>

<?php echo $exception ?>