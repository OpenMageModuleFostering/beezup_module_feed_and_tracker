<?php 

require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."bootstrap.php";
require_once  Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupRepository.php";


class Beezup_Model_System_Config_Backend_Credentials extends Mage_Core_Model_Config_Data
{			


    protected function _afterLoad()
    {
			$repository = new BeezupRepository();
			if($repository->isConnectionOk()) {
				$this->setValue(1);	
			} else {
				$this->setValue(0);
			}

	}
 
    protected function _beforeSave()
    {	
			$repository = new BeezupRepository();	
			if($repository->isConnectionOk()) {
					$this->setValue(1);		
			} else {
			$this->setValue(0);
			}
    }
	
}