<?php

/**
 * UserLogins form base class.
 *
 * @method UserLogins getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseUserLoginsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'smint_user_id' => new sfWidgetFormPropelChoice(array('model' => 'SmintUser', 'add_empty' => true)),
      'ip'            => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'smint_user_id' => new sfValidatorPropelChoice(array('model' => 'SmintUser', 'column' => 'id', 'required' => false)),
      'ip'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_logins[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserLogins';
  }


}
