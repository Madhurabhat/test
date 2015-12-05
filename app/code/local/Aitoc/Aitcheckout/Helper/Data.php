<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Helper/Data.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Helper_Data extends Aitoc_Aitcheckout_Helper_Abstract
{

    const IS_SHOW_CHECKOUT_OUTSIDE_CART = 0;
    const IS_SHOW_CHECKOUT_IN_CART = 1;
    const IS_SHOW_CART_IN_CHECKOUT = 2;
    const IS_DISABLED = 3;

    const DESIGN_DEFAULT = 0;
    const DESIGN_COMPACT = 1;

    const CONDITIONS_DEFAULT    = 0;
    const CONDITIONS_POPUP      = 1;

    const CHECKBOX_AVAILABLE_INSTANTLY  = 0;
    const CHECKBOX_VIEWING_REQUIRED     = 1;

    protected $_checkoutShowMethod = null;
    protected $_designType         = null;
    protected $_lastErrorMessage = false;

    protected $_rule = null;

    protected function _getCheckoutShowMethod()
    {
        if (is_null($this->_checkoutShowMethod))
        {
            $this->_checkoutShowMethod = Mage::getStoreConfig('checkout/aitcheckout/checkout_show_method');
        }
        return $this->_checkoutShowMethod;
    }

    protected function _getDesignType()
    {
        if (is_null($this->_designType))
        {
            $this->_designType = Mage::getStoreConfig('checkout/aitcheckout/design_type');
        }
        return $this->_designType;
    }

    public function isShowCheckoutInCart()
    {
        return (self::IS_SHOW_CHECKOUT_IN_CART == $this->_getCheckoutShowMethod());
    }

    public function isDisabled()
    {
        return (!$this->_checkRule() || self::IS_DISABLED == $this->_getCheckoutShowMethod() || !Mage::getStoreConfigFlag('checkout/options/onepage_checkout_enabled'));
    }

    public function isShowCheckoutOutsideCart()
    {
        return (self::IS_SHOW_CHECKOUT_OUTSIDE_CART == $this->_getCheckoutShowMethod());
    }

    public function isShowCartInCheckout()
    {
        return (self::IS_SHOW_CART_IN_CHECKOUT == $this->_getCheckoutShowMethod());
    }

    public function isCompactDesign()
    {
        return (self::DESIGN_COMPACT == $this->_getDesignType() );
    }

    public function isShowProceedToCheckoutButton()
    {
        if ($this->isShowCheckoutInCart())
        {
            return Mage::getStoreConfig('checkout/aitcheckout/show_proceed_to_checkout_button');
        }
        return true;
    }

    public function isShowCheckoutTitle()
    {
        return Mage::getStoreConfigFlag('checkout/aitcheckout/show_title');
    }

    public function getCheckoutTitle()
    {
        return Mage::getStoreConfig('checkout/aitcheckout/title_text');
    }

    public function getCheckoutUrl()
    {
        if ($this->isShowCheckoutInCart())
        {
            return $this->getCartUrl();
        }
        return 'aitcheckout/checkout';
    }

    public function getCartUrl()
    {
        if ($this->isShowCartInCheckout())
        {
            return $this->getCheckoutUrl();
        }
        return 'checkout/cart';
    }

    private function _checkRule()
    {
        if(is_null($this->_rule))
        {
            $this->_rule = true;
            
        }

        return $this->_rule;
    }

    public function canEditCartItems()
    {
        if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.5'))
        {
            return true;
        }
        return false;
    }

    public function getReviewTotalsColspan()
    {
        if($this->isCompactDesign() && $this->isShowCartInCheckout()) {
            return 2;
        }
        $isDisplayTax = Mage::helper('tax')->displayCartBothPrices() ? 2 : 0;
        $isAllowWishlist = Mage::helper('wishlist')->isAllowInCart() ? 1 : 0;
        $canEditCartItems = $this->canEditCartItems() ? 1 : 0;
        $isShowCheckoutInCart = $this->isShowCartInCheckout() ? 1 : 0;

        return ($this->isShowCartInCheckout() ? 5 : 3)
            + $isAllowWishlist  * $isShowCheckoutInCart
            + $canEditCartItems * $isShowCheckoutInCart
            + $isDisplayTax;
    }

    public function getDefaultCountry()
    {
        if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.5'))
        {
            return Mage::helper('core')->getDefaultCountry();
        }
        else {
            return Mage::getStoreConfig('general/country/default');
        }
    }

    /**
     * Get quote checkout method
     *
     * @return string
     */
    public function getCheckoutMethod($onepage)
    {
      if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('=1.4.1'))
      {
          return $onepage->getCheckoutMethod();
      }
      return $onepage->getCheckoutMehod();
    }

    /*
     * Terms and conditions display mode
     */
    public function getTocMode()
    {
        return Mage::getStoreConfig('checkout/aitcheckout/conditions_mode');
    }

    /*
     * Terms and conditions popup width
     */
    public function getTocPopupWidth()
    {
        return Mage::getStoreConfig('checkout/aitcheckout/popup_width');
    }

    /*
     * Terms and conditions popup height
     */
    public function getTocPopupHeight()
    {
        return Mage::getStoreConfig('checkout/aitcheckout/popup_height');
    }

    /*
     * Terms and conditions checkbox behavior
     */
    public function getTocCheckboxBehavior()
    {
        return Mage::getStoreConfig('checkout/aitcheckout/checkbox_behavior');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteActive()
    {
        try {
            return Aitoc_Aitsys_Abstract_Service::get()->isModuleActive('Ebizmarts_SagePaySuite');
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteFormModeActive()
    {
        return $this->checkIfEbizmartsSagePaySuiteActive() && Mage::getStoreConfig('payment/sagepayform/active');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteServerModeActive()
    {
        return $this->checkIfEbizmartsSagePaySuiteActive() && Mage::getStoreConfig('payment/sagepayserver/active');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteServerMotoModeActive()
    {
        return $this->checkIfEbizmartsSagePaySuiteActive() && Mage::getStoreConfig('payment/sagepayserver_moto/active');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteDirectProMotoModeActive()
    {
        return $this->checkIfEbizmartsSagePaySuiteActive() && Mage::getStoreConfig('payment/sagepaydirectpro_moto/active');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteDirectProModeActive()
    {
        return $this->checkIfEbizmartsSagePaySuiteActive() && Mage::getStoreConfig('payment/sagepaydirectpro/active');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuitePaypalModeActive()
    {
        return $this->checkIfEbizmartsSagePaySuiteActive() && Mage::getStoreConfig('payment/sagepaypaypal/active');
    }

    /**
     *
     * @return boolean
     */
    public function checkIfEbizmartsSagePaySuiteFormModeActiveOnly()
    {
        return $this->checkIfEbizmartsSagePaySuiteFormModeActive()
                && !$this->checkIfEbizmartsSagePaySuiteServerModeActive()
                && !$this->checkIfEbizmartsSagePaySuiteServerMotoModeActive()
                && !$this->checkIfEbizmartsSagePaySuiteDirectProMotoModeActive()
                && !$this->checkIfEbizmartsSagePaySuiteDirectProModeActive()
                && !$this->checkIfEbizmartsSagePaySuitePaypalModeActive();
    }

    /**
     *
     * @return boolean
     */
    public function isPlaceOrderDisabled() {
        return !Mage::getSingleton('checkout/session')->getQuote()->validateMinimumAmount();
    }
    
    public function isErrorQuoteItemQty()
    {
        $errorCode = '';
        
        if(version_compare(Mage::getVersion(), '1.6.0.0', '<'))
        {
            $quote = Mage::getSingleton('checkout/cart')->getQuote();
            
            if(isset($quote))
            {
                $messages = $quote->getMessages();
                    
                if(isset($messages) && count($messages) > 0)
                {
                    if(version_compare(Mage::getVersion(), '1.4.1.0', '<'))
                       $errorCode = Mage::helper('cataloginventory')->__('Some of the products can not be ordered in requested quantity');
                    else
                       $errorCode = Mage::helper('cataloginventory')->__('Some of the products can not be ordered in requested quantity.');
                    foreach($messages as $error)
                    {
                        if($error->getCode() == $errorCode) {
                            $this->_lastErrorMessage = $errorCode;
                            return true;
                        }
                    }
                }
            }
        }
        elseif(version_compare(Mage::getVersion(), '1.7.0.0', '>='))
        {
            $quote = Mage::getSingleton('checkout/cart')->getQuote();
        
            if(isset($quote))
            {
                $quoteItems = $quote->getAllItems();
                foreach($quoteItems as $quoteItem)
                {
                    if($quoteItem->getHasError())
                    {
                        $errors = $quoteItem->getErrorInfos();
                        foreach($errors as $error)
                        {
                            if(isset($error['code']) && $error['code'] == Mage_CatalogInventory_Helper_Data::ERROR_QTY) {
                                $this->_lastErrorMessage = $error['message'];
                                return true;
                            }
                        }
                    }
                }
            }
        }
        $this->_lastErrorMessage = false;
        return false;
    }

    public function getLastErrorMessage()
    {
        return $this->_lastErrorMessage;
    }
}  