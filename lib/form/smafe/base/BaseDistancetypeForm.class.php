<?php

/**
 * Distancetype form base class.
 *
 * @method Distancetype getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseDistancetypeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'inserted'         => new sfWidgetFormDateTime(),
      'updated'          => new sfWidgetFormDateTime(),
      'name'             => new sfWidgetFormInputText(),
      'distancejob_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Featurevector')),
      'distance_list'    => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Featurevector')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'inserted'         => new sfValidatorDateTime(array('required' => false)),
      'updated'          => new sfValidatorDateTime(array('required' => false)),
      'name'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'distancejob_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Featurevector', 'required' => false)),
      'distance_list'    => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Featurevector', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('distancetype[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Distancetype';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['distancejob_list']))
    {
      $values = array();
      foreach ($this->object->getDistancejobs() as $obj)
      {
        $values[] = $obj->getTrackId();
      }

      $this->setDefault('distancejob_list', $values);
    }

    if (isset($this->widgetSchema['distance_list']))
    {
      $values = array();
      foreach ($this->object->getDistances() as $obj)
      {
        $values[] = $obj->getTrackAId();
      }

      $this->setDefault('distance_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveDistancejobList($con);
    $this->saveDistanceList($con);
  }

  public function saveDistancejobList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['distancejob_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(DistancejobPeer::DISTANCETYPE_ID, $this->object->getPrimaryKey());
    DistancejobPeer::doDelete($c, $con);

    $values = $this->getValue('distancejob_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Distancejob();
        $obj->setDistancetypeId($this->object->getPrimaryKey());
        $obj->setTrackId($value);
        $obj->save();
      }
    }
  }

  public function saveDistanceList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['distance_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(DistancePeer::DISTANCETYPE_ID, $this->object->getPrimaryKey());
    DistancePeer::doDelete($c, $con);

    $values = $this->getValue('distance_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Distance();
        $obj->setDistancetypeId($this->object->getPrimaryKey());
        $obj->setTrackAId($value);
        $obj->save();
      }
    }
  }

}
