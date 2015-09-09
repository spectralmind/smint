<?php

/**
 * Track form base class.
 *
 * @method Track getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseTrackForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'fingerprint'        => new sfWidgetFormInputText(),
      'inserted'           => new sfWidgetFormDateTime(),
      'updated'            => new sfWidgetFormDateTime(),
      'mbid'               => new sfWidgetFormInputText(),
      'featurevector_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Featurevectortype')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'fingerprint'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'inserted'           => new sfValidatorDateTime(array('required' => false)),
      'updated'            => new sfValidatorDateTime(array('required' => false)),
      'mbid'               => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'featurevector_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Featurevectortype', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Track', 'column' => array('mbid')))
    );

    $this->widgetSchema->setNameFormat('track[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Track';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['featurevector_list']))
    {
      $values = array();
      foreach ($this->object->getFeaturevectors() as $obj)
      {
        $values[] = $obj->getFeaturevectortypeId();
      }

      $this->setDefault('featurevector_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveFeaturevectorList($con);
  }

  public function saveFeaturevectorList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['featurevector_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(FeaturevectorPeer::TRACK_ID, $this->object->getPrimaryKey());
    FeaturevectorPeer::doDelete($c, $con);

    $values = $this->getValue('featurevector_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Featurevector();
        $obj->setTrackId($this->object->getPrimaryKey());
        $obj->setFeaturevectortypeId($value);
        $obj->save();
      }
    }
  }

}
