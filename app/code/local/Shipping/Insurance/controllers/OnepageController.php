<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';

class Shipping_Insurance_OnepageController extends Mage_Checkout_OnepageController
{

    /**
     * Shipping method save action
     */
    public function saveShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);
            // $result will contain error data if shipping method is empty
            if (!$result) {
                Mage::dispatchEvent(
                    'checkout_controller_onepage_save_shipping_method',
                    array(
                        'request' => $this->getRequest(),
                        'quote'   => $this->getOnepage()->getQuote()));
                $this->getOnepage()->getQuote()->collectTotals();
                $this->_prepareDataJSON($result);
                if (Mage::helper('shipping_insurance')->isEnabled()){
                    if (Mage::helper('shipping_insurance')->isNewStep()) {
                        $result['goto_section'] = 'shippinginsurance';
                    } else {
                        $data = $this->getRequest()->getPost('has_insurance', '');
                        $quote = $this->getOnepage()->getQuote();
                        $this->getExtShippingInfo($quote, $data);
                        $result = $quote->setExtShippingInfo(serialize($this->extShippingInfo));
                        $result['goto_section'] = 'payment';
                        $result['update_section'] = array(
                            'name' => 'payment-method',
                            'html' => $this->_getPaymentMethodsHtml()
                        );
                    }
                } else {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                }
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->_prepareDataJSON($result);
        }
    }

    /**
     * Shipping Insurance save action
     */
    public function saveShippingInsuranceAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('has_insurance', '');
            $quote = $this->getOnepage()->getQuote();
            $this->getExtShippingInfo($quote, $data);
            $result = $quote->setExtShippingInfo(serialize($this->extShippingInfo));
            if ($result) {
                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            } else {
                $result['goto_section'] = 'shippinginsurance';
                if (! $data){
                    $result['error'] = $this->__('Unable to set Shipping Insurance.');
                }
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->_prepareDataJSON($result);
        }
    }


    private function getExtShippingInfo($quote, $data)
    {
        if ($amount = Mage::helper('shipping_insurance')->getAmount()) {
            if (Mage::helper('shipping_insurance')->getType() === '2') {
                $amount =  $quote->getSubtotal() * $amount / 100;
            }
        }
        $ext_shipping_info = unserialize($quote->getExtShippingInfo());
        if (isset($ext_shipping_info['shipping_insurance'])) {
            $this->extShippingInfo = array_merge($ext_shipping_info, array('shipping_insurance' => $amount));
        } else {
            $this->extShippingInfo = array('shipping_insurance' => $amount);
        }

        if ($data !== "1"){
            unset($this->extShippingInfo['shipping_insurance']);
        }
    }
}