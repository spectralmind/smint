<?php

/**
 * Filedesc form base class.
 *
 * @method Filedesc getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFiledescForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'tracknr'         => new sfWidgetFormInputHidden(),
      'performers'      => new sfWidgetFormTextarea(),
      'title'           => new sfWidgetFormTextarea(),
      'version'         => new sfWidgetFormTextarea(),
      'genre'           => new sfWidgetFormTextarea(),
      'subgenre'        => new sfWidgetFormTextarea(),
      'tempo'           => new sfWidgetFormTextarea(),
      'bpm'             => new sfWidgetFormInputText(),
      'leadvocalgender' => new sfWidgetFormTextarea(),
      'instruments'     => new sfWidgetFormTextarea(),
      'moods'           => new sfWidgetFormTextarea(),
      'situations'      => new sfWidgetFormTextarea(),
      'genre_sm'        => new sfWidgetFormTextarea(),
      'genre_sm2'       => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'tracknr'         => new sfValidatorChoice(array('choices' => array($this->getObject()->getTracknr()), 'empty_value' => $this->getObject()->getTracknr(), 'required' => false)),
      'performers'      => new sfValidatorString(array('required' => false)),
      'title'           => new sfValidatorString(array('required' => false)),
      'version'         => new sfValidatorString(array('required' => false)),
      'genre'           => new sfValidatorString(array('required' => false)),
      'subgenre'        => new sfValidatorString(array('required' => false)),
      'tempo'           => new sfValidatorString(array('required' => false)),
      'bpm'             => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'leadvocalgender' => new sfValidatorString(array('required' => false)),
      'instruments'     => new sfValidatorString(array('required' => false)),
      'moods'           => new sfValidatorString(array('required' => false)),
      'situations'      => new sfValidatorString(array('required' => false)),
      'genre_sm'        => new sfValidatorString(array('required' => false)),
      'genre_sm2'       => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('filedesc[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Filedesc';
  }


}
