<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Captcha.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  

if (version_compare(Mage::getVersion(), '1.7.0.0', '<'))
{
    class Aitoc_Aitcheckout_Block_Captcha extends Mage_Core_Block_Template
    {
    
    }
}
else
{
    class Aitoc_Aitcheckout_Block_Captcha extends Mage_Captcha_Block_Captcha
    {
    
        protected function _prepareLayout()
        {
            $this->getLayout()->getBlock('head')->addJs('mage/captcha.js');
    
            return parent::_prepareLayout();
        }    
    
    }
}  