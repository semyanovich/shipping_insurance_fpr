<?php

class Shipping_Insurance_Model_Sales_Order_Total_Creditmemo_Insurance extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{

    /**
     * Collect credit memo total
     *
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return Shippin_Insurance_Model_Sales_Order_Total_Creditmemo_Insurance
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        parent::collect($creditmemo);
        Mage::helper('shipping_insurance')->setInsurance($creditmemo);
        return $this;
    }
}

