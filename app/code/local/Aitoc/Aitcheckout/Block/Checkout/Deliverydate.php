<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout/Deliverydate.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout_Deliverydate extends Mage_Checkout_Block_Onepage_Abstract
{
    protected $_show = null;

    public function isShow()
    {   
        if(is_null($this->_show))
        {
            $this->_show = ($this->helper('aitcheckout')->isModuleEnabled('AdjustWare_Deliverydate') && Mage::getStoreConfigFlag('checkout/adjdeliverydate/enabled'));
        }
        return $this->_show;
    }
    
    protected function _toHtml()
    {
        if($this->isShow())
        {
            return $this->getLayout()
                ->createBlock('adjdeliverydate/container')
                ->setTemplate('aitcheckout/checkout/deliverydate.phtml')
                ->toHtml()
            ;
        }
        return '';
    }
}  