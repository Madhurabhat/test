<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout/Giftwrap.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout_Giftwrap extends Mage_Core_Block_Template
{
    public function isShow()
    {
        return (Mage::helper('aitcheckout/aitgiftwrap')->isEnabled());
    }

    protected function _toHtml()
    {
        if($this->isShow())
        {
            $html = $this->getLayout()
                ->createBlock('aitgiftwrap/giftwrap_onepage')
                ->setTemplate('aitgiftwrap/giftwrap.phtml')
                ->toHtml();
               
            if($html)
            {
                return $html . parent::_toHtml();
            }
        }
        return '';
    }
}  