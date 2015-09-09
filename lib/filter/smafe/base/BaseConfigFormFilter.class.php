<?php

/**
 * Config filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseConfigFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'value'    => new sfWidgetFormFilterInput(),
      'modified' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'value'    => new sfValidatorPass(array('required' => false)),
      'modified' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Config';
  }

  public function getFields()
  {
    return array(
      'key'      => 'Text',
      'value'    => 'Text',
      'modified' => 'Date',
    );
  }
}
