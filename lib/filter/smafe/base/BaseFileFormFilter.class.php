<?php

/**
 * File filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'hash'                 => new sfWidgetFormFilterInput(),
      'track_id'             => new sfWidgetFormPropelChoice(array('model' => 'Track', 'add_empty' => true)),
      'inserted'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'input_format'         => new sfWidgetFormFilterInput(),
      'uri'                  => new sfWidgetFormFilterInput(),
      'samplef'              => new sfWidgetFormFilterInput(),
      'bitrate'              => new sfWidgetFormFilterInput(),
      'channels'             => new sfWidgetFormFilterInput(),
      'encoding'             => new sfWidgetFormFilterInput(),
      'samplebit'            => new sfWidgetFormFilterInput(),
      'external_key'         => new sfWidgetFormFilterInput(),
      'guid'                 => new sfWidgetFormFilterInput(),
      'collection_file_list' => new sfWidgetFormPropelChoice(array('model' => 'Collection', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'hash'                 => new sfValidatorPass(array('required' => false)),
      'track_id'             => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Track', 'column' => 'id')),
      'inserted'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'input_format'         => new sfValidatorPass(array('required' => false)),
      'uri'                  => new sfValidatorPass(array('required' => false)),
      'samplef'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bitrate'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'channels'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'encoding'             => new sfValidatorPass(array('required' => false)),
      'samplebit'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'external_key'         => new sfValidatorPass(array('required' => false)),
      'guid'                 => new sfValidatorPass(array('required' => false)),
      'collection_file_list' => new sfValidatorPropelChoice(array('model' => 'Collection', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('file_filters[%s]');

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

    $criteria->addJoin(CollectionFilePeer::FILE_ID, FilePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(CollectionFilePeer::COLLECTION_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(CollectionFilePeer::COLLECTION_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'File';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'hash'                 => 'Text',
      'track_id'             => 'ForeignKey',
      'inserted'             => 'Date',
      'updated'              => 'Date',
      'input_format'         => 'Text',
      'uri'                  => 'Text',
      'samplef'              => 'Number',
      'bitrate'              => 'Number',
      'channels'             => 'Number',
      'encoding'             => 'Text',
      'samplebit'            => 'Number',
      'external_key'         => 'Text',
      'guid'                 => 'Text',
      'collection_file_list' => 'ManyKey',
    );
  }
}
