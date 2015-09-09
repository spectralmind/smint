<?php

/**
 * File form base class.
 *
 * @method File getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'hash'                 => new sfWidgetFormInputText(),
      'track_id'             => new sfWidgetFormPropelChoice(array('model' => 'Track', 'add_empty' => true)),
      'inserted'             => new sfWidgetFormDateTime(),
      'updated'              => new sfWidgetFormDateTime(),
      'input_format'         => new sfWidgetFormInputText(),
      'uri'                  => new sfWidgetFormInputText(),
      'samplef'              => new sfWidgetFormInputText(),
      'bitrate'              => new sfWidgetFormInputText(),
      'channels'             => new sfWidgetFormInputText(),
      'encoding'             => new sfWidgetFormInputText(),
      'samplebit'            => new sfWidgetFormInputText(),
      'external_key'         => new sfWidgetFormInputText(),
      'guid'                 => new sfWidgetFormInputText(),
      'collection_file_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Collection')),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'hash'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'track_id'             => new sfValidatorPropelChoice(array('model' => 'Track', 'column' => 'id', 'required' => false)),
      'inserted'             => new sfValidatorDateTime(array('required' => false)),
      'updated'              => new sfValidatorDateTime(array('required' => false)),
      'input_format'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'uri'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'samplef'              => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'bitrate'              => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'channels'             => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'encoding'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'samplebit'            => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'external_key'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'guid'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'collection_file_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Collection', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'File', 'column' => array('external_key'))),
        new sfValidatorPropelUnique(array('model' => 'File', 'column' => array('guid'))),
      ))
    );

    $this->widgetSchema->setNameFormat('file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'File';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['collection_file_list']))
    {
      $values = array();
      foreach ($this->object->getCollectionFiles() as $obj)
      {
        $values[] = $obj->getCollectionId();
      }

      $this->setDefault('collection_file_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveCollectionFileList($con);
  }

  public function saveCollectionFileList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['collection_file_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(CollectionFilePeer::FILE_ID, $this->object->getPrimaryKey());
    CollectionFilePeer::doDelete($c, $con);

    $values = $this->getValue('collection_file_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new CollectionFile();
        $obj->setFileId($this->object->getPrimaryKey());
        $obj->setCollectionId($value);
        $obj->save();
      }
    }
  }

}
