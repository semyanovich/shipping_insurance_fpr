<?php

class Shipping_Insurance_Helper_Data extends Mage_Core_Helper_Abstract
{
    /*
     * Path to settings
     */
    const XML_PATCH = "insurance_options/main_section/";

    /*
     * Value of percent type
     */
    private $percent_type = "2";

    /*
     * return is_enable setting
     */
    public function isEnabled()
    {
        return (Mage::getStoreConfig(self::XML_PATCH . 'is_enable', Mage::app()->getStore()) == 1);
    }

    /*
     * return value of amount
     */
    public function getAmount()
    {
        return Mage::getStoreConfig(self::XML_PATCH . 'amount', Mage::app()->getStore());
    }

    /*
     * return type setting
     */
    public function getType()
    {
        return Mage::getStoreConfig(self::XML_PATCH . 'type', Mage::app()->getStore());
    }

    /**
     * Retrieve checkout session model
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Retrieve checkout quote model object
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    /**
     * Retrieve value of shipping insurance
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getInsurance()
    {
        if ($this->isEnabled()){
            if ($amount = $this->getAmount()) {
                if ($this->getType() === $this->percent_type) {
                    return $this->getCheckout()->getQuote()->getSubtotal() * $amount / 100;
                }
                return $amount;
            }
        } 
        return false;
    }

    public function setInsurance($entity){
        if (get_class($entity) === "Mage_Sales_Model_Quote_Address") {
            $data = $entity->getQuote();
        } else {
            $data = $entity->getOrder();
        }
        $insurance_amount = $data->getShippingInsuranceAmount();
        if ($insurance_amount > 0) {
            $entity->setGrandTotal($entity->getGrandTotal() + $insurance_amount);
            $entity->setBaseGrandTotal($entity->getBaseGrandTotal() + $insurance_amount);
        }
        return $this;
    }
}   