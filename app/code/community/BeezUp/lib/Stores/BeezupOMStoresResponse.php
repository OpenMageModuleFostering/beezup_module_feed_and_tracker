<?php
	
	class BeezupOMStoresResponse extends BeezupOMResponse {
		
		public function createResult(array $aData = array()){
			return BeezupOMStoresResult::fromArray($aData);
		}
		public function createRequest(array $aData = array()){
			return BeezupOMStoresRequest::fromArray($aData);
		}
		
	}