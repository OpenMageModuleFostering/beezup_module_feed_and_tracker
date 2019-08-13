<?php 

class Beezup_Block_Adminhtml_System_Config_Syncstatus extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
$this->setElement($element);
	$helper = Mage::helper('beezup');
	$status = $helper->getConfig('beezup/marketplace/connection_status');
	$lang = $this->__("Not Syncing");
	$color = "#666";
	 $width = "67px";
	 if( $this->getElement()->getValue() == 1) {
		$lang = $this->__("Syncing");
		$color = "green";
		 $width = "45px";
	 }
	
	return "<div style='width:". $width.";padding:7px;height:17px;border-radius:6px;cursor:pointer;background:".$color."; color:white;'>".$lang."</div><input id='".$this->getElement()->getId() ."' type='hidden' name='".$this->getElement()->getName() ."' value='". $this->getElement()->getValue()."' />";
	 
    }
	
	
	
}