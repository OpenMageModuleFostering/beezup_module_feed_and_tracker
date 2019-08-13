<?php
	
	class BeezUp_Model_System_Config_Source_Carriers
	{
		
		public function toOptionArray()
		{
			
			$methods = Mage::getSingleton('shipping/config')->getActiveCarriers();
			
			$options = array();
			
			foreach($methods as $_ccode => $_carrier)
			{
				$_methodOptions = array();
				if($_methods = $_carrier->getAllowedMethods())
				{
					foreach($_methods as $_mcode => $_method)
					{
						$_code = $_ccode . '_' . $_mcode;
						$_methodOptions[] = array('value' => $_code, 'label' => $_method);
					}
					
					if(!$_title = Mage::getStoreConfig("carriers/$_ccode/title"))
					$_title = $_ccode;
					
					$options[] = array('value' => $_methodOptions, 'label' => $_title);
				}
			}
			
			return $options;	
			
		}
		
	}				