<?php 

class Beezup_Block_Adminhtml_System_Config_Credentials extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
$this->setElement($element);
	$helper = Mage::helper('beezup');
	$status = $helper->getConfig('beezup/marketplace/connection_status');
	$lang = $this->__("NOT OK");
	$color = "red";
	 $width = "45px";
	 $css_style = "";
	 if( $this->getElement()->getValue() == 1) {
		$lang = $this->__("OK");
		$color = "green";
		 $width = "18px";
	 } else {
		 $css_style = '<style>#row_beezup_marketplace_cron_call,
#row_beezup_marketplace_cron_url,
#row_beezup_marketplace_sync_status,
#row_beezup_marketplace_status_mapping,
#row_beezup_marketplace_status_new,
#row_beezup_marketplace_status_progress,
#row_beezup_marketplace_status_closed,
#row_beezup_marketplace_status_aborted,
#row_beezup_marketplace_status_shipped,
#row_beezup_marketplace_status_cancelled,
#row_beezup_marketplace_stores_mapping,
#row_beezup_marketplace_stores,
#row_beezup_marketplace_field_mapping,
#row_beezup_marketplace_attributes,
#row_beezup_marketplace_log_block,
#row_beezup_marketplace_log,
#marketPlaceLogBlock {
display:none;
}</style>';
		 
	 }
	
	return $css_style."<div style='width:". $width.";padding:7px;height:17px;border-radius:6px;cursor:pointer;background:".$color."; color:white;'>".$lang."</div><input id='".$this->getElement()->getId() ."' type='hidden' name='".$this->getElement()->getName() ."' value='". $this->getElement()->getValue()."' />";
	 
    }
	
	
	
}