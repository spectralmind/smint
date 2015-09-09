<?php

/**
 * Featurevectorsegment filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFeaturevectorsegmentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'data'                 => new sfWidgetFormFilterInput(),
      'file_id'              => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'startsample'          => new sfWidgetFormFilterInput(),
      'length'               => new sfWidgetFormFilterInput(),
      'inserted'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'data'                 => new sfValidatorPass(array('required' => false)),
      'file_id'              => new sfValidatorPropelChoice(array('required' => false, 'model' => 'File', 'column' => 'id')),
      'startsample'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'length'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'inserted'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('featurevectorsegment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Featurevectorsegment';
  }

  public function getFields()
  {
    return array(
      'segmentnr'            => 'Number',
      'track_id'             => 'ForeignKey',
      'featurevectortype_id' => 'Number',
      'data'                 => 'Text',
      'file_id'              => 'ForeignKey',
      'startsample'          => 'Number',
      'length'               => 'Number',
      'inserted'             => 'Date',
      'updated'              => 'Date',
    );
  }
}
