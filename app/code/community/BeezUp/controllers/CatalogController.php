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
			
			$this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(false)->setPagination(false)->toHtml());
		}
		
		public function configurableAction()
		{
			$this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(true)->setChildXML(false)->setPagination(false)->toHtml());
		}
		
		public function childAction()
		{
			$this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(true)->setPagination(false)->toHtml());
		}
		
		
		public function paginateAction() {
			$feed = $this->getRequest()->getParam('feed');
			$limit = $this->getRequest()->getParam('limit');
			$page = $this->getRequest()->getParam('page');
			if(empty($feed) || empty($limit) || empty($page) || !is_numeric($limit) || !is_numeric($page)) {
				echo "Error you need to specify: feed, limit and page";
				die();
			}
			
			$pagination = array("limit" => (int)$limit, "page" => (int)$page);
			if($feed == "xml") {
				$this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(false)->setPagination($pagination)->toHtml());
				} elseif($feed == "configurable") {
					$this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(true)->setChildXML(false)->setPagination($pagination)->toHtml());
				} elseif($feed == "child") {
				$this->getResponse()->setHeader('Content-Type', 'text/xml')->setBody($this->getLayout()->createBlock('beezup/xml')->setConfigurable(false)->setChildXML(true)->setPagination($pagination)->toHtml());
				
			}
		}
		
		
	}		