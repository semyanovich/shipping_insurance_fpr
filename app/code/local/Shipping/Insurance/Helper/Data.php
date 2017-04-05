<?php

class Shipping_Insurance_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Path to settings
     */
    const XML_PATCH = "insurance_options/main_section/";

    /*
     * Value of percent type
     */
    const PERCENT_TYPE = "2";

    /**
     * return is_enable setting
     */
    public function isEnabled()
    {
        return (Mage::getStoreConfig(self::XML_PATCH . 'is_enable', Mage::app()->getStore()) == 1);
    }

    /**
     * return value of amount
     */
    public function getAmount()
    {
        return Mage::getStoreConfig(self::XML_PATCH . 'amount', Mage::app()->getStore());
    }

    /**
     * check if type is Percent
     *
     * @return boolean
     */
    public function isPercentType()
    {
        return (Mage::getStoreConfig(self::XML_PATCH . 'type', Mage::app()->getStore()) === self::PERCENT_TYPE);
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
                if ($this->isPercentType()) {
                    return $this->getCheckout()->getQuote()->getSubtotal() * $amount / 100;
                }
                return $amount;
            }
        } 
        return false;
    }

    /**
     * set value of shipping amount
     * 
     * @param Mage_Sales_Model_Quote_Address | Mage_Sales_Model_Order_Invoice
     * @return Mage_Sales_Model_Quote_Address | Mage_Sales_Model_Order_Invoice
     */
    public function setInsurance($entity)
    {
        if ($entity instanceof  Mage_Sales_Model_Quote_Address) {
            $insurance_amount = $entity->getQuote()->getShippingInsuranceAmount();
        } else {
            $insurance_amount = $entity->getOrder()->getShippingInsuranceAmount();
        }
        if ($insurance_amount > 0) {
            $entity->setGrandTotal($entity->getGrandTotal() + $insurance_amount);
            $entity->setBaseGrandTotal($entity->getBaseGrandTotal() + $insurance_amount);
        }
        return $this;
    }
}   