<?php

/**
 * testajax actions.
 *
 * @package    SMINT
 * @subpackage testajax
 * @author     Wolfgang Jochum, Spectralmind GmbH
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testajaxActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->formfileupload = new FileUploadForm();
  }
}
