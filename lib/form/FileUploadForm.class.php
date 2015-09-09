<?php

class FileUploadForm extends BaseForm
{
  
    
  public function configure()
  {


      $this->setWidgets(array(
        'mp3' => new sfWidgetFormInputFile(array(),array('size'=>30)),
        'metadataquery' => new sfWidgetFormInputText(),
      ));

    $this->setValidators(array(
      'metadataquery' => new sfValidatorString(array('required'      => false,)),
      'mp3'    => new sfValidatorFile(array(
        'required'      => true,
//        'mime_types'    => array('audio/mpeg'),       // detection of filetype often resulted in wrong type detection and validation errors on mp3 files   
        'max_size'      => '30000000',
        'path'          => smintUploadFileHelper::getUploadPath(),        
      )
      ),
    ));
    
    $this->widgetSchema->setNameFormat('fileupload[%s]');

  }
}