<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Model/Checkout/Cart/Observer.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Model_Checkout_Cart_Observer extends Mage_Core_Model_Abstract
{
    public function onAdminhtmlControllerActionPredispatchStart($observer)
    {
        if(!Mage::registry('aitpagecache_check_14') && Mage::getConfig()->getNode('modules/Aitoc_Aitpagecache/active')==='true')
        {
            if(file_exists(Mage::getBaseDir('magentobooster').DS.'use_cache.ser'))
            {
                Mage::register('aitpagecache_check_14', 1);
            }
            elseif(file_exists(Mage::getBaseDir('app/etc').DS.'use_cache.ser'))
            {
                Mage::register('aitpagecache_check_13', 1);
            }
        }
    }
}  