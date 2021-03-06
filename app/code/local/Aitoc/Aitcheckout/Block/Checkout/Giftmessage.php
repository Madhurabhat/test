<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Checkout/Giftmessage.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Checkout_Giftmessage extends Mage_GiftMessage_Block_Message_Inline
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('aitcheckout/giftmessage/inline.phtml');  
//        $this->setId('giftmessage_form_0')
//             ->setDontDisplayContainer(false)
//             ->setEntity(Mage::getSingleton('checkout/session')->getQuote());  
    }
    
    protected function _beforeToHtml()
    {
        $this->setId('giftmessage_form_0')
             ->setDontDisplayContainer(false)
             ->setEntity(Mage::getSingleton('checkout/session')->getQuote())  
             ->setType('onepage_checkout');   
    }

    public function isShow()
    {
        return (Mage::getStoreConfigFlag(Mage_GiftMessage_Helper_Message::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ITEMS) ||
                Mage::getStoreConfigFlag(Mage_GiftMessage_Helper_Message::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ORDER));
    } 
    
    public function isMessagesAvailable()
    {
        if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.4.2'))
        {
            return parent::isMessagesAvailable();
        }
        else { 
            return Mage::helper('giftmessage/message')->isMessagesAvailable('quote', $this->getEntity());
        }
    }
       
    
}  