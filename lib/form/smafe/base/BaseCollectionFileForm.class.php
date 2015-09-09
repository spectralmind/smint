<?php

/**
 * CollectionFile form base class.
 *
 * @method CollectionFile getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseCollectionFileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_id' => new sfWidgetFormInputHidden(),
      'file_id'       => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'collection_id' => new sfValidatorPropelChoice(array('model' => 'Collection', 'column' => 'id', 'required' => false)),
      'file_id'       => new sfValidatorPropelChoice(array('model' => 'File', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collection_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionFile';
  }


}
