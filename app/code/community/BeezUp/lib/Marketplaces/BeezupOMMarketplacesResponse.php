<?php

	class BeezupOMMarketplacesResponse extends BeezupOMResponse {

		public function createResult(array $aData = array()){
			return BeezupOMMarketplacesResult::fromArray($aData);
		}
		public function createRequest(array $aData = array()){
			return BeezupOMMarketplacesRequest::fromArray($aData);
		}

	}