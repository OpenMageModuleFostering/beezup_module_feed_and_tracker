<?php

	class BeezupOMHarvestClientReporting extends BeezupOMHarvestAbstractReporting {
		
		protected $sExecutionId 		= null;
		protected $oCreationUtcDate 	= null;
		protected $oLastUpdateUtcDate 	= null;
		protected $sErrorMessage	 	= null;
		protected $nTotalOrderCount	 	= null;
		protected $sProcessingStatus 	= null;
		protected $oBeginPeriodUtcDate 	= null;
		protected $oEndPeriodUtcDate 	= null;
		protected $nEntriesPerPage	 	= null;
		protected $sBeezUPApiToken		= null;
		protected $sBeezUPUserId		= null;
		/**
		 * @todo verify name in API
		 * @var unknown_type
		 */
		protected $nRemainingPageCount	= null;

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
		 * @return the $nTotalOrderCount
		 */
		public function getTotalOrderCount()
		{
			return $this->nTotalOrderCount;
		}
	
			/**
		 * @param NULL $nTotalOrderCount
		 */
		public function setTotalOrderCount($nTotalOrderCount)
		{
			$this->nTotalOrderCount = $nTotalOrderCount;
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
		 * @return the $oBeginPeriodUtcDate
		 */
		public function getBeginPeriodUtcDate()
		{
			return $this->oBeginPeriodUtcDate;
		}
	
			/**
		 * @param NULL $oBeginPeriodUtcDate
		 */
		public function setBeginPeriodUtcDate($oBeginPeriodUtcDate)
		{
			$this->oBeginPeriodUtcDate = $oBeginPeriodUtcDate;
			return $this;
				
		}
	
			/**
		 * @return the $oEndPeriodUtcDate
		 */
		public function getEndPeriodUtcDate()
		{
			return $this->oEndPeriodUtcDate;
		}
	
			/**
		 * @param NULL $oEndPeriodUtcDate
		 */
		public function setEndPeriodUtcDate($oEndPeriodUtcDate)
		{
			$this->oEndPeriodUtcDate = $oEndPeriodUtcDate;
			return $this;
				
		}
	
			/**
		 * @return the $nEntriesPerPage
		 */
		public function getEntriesPerPage()
		{
			return $this->nEntriesPerPage;
		}
	
			/**
		 * @param NULL $nEntriesPerPage
		 */
		public function setEntriesPerPage($nEntriesPerPage)
		{
			$this->nEntriesPerPage = $nEntriesPerPage;
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
	
			/**
		 * @return the $nRemainingPageCount
		 */
		public function getRemainingPageCount()
		{
			return $this->nRemainingPageCount;
		}
	
			/**
		 * @param NULL $nRemainingPageCount
		 */
		public function setRemainingPageCount($nRemainingPageCount)
		{
			$this->nRemainingPageCount = max(0, (int)$nRemainingPageCount);
			return $this;
				
		}

	
	}