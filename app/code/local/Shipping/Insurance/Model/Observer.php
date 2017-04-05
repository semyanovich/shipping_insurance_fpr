<?php

class Shipping_Insurance_Model_Observer
{

    /**
     * Set insurance amount invoiced to the quote
     *
     * @param Varien_Event_Observer $observer
     * @return Shipping_Insurance_Model_Observer
     */
    public function saveShippingMethod(Varien_Event_Observer $observer)
    {
        if (Mage::helper('shipping_insurance')->isEnabled()) {
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $amount = 0;
            if (Mage::app()->getRequest()->getPost('has_insurance', '')) {
                if ($amount = Mage::helper('shipping_insurance')->getAmount()) {
                    if (Mage::helper('shipping_insurance')->isPercentType()) {
                        $amount = $quote->getSubtotal() * $amount / 100;
                    }
                }
            }
            $quote->setShippingInsuranceAmount($amount);
        }
    }

    /**
     * Set insurance amount invoiced to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Shipping_Insurance_Model_Observer
     */
    public function saveOrderBefore(Varien_Event_Observer $observer)
    {
        if ($order = $observer->getEvent()->getOrder()) {
            if ($quote = $observer->getDataObject()->getQuote()) {
                $insurance_amount = $quote->getShippingInsuranceAmount();
                if ($insurance_amount > 0) {
                    $order->setShippingInsuranceAmount($insurance_amount);
                }
            }
        }
        return $this;
    }

    /**
     * Set insurance amount invoiced to the invoice
     *
     * @param Varien_Event_Observer $observer *
     * @return Shipping_Insurance_Model_Observer
     */
    public function invoiceSaveBefore(Varien_Event_Observer $observer)
    {
        if ($invoice = $observer->getEvent()->getInvoice()) {
            if ($order = $observer->getDataObject()->getOrder()) {
                if ($amount = $order->getShippingInsuranceAmount()) {
                    $invoice->setShippingInsuranceAmount($amount + $invoice->getFeeAmount());
                    $invoice->setShippingInsuranceAmount($amount + $invoice->getBaseFeeAmount());
                }
            }
        }
        return $this;
    }

    /**
     * Set insurance amount refunded to the order
     *
     * @param Varien_Event_Observer $observer *
     * @return Shipping_Insurance_Model_Observer
     */
    public function creditmemoSaveBefore(Varien_Event_Observer $observer)
    {
        if ($creditmemo = $observer->getEvent()->getCreditmemo()) {
            if ($order = $observer->getDataObject()->getOrder()) {
                if ($amount = $order->getShippingInsuranceAmount()) {
                    $creditmemo->setShippingInsuranceAmount($amount + $creditmemo->getFeeAmount());
                    $creditmemo->setShippingInsuranceAmount($amount + $creditmemo->getBaseFeeAmount());
                }
            }
        }
        return $this;
    }
}