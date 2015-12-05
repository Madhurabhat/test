<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Model/System/Config/Source/CheckboxBehavior.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Model_System_Config_Source_CheckboxBehavior
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => Aitoc_Aitcheckout_Helper_Data::CHECKBOX_AVAILABLE_INSTANTLY,   'label'=>Mage::helper('aitcheckout')->__('Mark the checkbox')),
            array('value' => Aitoc_Aitcheckout_Helper_Data::CHECKBOX_VIEWING_REQUIRED,      'label'=>Mage::helper('aitcheckout')->__('Display a pop-up window')),
        );
    }

}  