<?php

class BeezUp_CatalogController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
		Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND,Mage_Core_Model_App_Area::PART_EVENTS);
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
		
        $this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(false)->toHtml());
    }

    public function configurableAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(true)->setChildXML(false)->toHtml());
    }
	
	public function childAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(true)->toHtml());
    }

}