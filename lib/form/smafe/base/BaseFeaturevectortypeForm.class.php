<?php

/**
 * Featurevectortype form base class.
 *
 * @method Featurevectortype getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFeaturevectortypeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInputText(),
      'version'            => new sfWidgetFormInputText(),
      'dimension_x'        => new sfWidgetFormInputText(),
      'dimension_y'        => new sfWidgetFormInputText(),
      'parameters'         => new sfWidgetFormInputText(),
      'class_id'           => new sfWidgetFormInputText(),
      'inserted'           => new sfWidgetFormDateTime(),
      'updated'            => new sfWidgetFormDateTime(),
      'featurevector_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Track')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'version'            => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'dimension_x'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'dimension_y'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'parameters'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'class_id'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'inserted'           => new sfValidatorDateTime(array('required' => false)),
      'updated'            => new sfValidatorDateTime(array('required' => false)),
      'featurevector_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Track', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('featurevectortype[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Featurevectortype';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['featurevector_list']))
    {
      $values = array();
      foreach ($this->object->getFeaturevectors() as $obj)
      {
        $values[] = $obj->getTrackId();
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
    $c->add(FeaturevectorPeer::FEATUREVECTORTYPE_ID, $this->object->getPrimaryKey());
    FeaturevectorPeer::doDelete($c, $con);

    $values = $this->getValue('featurevector_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Featurevector();
        $obj->setFeaturevectortypeId($this->object->getPrimaryKey());
        $obj->setTrackId($value);
        $obj->save();
      }
    }
  }

}
