<?php

class RegisterForm extends BaseForm {

 	protected static $professions = array('NA'=>'--Please choose---', 'Broadcasting'=>'Broadcasting', 'Advertising'=>'Advertising', 'Post-Production'=>'Post-Production', 'Editing'=>'Editing', 'Production Music'=>'Production Music', 'Music Distribution'=>'Music Distribution', 'Music Production'=>'Music Production', 'Other'=>'Other');

	public function configure() {
		sfContext::getInstance() -> getConfiguration() -> loadHelpers('Url');

		$this -> setWidgets(array(
		// name
			'name' => new sfWidgetFormInputText( array(), array('size' => '30')),
		// title/role
			'role' => new sfWidgetFormInputText( array(), array('size' => '30')),
		// org
			'organization' => new sfWidgetFormInputText( array(), array('size' => '30')),
		// industry
			'industry' => new sfWidgetFormSelect( array('choices' => self::$professions, 'multiple' => false), array()),
		// email address
			'email' => new sfWidgetFormInputText( array(), array('size' => '30')),
		// password
			'password' => new sfWidgetFormInputText( array('type' => 'password'), array('size' => '30')),
		// confirm pwd
			'password2' => new sfWidgetFormInputText( array('type' => 'password'), array('size' => '30')),
		// terms
			'tos' => new sfWidgetFormInputCheckbox  ( )
			) );

		// labels
		$this -> widgetSchema -> setLabel('email', 'e-Mail (login)');
		$this -> widgetSchema -> setLabel('role', 'Title/Role');
		$this -> widgetSchema -> setLabel('password2', 'Confirm password');
		$this -> widgetSchema -> setLabel('tos', 'I accept the <a target="_blank" href="' . url_for('sfApply/tos') . '">Terms of Service</a>');
		
		// default texts
		$this->setDefault('email', 'Your Email Here');

		// html as list, not table
		//$this -> widgetSchema -> setFormFormatterName('list');

		// data as array
		$this->widgetSchema->setNameFormat('register[%s]');

		// validators
		 $this->setValidators(array(
		 'name'          => new sfValidatorString (  array('required' => true  ,  'min_length' => 2 ) ),
		 'role'          => new sfValidatorString (  array('required' => false ) ),
		 'organization'  => new sfValidatorString (  array('required' => true ,  'min_length' => 2 ) ),
		 'industry'    => new sfValidatorChoice ( array('choices' => array_keys(self::$professions))),
		 'email'         =>new sfValidatorAnd( array (
		 					// is an valid email 
		 					new sfValidatorEmail  (  array('required' => true ), array('invalid' => 'Please enter a valid email address.') ),
		 					// email not already taken
		 					new EmailNotInUseValidator (array(), array('used'=> "Sorry, that email address is already associated with an account. Please write to <a href='support@spectralmind.com'>support@spectralmind.com</a> if you need to retrieve your password.")))),
		 'password'      => new sfValidatorString(),
		 'password2'      => new sfValidatorString(),
		 'tos'           => new sfValidatorBoolean(array('required' => true)),
		 ));
		 
		 // custom validator
		 // passwords must match
		 $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password2', sfValidatorSchemaCompare::EQUAL, 'password', array(), array('invalid' => 'Passwords do not match.')));

	}

}
