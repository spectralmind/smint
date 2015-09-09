<?php

/**
 * Distancejob form base class.
 *
 * @method Distancejob getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseDistancejobForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'featurevectortype_id' => new sfWidgetFormInputHidden(),
      'track_id'             => new sfWidgetFormInputHidden(),
      'distancetype_id'      => new sfWidgetFormInputHidden(),
      'smafejob_addfile_id'  => new sfWidgetFormPropelChoice(array('model' => 'SmafejobAddfile', 'add_empty' => true)),
      'status'               => new sfWidgetFormInputText(),
      'priority'             => new sfWidgetFormInputText(),
      'created'              => new sfWidgetFormDateTime(),
      'started'              => new sfWidgetFormDateTime(),
      'finished'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'featurevectortype_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->getFeaturevectortypeId()), 'empty_value' => $this->getObject()->getFeaturevectortypeId(), 'required' => false)),
      'track_id'             => new sfValidatorPropelChoice(array('model' => 'Featurevector', 'column' => 'track_id', 'required' => false)),
      'distancetype_id'      => new sfValidatorPropelChoice(array('model' => 'Distancetype', 'column' => 'id', 'required' => false)),
      'smafejob_addfile_id'  => new sfValidatorPropelChoice(array('model' => 'SmafejobAddfile', 'column' => 'id', 'required' => false)),
      'status'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'priority'             => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'created'              => new sfValidatorDateTime(),
      'started'              => new sfValidatorDateTime(array('required' => false)),
      'finished'             => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('distancejob[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Distancejob';
  }


}
