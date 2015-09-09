<?php

/**
 * GeneralComment form base class.
 *
 * @method GeneralComment getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseGeneralCommentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'smint_user_id' => new sfWidgetFormPropelChoice(array('model' => 'SmintUser', 'add_empty' => true)),
      'content'       => new sfWidgetFormTextarea(),
      'created_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'smint_user_id' => new sfValidatorPropelChoice(array('model' => 'SmintUser', 'column' => 'id', 'required' => false)),
      'content'       => new sfValidatorString(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('general_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GeneralComment';
  }


}
