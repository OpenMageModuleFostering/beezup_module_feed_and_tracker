<?php 

class BeezUp_Block_Adminhtml_Sales_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'beezup';
        $this->_controller = 'adminhtml_sales_order';
        $this->_headerText = Mage::helper('beezup')->__('BeezUP Orders');
 
        parent::__construct();
        $this->_removeButton('add');
    }
}