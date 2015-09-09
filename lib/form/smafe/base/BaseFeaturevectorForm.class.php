<?php

/**
 * Featurevector form base class.
 *
 * @method Featurevector getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFeaturevectorForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'track_id'             => new sfWidgetFormInputHidden(),
      'featurevectortype_id' => new sfWidgetFormInputHidden(),
      'data'                 => new sfWidgetFormInputText(),
      'file_id'              => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'inserted'             => new sfWidgetFormDateTime(),
      'updated'              => new sfWidgetFormDateTime(),
      'distancejob_list'     => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Distancetype')),
      'distance_list'        => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Distancetype')),
    ));

    $this->setValidators(array(
      'track_id'             => new sfValidatorPropelChoice(array('model' => 'Track', 'column' => 'id', 'required' => false)),
      'featurevectortype_id' => new sfValidatorPropelChoice(array('model' => 'Featurevectortype', 'column' => 'id', 'required' => false)),
      'data'                 => new sfValidatorPass(array('required' => false)),
      'file_id'              => new sfValidatorPropelChoice(array('model' => 'File', 'column' => 'id', 'required' => false)),
      'inserted'             => new sfValidatorDateTime(array('required' => false)),
      'updated'              => new sfValidatorDateTime(array('required' => false)),
      'distancejob_list'     => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Distancetype', 'required' => false)),
      'distance_list'        => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Distancetype', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Featurevector', 'column' => array('track_id', 'featurevectortype_id')))
    );

    $this->widgetSchema->setNameFormat('featurevector[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Featurevector';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['distancejob_list']))
    {
      $values = array();
      foreach ($this->object->getDistancejobs() as $obj)
      {
        $values[] = $obj->getDistancetypeId();
      }

      $this->setDefault('distancejob_list', $values);
    }

    if (isset($this->widgetSchema['distance_list']))
    {
      $values = array();
      foreach ($this->object->getDistances() as $obj)
      {
        $values[] = $obj->getDistancetypeId();
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
    $c->add(DistancejobPeer::TRACK_ID, $this->object->getPrimaryKey());
    DistancejobPeer::doDelete($c, $con);

    $values = $this->getValue('distancejob_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Distancejob();
        $obj->setTrackId($this->object->getPrimaryKey());
        $obj->setDistancetypeId($value);
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
    $c->add(DistancePeer::TRACK_A_ID, $this->object->getPrimaryKey());
    DistancePeer::doDelete($c, $con);

    $values = $this->getValue('distance_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Distance();
        $obj->setTrackAId($this->object->getPrimaryKey());
        $obj->setDistancetypeId($value);
        $obj->save();
      }
    }
  }

}
