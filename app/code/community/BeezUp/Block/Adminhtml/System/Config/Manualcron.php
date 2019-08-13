<?php 

class Beezup_Block_Adminhtml_System_Config_Manualcron extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
			$url = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL) . 'beezup/cron/execute';
			return "<a href='".$url."' target='_blank'>".$url."</a>";
    }
	
	
	
}