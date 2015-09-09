<?php

/**
 * QueryCommentTrack form base class.
 *
 * @method QueryCommentTrack getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseQueryCommentTrackForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'smint_querycomment_id' => new sfWidgetFormPropelChoice(array('model' => 'QueryComment', 'add_empty' => true)),
      'resultposition'        => new sfWidgetFormInputText(),
      'resulttrackid'         => new sfWidgetFormInputText(),
      'comment'               => new sfWidgetFormTextarea(),
      'rating'                => new sfWidgetFormInputText(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'smint_querycomment_id' => new sfValidatorPropelChoice(array('model' => 'QueryComment', 'column' => 'id', 'required' => false)),
      'resultposition'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'resulttrackid'         => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'comment'               => new sfValidatorString(array('required' => false)),
      'rating'                => new sfValidatorNumber(array('required' => false)),
      'created_at'            => new sfValidatorDateTime(array('required' => false)),
      'updated_at'            => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('query_comment_track[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'QueryCommentTrack';
  }


}
