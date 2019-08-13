<?php

	class BeezupOMMarketplacesResult extends BeezupOMResult {

		protected $aMarketplaces = array();


		/**
		 * @return the $aValues
		 */
		public function getMarketplaces()
		{
			return $this->aMarketplaces;
		}

			/**
		 * @param multitype: $aValues
		 */
		public function setMarketplaces($aMarketplaces)
		{
			$this->aMarketplaces = $aMarketplaces;
			return  $this;
		}

		public function addMarketplace(BeezupOMMarketplace $oMarketplace)
		{
			$this->aMarketplaces[] = $oMarketplace;
			return  $this;
		}

		public static function fromArray(array $aData = array()){
			$oResult = new BeezupOMMarketplacesResult();
			if (isset($aData['marketPlaceAccountStores']) && is_array($aData['marketPlaceAccountStores'])){
				foreach ($aData['marketPlaceAccountStores'] as $aMarketplace){
					$oResult->addMarketplace(BeezupOMMarketplace::fromArray($aMarketplace));
				}
			}
			return $oResult;
		}


	}