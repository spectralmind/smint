<?php

/**
 * extends sfConfig and overwrites the static get method to check if a user specific 
 * configuration is available, otherwise it defaults to the standard configuration
 * 
 */
class mysfConfig extends sfConfig {

  public static function get($name, $default = null)
  {
    $primaryUserGroup = "";
    $viewPath='app_view_';
    $userGroups = sfContext::getInstance()->getUser()->getGroupNames(); 
    if ( array_key_exists('0', $userGroups) ) {
      $primaryUserGroup = $userGroups[0];
    } 
    
    $nameByGroup = str_replace($viewPath, $viewPath.$primaryUserGroup.'_', $name);
    
    $defaultValue = isset(self::$config[$name]) ? self::$config[$name] : $default;
    
    $value = isset(self::$config[$nameByGroup]) ? self::$config[$nameByGroup] :  $defaultValue;
    
    return $value;
  }

}