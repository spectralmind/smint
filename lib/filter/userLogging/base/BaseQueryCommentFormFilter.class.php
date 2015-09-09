<?php

/**
 * QueryComment filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseQueryCommentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'smint_user_id'       => new sfWidgetFormPropelChoice(array('model' => 'SmintUser', 'add_empty' => true)),
      'querytrackid'        => new sfWidgetFormFilterInput(),
      'comment'             => new sfWidgetFormFilterInput(),
      'rating'              => new sfWidgetFormFilterInput(),
      'featurevectortypeid' => new sfWidgetFormFilterInput(),
      'distancetypeid'      => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'smint_user_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SmintUser', 'column' => 'id')),
      'querytrackid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment'             => new sfValidatorPass(array('required' => false)),
      'rating'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'featurevectortypeid' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'distancetypeid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('query_comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'QueryComment';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'smint_user_id'       => 'ForeignKey',
      'querytrackid'        => 'Number',
      'comment'             => 'Text',
      'rating'              => 'Number',
      'featurevectortypeid' => 'Number',
      'distancetypeid'      => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
