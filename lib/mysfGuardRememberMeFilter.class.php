<?php

// updated to work with the smint users 


/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Processes the "remember me" cookie.
 * 
 * This filter should be added to the application filters.yml file **above**
 * the security filter:
 * 
 *    remember_me:
 *      class: sfGuardRememberMeFilter
 * 
 *    security: ~
 * 
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardRememberMeFilter.class.php 15757 2009-02-24 21:15:40Z Kris.Wallsmith $
 */
class mysfGuardRememberMeFilter extends sfFilter
{
  /**
   * @see sfFilter
   */
  public function execute($filterChain)
  {
    $cookieName = sfConfig::get('app_sf_guard_plugin_remember_cookie_name', 'sfRemember');

    if (
      $this->isFirstCall()
      &&
      $this->context->getUser()->isAnonymous()
      &&
      $cookie = $this->context->getRequest()->getCookie($cookieName)
    )
    {
      $criteria = new Criteria();
      $criteria->add(sfGuardRememberKeyPeer::REMEMBER_KEY, $cookie);

      if ($rk = sfGuardRememberKeyPeer::doSelectOne($criteria))
      {
        
        $smintUserId = $this->context->getRequest()->getCookie('sfSmintUserId');
        
        $currentSmintUser = SmintUserPeer::retrieveByPK($smintUserId);
        
        if ( !is_null($currentSmintUser) ) {
        
          // add smint user info to session 
          $this->context->getUser()->setAttribute('userid',$currentSmintUser->getId() );
          $this->context->getUser()->setAttribute('username', $currentSmintUser->getUsername() );
          $this->context->getUser()->setAttribute('name', $currentSmintUser->getName() );
          $this->context->getUser()->setAttribute('email', $currentSmintUser->getEmail() );
          $this->context->getUser()->setAttribute('organization', $currentSmintUser->getOrganization() );
          $this->context->getUser()->setAttribute('ip', $this->context->getRequest()->getHttpHeader ('addr','remote'));
          
          // log user login
          UserLoginsPeer::login();

          // sfGuard login check
          $this->context->getUser()->signIn($rk->getsfGuardUser());
        }
      }
    }

    $filterChain->execute();
  }
}
