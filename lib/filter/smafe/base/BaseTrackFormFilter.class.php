<?php

/**
 * Track filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseTrackFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'fingerprint'        => new sfWidgetFormFilterInput(),
      'inserted'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'mbid'               => new sfWidgetFormFilterInput(),
      'featurevector_list' => new sfWidgetFormPropelChoice(array('model' => 'Featurevectortype', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'fingerprint'        => new sfValidatorPass(array('required' => false)),
      'inserted'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'mbid'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'featurevector_list' => new sfValidatorPropelChoice(array('model' => 'Featurevectortype', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('track_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addFeaturevectorListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(FeaturevectorPeer::TRACK_ID, TrackPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(FeaturevectorPeer::FEATUREVECTORTYPE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(FeaturevectorPeer::FEATUREVECTORTYPE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Track';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'fingerprint'        => 'Text',
      'inserted'           => 'Date',
      'updated'            => 'Date',
      'mbid'               => 'Number',
      'featurevector_list' => 'ManyKey',
    );
  }
}
