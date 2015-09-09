<?php

class EmailNotInUseValidator extends sfValidatorBase {

	/**
	 * Configures the current validator.
	 *
	 * Available error codes:
	 *
	 *  * used
	 *
	 * @param array $options   An array of options
	 * @param array $messages  An array of error messages
	 *
	 * @see sfValidatorBase
	 */
	protected function configure($options = array(), $messages = array()) {
		$this -> addMessage('used', 'Email address already associated with an account.');
	}

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value) {
		
		$clean = $value;
		
		mysfLog::log($this, "$clean");
		
		// check for active user
		if (sfGuardUserPeer::retrieveByUsername($clean, true)) {
			throw new sfValidatorError($this, 'used', array('value' => $value));
		}
		// check for not active user
		if (sfGuardUserPeer::retrieveByUsername($clean, false)) {
			throw new sfValidatorError($this, 'used', array('value' => $value));
		}

		return $clean;
	}

}
