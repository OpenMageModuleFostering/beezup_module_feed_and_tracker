<?php
	
	class BeezUp_Model_System_Config_Source_Countries
	{
		
		public function toOptionArray()
		{
			
			$countryList = Mage::getResourceModel('directory/country_collection')
			->loadData()
			->toOptionArray(false);
			return $countryList;
			
			
		}
		
	}			