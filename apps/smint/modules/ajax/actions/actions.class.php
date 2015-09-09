<?php

/**
 * ajax actions.
 *
 * @package    SMINT
 * @subpackage ajax
 * @author     Wolfgang Jochum, Spectralmind GmbH
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ajaxActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }


  public function executeTitle($request)
  {
    // write session, so it won't lock other requests 
    session_write_close();      

    $this->getResponse()->setContentType('application/json');
    
    $limit = $request->getParameter('limit');
    $limit_default = mysfConfig::get('app_ajax_autocomplete_results', 5);
    $limit = (isset($limit)) ? $limit : $limit_default ;
     
    $term = $request->getParameter('term'); 
    $title = FiledescPeer::retrieveForSelect($term, $limit, "Title");
    return $this->renderText(json_encode($title));
  }   

  public function executeGenre($request)
  {
    // write session, so it won't lock other requests 
    session_write_close();      

    $this->getResponse()->setContentType('application/json');
    
    $limit = $request->getParameter('limit');
    $limit_default = mysfConfig::get('app_ajax_autocomplete_results', 5);
    $limit = (isset($limit)) ? $limit : $limit_default ;
     
    $term = $request->getParameter('term'); 
    $title = FiledescPeer::retrieveForSelect($term, $limit, "Genre");
    return $this->renderText(json_encode($title));
  }
  
  
  public function executeFileuploadstatus($request)
  {
    $fileid = $request->getParameter('fileid');
    
    if (function_exists("uploadprogress_get_info")) {
        $info = uploadprogress_get_info($fileid);
    } else {
        $info = false;
    }
    $result = json_encode($info);

    $this->getResponse()->addCacheControlHttpHeader('no-cache');    
    $this->getResponse()->setHttpHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');    

    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->getResponse()->setHttpHeader("X-JSON", $result);
    return sfView::HEADER_ONLY;
  }


}
