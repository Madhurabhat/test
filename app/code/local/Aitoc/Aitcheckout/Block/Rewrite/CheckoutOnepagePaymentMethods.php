<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Rewrite/CheckoutOnepagePaymentMethods.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Rewrite_CheckoutOnepagePaymentMethods extends Mage_Checkout_Block_Onepage_Payment_Methods
{
    protected function _canUseMethod($method)
    {
        $country = $this->getQuote()->getBillingAddress()->getCountry();
        if(empty($country))
            $country = Mage::helper('aitcheckout')->getDefaultCountry();
                
        if (!$method || !$method->canUseCheckout()) 
        {
            return false;
        }
        if (!$method->canUseForCountry($country)) 
        {
            return false;
        }

        if (!$method->canUseForCurrency(Mage::app()->getStore()->getBaseCurrencyCode())) {
            return false;
        }

        /**
         * Checking for min/max order total for assigned payment method
         */
        $total = $this->getQuote()->getBaseGrandTotal();
        $minTotal = $method->getConfigData('min_order_total');
        $maxTotal = $method->getConfigData('max_order_total');

        if((!empty($minTotal) && ($total < $minTotal)) || (!empty($maxTotal) && ($total > $maxTotal))) {
            return false;
        }
        return true;
    }
}  