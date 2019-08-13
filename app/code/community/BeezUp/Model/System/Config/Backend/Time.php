<?php 
class Beezup_Model_System_Config_Backend_Time extends Mage_Core_Model_Config_Data
{
    protected function _afterLoad()
    {

					  $this->setValue( $this->getValue());
	
		
    }

    protected function _beforeSave()
    {
		$value = $this->getValue();
			if(strtotime($value)) {
			$oDateTime = new DateTime($value, new DateTimeZone('UTC'));
				if ($oDateTime->getTimestamp()){
					  $this->setValue($oDateTime->getTimestamp());
				}		  
			}		
	}
	
}