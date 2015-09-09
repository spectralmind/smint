<?php

/**
 * Collection filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseCollectionFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_name'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'collection_file_list' => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'collection_name'      => new sfValidatorPass(array('required' => false)),
      'collection_file_list' => new sfValidatorPropelChoice(array('model' => 'File', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collection_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addCollectionFileListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(CollectionFilePeer::COLLECTION_ID, CollectionPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(CollectionFilePeer::FILE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(CollectionFilePeer::FILE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Collection';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'collection_name'      => 'Text',
      'collection_file_list' => 'ManyKey',
    );
  }
}
