<?php

/**
 * Featurevectortype filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFeaturevectortypeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(),
      'version'            => new sfWidgetFormFilterInput(),
      'dimension_x'        => new sfWidgetFormFilterInput(),
      'dimension_y'        => new sfWidgetFormFilterInput(),
      'parameters'         => new sfWidgetFormFilterInput(),
      'class_id'           => new sfWidgetFormFilterInput(),
      'inserted'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'featurevector_list' => new sfWidgetFormPropelChoice(array('model' => 'Track', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'version'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dimension_x'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dimension_y'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parameters'         => new sfValidatorPass(array('required' => false)),
      'class_id'           => new sfValidatorPass(array('required' => false)),
      'inserted'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'featurevector_list' => new sfValidatorPropelChoice(array('model' => 'Track', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('featurevectortype_filters[%s]');

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

    $criteria->addJoin(FeaturevectorPeer::FEATUREVECTORTYPE_ID, FeaturevectortypePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(FeaturevectorPeer::TRACK_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(FeaturevectorPeer::TRACK_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Featurevectortype';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'name'               => 'Text',
      'version'            => 'Number',
      'dimension_x'        => 'Number',
      'dimension_y'        => 'Number',
      'parameters'         => 'Text',
      'class_id'           => 'Text',
      'inserted'           => 'Date',
      'updated'            => 'Date',
      'featurevector_list' => 'ManyKey',
    );
  }
}
