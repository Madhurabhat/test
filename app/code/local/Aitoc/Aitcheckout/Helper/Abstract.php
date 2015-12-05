<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Helper/Abstract.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Helper_Abstract extends Mage_Core_Helper_Abstract
{
    /**
     * Check is module exists and enabled in global config.
     *
     * @param string $moduleName the full module name, example Mage_Core
     * @return boolean
     */
    public function isModuleEnabled($moduleName = null)
    {
        if ($moduleName === null) {
            $moduleName = $this->_getModuleName();
        }

        if (!Mage::getConfig()->getNode('modules/' . $moduleName)) {
            return false;
        }

        $isActive = Mage::getConfig()->getNode('modules/' . $moduleName . '/active');
        if (!$isActive || !in_array((string)$isActive, array('true', '1'))) {
            return false;
        }
        return true;
    }
}  