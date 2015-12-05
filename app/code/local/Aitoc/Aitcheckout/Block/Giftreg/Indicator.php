<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Giftreg/Indicator.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Giftreg_Indicator extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        if(Mage::helper('aitcheckout/adjgiftregistry')->isEnabled())
        {
            return $this->getLayout()
                ->createBlock('adjgiftreg/indicator')
                ->setTemplate('adjgiftreg/indicator.phtml')
                ->toHtml()
            ;
        }
        return '';
    }
}  