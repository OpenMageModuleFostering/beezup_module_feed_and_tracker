<?php
	
	class BeezupOMLOVResponse extends BeezupOMResponse {
		
		public function createResult(array $aData = array()){
			return BeezupOMLOVResult::fromArray($aData);
		}
		public function createRequest(array $aData = array()){
			return BeezupOMLOVRequest::fromArray($aData);
		}
		
		
		
	}