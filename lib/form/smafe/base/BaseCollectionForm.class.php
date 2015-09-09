<?php

/**
 * Collection form base class.
 *
 * @method Collection getObject() Returns the current form's model object
 *
 * @package    smint
 * @subpackage form
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseCollectionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'collection_name'      => new sfWidgetFormInputText(),
      'collection_file_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'File')),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'collection_name'      => new sfValidatorString(array('max_length' => 255)),
      'collection_file_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'File', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Collection', 'column' => array('collection_name')))
    );

    $this->widgetSchema->setNameFormat('collection[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collection';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['collection_file_list']))
    {
      $values = array();
      foreach ($this->object->getCollectionFiles() as $obj)
      {
        $values[] = $obj->getFileId();
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
    $c->add(CollectionFilePeer::COLLECTION_ID, $this->object->getPrimaryKey());
    CollectionFilePeer::doDelete($c, $con);

    $values = $this->getValue('collection_file_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new CollectionFile();
        $obj->setCollectionId($this->object->getPrimaryKey());
        $obj->setFileId($value);
        $obj->save();
      }
    }
  }

}
