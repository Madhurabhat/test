<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Controller/Action.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Controller_Action extends Mage_Checkout_Controller_Action
{

    protected function _getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    protected function _getStepHtml($step)
    {
        $layout = Mage::getModel('core/layout');
        $update = $layout->getUpdate();
        $update->load('aitcheckout_checkout_' . $step);
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    protected function _getMessagesHtml()
    {
        $quote = $this->_getOnepage()->getQuote();
        if ($quote->hasItems() && !$quote->validateMinimumAmount())
        {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            $this->_getOnepage()->getCheckout()->addError($error);
        }

        foreach ($quote->getMessages() as $message) {
            if ($message) {
                $this->_getOnepage()->getCheckout()->addMessage($message);
            }
        }

        $layout = $this->loadLayout()
            ->_initLayoutMessages('checkout/session')
            ->_initLayoutMessages('catalog/session');
        return $this->getLayout()->getMessagesBlock()->getGroupedHtml();
    }

    protected function _extractStepOutput($step, $result = array(), $resolve = false)
    {
        $output = Mage::getSingleton('aitcheckout/save_response')->addStepResponse($step, $result);
        if($resolve !== false && is_array($resolve)) {
            $output->addStepResponse('resolve_'.$step, $resolve);
        }

        $output = $output->toArray();
        $data = $this->getRequest()->getPost('reload_steps');
        if ($data)
        {
            $reloadSteps = explode(',', $data);
            foreach ($reloadSteps as $reloadStep)
            {
                if($reloadStep=='messages')
                {
                    $html = $this->_getMessagesHtml();
                } else{
                    $html = $this->_getStepHtml($reloadStep);
                }
                $output['update_section'][$reloadStep] = array( 'name' => $reloadStep, 'html' => $html );

            }
        }
        return $output;
    }


}  