<?php

class Shipping_Insurance_Block_Checkout_Onepage_Shippinginsurance extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct()
    {

        $this->getCheckout()->setStepData('shippinginsurance', array(
            'label'     => Mage::helper('checkout')->__('Shipping Insurance'),
            'is_show'   => $this->isShow()
        ));

        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('shippinginsurance', 'allow', true);
            $this->getCheckout()->setStepData('billing', 'allow', false);
        }

        parent::_construct();
    }
}