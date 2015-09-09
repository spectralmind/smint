<?php

/**
 * provides access to the custom log (configured in ProjectConfiguration.class.php)
 *
 */
class mysfLog {


  public static function log($context, $message)
  {
    $user=sfContext::getInstance()->getUser();
    $userid = $user->getAttribute('userid');      
    
    $message = "userid{{$userid}} ".$message;
    
    sfContext::getInstance()->getLogger()->info($message);
    // sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent($context, 'user_actions.log', array($message)));
  }


  public static function logSmintWebRequestQueryParameters($context, $smintQueryParametersObject)
  {
    $logMessage = ''; 
    $smintQueryParameters = $smintQueryParametersObject->getSmintQueryParamaters(); 
    foreach ($smintQueryParameters as $parameter => $values) {
      $logMessage = $logMessage.$values[0]->toString();
    }
    
    self::log($context, $logMessage);
  }

  public static function logRequest($context, $request)
  {
    $logMessage = "request parameters: \n  "; 
    
    $parameters = $request->getParameterHolder()->getAll(); 
    
    $logMessage = $logMessage.smintTools::implode_with_key($parameters, ": ", "\n  ");
    
    self::log($context, $logMessage);
  }

  public static function logRequestFlat($context, $request)
  {
    $logMessage = "request parameters: ;"; 
    
    $parameters = $request->getParameterHolder()->getAll(); 
    
    $logMessage = $logMessage.smintTools::implode_with_key($parameters, ";", ";");
    
    self::log($context, $logMessage);
  }
  
}