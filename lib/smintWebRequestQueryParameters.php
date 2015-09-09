<?php

class smintQueryParameter
{
  const FIELDNAME = 'getFieldName';
  const FIELDVALUE = 'getFieldValue';
  const ISOPERATOROR = 'isOperatorOR';
  const ISNEGATION = 'isNegation';

  private $fieldName;
  private $fieldValue;
  private $operator_OR;
  private $negation;
  
  function __construct($fieldName, $fieldValue, $operator_OR = false, $negation = false)
  {
    $this->fieldName = $fieldName;
    $this->fieldValue = $fieldValue;
    $this->operator_OR = $operator_OR;
    $this->negation = $negation; 
  }
  
  public function getFieldName() {return $this->fieldName;} 
  public function getFieldValue() {return $this->fieldValue;} 
  public function isOperatorOR() {return $this->operator_OR;}   
  public function isNegation() {return $this->negation;}   
  public function getByConstant($varibleConstant) {return call_user_func(array( $this, $varibleConstant )); }
  
  public function toString() {
    $returnString = $this->fieldName.':'.$this->fieldValue;
    $returnString = ($this->operator_OR) ? "(OR)".$returnString : "(AND)".$returnString ;
    $returnString = ($this->negation) ? "(NOT)".$returnString : $returnString ;
    
    return $returnString; 
  }
}


/**
 * extends the sfRequest Class via Mixins 
 *
 * @package default
 * @author jochum
 */
class  smintWebRequestQueryParameters
{
  private $smintQueryParamters;
  
  function __construct($requestParameters) {
    $queryParamters = array();
    foreach ($requestParameters as $parameterName => $parameterValue) {
      if ( substr($parameterName, 0, 15) == 'queryparameter_' )       
      {
        $neg   = (substr($parameterName, 19, 3) == 'NOT') ? true : false ;
        $op_or = (substr($parameterName, 15, 3) == 'OR_') ? true : false ;

        $query = split("=", $parameterValue, 2);
        $field = $query[0];
        $fieldValue = $query[1];
        
        if (!isset($queryParamters[$field])) $queryParamters[$field] = array();  // create array if it does not exist yet
        array_push( $queryParamters[$field], new smintQueryParameter($field, $fieldValue, $op_or, $neg) );
      }
    }
    $this->smintQueryParamters = $queryParamters; 
  }
  
  public function getSmintQueryParamaters(){
    return $this->smintQueryParamters; 
  }  

  public function getSmintQueryParamatersByFieldName($fieldName){
    return $this->smintQueryParamters[$fieldName]; 
  }  

  public function getSmintQueryParamater($fieldName, $varibleConstant, $defaultValue){
    if ( array_key_exists($fieldName, $this->smintQueryParamters) ) {
      $queryParam = $this->smintQueryParamters[$fieldName][0];
      $param = ($queryParam != null) ? $queryParam->getByConstant($varibleConstant) : null ;
      return (isset($param) && ($param != null) ) ? $param : $defaultValue ;
    }
    else return $defaultValue;
  }  
  
}

