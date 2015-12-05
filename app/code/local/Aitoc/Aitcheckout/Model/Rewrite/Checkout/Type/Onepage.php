<?php
class Aitoc_Aitcheckout_Model_Rewrite_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    
    public function getQuote()
    {
        $quote = parent::getQuote();
        $action = Mage::app()->getRequest()->getActionName();
        if ('saveOrder' == $action)
        {   
            if (!$quote->validateMinimumAmount()) 
            {    
                $quote->setHasError(true);
            }    
        }
        return $quote;
    }
    
    public function saveCustomReview($data)
    {
        Mage::helper('aitcheckout/aitcheckoutfields')->saveCustomData($data);         
    }
    
 
} 