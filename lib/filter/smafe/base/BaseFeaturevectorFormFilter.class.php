<?php

/**
 * Featurevector filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFeaturevectorFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'data'                 => new sfWidgetFormFilterInput(),
      'file_id'              => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'inserted'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'distancejob_list'     => new sfWidgetFormPropelChoice(array('model' => 'Distancetype', 'add_empty' => true)),
      'distance_list'        => new sfWidgetFormPropelChoice(array('model' => 'Distancetype', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'data'                 => new sfValidatorPass(array('required' => false)),
      'file_id'              => new sfValidatorPropelChoice(array('required' => false, 'model' => 'File', 'column' => 'id')),
      'inserted'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'distancejob_list'     => new sfValidatorPropelChoice(array('model' => 'Distancetype', 'required' => false)),
      'distance_list'        => new sfValidatorPropelChoice(array('model' => 'Distancetype', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('featurevector_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addDistancejobListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(DistancejobPeer::TRACK_ID, FeaturevectorPeer::TRACK_ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(DistancejobPeer::DISTANCETYPE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(DistancejobPeer::DISTANCETYPE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function addDistanceListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(DistancePeer::TRACK_A_ID, FeaturevectorPeer::TRACK_ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(DistancePeer::DISTANCETYPE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(DistancePeer::DISTANCETYPE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Featurevector';
  }

  public function getFields()
  {
    return array(
      'track_id'             => 'ForeignKey',
      'featurevectortype_id' => 'ForeignKey',
      'data'                 => 'Text',
      'file_id'              => 'ForeignKey',
      'inserted'             => 'Date',
      'updated'              => 'Date',
      'distancejob_list'     => 'ManyKey',
      'distance_list'        => 'ManyKey',
    );
  }
}
