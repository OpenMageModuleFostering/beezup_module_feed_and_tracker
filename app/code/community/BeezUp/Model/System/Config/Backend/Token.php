<?php 
class Beezup_Model_System_Config_Backend_Token extends Mage_Core_Model_Config_Data
{
    protected function _afterLoad()
    {

         $value = $this->getValue();
		if($value ==5) {
            $this->setValue($value);
        } else {
			 $this->setValue("");
			 		
		}
		
    }

    protected function _beforeSave()
    {

		
                  $value = $this->getValue();
		if($value ==5) {
            $this->setValue($value);
        } else {
				 $this->setValue("");
		}
    }
}