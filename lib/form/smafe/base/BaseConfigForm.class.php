<?php

/**
 * Config form base class.
 *
 * @method Config getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseConfigForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'key'      => new sfWidgetFormInputHidden(),
      'value'    => new sfWidgetFormInputText(),
      'modified' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'key'      => new sfValidatorChoice(array('choices' => array($this->getObject()->getKey()), 'empty_value' => $this->getObject()->getKey(), 'required' => false)),
      'value'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'modified' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Config';
  }


}
