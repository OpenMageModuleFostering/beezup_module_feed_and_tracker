<?php

class BeezupOMOrderChangeReporting {
	
	protected $oCreationUtcDate = null;
	protected $sErrorMessage = null;
	protected $sExecutionUUID = null;
	protected $sIPAddress = null;
	protected $bIsTestMode = null;
	protected $oLastUpdateUtcDate = null;
	protected $sOrderChangeType = null;
	protected $sSourceType = null;
	protected $sSourceUserId = null;
	protected $sSourceUserName = null;
	protected $sWarningMessage = null;


	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMOrderChangeReporting();
		foreach ($aData as $sKey=>$mValue){
			$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
			$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
			if (method_exists($oResult,$sSetterMethod) && is_scalar($mValue)){
				call_user_func(array($oResult,$sSetterMethod), stristr($sSetterMethod,'UtcDate') ? new DateTime($mValue, new DateTimeZone('UTC')) : $mValue);
			} // if
		} // foreach
		return $oResult;
	}
	

	
	/**
	 * @return the $oCreationUtcDate
	 */
	public function getCreationUtcDate()
	{
		return $this->oCreationUtcDate;
	}

	/**
	 * @param NULL $oCreationUtcDate
	 */
	public function setCreationUtcDate(DateTime $oCreationUtcDate)
	{
		$this->oCreationUtcDate = $oCreationUtcDate;
		return $this;
		
	}

	/**
	 * @return the $sErrorMessage
	 */
	public function getErrorMessage()
	{
		return $this->sErrorMessage;
	}

	/**
	 * @param NULL $sErrorMessage
	 */
	public function setErrorMessage($sErrorMessage)
	{
		$this->sErrorMessage = $sErrorMessage;
		return $this;
		
	}

	/**
	 * @return the $sExecutionUUID
	 */
	public function getSExecutionUUID()
	{
		return $this->sExecutionUUID;
	}

	/**
	 * @param NULL $sExecutionUUID
	 */
	public function setExecutionUUID($sExecutionUUID)
	{
		$this->sExecutionUUID = $sExecutionUUID;
		return $this;
		
	}

	/**
	 * @return the $sIPAddress
	 */
	public function getIPAddress()
	{
		return $this->sIPAddress;
		
	}

	/**
	 * @param NULL $sIPAddress
	 */
	public function setIPAddress($sIPAddress)
	{
		$this->sIPAddress = $sIPAddress;
		return $this;
		
	}

	/**
	 * @return the $bIsTestMode
	 */
	public function getIsTestMode()
	{
		return $this->bIsTestMode;
		
	}
	
	public function isTestMode()
	{
		return $this->bIsTestMode;
	
	}
	/**
	 * @param NULL $bIsTestMode
	 */
	public function setIsTestMode($bIsTestMode)
	{
		$this->bIsTestMode = (boolean)$bIsTestMode;
		return $this;
		
	}

	/**
	 * @return the $oLastUpdateUtcDate
	 */
	public function getLastUpdateUtcDate()
	{
		return $this->oLastUpdateUtcDate;
	}

	/**
	 * @param NULL $oLastUpdateUtcDate
	 */
	public function setLastUpdateUtcDate($oLastUpdateUtcDate)
	{
		$this->oLastUpdateUtcDate = $oLastUpdateUtcDate;
		return $this;
	}

	/**
	 * @return the $sOrderChangeType
	 */
	public function getOrderChangeType()
	{
		return $this->sOrderChangeType;
	}

	/**
	 * @param NULL $sOrderChangeType
	 */
	public function setOrderChangeType($sOrderChangeType)
	{
		$this->sOrderChangeType = $sOrderChangeType;
		return $this;
	}

	/**
	 * @return the $sSourceType
	 */
	public function getSourceType()
	{
		return $this->sSourceType;
	}

	/**
	 * @param NULL $sSourceType
	 */
	public function setSourceType($sSourceType)
	{
		$this->sSourceType = $sSourceType;
		return $this;
	}

	/**
	 * @return the $sSourceUserId
	 */
	public function getSourceUserId()
	{
		return $this->sSourceUserId;
	}

	/**
	 * @param NULL $sSourceUserId
	 */
	public function setSourceUserId($sSourceUserId)
	{
		$this->sSourceUserId = $sSourceUserId;
		return $this;
	}

	/**
	 * @return the $sSourceUserName
	 */
	public function getSourceUserName()
	{
		return $this->sSourceUserName;
	}

	/**
	 * @param NULL $sSourceUserName
	 */
	public function setSourceUserName($sSourceUserName)
	{
		$this->sSourceUserName = $sSourceUserName;
		return $this;
	}

	/**
	 * @return the $sWarningMessage
	 */
	public function getWarningMessage()
	{
		return $this->sWarningMessage;
	}

	/**
	 * @param NULL $sWarningMessage
	 */
	public function setWarningMessage($sWarningMessage)
	{
		$this->sWarningMessage = $sWarningMessage;
		return $this;
	}

}