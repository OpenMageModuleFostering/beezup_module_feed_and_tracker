<?php

	class BeezupOMResponse {
		
		protected $oRequest;
		protected $oResult;
		protected $oInfo;
		protected $bNotModified = false;
		protected $nHttpStatus = null;
		protected $aReturnHeaders = array();
		
		public function setReturnHeaders(array $aReturnHeaders = array()){
			$this->aReturnHeaders = $aReturnHeaders;
			return $this;
			
		}
		public function getReturnHeaders(){
			return $this->aReturnHeaders;
				
		}
		
		public function getReturnHeader($sName){
			return isset($this->aReturnHeaders[$sName]) ? $this->aReturnHeaders[$sName] : null;
		}
		
		public function isNotModified()
		{
			return $this->getNotModified();
		}			
		
		public function isModified()
		{
			return !$this->isNotModified();
		}
		
		/**
		 * @return the $bNotModified
		 */
		public function getNotModified()
		{
			return $this->bNotModified;
		}
	
			/**
		 * @param boolean $bNotModified
		 */
		public function setNotModified($bNotModified)
		{
			$this->bNotModified = (boolean)$bNotModified;
			return $this;
		}

		public function getRequest(){
			return $this->oRequest;
		}
		public function setRequest(BeezupOMRequest $oRequest){
			$this->oRequest = $oRequest;
			return $this;
		}
		
		/**
		 * 
		 * @return BeezupOMResult
		 */
		public function getResult(){
			return $this->oResult;
		}
		public function setResult(BeezupOMResult $oResult){
			$this->oResult = $oResult;
			return $this;	
		}
		
		/**
		 * @return BeezupOMInfoSummaries
		 */
		public function getInfo(){
			return $this->oInfo;
		}
		public function setInfo(BeezupOMInfoSummaries $oInfo){
			$this->oInfo = $oInfo;
			return $this;
		}		
		public function getHttpStatus(){
			return $this->nHttpStatus;
		}
		public function setHttpStatus($nHttpStatus){
			$this->nHttpStatus = (int)$nHttpStatus;
			return $this;
		}
		
		public function parseRawResponse($aParsedResponse){
			
		}
		
		public static function fromArray(array $aData = array()){
			return new BeezupOMResponse();
		}
	
		public function createResult(array $aData = array()){
			return BeezupOMResult::fromArray($aData);
		}
		public function createRequest(array $aData = array()){
			return BeezupOMRequest::fromArray($aData);
		}
	}