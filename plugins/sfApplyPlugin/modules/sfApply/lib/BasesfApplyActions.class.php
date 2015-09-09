<?php

/**
 * sfApply actions.
 *
 * @package    5seven5
 * @subpackage sfApply
 * @author     Tom Boutell, tom@punkave.com
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BasesfApplyActions extends sfActions {
	public function validateApply() {
		// Extend me to validate your own stuff
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$email = $this -> getRequestParameter('email');
			$email2 = $this -> getRequestParameter('email2');
			$username = $this -> getRequestParameter('username');
			if ($email !== $email2) {
				$this -> logMessage("Emails don't match", 'info');
				$this -> getRequest() -> setError('email', "Email addresses do not match.");
				return false;
			}
			$password = $this -> getRequestParameter('password');
			$password2 = $this -> getRequestParameter('password2');
			if ($password !== $password2) {
				$this -> logMessage("Passwords don't match", 'info');
				$this -> getRequest() -> setError('password', "Passwords do not match.");
				return false;
			}
			if (sfGuardUserPeer::retrieveByUsername($username)) {
				// TODO: efficient username suggest-o-matic ala Accountify
				$this -> getRequest() -> setError('username', "Sorry, that username is already in use. Please select another.");
				return false;
			}
			if (!preg_match("/^[\w\ \.\-]+$/", $username)) {
				$this -> getRequest() -> setError('username', "Sorry, usernames may contain only letters, numbers, underscores, spaces, periods and dashes.");
				return false;
			}
			$c = new Criteria();
			$c -> add(sfGuardUserProfilePeer::EMAIL, $email);
			$match = sfGuardUserProfilePeer::doSelectOne($c);
			if ($match) {
				$this -> logMessage("Email in use", 'info');
				$this -> getRequest() -> setError('email', "Sorry, that email address is already associated with an account. Click on \"I forgot my password\" if you need to retrieve your password.");
				return false;
			}
		}
		$this -> logMessage("Validate function succeeded", 'info');
		return true;
	}

	public function executeApply() {
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$username = $this -> getRequestParameter('username');
			$password = $this -> getRequestParameter('password');
			$sfGuardUser = new sfGuardUser();
			$sfGuardUser -> setUsername($username);
			$sfGuardUser -> setPassword($password);
			// Not until confirmed
			$sfGuardUser -> setIsActive(false);
			$profile = $sfGuardUser -> getProfile();
			$this -> populateProfileSettings($profile);
			$sfGuardUser -> save();
			$profile -> save();
			$this -> setFlash('sfApplyPlugin_id', $sfGuardUser -> getId(), false);
			$this -> sendEmail('sfApply', 'sendValidate');
			return 'After';
		}
	}

	public function populateProfileSettings($profile) {
		// Extend me to save your own stuff the first time
		$fullname = $this -> getRequestParameter('fullname');
		$email = $this -> getRequestParameter('email');
		$profile -> setFullname($fullname);
		$profile -> setEmail($email);
		$guid = self::createGuid();
		$profile -> setValidate("n" . $guid);
	}

	public function executeSendValidate() {
		$sfGuardUser = sfGuardUserPeer::retrieveByPK($this -> getFlash('sfApplyPlugin_id', false));
		if (!$sfGuardUser) {
			$this -> logMessage('No user in executeSendValidate', 'err');
		}
		$profile = $sfGuardUser -> getProfile();
		if (!$profile -> getValidate()) {
			// Don't send a validation email unless they actually have
			// a validation code at the moment
			$this -> logMessage('attempted executeSendValidate for a user with no validate code.', 'err');
			return 'Error';
		}
		$mail = new sfMail();
		$mail -> setCharset('utf-8');
		$from = sfConfig::get('app_sfApplyPlugin_from');
		if (!$from) {
			$this -> logMessage('app_sfApplyPlugin_from is not set. Cannot send email.', 'err');
			return 'Error';
		}
		$this -> validate = $profile -> getValidate();
		$vtype = self::getValidationType($this -> validate);
		if ($vtype == 'Reset') {
			$mail -> setSubject(sfConfig::get('app_sfApplyPlugin_reset_subject', "Please verify your password reset request on " . $this -> getRequest() -> getHost()));
			$this -> logMessage('set reset subject', 'info');
		} else {
			$mail -> setSubject(sfConfig::get('app_sfApplyPlugin_apply_subject', "Please verify your account on " . $this -> getRequest() -> getHost()));
			$this -> logMessage('set new subject', 'info');
		}
		$mail -> setSender($from['email'], $from['fullname']);
		$mail -> setFrom($from['email'], $from['fullname']);
		$mail -> addAddress($profile -> getEmail(), $profile -> getFullname());
		$this -> mail = $mail;
		$this -> logMessage('returning from executeSendValidate', 'info');
		return $vtype;
	}

	public function executeConfirm() {
		$validate = $this -> getRequestParameter('validate');
		$c = new Criteria();
		// 0.6.3: oops, this was in sfGuardUserProfilePeer in my application
		// and therefore never got shipped with the plugin until I built
		// a second site and spotted it!
		$c -> add(sfGuardUserProfilePeer::VALIDATE, $validate);
		$c -> addJoin(sfGuardUserPeer::ID, sfGuardUserProfilePeer::USER_ID);
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
		}
		if ($type == 'Reset') {
			$this -> getUser() -> setAttribute('Reset', $sfGuardUser -> getId(), 'sfApplyPlugin');
			return $this -> redirect('sfApply/reset');
		}
	}

	public function validateResetRequest() {
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$username = $this -> getRequestParameter('username');
			$this -> sfGuardUser = sfGuardUserPeer::retrieveByUsername($username);
			if (!$this -> sfGuardUser) {
				$this -> getRequest() -> setError('username', 'There is no account with that username.');
				return false;
			}
			if (!$this -> sfGuardUser -> getIsActive()) {
				$this -> getRequest() -> setError('username', 'That account has not been activated.');
				return false;
			}
		}
		return true;
	}

	public function executeResetRequest() {
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$username = $this -> getRequestParameter('username');
			$profile = $this -> sfGuardUser -> getProfile();
			$profile -> setValidate('r' . self::createGuid());
			$profile -> save();
			$this -> setFlash('sfApplyPlugin_id', $this -> sfGuardUser -> getId(), false);
			$this -> sendEmail('sfApply', 'sendValidate');
			return 'After';
		}
	}

	public function validateReset() {
		$this -> id = $this -> getUser() -> getAttribute('Reset', false, 'sfApplyPlugin', false);
		if (!$this -> id) {
			// No need to be polite, this is probably a hack attempt
			$this -> forward404();
		}
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$password = $this -> getRequestParameter('password');
			$password2 = $this -> getRequestParameter('password2');
			if ($password !== $password2) {
				$this -> getRequest() -> setError('password', "Passwords do not match.");
				return false;
			}
			$this -> sfGuardUser = sfGuardUserPeer::retrieveByPK($this -> id);
			if (!$this -> sfGuardUser) {
				// No need to be polite, this is probably a hack attempt
				$this -> forward404();
			}
		}
		return true;
	}

	public function executeReset() {
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$sfGuardUser = $this -> sfGuardUser;
			$sfGuardUser -> setPassword($this -> getRequestParameter('password'));
			$sfGuardUser -> save();
			$this -> getUser() -> signIn($sfGuardUser);
			$this -> getUser() -> setAttribute('Reset', null, 'sfApplyPlugin');
			return 'After';
		}
	}

	public function executeResetCancel() {
		$this -> getUser() -> setAttribute('Reset', null, 'sfApplyPlugin');
		return $this -> redirect('@homepage');
	}

	public function executeSettings() {
		$profile = $this -> getUser() -> getGuardUser() -> getProfile();
		$this -> profile = $profile;
		if ($this -> getRequest() -> getMethod() == sfRequest::POST) {
			$this -> updateProfileSettings($profile);
			return $this -> redirect('@homepage');
		}
	}

	// Extend me to update your own fields when the user
	// changes their settings
	public function updateProfileSettings($profile) {
		$fullname = $this -> getRequestParameter('fullname');
		$this -> profile -> setFullname($fullname);
		$this -> profile -> save();
	}

	static protected function createGuid() {
		$guid = "";
		for ($i = 0; ($i < 16); $i++) {
			$guid .= sprintf("%02x", mt_rand(0, 255));
		}
		return $guid;
	}

	static protected function getValidationType($validate) {
		$t = substr($validate, 0, 1);
		if ($t == 'n') {
			return 'New';
		} elseif ($t == 'r') {
			return 'Reset';
		} else {
			return sfView::NONE;
		}
	}

	// Since I use this a lot, a simple proxy method
	private function setError($item, $message) {
		return $this -> getRequest() -> setError($item, $message);
	}

	public function handleError() {
		return sfView::SUCCESS;
	}

}
