<?php

	class BeezupOMHarvestOrderReporting extends BeezupOMHarvestAbstractReporting {

		protected $sExecutionId 				= null;
		protected $oCreationUtcDate 			= null;
		protected $oLastUpdateUtcDate 			= null;
		protected $sErrorMessage	 			= null;
		protected $sProcessingStatus 			= null;
		protected $sBeezUPApiToken				= null;
		protected $sBeezUPUserId				= null;

		protected $sAccountId					= null;
		protected $sBeezupOrderUUID				= null;
		protected $sEtag						= null;
		protected $sMarketPlaceTechnicalCode	= null;
		protected $sHttpStatus					= null;
		protected $sParentExecutionUUID			= null;
		protected $sOrderDetailJson				= null;
		protected $oLastModificationUtcDate		= null;


	/**
	  * @return $nAccountId
	  */
	public function getAccountId()
	{
		return $this->sAccountId;
	}

	/**
	  * @param NULL $nAccountId
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setAccountId($sAccountId)
	{
		$this->sAccountId = $sAccountId;
		return $this;
	}

	/**
	  * @return $sBeezupOrderUUID
	  */
	public function getBeezupOrderUUID()
	{
		return $this->sBeezupOrderUUID;
	}

	/**
	  * @param NULL $sBeezupOrderUUID
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setBeezupOrderUUID($sBeezupOrderUUID)
	{
		$this->sBeezupOrderUUID = $sBeezupOrderUUID;
		return $this;
	}

	/**
	  * @return $sEtag
	  */
	public function getEtag()
	{
		return $this->sEtag;
	}

	/**
	  * @param NULL $sEtag
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setEtag($sEtag)
	{
		$this->sEtag = $sEtag;
		return $this;
	}

	/**
	  * @return $sMarketPlaceTechnicalCode
	  */
	public function getMarketPlaceTechnicalCode()
	{
		return $this->sMarketPlaceTechnicalCode;
	}

	/**
	  * @param NULL $sMarketPlaceTechnicalCode
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setMarketPlaceTechnicalCode($sMarketPlaceTechnicalCode)
	{
		$this->sMarketPlaceTechnicalCode = $sMarketPlaceTechnicalCode;
		return $this;
	}

	/**
	  * @return $sHttpStatus
	  */
	public function getHttpStatus()
	{
		return $this->sHttpStatus;
	}

	/**
	  * @param NULL $sHttpStatus
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setHttpStatus($sHttpStatus)
	{
		$this->sHttpStatus = $sHttpStatus;
		return $this;
	}

	/**
	  * @return $sParentExecutionUUID
	  */
	public function getParentExecutionUUID()
	{
		return $this->sParentExecutionUUID;
	}

	/**
	  * @param NULL $sParentExecutionUUID
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setParentExecutionUUID($sParentExecutionUUID)
	{
		$this->sParentExecutionUUID = $sParentExecutionUUID;
		return $this;
	}

	/**
	  * @return $sOrderDetailJson
	  */
	public function getOrderDetailJson()
	{
		return $this->sOrderDetailJson;
	}

	/**
	  * @param NULL $sOrderDetailJson
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setOrderDetailJson($sOrderDetailJson)
	{
		$this->sOrderDetailJson = $sOrderDetailJson;
		return $this;
	}

	/**
	  * @return $oLastModificationUtcDate
	  */
	public function getLastModificationUtcDate()
	{
		return $this->oLastModificationUtcDate;
	}

	/**
	  * @param NULL $oLastModificationUtcDate
	  * @return BeezupOMrderHarvestClientReporting
	  */
	public function setLastModificationUtcDate($oLastModificationUtcDate)
	{
		$this->oLastModificationUtcDate = $oLastModificationUtcDate;
		return $this;
	}

		/**
		 * @return the $sExecutionId
		 */
		public function getExecutionId()
		{
			return $this->sExecutionId;
		}

		/**
		 * @param NULL $sExecutionId
		 */
		public function setExecutionId($sExecutionId)
		{
			$this->sExecutionId = $sExecutionId;
			return $this;
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
		public function setCreationUtcDate($oCreationUtcDate)
		{
			$this->oCreationUtcDate = $oCreationUtcDate;
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
		 * @return the $oErrorMessage
		 */
		public function getErrorMessage()
		{
			return $this->sErrorMessage;
		}

		/**
		 * @param NULL $oErrorMessage
		 */
		public function setErrorMessage($sErrorMessage)
		{
			$this->sErrorMessage = substr($this->sErrorMessage === null ? $sErrorMessage : $this->sErrorMessage . PHP_EOL . $sErrorMessage, 0, 4090);
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
		 * @param NULL $oProcessingStatus
		 */
		public function setProcessingStatus($sProcessingStatus)
		{
			$this->sProcessingStatus = $sProcessingStatus;
			return $this;
		}

		/**
		 * @return the $sBeezUPApiToken
		 */
		public function getBeezUPApiToken()
		{
			return $this->sBeezUPApiToken;
		}

		/**
		 * @param NULL $sBeezUPApiToken
		 */
		public function setBeezUPApiToken($sBeezUPApiToken)
		{
			$this->sBeezUPApiToken = $sBeezUPApiToken;
			return $this;

		}

		/**
		 * @return the $sBeezUPUserId
		 */
		public function getBeezUPUserId()
		{
			return $this->sBeezUPUserId;
		}

		/**
		 * @param NULL $sBeezUPUserId
		 */
		public function setBeezUPUserId($sBeezUPUserId)
		{
			$this->sBeezUPUserId = $sBeezUPUserId;
			return $this;

		}


	}