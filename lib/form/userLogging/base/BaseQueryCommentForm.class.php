<?php

/**
 * QueryComment form base class.
 *
 * @method QueryComment getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseQueryCommentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'smint_user_id'       => new sfWidgetFormPropelChoice(array('model' => 'SmintUser', 'add_empty' => true)),
      'querytrackid'        => new sfWidgetFormInputText(),
      'comment'             => new sfWidgetFormTextarea(),
      'rating'              => new sfWidgetFormInputText(),
      'featurevectortypeid' => new sfWidgetFormInputText(),
      'distancetypeid'      => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'smint_user_id'       => new sfValidatorPropelChoice(array('model' => 'SmintUser', 'column' => 'id', 'required' => false)),
      'querytrackid'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'comment'             => new sfValidatorString(array('required' => false)),
      'rating'              => new sfValidatorNumber(array('required' => false)),
      'featurevectortypeid' => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'distancetypeid'      => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(array('required' => false)),
      'updated_at'          => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('query_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'QueryComment';
  }


}
