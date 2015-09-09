<?php

/**
 * Distancetype filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseDistancetypeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'inserted'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'name'             => new sfWidgetFormFilterInput(),
      'distancejob_list' => new sfWidgetFormPropelChoice(array('model' => 'Featurevector', 'add_empty' => true)),
      'distance_list'    => new sfWidgetFormPropelChoice(array('model' => 'Featurevector', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'inserted'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'name'             => new sfValidatorPass(array('required' => false)),
      'distancejob_list' => new sfValidatorPropelChoice(array('model' => 'Featurevector', 'required' => false)),
      'distance_list'    => new sfValidatorPropelChoice(array('model' => 'Featurevector', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('distancetype_filters[%s]');

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

    $criteria->addJoin(DistancejobPeer::DISTANCETYPE_ID, DistancetypePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(DistancejobPeer::TRACK_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(DistancejobPeer::TRACK_ID, $value));
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

    $criteria->addJoin(DistancePeer::DISTANCETYPE_ID, DistancetypePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(DistancePeer::TRACK_A_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(DistancePeer::TRACK_A_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Distancetype';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'inserted'         => 'Date',
      'updated'          => 'Date',
      'name'             => 'Text',
      'distancejob_list' => 'ManyKey',
      'distance_list'    => 'ManyKey',
    );
  }
}
