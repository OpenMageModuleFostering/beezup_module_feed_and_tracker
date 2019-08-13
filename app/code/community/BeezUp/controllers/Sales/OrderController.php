<?php

$defController = Mage::getBaseDir()
	. DS . 'app' . DS . 'code' . DS . 'core'
	. DS . 'Mage' . DS . 'Adminhtml' . DS . 'controllers'
	. DS . 'Sales' . DS . 'OrderController.php';
	
	

require_once $defController;

/**
 * Adminhtml sales(123) orders controller
 *
 * @author      Inchoo <ivan.galambos@inchoo.net>
 */
class BeezUp_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{


    /**
     * Orders grid
     */
    public function indexAction()
    {
 
        if ($this->getRequest()->getParam('prepared') === 'beezup') {
 
     /*   	Mage::getSingleton('adminhtml/session')->addSuccess($this->__('You are viewing a) case of order grid.'));
 
        	$this->_title($this->__('Sales'))->_title($this->__('Orders'));
 
        	$from = date("Y-m-d", strtotime('-120 day'));
        	$to = date("Y-m-d", strtotime('-1 day'));
        	$locale = Mage::app()->getLocale()->getLocaleCode();
 
        	Mage::register('preparedFilter', array(
	        	'store_id' => '1',
	        	'status' => 'processing',
	        	'created_at' => array(
		        	'from'=> new Zend_Date($from, null, $locale),
		        	'to'=> new Zend_Date($to, null, $locale),
		        	'locale' => $locale,
		        	'orig_to' => Mage::helper('core')->formatDate($to),
		        	'orig_from' => Mage::helper('core')->formatDate($from),
		        	'datetime' => true
	        	)
        	));*/
			

			 
             //  Mage::getSingleton('adminhtml/session')->addSuccess($this->__('You are viewing Beezup sales order grid.'));
 
        	$this->_title($this->__('Sales'))->_title($this->__('Beezup Orders'));
			
			
 
		}
		else {
 
        //	Mage::getSingleton('adminhtml/session')->addSuccess($this->__('You are viewing default sales order grid.'));
 
        	$this->_title($this->__('Sales'))->_title($this->__('Orders'));
 
        }
        $this->_initAction()->renderLayout();
    }
}