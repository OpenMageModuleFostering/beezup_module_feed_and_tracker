<?php 
class Beezup_Model_System_Config_Backend_Attributes extends Mage_Core_Model_Config_Data
{
    protected function _afterLoad()
    {

        if (!is_array($this->getValue())) {
            $value = $this->getValue();
            $this->setValue(empty($value) ? false : unserialize($value));
        }
    }
 
    protected function _beforeSave()
    {

		//    Mage::getSingleton('core/session')->addError("Error");
        if (is_array($this->getValue())) {
	
	       $this->setValue(serialize($this->getValue()));
        }
    }
	
	

	
}