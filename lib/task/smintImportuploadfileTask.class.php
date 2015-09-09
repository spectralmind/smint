<?php

class smintImportuploadfileTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      // add your own options here
      new sfCommandOption('filename', null, sfCommandOption::PARAMETER_REQUIRED, 'The file to import', ''),
      
    ));

    $this->namespace        = 'smint';
    $this->name             = 'importuploadfile';
    $this->briefDescription = 'Task takes a local file and imports it into SMINT. The return value is the link to query the file.';
    $this->detailedDescription = <<<EOF
The [smint:importuploadfile|INFO] task does things.
Call it with:

  [php symfony smint:importuploadfile|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize default context
    sfContext::createInstance( sfProjectConfiguration::getApplicationConfiguration('smint', 'dev', true) );
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $tmpFilePath = smintUploadFileHelper::getUploadPath();

    $tmpfname = tempnam($tmpFilePath, "uploadscript.tmp");    

    copy( $options['filename'] , $tmpfname);

    $newFile = new sfValidatedFile( $options['filename'] , "audio/mpeg", $tmpfname, null, $tmpFilePath);

    $fileInfo = smintUploadFileHelper::saveFile($newFile);

    //print_r($fileInfo);
        
    echo "originalFilename=" . urlencode($fileInfo["originalFilename"]) . "&existingUploadedFile=" . urlencode($fileInfo["filename"]) . "\n"; 


    unlink($tmpfname); 

    
  }
}
