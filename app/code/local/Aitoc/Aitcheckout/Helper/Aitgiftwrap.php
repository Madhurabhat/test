<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Helper/Aitgiftwrap.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Helper_Aitgiftwrap extends Aitoc_Aitcheckout_Helper_Abstract
{
    protected $_isEnabled = null;
    
    /**
     * Check whether the GR module is active or not
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        if($this->_isEnabled === null)
        {
            $this->_isEnabled = ($this->isModuleEnabled('Aitoc_Aitgiftwrap') && Mage::app()->getLayout()->createBlock('aitgiftwrap/giftwrap_onepage')->isShow())?true:false;
        }
        return $this->_isEnabled;
    }
}  