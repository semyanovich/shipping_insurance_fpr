<?php

class Shipping_Insurance_Model_Sales_Quote_Address_Total_Insurance extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    protected $_code = 'insurance';

    protected $shipping_amount = false;

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
            if ($address->getAddressType() == 'shipping') {
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
        if(Mage::helper('shipping_insurance')->isEnabled()) {
            if ($address->getAddressType() == 'shipping') {
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