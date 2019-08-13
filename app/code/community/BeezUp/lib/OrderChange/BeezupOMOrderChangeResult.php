<?php

	class BeezupOMOrderChangeResult extends BeezupOMResult {
		
		protected $sExecutionUUID = null;
		protected $aLinks = null;
	
		public static function fromArray(array $aData = array()){
			
			$oResult = new BeezupOMOrderChangeResult();
			
			foreach ($aData['links'] as $aLink){
				$oResult->addLink(BeezupOMLink::fromArray($aLink));
			}
			
			$oResult->setExecutionUUID($aData['executionUUID']);
			
			return $oResult;
			
		}
		
		
		/**
	 * @return the $sExecutionUUID
	 */
	public function getExecutionUUID()
	{
		return $this->sExecutionUUID;
	}

		/**
	 * @param NULL $sExecutionUUID
	 */
	public function setExecutionUUID($sExecutionUUID)
	{
		$this->sExecutionUUID = (string)$sExecutionUUID;
	}

	
		
	public function getLinkByRel($sRel){
		foreach ($this->getLinks() as $oLink){
			if ($oLink->getRel() === $sRel){
				return $oLink;
			}
		}
		return null;
	}
		/**
		 * @return the $aLinks
		 */
		public function getLinks()
		{
			return $this->aLinks;
		}
		
		/**
		 * @param multitype: $aLinks
		 */
		public function setLinks($aLinks)
		{
			$this->aLinks = $aLinks;
			return $this;
		}
		
		public function addLink(BeezupOMLink $oLink)
		{
			$this->aLinks[] = $oLink;
			return $this;
		}
		
		
	

}