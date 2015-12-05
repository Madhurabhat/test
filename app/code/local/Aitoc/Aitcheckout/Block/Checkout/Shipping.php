<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout/Shipping.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout_Shipping extends Aitoc_Aitcheckout_Block_Checkout_Step
{
    protected $_stepType = 'Shipping';
    
    public function isShow()
    {
        return !$this->getQuote()->isVirtual();    
    }
    
    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }
    
    public function customerHasAddresses()
    {
        if (Mage::helper('aitcheckout/adjgiftregistry')->getGiftAddressId()){
            return true;
        }
        return parent::customerHasAddresses();
    }
}  