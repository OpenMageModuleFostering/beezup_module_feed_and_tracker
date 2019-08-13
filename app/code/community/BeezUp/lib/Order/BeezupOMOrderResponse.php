<?php

/**
 * @todo Add management of etag, httpstatus, haschanged
 */
class BeezupOMOrderResponse extends BeezupOMResponse {

	protected $sJson;
	protected $sEtag;
	protected $nHttpStatus;
	protected $bHasChanged;
	protected $sExecutionId;

	public function getExecutionId(){
		return $this->sExecutionId;
	}

	public function setExecutionId($sExecutionId){
		$this->sExecutionId = $sExecutionId;
		return $this;
	}

	public function getJson(){
		return $this->sJson;
	}
	public function setJson($sJson){
		$this->sJson = $sJson;
		return $this;
	}

	public function getEtag(){
		return $this->sEtag;
	}
	public function setEtag($sEtag){
		$this->sEtag = $sEtag;
		return $this;
	}

	public function getHasChanged(){
		return $this->bHasChanged;
	}

	public function setHasChanged($bHasChanged){
		$this->bHasChanged = (boolean)$bHasChanged;
		return $this;
	}
	public function hasChanged($bHasChanged){
			return $this->bHasChanged;
	}

	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMOrderResponse();
		if (is_array($aData) && isset($aData['paginationResult']) && isset($aData['orderHeaders'])){
			$oResult->setPaginationResult(BeezupOMPaginationResult::fromArray($aData['paginationResult']));
			foreach ($aData['orderHeaders'] as $aOrderHeader){
				$oResult->addOrderHeader(BeezupOMOrderHeader::fromArray($aOrderHeader));
			}
		}
		return $oResult;
	}

	public function parseRawResponse($aParsedResponse){

		if (is_array($aParsedResponse)){
			$this
				->setJson(json_encode($aParsedResponse));
		}
	}

	public function createResult(array $aData = array()){
		return BeezupOMOrderResult::fromArray($aData);
	}
	public function createRequest(array $aData = array()){
		return BeezupOMOrderRequest::fromArray($aData);
	}


}