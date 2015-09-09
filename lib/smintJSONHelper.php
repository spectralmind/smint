<?php 

abstract Class smintJSONHelper {
  
  public static function jsonError(sfWebRequest $request, $errorMessage){
    $errorArray = array( "error" => $errorMessage);
    
    $output = json_encode($errorArray);
    sfContext::getInstance()->getResponse()->setHttpHeader('Content-type', 'application/json');
    sfContext::getInstance()->getResponse()->setHttpHeader("X-JSON", '('.$output.')');
    return sfView::HEADER_ONLY;    
  }
  
  public static function implode_with_key_4js($assoc)
  {
     return '{'.smintTools::implode_with_key($assoc,": '","',").'\'}';
  }

}

