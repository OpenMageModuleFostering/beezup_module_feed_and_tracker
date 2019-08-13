<?php 

	class BeezupOMOrderHarvestReporting {
		
		protected $sBeezUPForcedStatus = null;
		protected $sBeezUPStatus = null;
		protected $oCreationUtcDate = null;
		protected $sErrorMessage = null;
		protected $sExecutionUUID = null;
		protected $sLastupdateUtcDate = null;
		protected $sProcessingStatus = null;
		protected $sWarningMessage = null;

		
		public static function fromArray(array $aData = array()){
			$oResult = new BeezupOMOrderHarvestReporting();
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
	 * @return the $sBeezUPForcedStatus
	 */
	public function getBeezUPForcedStatus()
	{
		return $this->sBeezUPForcedStatus;
	}

		/**
	 * @param NULL $sBeezUPForcedStatus
	 */
	public function setBeezUPForcedStatus($sBeezUPForcedStatus)
	{
		$this->sBeezUPForcedStatus = $sBeezUPForcedStatus;
		return $this;
	}

		/**
	 * @return the $sBeezUPStatus
	 */
	public function getBeezUPStatus()
	{
		return $this->sBeezUPStatus;
	}

		/**
	 * @param NULL $sBeezUPStatus
	 */
	public function setBeezUPStatus($sBeezUPStatus)
	{
		$this->sBeezUPStatus = $sBeezUPStatus;
		return $this;
		
	}

		/**
	 * @return the $oCreationUtcDate
	 */
	public function getCreationUtcDate()
	{
		return $this->oCreationUtcDate;
		return $this;
		
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
	 * @return the $sLastupdateUtcDate
	 */
	public function getLastupdateUtcDate()
	{
		return $this->sLastupdateUtcDate;
	}

		/**
	 * @param NULL $sLastupdateUtcDate
	 */
	public function setLastupdateUtcDate($sLastupdateUtcDate)
	{
		$this->sLastupdateUtcDate = $sLastupdateUtcDate;
		return $this;
		
	}

		/**
	 * @return the $sProcessingStatus
	 */
	public function getProcessingStatus()
	{
		return $this->sProcessingStatus;
	}

		/**
	 * @param NULL $sProcessingStatus
	 */
	public function setProcessingStatus($sProcessingStatus)
	{
		$this->sProcessingStatus = $sProcessingStatus;
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