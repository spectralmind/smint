<?php

/**
 * Distance filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseDistanceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'value'                => new sfWidgetFormFilterInput(),
      'inserted'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'value'                => new sfValidatorPass(array('required' => false)),
      'inserted'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('distance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Distance';
  }

  public function getFields()
  {
    return array(
      'track_a_id'           => 'ForeignKey',
      'track_b_id'           => 'ForeignKey',
      'featurevectortype_id' => 'Number',
      'distancetype_id'      => 'ForeignKey',
      'value'                => 'Text',
      'inserted'             => 'Date',
      'updated'              => 'Date',
    );
  }
}
