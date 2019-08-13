<?php

class BeezupOMOrderListResult extends BeezupOMResult {

	/**
	 * Paginntion result
	 * @var BeezupOMPaginationResult
	 */
	public $oPaginationResult = null;
	
	/**
	 * Order headers collection
	 * @var array of BeezupOMOrderHeader
	 */
	protected $aOrderHeaders = array();

	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMOrderListResult();
		if (is_array($aData) && isset($aData['paginationResult']) && isset($aData['orderHeaders'])){
			$oResult->setPaginationResult(BeezupOMPaginationResult::fromArray($aData['paginationResult']));
			foreach ($aData['orderHeaders'] as $aOrderHeader){
				$oResult->addOrderHeader(BeezupOMOrderHeader::fromArray($aOrderHeader));
			}
		}
		return $oResult;
	}

	/**
	 * Gets orders headers
	 * @return array of BeezupOMOrderHeader
	 */
	public function getOrderHeaders(){
		return $this->aOrderHeaders;
	}

	/**
	 * Adds order header
	 * @param BeezupOMOrderHeader $oOrderHeader
	 * @return BeezupOMOrderListResult Self
	 */
	public function addOrderHeader(BeezupOMOrderHeader $oOrderHeader){
		$this->aOrderHeaders[] = $oOrderHeader;
		return $this;
	}
	
	/**
	 * 
	 * @return BeezupOMPaginationResult|null
	 */
	public function getPaginationResult(){
		return $this->oPaginationResult;
	}
	
	/**
	 * 
	 * @param BeezupOMPaginationResult $oPaginationResult
	 * @return BeezupOMOrderListResult Self
	 */
	public function setPaginationResult(BeezupOMPaginationResult $oPaginationResult){
		$this->oPaginationResult = $oPaginationResult;
		return $this;
	}
}  