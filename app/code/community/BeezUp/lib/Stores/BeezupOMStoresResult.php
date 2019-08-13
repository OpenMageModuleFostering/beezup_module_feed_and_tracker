<?php
	
	class BeezupOMStoresResult extends BeezupOMResult {
	
		protected $aStores = array();
		
	
		/**
	 * @return the $aValues
	 */
	public function getStores()
	{
		return $this->aStores;
	}

		/**
	 * @param multitype: $aValues
	 */
	public function setStores($aStores)
	{
		$this->aStores = $aStores;
		return  $this;
	}
	
	public function addStore(BeezupOMStore $oStore)
	{
		$this->aStores[] = $oStore;
		return  $this;
	}
	
	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMStoresResult();
		if (isset($aData['beezUPStores']) && is_array($aData['beezUPStores'])){
			foreach ($aData['beezUPStores'] as $aStore){
				$oResult->addStore(BeezupOMStore::fromArray($aStore));
			}
		}
		return $oResult;
		
	}
	
	
	}