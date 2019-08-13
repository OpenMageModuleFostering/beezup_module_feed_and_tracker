<?php

	class BeezupOMSetOrderIdResponse extends BeezupOMResponse {
		public function createResult(array $aData = array()){
			return BeezupOMSetOrderIdResult::fromArray($aData);
		}
		public function createRequest(array $aData = array()){
			return BeezupOMSetOrderIdRequest::fromArray($aData);
		}

	}