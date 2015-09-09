<?php

/**
 * helpers actions.
 *
 * @package    smint
 * @subpackage helpers
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class helpersActions extends sfActions
{
  /**
   * Warning on missing page
   */
  public function executeError404() 
  {
    $emailSiteAdmin = sfConfig::get('app_email_siteadmin'); 
    $this->siteadmin = $emailSiteAdmin;
  }
  
}
