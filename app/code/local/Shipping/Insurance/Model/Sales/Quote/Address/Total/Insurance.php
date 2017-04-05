<?php

class Shipping_Insurance_Model_Sales_Quote_Address_Total_Insurance extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    const SHIPPING_ADDRESS_TYPE = 'shipping';

    /**
     * Collect fee address amount
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Shipping_Insurance_Sales_Quote_Address_Total_Insurance
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        if (Mage::helper('shipping_insurance')->isEnabled()) {
            if ($address->getAddressType() == self::SHIPPING_ADDRESS_TYPE) {
                Mage::helper('shipping_insurance')->setInsurance($address);
            }
        }

        return $this;
    }

    /**
     * Add fee information to address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Shipping_Insurance_Sales_Quote_Address_Total_Insurance
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        parent::fetch($address);
        if (Mage::helper('shipping_insurance')->isEnabled()) {
            if ($address->getAddressType() == self::SHIPPING_ADDRESS_TYPE) {
                $quote = $address->getQuote();
                if ($insurance_amount = $quote->getShippingInsuranceAmount()) {
                    $address->addTotal(array(
                        'code' => $this->getCode(),
                        'title' => Mage::helper('shipping_insurance')->__('Shipping Insurance'),
                        'value' => $insurance_amount
                    ));
                }
            }
        }
        return $this;
    }
}