<?php

/**
 * GeneralComment filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseGeneralCommentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'smint_user_id' => new sfWidgetFormPropelChoice(array('model' => 'SmintUser', 'add_empty' => true)),
      'content'       => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'smint_user_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SmintUser', 'column' => 'id')),
      'content'       => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('general_comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GeneralComment';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'smint_user_id' => 'ForeignKey',
      'content'       => 'Text',
      'created_at'    => 'Date',
    );
  }
}
