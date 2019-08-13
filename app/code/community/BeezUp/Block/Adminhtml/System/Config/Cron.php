<?php 

class Beezup_Block_Adminhtml_System_Config_Cron extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
			$sPhpBinary = defined('PHP_BINARY') ? PHP_BINARY : PHP_BINDIR;
			$sPhpExecutable = ($sPhpBinary ? rtrim($sPhpBinary, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR  : '' ) . 'php' . (DIRECTORY_SEPARATOR == '\\' ? '.exe' : '');
			return "<p style='width:550px;background: #EFECEC;'><code>".sprintf('*/10 * * * * %s %s', $sPhpExecutable,  Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL) . 'beezup/cron/execute')."</code></p>";
	 
    }
	
	
	
}		