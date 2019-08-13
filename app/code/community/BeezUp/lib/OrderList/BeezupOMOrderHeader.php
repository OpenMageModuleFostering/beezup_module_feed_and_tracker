<?php
class BeezupOMOrderHeader {
	
	private $nAccountId = null;
	private $sBeezupOrderState = null;
	private $sBeezupOrderUUID = null;
	private $sBuyerFullName = null;
	private $sETag = null;
	private $sIsoCurrencyCode = null;
	private $bIsPendingSynchronization = null;
	private $oLastModificationUtcDate = null;
	private $aLinks = array();
	private $oMarketPlaceLastModificationUtcDate = null;
	private $sMarketPlaceOrderId = null;
	private $sMarketPlaceOrderState = null;
	private $sMarketPlaceTechnicalCode = null;
	private $sMerchantOrderId = null;
	private $oPurchaseUtcDate = null;
	private $fTotalAmount = null;
	
	/**
	 * @return the $nAccountId
	 */
	public function getAccountId()
	{
		return $this->nAccountId;
	}

	/**
	 * @param NULL $nAccountId
	 */
	public function setAccountId($nAccountId)
	{
		$this->nAccountId = $nAccountId;
		return $this;
		
	}

	/**
	 * @return the $sBeezupOrderState
	 */
	public function getBeezupOrderState()
	{
		return $this->sBeezupOrderState;
	}

	/**
	 * @param NULL $sBeezupOrderState
	 */
	public function setBeezupOrderState($sBeezupOrderState)
	{
		$this->sBeezupOrderState = $sBeezupOrderState;
		return $this;
		
	}

	/**
	 * @return the $sbeezupOrderUUID
	 */
	public function getBeezupOrderUUID()
	{
		return $this->sBeezupOrderUUID;
	}

	/**
	 * @param NULL $sbeezupOrderUUID
	 */
	public function setBeezupOrderUUID($sBeezupOrderUUID)
	{
		$this->sBeezupOrderUUID = $sBeezupOrderUUID;
		return $this;
		
	}

	/**
	 * @return the $sBuyerFullName
	 */
	public function getBuyerFullName()
	{
		return $this->sBuyerFullName;
	}

	/**
	 * @param NULL $sBuyerFullName
	 */
	public function setBuyerFullName($sBuyerFullName)
	{
		$this->sBuyerFullName = $sBuyerFullName;
		return $this;
		
	}

	/**
	 * @return the $sETag
	 */
	public function getETag()
	{
		return $this->sETag;
	}

	/**
	 * @param NULL $sETag
	 */
	public function setETag($sETag)
	{
		$this->sETag = $sETag;
		return $this;
		
	}

	/**
	 * @return the $sIsoCurrencyCode
	 */
	public function getIsoCurrencyCode()
	{
		return $this->sIsoCurrencyCode;
	}

	/**
	 * @param NULL $sIsoCurrencyCode
	 */
	public function setIsoCurrencyCode($sIsoCurrencyCode)
	{
		$this->sIsoCurrencyCode = $sIsoCurrencyCode;
		return $this;
		
	}

	public function isPendingSynchronization()
	{
		return $this->bIsPendingSynchronization;
	}
	
	
	/**
	 * @return the $bIsPendingSynchronization
	 */
	public function getIsPendingSynchronization()
	{
		return $this->bIsPendingSynchronization;
	}

	/**
	 * @param NULL $bIsPendingSynchronization
	 */
	public function setIsPendingSynchronization($bIsPendingSynchronization)
	{
		$this->bIsPendingSynchronization = $bIsPendingSynchronization;
		return $this;
		
	}

	/**
	 * @return the $oLastModificationUtcDate
	 */
	public function getLastModificationUtcDate()
	{
		return $this->oLastModificationUtcDate;
	}

	/**
	 * @param NULL $oLastModificationUtcDate
	 */
	public function setLastModificationUtcDate($oLastModificationUtcDate)
	{
		$this->oLastModificationUtcDate = $oLastModificationUtcDate;
		return $this;
		
	}

	/**
	 * @return the $aLinks
	 */
	public function getLinks()
	{
		return $this->aLinks;
	}
	public function getLinkByRel($sRel){
		foreach ($this->getLinks() as $oLink){
			if ($oLink->getRel() === $sRel){
				return $oLink;
			}
		}
		return null;
	}
	/**
	 * @param multitype: $aLinks
	 */
	public function setLinks($aLinks)
	{
		$this->aLinks = $aLinks;
		return $this;
		
	}
	
	public function addLink(BeezupOMLink $oLink)
	{
		$this->aLinks[] = $oLink;
		return $this;
		
	}

	/**
	 * @return the $oMarketPlaceLastModificationUtcDate
	 */
	public function getMarketPlaceLastModificationUtcDate()
	{
		return $this->oMarketPlaceLastModificationUtcDate;
	}

	/**
	 * @param NULL $oMarketPlaceLastModificationUtcDate
	 */
	public function setMarketPlaceLastModificationUtcDate($oMarketPlaceLastModificationUtcDate)
	{
		$this->oMarketPlaceLastModificationUtcDate = $oMarketPlaceLastModificationUtcDate;
		return $this;
		
	}

	/**
	 * @return the $sMarketPlaceOrderId
	 */
	public function getMarketPlaceOrderId()
	{
		return $this->sMarketPlaceOrderId;
	}

	/**
	 * @param NULL $sMarketPlaceOrderId
	 */
	public function setMarketPlaceOrderId($sMarketPlaceOrderId)
	{
		$this->sMarketPlaceOrderId = $sMarketPlaceOrderId;
		return $this;
		
	}

	/**
	 * @return the $sMarketPlaceOrderState
	 */
	public function getMarketPlaceOrderState()
	{
		return $this->sMarketPlaceOrderState;
	}

	/**
	 * @param NULL $sMarketPlaceOrderState
	 */
	public function setMarketPlaceOrderState($sMarketPlaceOrderState)
	{
		$this->sMarketPlaceOrderState = $sMarketPlaceOrderState;
		return $this;
		
	}

	/**
	 * @return the $sMarketPlaceTechnicalCode
	 */
	public function getMarketPlaceTechnicalCode()
	{
		return $this->sMarketPlaceTechnicalCode;
	}

	/**
	 * @param NULL $sMarketPlaceTechnicalCode
	 */
	public function setMarketPlaceTechnicalCode($sMarketPlaceTechnicalCode)
	{
		$this->sMarketPlaceTechnicalCode = $sMarketPlaceTechnicalCode;
		return $this;
		
	}

	/**
	 * @return the $sMerchantOrderId
	 */
	public function getMerchantOrderId()
	{
		return $this->sMerchantOrderId;
	}

	/**
	 * @param NULL $sMerchantOrderId
	 */
	public function setMerchantOrderId($sMerchantOrderId)
	{
		$this->sMerchantOrderId = $sMerchantOrderId;
		return $this;
		
	}

	/**
	 * @return the $oPurchaseUtcDate
	 */
	public function getPurchaseUtcDate()
	{
		return $this->oPurchaseUtcDate;
	}

	/**
	 * @param NULL $oPurchaseUtcDate
	 */
	public function setPurchaseUtcDate($oPurchaseUtcDate)
	{
		$this->oPurchaseUtcDate = $oPurchaseUtcDate;
		return $this;
		
	}

	/**
	 * @return the $fTotalAmount
	 */
	public function getTotalAmount()
	{
		return $this->fTotalAmount;
	}

	/**
	 * @param NULL $fTotalAmount
	 */
	public function setTotalAmount($fTotalAmount)
	{
		$this->fTotalAmount = $fTotalAmount;
		return $this;
	}

	public static function fromArray(array $aData = array()){
		
		// @todo orderMetaInfos ? 
		$oOrderHeader = new BeezupOMOrderHeader();
		$oOrderHeader
			->setMarketPlaceOrderId($aData['marketPlaceOrderId'])
			->setBeezupOrderState($aData['beezUPOrderState'])
			->setBeezupOrderState($aData['beezUPOrderState'])
			->setPurchaseUtcDate(new DateTime($aData['purchaseUtcDate'], new DateTimeZone('UTC')))
			->setBuyerFullName($aData['buyerFullName'])
			->setLastModificationUtcDate(new DateTime($aData['lastModificationUtcDate'], new DateTimeZone('UTC')))
			->setTotalAmount($aData['totalAmount'])
			->setIsoCurrencyCode($aData['isoCurrencyCode'])
			->setMerchantOrderId($aData['merchantOrderId'])
			->setIsPendingSynchronization($aData['isPendingSynchronization'])
			->setETag($aData['eTag'])
			->setMarketPlaceLastModificationUtcDate($aData['marketPlaceLastModificationUtcDate']);
		foreach ($aData['links'] as $aLink){
			$oOrderHeader->addLink(BeezupOMLink::fromArray($aLink));
		}
		return $oOrderHeader;
	}
		
}