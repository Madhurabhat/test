<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout/Review.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout_Review extends Mage_Checkout_Block_Onepage_Review
{
    /**
     *
     * @return Aitoc_Autcheckout_Helper_Data
     */
    public function getDefaultHelper()
    {
        return Mage::helper('aitcheckout');
    }

    public function isSagePayFormPaymentModeActive()
    {
        $post = $this->getRequest()->getPost();
        return isset($post['payment']['method']) && ('sagepayform' == $post['payment']['method']);
    }

    public function getReviewUrl()
    {
        
        if ($this->getDefaultHelper()->checkIfEbizmartsSagePaySuiteFormModeActiveOnly() && $this->isSagePayFormPaymentModeActive())
        {
            return $this->getUrl('sgps/payment/onepageSaveOrder', array('_secure'=>true));
        }
        else
        {
            return $this->getUrl('aitcheckout/checkout/saveOrder', array('_secure'=>true));
        }
    }
    
    /**
     * Validate if order amount is allowed to purchase
     *
     * @return boolean
     */
    public function isDisabled()
    {
        return Mage::helper('aitcheckout')->isPlaceOrderDisabled();
    }
    
}  