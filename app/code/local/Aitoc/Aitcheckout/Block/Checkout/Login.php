<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout/Login.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout_Login extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getCaptchaReloadUrl() {
        if(!$this->helper('aitcheckout/captcha')->checkIfCaptchaEnabled()) {
            return '';
        }
        $blockPath = Mage::helper('captcha')->getCaptcha('user_login')->getBlockName();
        $block = $this->getLayout()->createBlock($blockPath);
        return $block->getRefreshUrl();
    }

}  