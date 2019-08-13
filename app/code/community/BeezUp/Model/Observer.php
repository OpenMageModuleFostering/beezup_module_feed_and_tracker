<?php

class BeezUp_Model_Observer
{

    public function addBlockTracking($observer)
    {
        if (!Mage::getStoreConfigFlag('beezup/tracking/active') || !Mage::getStoreConfig('beezup/tracking/storeid')) return '';

        $layout = $observer->getLayout();
        $blockParent = $layout->getXpath("//block[@name='".Mage::getStoreConfig('beezup/tracking/position')."']");

        if (!$blockParent) return $this;

        $block = $blockParent[0]->addChild('block');
        $block->addAttribute('type', 'beezup/tracking');
        $block->addAttribute('name', 'beezup_tracking');
        $block->addAttribute('as', 'beezup_tracking');
    }

    public function addOrder($observer)
    {	
        if (!Mage::getStoreConfigFlag('beezup/tracking/active') || !Mage::getStoreConfig('beezup/tracking/storeid')) return '';

        $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
        $beezupBlock = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('beezup_tracking');
        if ($order && $beezupBlock) {
            $beezupBlock->setOrder($order);
        }
    }

}