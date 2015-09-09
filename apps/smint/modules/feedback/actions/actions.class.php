<?php

/**
 * feedback actions.
 *
 * @package    smint
 * @subpackage feedback
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class feedbackActions extends sfActions
{
  public function executeGeneral(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $postParam = $request->getPostParameters();
      
      if (array_key_exists('content', $postParam) ) {
      if (strlen($postParam['content']) > 0) {
			// store comment in db
	        GeneralCommentPeer::add($postParam['content']);
	        return $this->renderText("Thank you for your feedback!");
			// TODO: add send email ??? 
			
		}
		// if an empty string was submitted do nothing and return without error. 
        return $this->renderText("");
      }
    }
    
    $this->getResponse()->setStatusCode(500);
    return $this->renderText("Comment was not saved! Please send us your Feedback by mail.");
  }
  

  public function executeText(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $postParam = $request->getPostParameters();
      
      if (
          array_key_exists('feedbacktext', $postParam)
          && array_key_exists('querytrackid', $postParam)
          && array_key_exists('resulttrackid', $postParam)
          && array_key_exists('resultposition', $postParam)
          && array_key_exists('featurevectortypeid', $postParam)
          && array_key_exists('distancetypeid', $postParam)
          ) {
        QueryCommentTrackPeer::updateTextComment(
          $postParam['feedbacktext'],
          $postParam['querytrackid'],
          $postParam['resulttrackid'],
          $postParam['resultposition'],
          $postParam['featurevectortypeid'],
          $postParam['distancetypeid']
          );
	      return $this->renderText("");
      }
    }
    
    $this->getResponse()->setStatusCode(500);
    return $this->renderText("Comment was not saved! Please contact us about the problem.");
  }

  public function executeRating(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest())
    {
      $postParam = $request->getRequestParameters();
      $postParam2 = $request->getPostParameters();
      
      if (
          ( array_key_exists('rating', $postParam) || array_key_exists('rating', $postParam2) )
          && array_key_exists('querytrackid', $postParam)
          && array_key_exists('resulttrackid', $postParam)
          && array_key_exists('resultposition', $postParam)
          && array_key_exists('featurevectortypeid', $postParam)
          && array_key_exists('distancetypeid', $postParam)
          ) {
        $rating = (array_key_exists('rating', $postParam)) ? $postParam['rating'] : $postParam2['rating'] ;    
        QueryCommentTrackPeer::updateRating(
          $rating,
          $postParam['querytrackid'],
          $postParam['resulttrackid'],
          $postParam['resultposition'],
          $postParam['featurevectortypeid'],
          $postParam['distancetypeid']
          );
	      return $this->renderText($postParam['rating']);
      }
    }
    
    $this->getResponse()->setStatusCode(500);
    return $this->renderText("Comment was not saved! Please contact us about the problem.".print_r($postParam,true).print_r($postParam2,true));
  }


}
