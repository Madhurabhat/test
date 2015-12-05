<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Block/Links.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Block_Links extends Mage_Checkout_Block_Links
{
    protected $_rule = null; 

    /**
     * Add shopping cart link to parent block
     *
     * @return Mage_Checkout_Block_Links
     */
    public function addCartLink()
    {
        if(!$this->_checkRule() || $this->helper('aitcheckout')->isDisabled() || !$this->helper('aitcheckout')->isShowCartInCheckout())
        {
            return parent::addCartLink();
        }
        return $this;
    }

    /**
     * Add link on checkout page to parent block
     *
     * @return Mage_Checkout_Block_Links
     */
    public function addCheckoutLink()
    {
        if (!$this->_checkRule() || $this->helper('aitcheckout')->isDisabled() || $this->helper('aitcheckout')->isShowCheckoutOutsideCart())
        {
            return parent::addCheckoutLink();
        }
        
        if ($this->helper('aitcheckout')->isShowCartInCheckout())
        {
            $parentBlock = $this->getParentBlock();
            if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
                $count = $this->helper('checkout/cart')->getSummaryCount();
                
                $text = Mage::helper('checkout')->__('Checkout');
                if( $count > 0 ) {
                    $text .= " ($count ".$this->__(($count==1)?'item':'items').')';
                }
    
                $parentBlock->addLink($text, 'checkout', $text, true, array(), 50, null, 'class="top-link-checkout"');
            }
        }
        return $this;
    }
    
    private function _checkRule()
    {
        if(is_null($this->_rule))
        {
            $this->_rule = true;
            
        }
    
        return $this->_rule;
    }
}  