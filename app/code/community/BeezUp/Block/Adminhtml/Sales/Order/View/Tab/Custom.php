<?php
///app/design/adminhtml/default/default/layout/ beezup_salestab.xml
//app\design\adminhtml\default\default\template/beezup/custom.phtml
class BeezUp_Block_Adminhtml_Sales_Order_View_Tab_Custom
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_chat = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('beezup/custom.phtml');
    }

    public function getTabLabel() {
        return $this->__('BeezUP Info');
    }

    public function getTabTitle() {
        return $this->__('BeezUP Info');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }
	
}