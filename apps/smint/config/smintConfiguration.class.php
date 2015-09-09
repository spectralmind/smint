<?php

class smintConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
  }
  
  /**
   * returns the configured maximum of results for smint-related queries
   * a max of 100 and a default of 3 will be used if other values are given 
   *
   * @return integer
   * @author jochum
   */
  public function getSmintMaxQueryResults(){
    $max = mysfConfig::get('app_view_query_maxrelated');
    $max = (is_int($max)) ? $max : 3 ;
    $max = ($max > 100) ? 3 : $max ;
    return $max;
  }  
}
