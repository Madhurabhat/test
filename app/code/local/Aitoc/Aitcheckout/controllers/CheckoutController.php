<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/controllers/CheckoutController.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_CheckoutController extends Aitoc_Aitcheckout_Controller_Action
{
    protected $_sectionSaveFunctions = array(
        'billing'           => 'saveBilling',
        'shipping'          => 'saveShipping',
        'shipping_method'   => 'saveShippingMethod',
        'payment'           => 'savePayment',
        'deliverydate'      => 'saveShippingMethod',
        'customreview'      => 'saveCustomReview',
    );

//    protected $_addressLoadFunctions = array(
//        'billing'           => 'getBillingAddress',
//        'shipping'          => 'getShippingAddress',
//    );

    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    protected function _expireAjax()
    {
        if (!$this->_getOnepage()->getQuote()->hasItems()
            || $this->_getOnepage()->getCheckout()->getCartWasUpdated(true))
        {
            $this->_ajaxRedirectResponse();
            return true;
        }

        return false;
    }

    protected function _saveCustomerRequiredData()
    {
        if (!$this->_getOnepage()->getCustomerSession()->getCustomerId())
        {
            return ;
        }
        $data = $requiredData = $this->getRequest()->getPost();
        $customer = $this->_getOnepage()
                         ->getCustomerSession()->getCustomer();
        if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.5'))
        {
        $customerForm = Mage::getModel('customer/form');
        $customerForm->setFormCode('checkout_register')
                     ->setIsAjaxRequest(Mage::app()->getRequest()->isAjax());
        $customerForm->setEntity($customer);
        $customerRequest = $customerForm->prepareRequest($data);
        $customerFields = $customerForm->extractData($customerRequest);
        }
        else
        {
            $customerFields = array(
                'firstname'    => 'firstname',
                'lastname'     => 'lastname',
                'email'        => 'email',
                'dob'          => 'dob',
                'taxvat'       => 'taxvat',
                'gender'       => 'gender',
                'suffix'       => 'suffix',
                'prefix'       => 'prefix',
            );
        }
        $requiredData = $this->getRequest()->getPost('billing');
        foreach ($requiredData as $fieldId=>$value)
        {
            if(isset($customerFields[$fieldId]))
            {
                $customer->setData($fieldId,$value);
            }
        }
        $customer->save();
        $this->_getOnepage()->getQuote()->setCustomer($customer);
    }

    protected function _isCaptchaCorrect()
    {
        if(!Mage::helper('aitcheckout/captcha')->checkIfCaptchaEnabled()) {
            return true;//not 1.7
        }
        $formId = $this->_getCheckoutCaptchaMethod();
        $helper = Mage::helper('aitcheckout/captcha');
        if($helper->isCaptchaPassed($formId)) {
            return true;
        }

        $observer = new Varien_Object();
        $observer->setControllerAction($this);

        Mage::getSingleton('captcha/observer')->checkGuestCheckout($observer);
        Mage::getSingleton('captcha/observer')->checkRegisterCheckout($observer);

        $response = $this->getResponse();
        if($response->getBody()) {
            $result = Mage::helper('core')->jsonDecode($response->getBody());
            if($result['error']== 1) {
                $result = new Varien_Object($result);
                $helper->renewCaptcha($result, $formId);

                $response->setBody(Mage::helper('core')->jsonEncode($this->_extractStepOutput('billing', $result->__toArray())));
                return false;
            }
        }
        $helper->setCaptchaAsConfirmed($formId);
        return true;
    }

    protected function _getCheckoutCaptchaMethod() {
        $checkoutMethod = $this->_getOnepage()->getQuote()->getCheckoutMethod();
        if ($checkoutMethod == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER) {
            $formId = 'register_during_checkout';
        } else {
            $formId = 'guest_checkout';
        }
        return $formId;
    }

    public function indexAction()
    {
        

        if(Mage::helper('aitcheckout')->isDisabled())
        {
            $this->_redirect('checkout/onepage');
            return;
        }

        $quote = $this->_getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            if (Mage::helper('aitcheckout')->isShowCheckoutOutsideCart())
            {
                $this->_redirect('checkout/cart');
                return;
            }
        }

        if ($quote->hasItems() && !$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            $this->_getOnepage()->getCheckout()->addError($error);
            if (Mage::helper('aitcheckout')->isShowCheckoutOutsideCart())
            {
                $this->_redirect('checkout/cart');
                return;
            }
        }

        foreach ($quote->getMessages() as $message) {
            if ($message) {
                $this->_getOnepage()->getCheckout()->addMessage($message);
            }
        }

        $this->_getOnepage()->getCheckout()->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
        $this->_getOnepage()->initCheckout();
        $this->loadLayout()
            ->_initLayoutMessages('checkout/session')
            ->_initLayoutMessages('catalog/session')
            ->getLayout()->getBlock('head')->setTitle(Mage::helper('aitcheckout')->getCheckoutTitle())
            ;

        if (Mage::helper('aitcheckout')->isShowCheckoutTitle())
        {
            $this->getLayout()
                ->getBlock('head')
                ->setTitle(
                    Mage::helper('aitcheckout')->__(Mage::helper('aitcheckout')->getCheckoutTitle())
                );
        }
        else {
            $this->getLayout()
                ->getBlock('head')
                ->setTitle(
                    Mage::helper('checkout')->__('Checkout')
                );
        }
        $this->renderLayout();
    }

    public function updateStepsAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getPost();
            $currentStep = $data['step'];
            $data = $this->getRequest()->getPost($currentStep, array());
            $customerAddressId = null;
            switch($currentStep)
            {
                case 'billing':
                    Mage::helper('aitcheckout')->getCheckoutMethod($this->_getOnepage());
                    if(false === $this->_isCaptchaCorrect()) {
                        return;
                    }
                    $this->_saveCustomerRequiredData();
                case 'shipping':
                    Mage::getSingleton('checkout/session')->setConfirmSameAsBilling(1);
                    $customerAddressId = $this->getRequest()->getPost($currentStep . '_address_id', false);
                    break;
                case 'shipping_method':
                    break;
                case 'payment':
                    // save and unset cfm params
//                    Mage::helper('aitcheckout/aitcheckoutfields')->saveCustomData($data);
//                    foreach ($data as $key => $val)
//                    {
//                        if (strpos($key, 'itoc_checkout_'))
//                        {
//                            unset($data[$key]);
//                        }
//                    }
                    break;
                case 'deliverydate':
                    $data = $this->getRequest()->getPost('shipping_method', array());
                    break;
                case 'aitgiftwrap':
                case 'giftmessage':
                    Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request'=>$this->getRequest(), 'quote'=>$this->_getOnepage()->getQuote()));
                    break;
            }
            $saveFunction = isset($this->_sectionSaveFunctions[$currentStep]) ? $this->_sectionSaveFunctions[$currentStep] : null;
            $resolve = false;
            $result = $saveFunction ? $this->_getOnepage()->$saveFunction($data, $customerAddressId) : null;
            if(Mage::helper('aitcheckout/captcha')->isJustConfirmed()) {
                $resolve = array(
                    'hide_captcha' => 'captcha-input-box-'.$this->_getCheckoutCaptchaMethod()
                );
            }

            $this->getResponse()
                ->setBody(
                    Mage::helper('core')->jsonEncode($this->_extractStepOutput($currentStep, $result, $resolve))
                );
        }
    }

    public function saveOrderAction()
    {
        $result = array();

        $payment = $this->getRequest()->getParam('payment');
        $this->_getOnepage()->savePayment($payment);

        try{
            $result = $this->validateOrder();
            $result['success'] = true;
            $result['error'] = false;
        } catch (Mage_Core_Exception $e) {
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
        }

        $redirectUrl = $this->_getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();

        Mage::dispatchEvent('aitcheckout_save_order',
                        array('request'=>$this->getRequest(),
                            'quote'=>$this->_getOnepage()->getQuote()));

        if ($redirectUrl)
        {
            $result['redirect'] = $redirectUrl;
            return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }

        $this->_forward('saveOrder', 'onepage', 'checkout', array('_secure' => true));
    }

    /**
     * Validate quote state to be able submited from one page checkout page
     *
     * @deprecated after 1.4 - service model doing quote validation
     * @return Mage_Checkout_Model_Type_Onepage
     */
    protected function validateOrder()
    {
        $helper = Mage::helper('checkout');
        if ($this->_getOnepage()->getQuote()->getIsMultiShipping()) {
            Mage::throwException(Mage::helper('aitcheckout')->__('Invalid checkout type.'));
        }

        if (!$this->_getOnepage()->getQuote()->isVirtual()) {
            $address = $this->_getOnepage()->getQuote()->getShippingAddress();
            $addressValidation = $address->validate();
            if ($addressValidation !== true) {
                Mage::throwException(Mage::helper('aitcheckout')->__('Please check shipping address information.'));
            }
            $method= $address->getShippingMethod();
            $rate  = $address->getShippingRateByCode($method);
            if (!$this->_getOnepage()->getQuote()->isVirtual() && (!$method || !$rate)) {
                Mage::throwException(Mage::helper('core')->__('Please specify shipping method.'));
            }
        }

        $addressValidation = $this->_getOnepage()->getQuote()->getBillingAddress()->validate();
        if ($addressValidation !== true) {
            Mage::throwException(Mage::helper('aitcheckout')->__('Please check billing address information.'));
        }

        if (!($this->_getOnepage()->getQuote()->getPayment()->getMethod())) {
            Mage::throwException(Mage::helper('aitcheckout')->__('Please select valid payment method.'));
        }
    }
}  