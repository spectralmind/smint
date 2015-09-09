<?php 
class logUseractionsFilter extends sfFilter
{
  public function execute($filterChain)
  {
    
    
    // Execute next filter
    $filterChain->execute();
  }
}