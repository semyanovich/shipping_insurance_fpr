<?php

class Shipping_Insurance_Model_Sales_Order_Total_Invoice_Insurance extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{

    /**
     * Collect invoice total
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return Shippin_Insurance_Model_Sales_Order_Total_Invoice_Insurance
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        parent::collect($invoice);
        Mage::helper('shipping_insurance')->setInsurance($invoice);
        return $this;
    }

}

