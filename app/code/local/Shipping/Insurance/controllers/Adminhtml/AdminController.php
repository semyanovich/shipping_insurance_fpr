<?php

class Shipping_Insurance_Adminhtml_AdminController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('shipping_insurance');

        $contentBlock = $this->getLayout()->createBlock('shipping_insurance/adminhtml_admin');
        $this->_addContent($contentBlock);
        $this->renderLayout();
    }

}