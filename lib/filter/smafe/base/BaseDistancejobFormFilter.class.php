<?php

/**
 * Distancejob filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseDistancejobFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'smafejob_addfile_id'  => new sfWidgetFormPropelChoice(array('model' => 'SmafejobAddfile', 'add_empty' => true)),
      'status'               => new sfWidgetFormFilterInput(),
      'priority'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'started'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'finished'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'smafejob_addfile_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SmafejobAddfile', 'column' => 'id')),
      'status'               => new sfValidatorPass(array('required' => false)),
      'priority'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'started'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'finished'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('distancejob_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Distancejob';
  }

  public function getFields()
  {
    return array(
      'featurevectortype_id' => 'Number',
      'track_id'             => 'ForeignKey',
      'distancetype_id'      => 'ForeignKey',
      'smafejob_addfile_id'  => 'ForeignKey',
      'status'               => 'Text',
      'priority'             => 'Number',
      'created'              => 'Date',
      'started'              => 'Date',
      'finished'             => 'Date',
    );
  }
}
