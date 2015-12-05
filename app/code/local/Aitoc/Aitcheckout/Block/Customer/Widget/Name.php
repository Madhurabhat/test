<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Customer/Widget/Name.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Customer_Widget_Name extends Mage_Customer_Block_Widget_Name
{
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('aitcheckout/customer/widget/name.phtml');
    }
    
    private $_showAmount = null;
    public function suffixBlockAmount() {
        if($this->_showAmount == null) {
            $this->_showAmount = ($this->showPrefix()?1:0) + ($this->showSuffix()?1:0) + ($this->showMiddlename()?1:0);
        }
        return $this->_showAmount;
    }

}  