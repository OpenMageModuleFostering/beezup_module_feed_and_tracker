<?php

class BeezUp_CatalogController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

        $helper = Mage::helper('beezup');

        $_active = $helper->getConfig('beezup/flux/active');		
        $_ip = $helper->getConfig('beezup/flux/ip');
        $_key = $helper->getConfig('beezup/flux/key');

        if (!$_active || ($_ip && $_ip != $helper->getRemoteAddr()) || ($_key && $this->getRequest()->getParam('key') != $_key)) {
            $this->norouteAction();
        }
    }

    public function xmlAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(false)->toHtml());
    }

    public function configurableAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(true)->setChildXML(false)->toHtml());
    }
	
	public function childAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(true)->toHtml());
    }

}