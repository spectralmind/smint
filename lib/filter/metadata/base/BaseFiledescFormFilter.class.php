<?php

/**
 * Filedesc filter form base class.
 *
 * @package    smint
 * @subpackage filter
 * @author     Wolfgang Jochum, Spectralmind GmbH
 */
abstract class BaseFiledescFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'performers'      => new sfWidgetFormFilterInput(),
      'title'           => new sfWidgetFormFilterInput(),
      'version'         => new sfWidgetFormFilterInput(),
      'genre'           => new sfWidgetFormFilterInput(),
      'subgenre'        => new sfWidgetFormFilterInput(),
      'tempo'           => new sfWidgetFormFilterInput(),
      'bpm'             => new sfWidgetFormFilterInput(),
      'leadvocalgender' => new sfWidgetFormFilterInput(),
      'instruments'     => new sfWidgetFormFilterInput(),
      'moods'           => new sfWidgetFormFilterInput(),
      'situations'      => new sfWidgetFormFilterInput(),
      'genre_sm'        => new sfWidgetFormFilterInput(),
      'genre_sm2'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'performers'      => new sfValidatorPass(array('required' => false)),
      'title'           => new sfValidatorPass(array('required' => false)),
      'version'         => new sfValidatorPass(array('required' => false)),
      'genre'           => new sfValidatorPass(array('required' => false)),
      'subgenre'        => new sfValidatorPass(array('required' => false)),
      'tempo'           => new sfValidatorPass(array('required' => false)),
      'bpm'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'leadvocalgender' => new sfValidatorPass(array('required' => false)),
      'instruments'     => new sfValidatorPass(array('required' => false)),
      'moods'           => new sfValidatorPass(array('required' => false)),
      'situations'      => new sfValidatorPass(array('required' => false)),
      'genre_sm'        => new sfValidatorPass(array('required' => false)),
      'genre_sm2'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('filedesc_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Filedesc';
  }

  public function getFields()
  {
    return array(
      'tracknr'         => 'Text',
      'performers'      => 'Text',
      'title'           => 'Text',
      'version'         => 'Text',
      'genre'           => 'Text',
      'subgenre'        => 'Text',
      'tempo'           => 'Text',
      'bpm'             => 'Number',
      'leadvocalgender' => 'Text',
      'instruments'     => 'Text',
      'moods'           => 'Text',
      'situations'      => 'Text',
      'genre_sm'        => 'Text',
      'genre_sm2'       => 'Text',
    );
  }
}
