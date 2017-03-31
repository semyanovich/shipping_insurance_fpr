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

        if ($address->getAddressType() == 'shipping') {
            $this->_setAmount(0);
            $this->_setBaseAmount(0);

            $items = $this->_getAddressItems($address);
            if (!count($items)) {
                return $this;
            }

            $shipping_insurance = $this->getInsuranceAmount($address);

            if ($shipping_insurance) {
                $address->setGrandTotal($address->getGrandTotal() + $shipping_insurance);
                $address->setBaseGrandTotal($address->getBaseGrandTotal() + $shipping_insurance);
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
        if ($address->getAddressType() == 'shipping') {
            if ($amount = $this->getInsuranceAmount($address)) {
                $address->addTotal(array(
                    'code' => $this->getCode(),
                    'title' => Mage::helper('shipping_insurance')->__('Shipping Insurance'),
                    'value' => $amount
                ));
            }
        }
        return $this;
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @return bool|mixed
     */
    public function getInsuranceAmount(Mage_Sales_Model_Quote_Address $address)
    {
        $quote = $address->getQuote();
        if (empty($this->shipping_info)) {
            $this->setShippingInsurance($quote);
        }
        return $this->shipping_amount;
    }

    /**
     * setting $shipping_info
     *
     * @param Mage_Sales_Model_Quote $quote
     */
    protected function setShippingInsurance(Mage_Sales_Model_Quote $quote)
    {
        if ($shipping_info = $quote->getExtShippingInfo()) {
            $shipping_info = unserialize($shipping_info);
            if (array_key_exists('shipping_insurance', $shipping_info) && $shipping_info['shipping_insurance']) {
                $this->shipping_amount = $shipping_info['shipping_insurance'];
            }
        }
    }

}