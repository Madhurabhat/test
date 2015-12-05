<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Model/Observer.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Model_Observer
{
    public function onPredispatchCheckoutOnepageIndex(Varien_Event_Observer $observer)
    {
        if(!$this->_checkRule() || Mage::helper('aitcheckout')->isDisabled())return;
        $helper = Mage::helper('aitcheckout');
        $checkoutUrl = Mage::getUrl($helper->getCheckoutUrl(), array('_secure'=>true));
        $observer->getEvent()->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
        $observer->getEvent()->getControllerAction()->getResponse()->setRedirect($checkoutUrl);
    }

    public function onPredispatchCheckoutCartIndex(Varien_Event_Observer $observer)
    {
        if(!$this->_checkRule() || Mage::helper('aitcheckout')->isDisabled()) return;

        $helper = Mage::helper('aitcheckout');
        $cartUrl = Mage::getUrl($helper->getCartUrl(), array('_secure'=>true));
        if ($helper->isShowCartInCheckout())
        {
            $observer->getEvent()->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            $observer->getEvent()->getControllerAction()->getResponse()->setRedirect($cartUrl);
        }
    }

    private function _checkRule()
    {
        

        return true;
    }

    public function onOnepageSaveOrderAfter(Varien_Event_Observer $observer)
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $quote = $observer->getQuote();
        $helper = Mage::helper('aitcheckout');
        Mage::getSingleton('checkout/session')->setConfirmSameAsBilling(0);
        if ($request->getPost('newsletter') && $quote->getBillingAddress()->getEmail())
        {
            $session            = Mage::getSingleton('core/session');
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = $quote->getBillingAddress()->getEmail();

            $ownerId = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email)
                    ->getId();
            if ($ownerId !== null && $ownerId != $customerSession->getId())
            {
                $session->addError(Mage::helper('aitcheckout')->__('There was a problem with the newsletter subscription: This email address is already assigned to another user.'));
            } else {
                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE)
                {
                    $session->addSuccess(Mage::helper('aitcheckout')->__('Confirmation request for newsletter subscription has been sent.'));
                }
                else {
                    $session->addSuccess(Mage::helper('aitcheckout')->__('Thank you for your newsletter subscription.'));
                }
            }
        }
    }

    function onSalesOrderPlaceBefore(Varien_Event_Observer $observer)
    {
        $customer = $observer->getOrder()->getCustomer();
        //$checkoutMethod =  $observer->getOrder()->getQuote()->getData('checkout_method');
        $checkoutMethod =  $observer->getQuote()->getData('checkout_method');
        $password = $customer->getPassword();
        if( ($checkoutMethod == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER)
                || $checkoutMethod == 'register')
        {
            if ($password=== NULL || $password == '') {
                Mage::throwException( Mage::helper('aitcheckout')->__('The password cannot be empty.') );
            }
        }
    }

    public function updateQuote(Varien_Event_Observer $observer)
    {
        $quote = $observer->getQuote();
        if($quote instanceof Mage_Sales_Model_Quote)
        {
            $countryId = $quote->getBillingAddress()->getCountryId();
            if(empty($countryId))
            {
                $quote->getBillingAddress()->setCountryId(Mage::helper('aitcheckout')->getDefaultCountry());
            }
        }
    }

    public function checkCheckoutLoginCaptcha(Varien_Event_Observer $observer)
    {
       if(!Mage::helper('aitcheckout/captcha')->checkIfCaptchaEnabled()) {
           return false;//not 1.7
       }
       switch($observer->getEvent()->getName()) {
           case 'controller_action_predispatch_aitcheckout_customer_account_loginAjax':
               $formId = 'user_login';
               Mage::getSingleton('captcha/observer')->checkUserLogin($observer);
           break;
           case 'controller_action_predispatch_aitcheckout_customer_account_forgotPasswordAjax':
               $formId = 'user_forgotpassword';
               Mage::getSingleton('captcha/observer')->checkForgotPassword($observer);
           break;
       }
       $message = Mage::getSingleton('customer/session')->getMessages()->getLastAddedMessage();
       if($message instanceof Mage_Core_Model_Message_Error) {
           $controller = $observer->getControllerAction();
           Mage::getSingleton('customer/session')->getMessages()->deleteMessageByIdentifier( $message->getIdentifier() );
           $response = $controller->getResponse();
           if($response->isRedirect()) {
               $response->clearHeader('Location')->setHttpResponseCode(200);
           }
           $result = array('error' => $message->getText(), 'form_id'=> $formId, 'captcha_image'=>Mage::helper('aitcheckout/captcha')->generateNewCaptcha($formId) );
           $response->setBody(Mage::helper('core')->jsonEncode($result));
       }
    }

    /**
     * Force payment method save for the case when only one payment method is available
     */
    public function beforeSagepaySaveOrder($observer)
    {   
        $payment = Mage::app()->getRequest()->getParam('payment');
        Mage::getSingleton('checkout/type_onepage')->savePayment($payment);
    }
}  