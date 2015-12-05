<?php
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.3.23_402374
 * Purchase ID: xiV6MfXRmTSGh2Y43bx6V0jXUK5bHK0Rez65sSGYYW
 * Generated:   2012-10-18 00:11:49
 * File path:   app/code/local/Aitoc/Aitcheckout/Model/Rewrite/Core/Url.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
  
class Aitoc_Aitcheckout_Model_Rewrite_Core_Url extends Mage_Core_Model_Url
{
    public function getRouteUrl($routePath=null, $routeParams=null)
    {
        $this->unsetData('route_params');

        if (isset($routeParams['_direct'])) {
            if (is_array($routeParams)) {
                $this->setRouteParams($routeParams, false);
            }
            return $this->getBaseUrl().$routeParams['_direct'];
        }

        if (!is_null($routePath)) {
            $this->setRoutePath($routePath);
        }
        if (is_array($routeParams)) {
            $this->setRouteParams($routeParams, false);
        }
        
//aitoc start 
        if (!Mage::helper('aitcheckout')->isDisabled() && Mage::helper('aitcheckout')->isShowCheckoutInCart())
        {
            if (false !== strpos($routePath, 'checkout/cart') )
            {
                $this->setSecure(true);
            }           
        } 
 //aitoc finish
 
        $url = $this->getBaseUrl().$this->getRoutePath($routeParams);
        return $url;
    }
}  