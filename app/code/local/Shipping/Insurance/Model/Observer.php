<?php
class Shipping_Insurance_Model_Observer
{
    public function saveShippingMethod($observer) {
        if(Mage::helper('shipping_insurance')->isEnabled()) {
            $has_insurance = Mage::app()->getRequest()->getPost('has_insurance', '');
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $amount = 0;
            if ($has_insurance === "1") {
                if ($amount = Mage::helper('shipping_insurance')->getAmount()) {
                    if (Mage::helper('shipping_insurance')->getType() === '2') {
                        $amount = $quote->getSubtotal() * $amount / 100;
                    }
                }
            }
            $quote->setShippingInsuranceAmount($amount);
        }
    }

    public function saveOrderBefore(Varien_Event_Observer $observer) {
        if ($order = $observer->getEvent()->getOrder()) {
            if ($quote = $observer->getDataObject()->getQuote()) {
                $insurance_amount = $quote->getShippingInsuranceAmount();
                if($insurance_amount > 0) {
                    $order->setShippingInsuranceAmount($insurance_amount);
                }
            }
        }
        return $this;
    }

    /**
     * Set fee amount invoiced to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Shipping_Insurance_Model_Observer
     */
    public function invoiceSaveBefore(Varien_Event_Observer $observer)
    {
        if ($invoice = $observer->getEvent()->getInvoice()) {
            if ($order = $observer->getDataObject()->getOrder()) {
                if($amount = $order->getShippingInsuranceAmount()) {
                    $invoice->setShippingInsuranceAmount($amount + $invoice->getFeeAmount());
                    $invoice->setShippingInsuranceAmount($amount + $invoice->getBaseFeeAmount());
                }
            }
        }
        return $this;
    }

    /**
     * Set fee amount refunded to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Shipping_Insurance_Model_Observer
     */
    public function creditmemoSaveBefore(Varien_Event_Observer $observer)
    {
        if ($creditmemo = $observer->getEvent()->getCreditmemo()) {
            if ($order = $observer->getDataObject()->getOrder()){
                if($amount = $order->getShippingInsuranceAmount()) {
                    $creditmemo->setShippingInsuranceAmount($amount + $creditmemo->getFeeAmount());
                    $creditmemo->setShippingInsuranceAmount($amount + $creditmemo->getBaseFeeAmount());
                }
            }
        }
        return $this;
    }
}