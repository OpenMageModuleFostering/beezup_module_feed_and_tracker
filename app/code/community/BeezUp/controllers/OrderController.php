<?php 

class BeezUp_OrderController extends Mage_Adminhtml_Controller_Action {
	
	    public function indexAction()
    {
		
        $this->_title($this->__('Sales'))->_title($this->__('BeezUP Orders'));
        $this->loadLayout();
        $this->_setActiveMenu('sales/sales');
        $this->_addContent($this->getLayout()->createBlock('beezup/adminhtml_sales_order'));
        $this->renderLayout();
    }
 
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('beezup/adminhtml_sales_order_grid')->toHtml()
        );
    }
 
    public function exportInchooCsvAction()
    {
        $fileName = 'beezupOrders.csv';
        $grid = $this->getLayout()->createBlock('beezup/adminhtml_sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
 
    public function exportInchooExcelAction()
    {
        $fileName = 'beezupOrders.xml';
        $grid = $this->getLayout()->createBlock('beezup/adminhtml_sales_order_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
	
	
	
}