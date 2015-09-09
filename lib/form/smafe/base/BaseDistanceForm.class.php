<?php

/**
 * Distance form base class.
 *
 * @method Distance getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseDistanceForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'track_a_id'           => new sfWidgetFormInputHidden(),
      'track_b_id'           => new sfWidgetFormInputHidden(),
      'featurevectortype_id' => new sfWidgetFormInputHidden(),
      'distancetype_id'      => new sfWidgetFormInputHidden(),
      'value'                => new sfWidgetFormInputText(),
      'inserted'             => new sfWidgetFormDateTime(),
      'updated'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'track_a_id'           => new sfValidatorPropelChoice(array('model' => 'Featurevector', 'column' => 'track_id', 'required' => false)),
      'track_b_id'           => new sfValidatorPropelChoice(array('model' => 'Featurevector', 'column' => 'track_id', 'required' => false)),
      'featurevectortype_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->getFeaturevectortypeId()), 'empty_value' => $this->getObject()->getFeaturevectortypeId(), 'required' => false)),
      'distancetype_id'      => new sfValidatorPropelChoice(array('model' => 'Distancetype', 'column' => 'id', 'required' => false)),
      'value'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'inserted'             => new sfValidatorDateTime(array('required' => false)),
      'updated'              => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('distance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Distance';
  }


}
