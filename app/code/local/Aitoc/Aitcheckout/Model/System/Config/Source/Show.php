<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Model/System/Config/Source/Show.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Model_System_Config_Source_Show
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => Aitoc_Aitcheckout_Helper_Data::IS_SHOW_CHECKOUT_IN_CART, 'label'=>Mage::helper('aitcheckout')->__('Move Checkout to Cart')),
            array('value' => Aitoc_Aitcheckout_Helper_Data::IS_SHOW_CHECKOUT_OUTSIDE_CART, 'label'=>Mage::helper('aitcheckout')->__('Expand Checkout steps')),
            array('value' => Aitoc_Aitcheckout_Helper_Data::IS_SHOW_CART_IN_CHECKOUT, 'label'=>Mage::helper('aitcheckout')->__('Move Cart to Checkout')),
            array('value' => Aitoc_Aitcheckout_Helper_Data::IS_DISABLED, 'label'=>Mage::helper('aitcheckout')->__('Turn Off the extension'))
        );
    }

}  