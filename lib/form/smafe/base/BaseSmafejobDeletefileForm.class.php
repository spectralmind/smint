<?php

/**
 * SmafejobDeletefile form base class.
 *
 * @method SmafejobDeletefile getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseSmafejobDeletefileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'priority'        => new sfWidgetFormInputText(),
      'collection_name' => new sfWidgetFormInputText(),
      'file_id'         => new sfWidgetFormInputText(),
      'created'         => new sfWidgetFormDateTime(),
      'started'         => new sfWidgetFormDateTime(),
      'finished1'       => new sfWidgetFormDateTime(),
      'started2'        => new sfWidgetFormDateTime(),
      'finished2'       => new sfWidgetFormDateTime(),
      'finished'        => new sfWidgetFormDateTime(),
      'status'          => new sfWidgetFormInputText(),
      'log'             => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'priority'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'collection_name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'file_id'         => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'created'         => new sfValidatorDateTime(),
      'started'         => new sfValidatorDateTime(array('required' => false)),
      'finished1'       => new sfValidatorDateTime(array('required' => false)),
      'started2'        => new sfValidatorDateTime(array('required' => false)),
      'finished2'       => new sfValidatorDateTime(array('required' => false)),
      'finished'        => new sfValidatorDateTime(array('required' => false)),
      'status'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'log'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('smafejob_deletefile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmafejobDeletefile';
  }


}
