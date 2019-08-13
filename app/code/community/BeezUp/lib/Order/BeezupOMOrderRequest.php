<?php

	class BeezupOMOrderRequest extends BeezupOMRequest {

		private $sETagIfNoneMatch = '';
		private $bIgnoreCurrentActivity = false;
		private $oOrderIdentifier = null;
		private $aOrderItemMetaInfoCodes = array();
		private $aOrderMetaInfoCodes = null;

	/**
	 * @return the $sETagIfNoneMatch
	 */
	public function getETagIfNoneMatch()
	{
		return $this->sETagIfNoneMatch;
	}

		/**
	 * @return the $bIgnoreCurrentActivity
	 */
	public function getIgnoreCurrentActivity()
	{
		return $this->bIgnoreCurrentActivity;
	}
	
	public function ignoreCurrentActivity()
	{
		return $this->bIgnoreCurrentActivity;
	}
		/**
	 * @return the $oOrderIdentifier
	 */
	public function getOrderIdentifier()
	{
		return $this->oOrderIdentifier;
	}

		/**
	 * @return the $aOrderItemMetaInfoCodes
	 */
	public function getOrderItemMetaInfoCodes()
	{
		return $this->aOrderItemMetaInfoCodes;
	}

		/**
	 * @return the $aOrderMetaInfoCodes
	 */
	public function getOrderMetaInfoCodes()
	{
		return $this->aOrderMetaInfoCodes;
	}

		/**
	 * @param string $sETagIfNoneMatch
	 */
	public function setETagIfNoneMatch($sETagIfNoneMatch)
	{
		$this->sETagIfNoneMatch = $sETagIfNoneMatch;
		return $this;
	}

		/**
	 * @param boolean $bIgnoreCurrentActivity
	 */
	public function setIgnoreCurrentActivity($bIgnoreCurrentActivity)
	{
		$this->bIgnoreCurrentActivity = $bIgnoreCurrentActivity;
		return $this;
		
	}

		/**
	 * @param NULL $oOrderIdentifier
	 */
	public function setOrderIdentifier(BeezupOMOrderIdentifier $oOrderIdentifier)
	{
		$this->oOrderIdentifier = $oOrderIdentifier;
		return $this;
		
	}

		/**
	 * @param multitype: $aOrderItemMetaInfoCodes
	 */
	public function setaOrderItemMetaInfoCodes($aOrderItemMetaInfoCodes)
	{
		$this->aOrderItemMetaInfoCodes = $aOrderItemMetaInfoCodes;
		return $this;
		
	}

		/**
	 * @param NULL $aOrderMetaInfoCodes
	 */
	public function setOrderMetaInfoCodes($aOrderMetaInfoCodes)
	{
		$this->aOrderMetaInfoCodes = $aOrderMetaInfoCodes;
		return $this;
		
	}

	public static function fromArray(array $aData = array()){

		$oRequest = new BeezupOMOrderRequest();
		foreach ($aData as $sKey=>$mValue){
			$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
			$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
			if (!method_exists($oRequest,$sSetterMethod)){
				continue;
			}
			$cCallback = array($oRequest,$sSetterMethod);
			if ($sKey == 'orderIdentifier' && is_array($mValue)){
				call_user_func($cCallback, BeezupOMOrderIdentifier::fromArray($mValue));
			} else if (is_scalar($mValue)){
				call_user_func($cCallback, stristr($sSetterMethod,'UtcDate') ? new DateTime($mValue, new DateTimeZone('UTC')) : $mValue);
			} // if
		} // foreach
		return $oRequest;
	}

	public function toArray(){
			return array(
				'marketPlace' => $this->getOrderIdentifier()->getMarketplaceTechnicalCode(),
				'accountId' => $this->getOrderIdentifier()->getAccountId(),
				'beezUPOrderUUID' => $this->getOrderIdentifier()->getBeezupOrderUUID(),
				'ignoreCurrentActivity' => $this->getIgnoreCurrentActivity()
			);
		}
		
}
