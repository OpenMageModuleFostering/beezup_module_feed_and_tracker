<?php

class BeezupOMOrderHistoryResult extends BeezupOMResult {

	protected $oLastModificationUtcData = null;
	protected $aOrderChangeReporting = array();
	protected $aOrderHarvestReporting = array();
	
	
	
	/**
	 * 
	 * 
	 * @param array $aData
	 * @return BeezupOMOrderListResult
	 */
	
	/**
	 * @return the $oLastModificationUtcData
	 */
	public function getLastModificationUtcData()
	{
		return $this->oLastModificationUtcData;
	}

	/**
	 * @param NULL $oLastModificationUtcData
	 */
	public function setLastModificationUtcData(DateTime $oLastModificationUtcData)
	{
		$this->oLastModificationUtcData = $oLastModificationUtcData;
		return $this;
	}

	/**
	 * @return the $aOrderChangeReporting
	 */
	public function getOrderChangeReporting()
	{
		return $this->aOrderChangeReporting;
	}

	/**
	 * @param multitype: $aOrderChangeReporting
	 */
	public function setOrderChangeReporting($aOrderChangeReporting)
	{
		$this->aOrderChangeReporting = $aOrderChangeReporting;
		return $this;
	}
	
	/**
	 * @param multitype: $aOrderChangeReporting
	 */
	public function addOrderChangeReporting($oOrderChangeReporting)
	{
		$this->aOrderChangeReporting[] = $oOrderChangeReporting;
		return $this;
	}
	
	/**
	 * @return the $aOrderHarvestReporting
	 */
	public function getOrderHarvestReporting()
	{
		return $this->aOrderHarvestReporting;
	}

	/**
	 * @param multitype: $aOrderHarvestReporting
	 */
	public function setOrderHarvestReporting($aOrderHarvestReporting)
	{
		$this->aOrderHarvestReporting = $aOrderHarvestReporting;
		return $this;
	}
	
	/**
	 * @param multitype: $aOrderHarvestReporting
	 */
	public function addOrderHarvestReporting($oOrderHarvestReporting)
	{
		$this->aOrderHarvestReporting[] = $oOrderHarvestReporting;
		return $this;
	}

	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMOrderHistoryResult();
		if (is_array($aData) && isset($aData['OrderChangeReportings'])){
			foreach ($aData['OrderChangeReportings'] as $aOrderChangeReporting){
				$oResult->addOrderChangeReporting(BeezupOMOrderChangeReporting::fromArray($aOrderChangeReporting));
			}
		}
		if (is_array($aData) && isset($aData['OrderHarvestReportings'])){
			foreach ($aData['OrderHarvestReportings'] as $aOrderHarvestReporting){
				$oResult->addOrderHarvestReporting(BeezupOMOrderharvestReporting::fromArray($aOrderHarvestReporting));
			}
		}
		$oResult->setLastModificationUtcData(new DateTime($aData['lastModificationUtcDate'], new DateTimeZone('UTC')));
		return $oResult;
	}

}  