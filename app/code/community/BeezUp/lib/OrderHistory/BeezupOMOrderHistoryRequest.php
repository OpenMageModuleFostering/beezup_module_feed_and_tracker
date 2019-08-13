<?php

class BeezupOMOrderHistoryRequest extends BeezupOMRequest {
	
	protected $sEtagIfNoneMatch = null;
	protected $oOrderIdentifier = null;
	
	// @todo
	public static function fromArray(array $aData = array()){
		$oRequest = new BeezupOMOrderHistoryRequest();
		$oOrderIdentifier = BeezupOMOrderIdentifier::fromArray(
			array(
				'beezuporderuuid' 			=> $aData['beezUPOrderUUID'],
				'marketplace'  				=> $aData['marketPlaceTechnicalCode'],
				'accountid'					=> $aData['accountId'],
			)
		);
		$oRequest->setOrderIdentifier($oOrderIdentifier);
	
		return $oRequest;
		
	}
	
	public function toArray(array $aData = array()){
			return array(
				'EtagIfNoneMatch' => $this->getEtagIfNoneMatch(),
				'accountId' => $this->getOrderIdentifier()->getAccountId(),
				'beezUPOrderUUID' => $this->getOrderIdentifier()->getBeezupOrderUUID(),
				'ignoreCurrentActivity' => $this->getIgnoreCurrentActivity()
			);
	}
	
	/**
	 * 
	 * 
	 * @return the $sEtagIfNoneMatch
	 */
	public function getEtagIfNoneMatch()
	{
		return $this->sEtagIfNoneMatch;
	}

	/**
	 * @param NULL $sEtagIfNoneMatch
	 */
	public function setEtagIfNoneMatch($sEtagIfNoneMatch)
	{
		$this->sEtagIfNoneMatch = $sEtagIfNoneMatch;
		return $this;
	}

	/**
	 * @return the $oOrderIdentifier
	 */
	public function getOrderIdentifier()
	{
		return $this->oOrderIdentifier;
	}

	/**
	 * @param NULL $oOrderIdentifier
	 */
	public function setOrderIdentifier(BeezupOMOrderIdentifier $oOrderIdentifier)
	{
		$this->oOrderIdentifier = $oOrderIdentifier;
		return $this;
	}
		
}