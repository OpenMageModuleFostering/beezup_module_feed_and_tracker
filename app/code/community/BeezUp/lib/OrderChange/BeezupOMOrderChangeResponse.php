<?php

class BeezupOMOrderChangeResponse extends BeezupOMResponse {
		
		protected $sEtag = null;
		protected $nHttpStatus = null;
		protected $oInfo = null;
		protected $sJson = null;
		protected $oRequest = null;
		protected $oResult = null;
	
		
		/**
	 * @return the $sEtag
	 */
	public function getEtag()
	{
		return $this->sEtag;
	}

		/**
	 * @param NULL $sEtag
	 */
	public function setEtag($sEtag)
	{
		$this->sEtag = $sEtag;
		return $this;
		
	}

		/**
	 * @return the $nHttpStatus
	 */
	public function getHttpStatus()
	{
		return $this->nHttpStatus;
	}

		/**
	 * @param NULL $nHttpStatus
	 */
	public function setHttpStatus($nHttpStatus)
	{
		$this->nHttpStatus = (int)$nHttpStatus;
		return $this;
	}

		/**
	 * @return the $oInfo
	 */
	public function getInfo()
	{
		return $this->oInfo;
	}

		/**
	 * @param NULL $oInfo
	 */
	public function setInfo(BeezupOMInfoSummaries $oInfo)
	{
		$this->oInfo = $oInfo;
		return $this;
	}

		/**
	 * @return the $sJson
	 */
	public function getJson()
	{
		return $this->sJson;
	}

		/**
	 * @param NULL $sJson
	 */
	public function setJson($sJson)
	{
		$this->sJson = $sJson;
		return $this;
	}

	public function createResult(array $aData = array()){
		return BeezupOMOrderChangeResult::fromArray($aData);
	}
	public function createRequest(array $aData = array()){
		return BeezupOMOrderChangeRequest::fromArray($aData);
	}
}