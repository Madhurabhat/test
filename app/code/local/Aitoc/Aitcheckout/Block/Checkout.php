<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout extends Mage_Checkout_Block_Onepage_Abstract
{
    public function __construct() {
        Mage::helper('aitcheckout/captcha')->resetConfirmedCaptchas();
        return parent::__construct();
    }

    protected function _preloadShippingMethods()
    {
        $quote = $this->getQuote();
        $customer = $this->getCustomer();
        
        if(!$customer->getId() || (!$customer->getDefaultShippingAddress() && !$customer->getDefaultBillingAddress()))
        {
            $address = $quote->getShippingAddress();
            if(!$address->getCountryId()) 
            {
                $address
                    ->setCollectShippingRates(true)
                    ->setCountryId(Mage::helper('aitcheckout')->getDefaultCountry());
                if ($customer->getTaxClassId())
                {
                    $address
                        ->setRegionId(Mage::getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_DEFAULT_REGION))
                        ->setPostcode(Mage::getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_DEFAULT_POSTCODE));
                }
                $quote->save();
            }
        }
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $this->_preloadShippingMethods();

        foreach(Mage::helper('aitcheckout/aitconfcheckout')->getDisabledSectionHash() as $disabledStep)
        {
            $this->unsetChild($disabledStep);
        }
    }

    protected function _afterToHtml($html)
    {
        if($this->helper('aitcheckout')->isShowCheckoutInCart() && $this->helper('aitcheckout')->isCompactDesign()) {
            $content = $this->getLayout()->getBlock('content');
            $content->setText( preg_replace('|\<div class="totals"\>(.*)\</div\>|Uis','', $content->getText()) );
        }
        return parent::_afterToHtml($html);
    }

    protected function _setCompactTemplate() {
        $this->getLayout()->getBlock('head')->addItem('skin_css', 'css/aitoc/aitcheckout/compact.css');
        $this->setTemplate($this->getCompactTemplate());
    }

    public function setContext($context)
    {
        $cartBlock = $this->getLayout()->getBlock('checkout.cart');

        if (!$this->helper('aitcheckout')->isShowCheckoutInCart() || $this->helper('aitcheckout')->isDisabled())
        {
            $cartBlock->getParentBlock()->unsetChild('aitcheckout.checkout');
        } else {
            $this->getCheckout()->setCartWasUpdated(false);
            $cartBlock->unsetChild('shipping');

            if($this->helper('aitcheckout')->isCompactDesign()) {
                $cartBlock->unsetChild('crosssell');
                $cartBlock->unsetChild('coupon');
                $cartBlock->unsetChild('totals');
                $cartBlock->unsetChild('methods');
                $cartBlock->getChild('top_methods')->unsetChild('checkout.cart.methods.onepage');
            }

            if (!$this->helper('aitcheckout')->isShowProceedToCheckoutButton())
            {
                $cartBlock->getChild('top_methods')->unsetChild('checkout.cart.methods.onepage');
                if(!Mage::helper('aitcheckout')->isCompactDesign())
                {
                    $cartBlock->getChild('methods')->unsetChild('checkout.cart.methods.onepage');
                }
            }
            if ($this->getQuote()->hasItems() && !$this->getQuote()->validateMinimumAmount())
            {
                if ($this->helper('aitcheckout')->isShowCheckoutInCart())
                {
                    $error = Mage::getStoreConfig('sales/minimum_order/error_message');
                    $this->getCheckout()->addError($error);
                }
            }
        }
    }

    public function chooseTemplate()
    {
	    $controller = Mage::app()->getRequest()->getControllerName();
        
		if($controller == 'cart' && $this->helper('aitcheckout')->isShowCheckoutInCart())
		{									
		    if($this->helper('aitcheckout')->isErrorQuoteItemQty())
			{
                $this->setTemplate($this->getEmptyTemplate());
				return;
			}
		}
	
        if ($this->getQuote()->getItemsCount()) {
            if($this->helper('aitcheckout')->isCompactDesign()) {
                $this->_setCompactTemplate();
            } else {
                $this->setTemplate($this->getCheckoutTemplate());
            }
            if ($this->helper('aitcheckout')->getTocMode() == Aitoc_Aitcheckout_Helper_Data::CONDITIONS_POPUP) {
                $this->getLayout()->getBlock('head')->addItem('skin_css', 'css/aitoc/aitcheckout/popup.css');
            }
            if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->getLayout()->getBlock('head')->addItem('skin_css', 'css/aitoc/aitcheckout/popup.css');
                $this->getLayout()->getBlock('head')->addItem('skin_js', 'js/aitoc/aitcheckout/popup.js');
            }
        } else {
            $this->setTemplate($this->getEmptyTemplate());
        }
    }

    public function getContinueShoppingUrl()
    {
        $url = $this->getData('continue_shopping_url');
        if (is_null($url)) {
            $url = Mage::getSingleton('checkout/session')->getContinueShoppingUrl(true);
            if (!$url) {
                $url = Mage::getUrl();
            }
            $this->setData('continue_shopping_url', $url);
        }
        return $url;
    }

    /**
     * Return list of available checkout methods
     *
     * @param string $nameInLayout Container block alias in layout
     * @return array
     */
    public function getMethods($nameInLayout)
    {
        if ($this->getChild($nameInLayout) instanceof Mage_Core_Block_Abstract) {
            return $this->getChild($nameInLayout)->getSortedChildren();
        }
        return array();
    }

    /**
     * Return HTML of checkout method (link, button etc.)
     *
     * @param string $name Block name in layout
     * @return string
     */
    public function getMethodHtml($name)
    {
        $block = $this->getLayout()->getBlock($name);
        if (!$block) {
            Mage::throwException(Mage::helper('aitcheckout')->__('Invalid method: %s', $name));
        }
        return $block->toHtml();
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