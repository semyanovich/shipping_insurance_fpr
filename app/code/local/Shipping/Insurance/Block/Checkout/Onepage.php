<?php

class Shipping_Insurance_Block_Checkout_Onepage extends Mage_Checkout_Block_Onepage
{
    /**
     * @return array
     */
    public function getSteps()
    {

        $steps = array();

        if (!$this->isCustomerLoggedIn()) {
            $steps['login'] = $this->getCheckout()->getStepData('login');
        }

        $is_enable = Mage::helper('shipping_insurance')->isEnabled();
        $is_new_step = Mage::helper('shipping_insurance')->isNewStep();
        $stepCodes = $this->_getStepCodes();
        $step = array_search('shipping_method', $stepCodes);

        if ($is_enable && $is_new_step && $step) {
            $stepCodes = array_merge(array_slice($stepCodes, 0, ++$step), ['shippinginsurance'], array_slice($stepCodes, $step));
        }

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
        return $steps;
    }
}