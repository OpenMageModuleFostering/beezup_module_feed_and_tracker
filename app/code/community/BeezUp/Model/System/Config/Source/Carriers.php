<?php

	class BeezUp_Model_System_Config_Source_Carriers
	{

		public function toOptionArray()
		{

			$methods = Mage::getSingleton('shipping/config')->getAllCarriers();

			$options = array();

			foreach($methods as $_ccode => $_carrier)
			{
				$_methodOptions = array();


					if(!$_title = Mage::getStoreConfig("carriers/$_ccode/title"))
					$_title = $_ccode;

					$options[] = array('value' => $_ccode, 'label' => $_title);

			}

			return $options;

		}

	}
