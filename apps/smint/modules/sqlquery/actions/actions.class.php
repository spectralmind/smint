<?php

/**
 * sqlquery actions.
 *
 * @package    smint
 * @subpackage sqlquery
 * @author     Wolfgang Jochum, Spectralmind GmbH
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sqlqueryActions extends sfActions
{
  private function runQuery(sfWebRequest $request, $databaseName){
    try {
      $e=null;
      $this->getResponse()->setContentType('text/plain');

      $query = $this->getRequestParameter('query');
      
      if ( strlen($query) < 10 ) {
        // consider the query as bogus
        $e = "The Query seems to be too short. Try something like ?query=select * from smint_user.";
        $result = ""; 
        return $this->renderPartial('sqlquery/viewResult', array(
          'result' => $result,
          'query' => $query,
          'exception' => $e
          ));    
        
      } else {
        // open database connection based on filedescpeer settings 
    		$con = Propel::getConnection($databaseName, Propel::CONNECTION_READ);

        // prepare statement
        $stmt = $con->prepare($query);
        $stmt->execute();
        // fetch the results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->renderPartial('sqlquery/viewResult', array(
          'result' => $result,
          'query' => $query,
          'exception' => $e
          ));    
      }
      

    } catch (Exception $e) {
      $result = null;
      return $this->renderPartial('sqlquery/viewResult', array(
        'result' => $result,
        'query' => $query,
        'exception' => $e
        ));    
    }
  }

  /**
   * Executes query on Database
   *
   * @param sfRequest $request A request object
   */
   public function executeSqlQuerySimple(sfWebRequest $request)
   {
     return $this->runQuery($request, FilePeer::DATABASE_NAME);
   }
   

}
