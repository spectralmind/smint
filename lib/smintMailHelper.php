<?php

abstract Class smintMailHelper {

	private static $context = "smintMailHelper";

/**
 * Sends a mail.
 * It uses the Swiftmail library, but not the symfony wrapper around it (the wrapper did not work)
 * @param options assoc array with options: * = mandatory
 * 		- subject*
 * 		- parameters*	assoc array for partial rendering
 * 		- to_email*
 * 		- to_name*
 * 		- text*			either a symfony partial view or the text directly
 * 		- html			either a symfony partial view or the html text directly
 * 		- from_email*
 * 		- from_name*
 * 		- cc
 * 		- bcc
 * 		- attachments
 * @return number of emails sent
 * @throws Exception if an error occurs or sfException if a mandatory option is not set 
 */
	public static function mail($options) {

		sfProjectConfiguration::getActive() -> loadHelpers('Partial');

		$required = array('subject', 'parameters', 'to_email', 'to_name', 'text', 'from_email', 'from_name');
		foreach ($required as $option) {
			if (!isset($options[$option])) {
				throw new sfException("Required option $option not supplied to " . self::$context . "::mail");
			}
		}

		// Create the Transport
		$transport = Swift_SmtpTransport::newInstance(
		// Host
			sfConfig::get('app_mail_host', "!!specify host in app.yml!!"),
			// Port 
			sfConfig::get('app_mail_port', 25)) 
			// username
			-> setUsername(sfConfig::get('app_mail_username', "!!Please set username in app.yml!!")) 
			// pwd
			-> setPassword(sfConfig::get('app_mail_password', "***"));
			
			
			
		// check for encryption
		if ($tmpenc = sfConfig::get('app_mail_encryption', '~') != '~') {
			if (in_array($tmpenc, stream_get_transports())) {
				$transport->setEncryption($tmpenc);			
				mysfLog::log(self::$context, "Encryption $tmpenc enabled.");	
			} else {
				mysfLog::log(self::$context, "WARNING: Encryption $tmpenc NOT enabled since it is not in stream_get_transports() result which is: " . print_r(stream_get_transports(), true));					
			}			
		}

		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);

		/*
		// Or to use the Echo Logger
		$logger = new Swift_Plugins_Loggers_EchoLogger();
		$mailer -> registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
		 * 
		 */

		$address = array();
		$address['fullname'] = $options['from_name'];
		$address['email'] = $options['from_email'];

		if (!isset($options['body_is_partial']) || $options['body_is_partial'] == true) {

			$message = Swift_Message::newInstance() -> setFrom(array($address['email'] => $address['fullname'])) -> setTo(array($options['to_email'] => $options['to_name'])) -> setSubject($options['subject']);
			
			if (isset($options['html'])) {
				$message-> setBody(get_partial($options['html'], $options['parameters']), 'text/html') -> addPart(get_partial($options['text'], $options['parameters']), 'text/plain');
			} else {
				$message-> setBody(get_partial($options['text'], $options['parameters']), 'text/plain');		
			}
			//$message = Swift_Message::newInstance() -> setFrom(array($address['email'] => $address['fullname'])) -> setTo(array($options['to_email'] => $options['to_name'])) -> setSubject($options['subject']) -> setBody('test');
			

		} else {

			$message = Swift_Message::newInstance() -> setFrom(array($address['email'] => $address['fullname'])) -> setTo(array($options['to_email'] => $options['to_name'])) -> setSubject($options['subject']);


			if (isset($options['html'])) {
				$message -> setBody($options['html'], 'text/html') -> addPart($options['text'], 'text/plain');
			} else {
				$message->setBody($options['text'], 'text/plain');		
			}
			
		}

		if (isset($options['cc']) && !is_null($options['cc'])) {
			if (is_array($options['cc'])) {
				foreach ($options['cc'] as $key => $value) {
					if (!is_number($key))
						$message -> addCc($key, $value);
					else
						$message -> addCc($value);
				}
			} elseif (is_string($options['cc'])) {
				$message -> addCc($options['cc']);
			}
		}

		if (isset($options['bcc']) && !is_null($options['bcc'])) {
			if (is_array($options['bcc'])) {
				foreach ($options['bcc'] as $key => $value) {
					if (!is_number($key))
						$message -> addBcc($key, $value);
					else
						$message -> addBcc($value);
				}
			} elseif (is_string($options['bcc'])) {
				$message -> addBcc($options['bcc']);
			}
		}

		if (isset($options['attachments'])) {
			$atts = $options['attachments'];
			if (is_array($atts)) {
				foreach ($atts as $att) {
					$message -> attach(Swift_Attachment::fromPath($att));
				}
			} elseif (is_string($atts)) {
				$message -> attach(Swift_Attachment::fromPath($atts));
			}
		}

		
		try {
			$failedRecipients = array();
			$ret = $mailer -> send($message, $failedRecipients);

			mysfLog::log(self::$context, "DEBUG: " . print_r($failedRecipients, TRUE));

			return $ret;
		} catch (Exception $e) {
			throw new Exception("Error when sending mail: " . $e -> getMessage());
		}

	}

}
