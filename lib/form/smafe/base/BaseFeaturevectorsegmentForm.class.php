<?php

/**
 * Featurevectorsegment form base class.
 *
 * @method Featurevectorsegment getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFeaturevectorsegmentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'segmentnr'            => new sfWidgetFormInputHidden(),
      'track_id'             => new sfWidgetFormInputHidden(),
      'featurevectortype_id' => new sfWidgetFormInputHidden(),
      'data'                 => new sfWidgetFormInputText(),
      'file_id'              => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'startsample'          => new sfWidgetFormInputText(),
      'length'               => new sfWidgetFormInputText(),
      'inserted'             => new sfWidgetFormDateTime(),
      'updated'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'segmentnr'            => new sfValidatorChoice(array('choices' => array($this->getObject()->getSegmentnr()), 'empty_value' => $this->getObject()->getSegmentnr(), 'required' => false)),
      'track_id'             => new sfValidatorPropelChoice(array('model' => 'Featurevector', 'column' => 'track_id', 'required' => false)),
      'featurevectortype_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->getFeaturevectortypeId()), 'empty_value' => $this->getObject()->getFeaturevectortypeId(), 'required' => false)),
      'data'                 => new sfValidatorPass(array('required' => false)),
      'file_id'              => new sfValidatorPropelChoice(array('model' => 'File', 'column' => 'id', 'required' => false)),
      'startsample'          => new sfValidatorInteger(array('min' => -9.2233720368548E+18, 'max' => 9.2233720368548E+18, 'required' => false)),
      'length'               => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'inserted'             => new sfValidatorDateTime(array('required' => false)),
      'updated'              => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('featurevectorsegment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Featurevectorsegment';
  }


}
