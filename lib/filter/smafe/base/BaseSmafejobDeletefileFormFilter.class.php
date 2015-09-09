<?php

/**
 * SmafejobDeletefile filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseSmafejobDeletefileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'priority'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'collection_name' => new sfWidgetFormFilterInput(),
      'file_id'         => new sfWidgetFormFilterInput(),
      'created'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'started'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'finished1'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'started2'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'finished2'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'finished'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status'          => new sfWidgetFormFilterInput(),
      'log'             => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'priority'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'collection_name' => new sfValidatorPass(array('required' => false)),
      'file_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'started'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'finished1'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'started2'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'finished2'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'finished'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'status'          => new sfValidatorPass(array('required' => false)),
      'log'             => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('smafejob_deletefile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmafejobDeletefile';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'priority'        => 'Number',
      'collection_name' => 'Text',
      'file_id'         => 'Number',
      'created'         => 'Date',
      'started'         => 'Date',
      'finished1'       => 'Date',
      'started2'        => 'Date',
      'finished2'       => 'Date',
      'finished'        => 'Date',
      'status'          => 'Text',
      'log'             => 'Text',
    );
  }
}
