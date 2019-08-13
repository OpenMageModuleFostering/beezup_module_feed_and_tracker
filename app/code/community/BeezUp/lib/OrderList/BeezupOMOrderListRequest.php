<?php

	class BeezupOMOrderListRequest extends BeezupOMRequest {

		private $aAccountIds = array();
		private $aBeezupOrderStates = array();
		private $oBeginPeriodUtcDate = null;
		private $oEndPeriodUtcDate = null;
		private $sDateSearchType = 'Modification';
		private $nEntriesPerPage = 100;
		private $aMarketPlaceOrderIds = array();
		private $aMarketPlaceTechnicalCodes = array();
		private $nPageNumber = 1;
		private $sBuyerFullName = null;

		public function __construct(){
			$this->setBeginPeriodUtcDate(new DateTime('now', new DateTimeZone('UTC')));
			$this->setEndPeriodUtcDate(new DateTime('now', new DateTimeZone('UTC')));
		}
		public static function fromArray(array $aData = array()){
			$oRequest = new BeezupOMOrderListRequest();
			foreach ($aData as $sKey=>$mValue){
				$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
				$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
				if (!method_exists($oRequest,$sSetterMethod)){
					continue;
				}
				$cCallback = array($oRequest,$sSetterMethod);
				if ($sKey == 'accountIds' || $sKey=='marketPlaceOrderIds' || $sKey =='beezUPOrderStates' || $sKey=='marketPlaceTechnicalCodes'){
					call_user_func($cCallback, is_array($mValue) ? $mValue : explode(',', (string)$mValue));
				} else if (is_scalar($mValue)){
					call_user_func($cCallback, stristr($sSetterMethod,'UtcDate') ? new DateTime($mValue, new DateTimeZone('UTC')) : $mValue);
				} // if
			} // foreach
			return $oRequest;
		}

		public function toArray(){
			return array(
				'beezUPOrderStates' => implode(',', $this->getBeezupOrderStates()),
				'beginPeriodUtcDate' => $this->getBeginPeriodUtcDate()->format($this->sDateFormat),
				'endPeriodUtcDate' => $this->getEndPeriodUtcDate()->format($this->sDateFormat),
				'entriesPerPage' => $this->getEntriesPerPage(),
				'pageNumber' => $this->getPageNumber(),
				'dateSearchType' => $this->getDateSearchType(),
				'marketPlaceTechnicalCodes' => implode(',', $this->getMarketPlaceTechnicalCodes()),
				'buyerFullName' => $this->getBuyerFullName(),
				'marketPlaceOrderIds' => implode(',', $this->getMarketPlaceOrderIds()),
				'accountIds' => implode(',', $this->getAccountIds()),
			);
		}

		public function setAccountIds(array $aAccountIds = array()){
			$this->aAccountIds = $aAccountIds;
			return $this;
		}
		public function getAccountIds(){
			return $this->aAccountIds;
		}
		public function setBeezupOrderStates(array $aBeezupOrderStates = array()){
			$this->aBeezupOrderStates = $aBeezupOrderStates;
			return $this;
		}
		public function getBeezupOrderStates(){
			return $this->aBeezupOrderStates;
		}

		public function setBeginPeriodUtcDate(DateTime $oBeginPeriodUtcDate){
			$this->oBeginPeriodUtcDate = $oBeginPeriodUtcDate;
			return $this;
		}
		
		/**
		 * 
		 * @return Datetime|null
		 */
		public function getBeginPeriodUtcDate(){
			// -2H en dur
			return $this->oBeginPeriodUtcDate;
		}

		public function setEndPeriodUtcDate(DateTime $oEndPeriodUtcDate){
			$this->oEndPeriodUtcDate = $oEndPeriodUtcDate;
			return $this;
		}
		public function getEndPeriodUtcDate(){
			return $this->oEndPeriodUtcDate;
		}

		public function setEntriesPerPage($nEntriesPerPage){
			$this->nEntriesPerPage = min(100, max(25, (int)$nEntriesPerPage));
			return $this;
		}
		public function getEntriesPerPage(){
			return $this->nEntriesPerPage;
		}

		public function setPageNumber($nPageNumber){
			$this->nPageNumber = (int)$nPageNumber;
			return $this;
		}
		public function getPageNumber(){
			return $this->nPageNumber;
		}

		public function setDateSearchType($sDateSearchType){
			$this->sDateSearchType = $sDateSearchType;
			return $this;
		}
		public function getDateSearchType(){
			return $this->sDateSearchType;
		}

		public function setMarketPlaceTechnicalCodes(array $aMarketPlaceTechnicalCodes = array()){
			$this->aMarketPlaceTechnicalCodes = $aMarketPlaceTechnicalCodes;
			return $this;
		}
		public function getMarketPlaceTechnicalCodes(){
			return $this->aMarketPlaceTechnicalCodes;
		}

		public function setBuyerFullName($sBuyerFullName){
			$this->sBuyerFullName = (string)$sBuyerFullName;
			return $this;
		}

		public function getBuyerFullName(){
			return $this->sBuyerFullName;
		}

		public function setMarketPlaceOrderIds(array $aMarketPlaceOrderIds = array()){
			$this->aMarketPlaceOrderIds = $aMarketPlaceOrderIds;
			return $this;
		}
		public function getMarketPlaceOrderIds(){
			return $this->aMarketPlaceOrderIds;
		}


}
