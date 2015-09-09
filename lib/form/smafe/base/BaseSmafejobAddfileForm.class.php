<?php

/**
 * SmafejobAddfile form base class.
 *
 * @method SmafejobAddfile getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseSmafejobAddfileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'priority'        => new sfWidgetFormInputText(),
      'file_uri'        => new sfWidgetFormInputText(),
      'created'         => new sfWidgetFormDateTime(),
      'started'         => new sfWidgetFormDateTime(),
      'finished1'       => new sfWidgetFormDateTime(),
      'started2'        => new sfWidgetFormDateTime(),
      'finished2'       => new sfWidgetFormDateTime(),
      'finished'        => new sfWidgetFormDateTime(),
      'status'          => new sfWidgetFormInputText(),
      'collection_name' => new sfWidgetFormInputText(),
      'log'             => new sfWidgetFormInputText(),
      'external_key'    => new sfWidgetFormInputText(),
      'guid'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'priority'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'file_uri'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created'         => new sfValidatorDateTime(),
      'started'         => new sfValidatorDateTime(array('required' => false)),
      'finished1'       => new sfValidatorDateTime(array('required' => false)),
      'started2'        => new sfValidatorDateTime(array('required' => false)),
      'finished2'       => new sfValidatorDateTime(array('required' => false)),
      'finished'        => new sfValidatorDateTime(array('required' => false)),
      'status'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'collection_name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'log'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'external_key'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'guid'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'SmafejobAddfile', 'column' => array('external_key'))),
        new sfValidatorPropelUnique(array('model' => 'SmafejobAddfile', 'column' => array('guid'))),
      ))
    );

    $this->widgetSchema->setNameFormat('smafejob_addfile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmafejobAddfile';
  }


}
