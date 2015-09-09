<?php

/**
 * QueryCommentTrack filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseQueryCommentTrackFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'smint_querycomment_id' => new sfWidgetFormPropelChoice(array('model' => 'QueryComment', 'add_empty' => true)),
      'resultposition'        => new sfWidgetFormFilterInput(),
      'resulttrackid'         => new sfWidgetFormFilterInput(),
      'comment'               => new sfWidgetFormFilterInput(),
      'rating'                => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'smint_querycomment_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'QueryComment', 'column' => 'id')),
      'resultposition'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'resulttrackid'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment'               => new sfValidatorPass(array('required' => false)),
      'rating'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('query_comment_track_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'QueryCommentTrack';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'smint_querycomment_id' => 'ForeignKey',
      'resultposition'        => 'Number',
      'resulttrackid'         => 'Number',
      'comment'               => 'Text',
      'rating'                => 'Number',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
