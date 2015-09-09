<?php

/**
 * sfApply actions.
 *
 * @package    smint
 * @subpackage sfApply
 * @author     Ewald Peiszer
 */

// Necessary due to a bug in the Symfony autoloader
require_once (dirname(__FILE__) . "/../../../../../plugins/sfApplyPlugin/modules/sfApply/lib/BasesfApplyActions.class.php");

class sfApplyActions extends BasesfApplyActions {

	public $form_ref;

	public function executeApply() {

		$request = $this -> getRequest();

		$this -> form = new RegisterForm();

		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$this -> form -> bind($request -> getParameter('register'));
			if ($this -> form -> isValid()) {

				mysfLog::log($this, "DEBUG: " . print_r($this -> form -> getValues(), TRUE));

				$this -> form_ref = &$this -> form;

				$username = $this -> form -> getValue('email');
				$password = $this -> form -> getValue('password');
				$sfGuardUser = new sfGuardUser();
				$sfGuardUser -> setUsername($username);
				$sfGuardUser -> setPassword($password);
				// Not until confirmed
				$sfGuardUser -> setIsActive(false);
				$profile = $sfGuardUser -> getProfile();
				$sfGuardUser -> save();
				// save sfGuardUser before we populate the user profile because we need the user id
				$this -> myPopulateProfileSettings($profile, $sfGuardUser);
				$profile -> save();

				// Email start
				$opts = array();
				$opts['from_name'] = sfConfig::get('app_mail_fromname', "Spectralmind");
				$opts['from_email'] = sfConfig::get('app_mail_from', "office@spectralmind.com");

				$opts['parameters'] = array('validate' => $profile -> getValidate());
				$opts['body_is_partial'] = true;
				$opts['to_name'] = $profile -> getName();
				$opts['to_email'] = $profile -> getEmail();
				$opts['subject'] = sfConfig::get('app_mail_subjectconfirmmail', "Confirm your registration");
				;
				//$opts['html'] = "sendValidateNew";
				$opts['text'] = "sendValidateNew";

				/*
				 // Or to use the Echo Logger
				 $logger = new Swift_Plugins_Loggers_EchoLogger();
				 $this->getMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
				 * */

				$numSent = smintMailHelper::mail($opts);

				// not sent? react accordingly
				if ($numSent != 1) {
					mysfLog::log($this, "ERROR: confirmation email not sent. Return value was $numSent");
					return 'Error';
				}

				mysfLog::log($this, "DEBUG: Confirm link:" . url_for("sfApply/confirm?validate=" . $profile -> getValidate(), true));

				return 'After';

			}

		}
	}

	/** expects $this->form_ref to be set !! */
	public function myPopulateProfileSettings($profile, $sfGuardUser) {
		$profile -> setSfGuardUserId($sfGuardUser -> getId());

		$email = $this -> form_ref -> getValue('email');
		$profile -> setEmail($email);
		$profile -> setUsername($email);

		$profile -> setName($this -> form_ref -> getValue('name'));
		$profile -> setRole($this -> form_ref -> getValue('role'));
		$profile -> setIndustry($this -> form_ref -> getValue('industry'));
		$profile -> setOrganization($this -> form_ref -> getValue('organization'));

		$guid = self::createGuid();
		$profile -> setValidate("n" . $guid);
	}

	public function executeConfirm() {
		$validate = $this -> getRequestParameter('validate');
		$c = new Criteria();
		// 0.6.3: oops, this was in sfGuardUserProfilePeer in my application
		// and therefore never got shipped with the plugin until I built
		// a second site and spotted it!
		$c -> add(SmintUserPeer::VALIDATE, $validate);
		$c -> addJoin(sfGuardUserPeer::ID, SmintUserPeer::SF_GUARD_USER_ID);
		$sfGuardUser = sfGuardUserPeer::doSelectOne($c);
		if (!$sfGuardUser) {
			return 'Invalid';
		}
		$type = self::getValidationType($validate);
		if (!strlen($validate)) {
			return 'Invalid';
		}
		$profile = $sfGuardUser -> getProfile();
		$profile -> setValidate(null);
		$profile -> save();
		if ($type == 'New') {
			$sfGuardUser -> setIsActive(true);
			$sfGuardUser -> save();
			$this -> getUser() -> signIn($sfGuardUser);

			// Email start
			$opts = array();
			$opts['from_name'] = sfConfig::get('app_mail_fromname', "Spectralmind");
			$opts['from_email'] = sfConfig::get('app_mail_from', "office@spectralmind.com");

			// the password is not plaintext, so we do not show it in the mail!
			$opts['parameters'] = array('username' => $sfGuardUser->getUsername(), 'pwd' => $sfGuardUser->getPassword());
			$opts['body_is_partial'] = true;
			$opts['to_name'] = $profile -> getName();
			$opts['to_email'] = $profile -> getEmail();
			$opts['subject'] = sfConfig::get('app_mail_subjectwelcomemail', "Welcome to SEARCH by Sound portal");
			;
			//$opts['html'] = "sendValidateNew";
			$opts['text'] = "sendWelcomeEmail";

			/*
			 // Or to use the Echo Logger
			 $logger = new Swift_Plugins_Loggers_EchoLogger();
			 $this->getMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
			 * */

			$numSent = smintMailHelper::mail($opts);

			// not sent? react accordingly
			if ($numSent != 1) {
				mysfLog::log($this, "ERROR: welcome email not sent. Return value was $numSent");
				return 'Error';
			}

		}
		if ($type == 'Reset') {
			$this -> getUser() -> setAttribute('Reset', $sfGuardUser -> getId(), 'sfApplyPlugin');
			return $this -> redirect('sfApply/reset');
		}
	}

	public function executeTos() {
		// only show the view, no busines logic here
	}

	public function executeTechnical() {
		// only show the view, no busines logic here
	}

}
