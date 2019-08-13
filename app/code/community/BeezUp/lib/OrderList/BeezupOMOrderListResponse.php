<?php

	class BeezupOMOrderListResponse extends BeezupOMResponse {

		protected $sExecutionId = null;
		
		public function getExecutionId(){
			return $this->sExecutionId;
		}
		public function setExecutionId($sExecutionId){
			$this->sExecutionId = $sExecutionId;
			return $this;
		}
		public function createResult(array $aData = array()){
			return BeezupOMOrderListResult::fromArray($aData);
		}
		public function createRequest(array $aData = array()){
			return BeezupOMOrderListRequest::fromArray($aData);
		}

		
		
	}