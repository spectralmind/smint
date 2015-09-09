<?php

class LoginForm extends BaseForm {

	public function configure() {

		$this -> setWidgets(array(
		// email
			'user' => new sfWidgetFormInputText( array(), array('size' => '30')),
		// password
			'password' => new sfWidgetFormInputText( array('type' => 'password'), array('size' => '30')), ));

		// variable saving
		$this -> widgetSchema -> setNameFormat('signin[%s]');

		// labels
		$this -> widgetSchema -> setLabel('user', 'e-Mail');

		$this -> setValidators(array(
		// email
		// no email validator since we have our old logins and those are not email addresses.
			'user' => new sfValidatorString( array('required' => false), array('invalid' => 'Please enter a valid email address.') ),
		// password
			'password' => new sfValidatorString( array('required' => false)) ));

		// validator
		$this -> validatorSchema -> setPostValidator(new sfGuardValidatorUser(array('username_field'=>'user', 'throw_global_error'=>true)));

	}

}
