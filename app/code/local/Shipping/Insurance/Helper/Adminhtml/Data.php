<?php

class Shipping_Insurance_Helper_Adminhtml_Data extends Mage_Core_Helper_Abstract
{
    
    public function getExtShippingInfo($id)
    {
        $order = Mage::getModel('sales/order')->load($id);
        $quote = Mage::getModel('sales/quote')->setStoreId($order->getStoreId());
        $quote->load($order->getQuoteId());
        $shipping_info = unserialize($quote->getExtShippingInfo());
        if (array_key_exists('shipping_insurance', $shipping_info) && $shipping_info['shipping_insurance']){
            return $shipping_info['shipping_insurance'];
        } else {
            return false;
        }
    }
}