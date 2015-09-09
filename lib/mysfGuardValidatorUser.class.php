<?php 

class mysfGuardValidatorUser extends sfGuardValidatorUser
{
  
  protected function doClean($values)
  {
    $username = isset($values['username']) ? $values['username'] : '';
    $name = isset($values['name']) ? $values['name'] : '';
    $email = isset($values['email']) ? $values['email'] : '';
    $organization = isset($values['organization']) ? $values['organization'] : '';
    $reuse = ( isset($values['reuse']) && $values['reuse'] == 1 ) ? true : false;

    $newuser = $user=sfContext::getInstance()->getUser()->getAttribute('newuser');
    $newuser = (is_null($newuser)) ? false : true ;
    if (!$newuser) {
      $existingUser = SmintUserPeer::retrieveByUniqueColumnValues($username, $name, $email, $organization);
    }

	  $confirmExisting = mysfConfig::get('app_login_settings_confirm_existing_users', true);

    // if the user exists show warning/question to reuse user
    if ( !$newuser && !$reuse && isset($existingUser) && $confirmExisting ) 
    {
      throw new sfValidatorErrorSchema($this, array(
        'reuse' => new sfValidatorError($this, 'user already exists !!! change name / email / organization or activate option to reuse the same user.' ),
        'name' => new sfValidatorError($this, '' ),
        'email' => new sfValidatorError($this, '' ),
        'organization' => new sfValidatorError($this, '' ),
        ));
    } 
    else {
      // check username/password
      return parent::doClean($values);
    }
    
    // $username = isset($values[$this->getOption('username_field')]) ? $values[$this->getOption('username_field')] : '';
    // $password = isset($values[$this->getOption('password_field')]) ? $values[$this->getOption('password_field')] : '';
    // $remember = isset($values[$this->getOption('rememeber_checkbox')]) ? $values[$this->getOption('rememeber_checkbox')] : '';
    // 
    // // user exists?
    // if ($user = sfGuardUserPeer::retrieveByUsername($username))
    // {
    //   // password is ok?
    //   if ($user->checkPassword($password))
    //   {
    //     return array_merge($values, array('user' => $user));
    //   }
    // }
    // 
    // if ($this->getOption('throw_global_error'))
    // {
    //   throw new sfValidatorError($this, 'invalid');
    // }
    // 
    // throw new sfValidatorErrorSchema($this, array($this->getOption('username_field') => new sfValidatorError($this, 'invalid')));
  }
  

}