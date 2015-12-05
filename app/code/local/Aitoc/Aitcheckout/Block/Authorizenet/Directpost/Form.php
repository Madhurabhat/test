<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Authorizenet/Directpost/Form.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
// use magento autoload to check if file exists, if file doesn't exists - magento generate error
if(true !== @class_exists('Mage_Authorizenet_Block_Directpost_Form')) 
{
    class Mage_Authorizenet_Block_Directpost_Form extends Mage_Core_Block_Template
    {
    }
}

class Aitoc_Aitcheckout_Block_Authorizenet_Directpost_Form extends Mage_Authorizenet_Block_Directpost_Form
{
    public function setMethodInfo()
    {
        $payment = Mage::getSingleton('checkout/type_onepage')
            ->getQuote()
            ->getPayment();
        
        if(!$payment->getMethod())
        {
            return $this;
        }
        
        $this->setMethod($payment->getMethodInstance());
        return $this;
    }
    
    protected function _toHtml()
    {
	
	
        try {
			$this->getMethod();
		} catch (Exception $e) {
			return null;
		}

		if(!Mage::getStoreConfig('payment/authorizenet_directpost/active'))
        {
			return null;            
        }
       
        if ($this->getMethod()->getCode() != Mage::getSingleton('authorizenet/directpost')->getCode()) 
        {
            return null;
        }
		
		$html = parent::_toHtml();
		//start of fix for #0028299 bug
		$html = str_ireplace('<form id="co-directpost-form" action="#" method="post">', '<!--<form id="co-directpost-form" action="#" method="post">-->', $html);
		$html = str_ireplace('</form>', '<!--</form>-->', $html);
		//end of fix for #0028299 bug

		return $html;        
    }
}

  