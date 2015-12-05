<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Model/Save/Response.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Model_Save_Response
{
    
    protected $_data = array();
    
    public function addStepResponse($step, $response)
    {
        $this->_data[$step] = $response;
        return $this;    
    }   
    
    public function isValid()
    {
        return true;    
    } 
    
    public function toArray()
    {
        if ($this->isValid())
        {
            return $this->_data;
        }        
    } 
    
}  