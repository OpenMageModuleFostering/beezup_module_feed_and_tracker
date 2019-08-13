<?php

	class BeezupOMOrderChangeRequest extends BeezupOMRequest {
		
		protected $sEtagIfMatch = null;
		protected $aMetaInfo = array();
		protected $oOrderIdentifier = null;
		protected $sUserName = null;
		protected $bTestMode = true; // on prendre pas de risk
		
		protected $sMethod = self::METHOD_POST;
		
	public static function fromArray(array $aData = array()){
		$oRequest = new BeezupOMOrderChangeRequest();
		foreach ($aData as $sKey=>$mValue){
			$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
			$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
			if (!method_exists($oRequest,$sSetterMethod)){
				continue;
			}
			$cCallback = array($oRequest,$sSetterMethod);
			if (is_scalar($mValue) && !is_null($mValue)){
				call_user_func($cCallback, $mValue);
			} // if
		} // foreach
		return $oRequest;
	}	
		
		
		/**
	 * @return the $sEtagIfMatch
	 */
	public function getEtagIfMatch()
	{
		return $this->sEtagIfMatch;
	}

		/**
	 * @param NULL $sEtagIfMatch
	 */
	public function setEtagIfMatch($sEtagIfMatch)
	{
		$this->sEtagIfMatch = $sEtagIfMatch;
		return $this;
	}

		/**
	 * @return the $aMetaInfo
	 */
	public function getMetaInfo()
	{
		return $this->aMetaInfo;
	}

		/**
	 * @param multitype: $aMetaInfo
	 */
	public function setMetaInfo($aMetaInfo)
	{
		$this->aMetaInfo = $aMetaInfo;
		return $this;
	}
	
	public function addMetaInfo($oMetaInfo)
	{
		$this->aMetaInfo[] = $oMetaInfo;
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

		/**
	 * @return the $sUserName
	 */
	public function getUserName()
	{
		return $this->sUserName;
	}

		/**
	 * @param NULL $sUserName
	 */
	public function setUserName($sUserName)
	{
		$this->sUserName = $sUserName;
		return $this;
	}

	public function isTestMode($bTestMode){
		return $this->bTestMode;
	}
	
	public function setTestMode($bTestMode){
		$this->bTestMode = $bTestMode; 
		return $this;
	}
	
	public function getTestMode(){
		return $this->bTestMode;
	}
	
}