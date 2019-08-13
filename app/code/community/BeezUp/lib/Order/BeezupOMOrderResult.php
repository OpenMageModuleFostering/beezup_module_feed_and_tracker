<?php

class BeezupOMOrderResult extends BeezupOMResult {

	protected $sBeezupOrderUUID = null;
	protected $bIsPendingSynchronization = null;
	protected $aLinks = array();
	protected $aTransitionLinks = array();
	protected $sMarketPlaceTechnicalCode = null;
	protected $aOrderItems = array();
	protected $sOrderBuyerAddressCity = null;
	protected $sOrderBuyerAddressCountryIsoCodeAlpha2 = null;
	protected $sOrderBuyerAddressCountryName= null;
	protected $sOrderBuyerAddressLine1 = null;
	protected $sOrderBuyerAddressLine2 = null;
	protected $sOrderBuyerAddressLine3 = null;
	protected $sOrderBuyerAddressPostalCode = null;
	protected $sOrderBuyerStateOrRegion = null;
	protected $sOrderBuyerCivility = null;
	protected $sOrderBuyerCompanyName = null;
	protected $sOrderBuyerEmail = null;
	protected $sOrderBuyerIdentifier = null;
	protected $sOrderBuyerMobilePhone = null;
	protected $sOrderBuyerName = null;
	protected $sOrderBuyerPhone = null;
	protected $sOrderComment = null;
	protected $sOrderCurrencyCode = null;
	protected $oOrderLastModificationUtcDate = null;
	protected $oOrderMarketPlaceLastModificationUtcDate = null;
	protected $sOrderMarketPlaceOrderId = null;
	protected $sOrderMerchantOrderId = null;
	protected $oOrderPayingUtcDate = null;
	protected $sOrderPaymentMethod = null;
	protected $oOrderPurchaseUtcDate = null;
	protected $sOrderShippingAddressCity = null;
	protected $sOrderShippingAddressCountryIsoCodeAlpha2 = null;
	protected $sOrderShippingAddressCountryName= null;
	protected $sOrderShippingAddressLine1 = null;
	protected $sOrderShippingAddressLine2 = null;
	protected $sOrderShippingAddressLine3 = null;
	protected $sOrderShippingAddressName = null;
	protected $sOrderShippingAddressPostalCode = null;
	protected $sOrderShippingCivility = null;
	protected $sOrderShippingCompanyName = null;
	protected $oOrderShippingEarliestShipUtcDate = null;
	protected $sOrderShippingEmail = null;
	protected $oOrderShippingLatestShipUtcDate = null;
	protected $sOrderShippingMethod = null;
	protected $sOrderShippingPhone = null;
	protected $fOrderShippingPrice = null;
	protected $fOrderShippingTax = null;
	protected $sOrderStatusBeezUPOrderStatus = null;
	protected $sOrderStatusMarketPlaceOrderStatus = null;
	protected $fOrderTotalPrice = null;
	protected $fOrderTotalTax = null;
	protected $fOrderTotalCommission = null;
	protected $sOrderShippingAddressStateOrRegion = null;
	protected $sOrderShippingMobilePhone = null;
	protected $fOrderShippingShippingTax = null;
	protected $sOrderMerchantECommerceSoftwareName = null;
	protected $sOrderMerchantECommerceSoftwareVersion = null;
	protected $sOrderStatusMarketPlaceStatus = null;

	protected $sDateFormat = 'Y-m-d\TH:i:s\Z';//DateTime::ISO8601;

	/**
	 * @return the $sOrderMerchantECommerceSoftwareVersion
	 */
	public function getOrderMerchantECommerceSoftwareVersion()
	{
		return $this->sOrderMerchantECommerceSoftwareVersion;
	}

	/**
	 * @return the $sOrderStatusMarketPlaceStatus
	 */
	public function getOrderStatusMarketPlaceStatus()
	{
		return $this->sOrderStatusMarketPlaceStatus;
	}

	/**
	 * @param NULL $sOrderMerchantECommerceSoftwareVersion
	 */
	public function setOrderMerchantECommerceSoftwareVersion($sOrderMerchantECommerceSoftwareVersion)
	{
		$this->sOrderMerchantECommerceSoftwareVersion = $sOrderMerchantECommerceSoftwareVersion;
	}

	/**
	 * @param NULL $sOrderStatusMarketPlaceStatus
	 */
	public function setOrderStatusMarketPlaceStatus($sOrderStatusMarketPlaceStatus)
	{
		$this->sOrderStatusMarketPlaceStatus = $sOrderStatusMarketPlaceStatus;
	}

	/**
	 * @return the $sOrderMerchantECommerceSoftwareName
	 */
	public function getOrderMerchantECommerceSoftwareName()
	{
		return $this->sOrderMerchantECommerceSoftwareName;
	}

	/**
	 * @param NULL $sOrderMerchantECommerceSoftwareName
	 */
	public function setOrderMerchantECommerceSoftwareName($sOrderMerchantECommerceSoftwareName)
	{
		$this->sOrderMerchantECommerceSoftwareName = $sOrderMerchantECommerceSoftwareName;
	}

	/**
	 * @return the $sBeezupOrderUUID
	 */
	public function getBeezupOrderUUID()
	{
		return $this->sBeezupOrderUUID;
	}

	/**
	 * @param NULL $sBeezupOrderUUID
	 */
	public function setBeezupOrderUUID($sBeezupOrderUUID)
	{
		$this->sBeezupOrderUUID = $sBeezupOrderUUID;
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
	 * @return the $aLinks
	 */
	public function getLinks()
	{
		return $this->aLinks;
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
	 * @return the $aLinks
	 */
	public function getTransitionLinks()
	{
		return $this->aTransitionLinks;
	}

	/**
	 * @param multitype: $aLinks
	 */
	public function setTransitionLinks($aTransitionLinks)
	{
		$this->aTransitionLinks = $aTransitionLinks;
		return $this;
	}

	public function addTransitionLink(BeezupOMLink $oLink)
	{
		$this->aTransitionLinks[] = $oLink;
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
	 * @return the $aOrderItems
	 */
	public function getOrderItems()
	{
		return $this->aOrderItems;
	}

	/**
	 * @param NULL $aOrderItems
	 */
	public function setOrderItems($aOrderItems)
	{
		$this->aOrderItems = $aOrderItems;
		return $this;

	}

	public function addOrderItem(BeezupOMOrderItem $oOrderItem)
	{
		$this->aOrderItems[] = $oOrderItem;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressCity
	 */
	public function getOrderBuyerAddressCity()
	{
		return $this->sOrderBuyerAddressCity;
	}

	/**
	 * @param NULL $sOrderBuyerAddressCity
	 */
	public function setOrderBuyerAddressCity($sOrderBuyerAddressCity)
	{
		$this->sOrderBuyerAddressCity = $sOrderBuyerAddressCity;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressCountryIsoCodeAlpha2
	 */
	public function getOrderBuyerAddressCountryIsoCodeAlpha2()
	{
		return $this->sOrderBuyerAddressCountryIsoCodeAlpha2;
	}

	/**
	 * @param NULL $sOrderBuyerAddressCountryIsoCodeAlpha2
	 */
	public function setOrderBuyerAddressCountryIsoCodeAlpha2($sOrderBuyerAddressCountryIsoCodeAlpha2)
	{
		$this->sOrderBuyerAddressCountryIsoCodeAlpha2 = $sOrderBuyerAddressCountryIsoCodeAlpha2;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressCountryName
	 */
	public function getOrderBuyerAddressCountryName()
	{
		return $this->sOrderBuyerAddressCountryName;
	}

	/**
	 * @param NULL $sOrderBuyerAddressCountryName
	 */
	public function setOrderBuyerAddressCountryName($sOrderBuyerAddressCountryName)
	{
		$this->sOrderBuyerAddressCountryName = $sOrderBuyerAddressCountryName;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressLine1
	 */
	public function getOrderBuyerAddressLine1()
	{
		return $this->sOrderBuyerAddressLine1;
	}

	/**
	 * @param NULL $sOrderBuyerAddressLine1
	 */
	public function setOrderBuyerAddressLine1($sOrderBuyerAddressLine1)
	{
		$this->sOrderBuyerAddressLine1 = $sOrderBuyerAddressLine1;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressLine2
	 */
	public function getOrderBuyerAddressLine2()
	{
		return $this->sOrderBuyerAddressLine2;
	}

	/**
	 * @param NULL $sOrderBuyerAddressLine2
	 */
	public function setOrderBuyerAddressLine2($sOrderBuyerAddressLine2)
	{
		$this->sOrderBuyerAddressLine2 = $sOrderBuyerAddressLine2;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressLine3
	 */
	public function getOrderBuyerAddressLine3()
	{
		return $this->sOrderBuyerAddressLine3;
	}

	/**
	 * @param NULL $sOrderBuyerAddressLine3
	 */
	public function setOrderBuyerAddressLine3($sOrderBuyerAddressLine3)
	{
		$this->sOrderBuyerAddressLine3 = $sOrderBuyerAddressLine3;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerAddressPostalCode
	 */
	public function getOrderBuyerAddressPostalCode()
	{
		return $this->sOrderBuyerAddressPostalCode;
	}

	/**
	 * @param NULL $sOrderBuyerAddressPostalCode
	 */
	public function setOrderBuyerAddressPostalCode($sOrderBuyerAddressPostalCode)
	{
		$this->sOrderBuyerAddressPostalCode = $sOrderBuyerAddressPostalCode;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerStateOrRegion
	 */
	public function getOrderBuyerStateOrRegion()
	{
		return $this->sOrderBuyerStateOrRegion;
	}

	/**
	 * @param NULL $sOrderBuyerStateOrRegion
	 */
	public function setOrderBuyerStateOrRegion($sOrderBuyerStateOrRegion)
	{
		$this->sOrderBuyerStateOrRegion = $sOrderBuyerStateOrRegion;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerCivility
	 */
	public function getOrderBuyerCivility()
	{
		return $this->sOrderBuyerCivility;
	}

	/**
	 * @param NULL $sOrderBuyerCivility
	 */
	public function setOrderBuyerCivility($sOrderBuyerCivility)
	{
		$this->sOrderBuyerCivility = $sOrderBuyerCivility;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerCompanyName
	 */
	public function getOrderBuyerCompanyName()
	{
		return $this->sOrderBuyerCompanyName;
	}

	/**
	 * @param NULL $sOrderBuyerCompanyName
	 */
	public function setOrderBuyerCompanyName($sOrderBuyerCompanyName)
	{
		$this->sOrderBuyerCompanyName = $sOrderBuyerCompanyName;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerEmail
	 */
	public function getOrderBuyerEmail()
	{
		return $this->sOrderBuyerEmail;
	}

	/**
	 * @param NULL $sOrderBuyerEmail
	 */
	public function setOrderBuyerEmail($sOrderBuyerEmail)
	{
		$this->sOrderBuyerEmail = $sOrderBuyerEmail;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerIdentifier
	 */
	public function getOrderBuyerIdentifier()
	{
		return $this->sOrderBuyerIdentifier;
	}

	/**
	 * @param NULL $sOrderBuyerIdentifier
	 */
	public function setOrderBuyerIdentifier($sOrderBuyerIdentifier)
	{
		$this->sOrderBuyerIdentifier = $sOrderBuyerIdentifier;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerMobilePhone
	 */
	public function getOrderBuyerMobilePhone()
	{
		return $this->sOrderBuyerMobilePhone;
	}

	/**
	 * @param NULL $sOrderBuyerMobilePhone
	 */
	public function setOrderBuyerMobilePhone($sOrderBuyerMobilePhone)
	{
		$this->sOrderBuyerMobilePhone = $sOrderBuyerMobilePhone;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerName
	 */
	public function getOrderBuyerName()
	{
		return $this->sOrderBuyerName;
	}

	/**
	 * @param NULL $sOrderBuyerName
	 */
	public function setOrderBuyerName($sOrderBuyerName)
	{
		$this->sOrderBuyerName = $sOrderBuyerName;
		return $this;

	}

	/**
	 * @return the $sOrderBuyerPhone
	 */
	public function getOrderBuyerPhone()
	{
		return $this->sOrderBuyerPhone;
	}

	/**
	 * @param NULL $sOrderBuyerPhone
	 */
	public function setOrderBuyerPhone($sOrderBuyerPhone)
	{
		$this->sOrderBuyerPhone = $sOrderBuyerPhone;
		return $this;

	}

	/**
	 * @return the $sOrderComment
	 */
	public function getOrderComment()
	{
		return $this->sOrderComment;
	}

	/**
	 * @param NULL $sOrderComment
	 */
	public function setOrderComment($sOrderComment)
	{
		$this->sOrderComment = $sOrderComment;
		return $this;

	}

	/**
	 * @return the $sOrderCurrencyCode
	 */
	public function getOrderCurrencyCode()
	{
		return $this->sOrderCurrencyCode;
	}

	/**
	 * @param NULL $sOrderCurrencyCode
	 */
	public function setOrderCurrencyCode($sOrderCurrencyCode)
	{
		$this->sOrderCurrencyCode = $sOrderCurrencyCode;
		return $this;

	}

	/**
	 * @return the $oOrderLastModificationUtcDate
	 */
	public function getOrderLastModificationUtcDate()
	{
		return $this->oOrderLastModificationUtcDate;
	}

	/**
	 * @param NULL $oOrderLastModificationUtcDate
	 */
	public function setOrderLastModificationUtcDate($oOrderLastModificationUtcDate)
	{
		$this->oOrderLastModificationUtcDate = $oOrderLastModificationUtcDate;
		return $this;

	}

	/**
	 * @return the $oOrderMarketPlaceLastModificationUtcDate
	 */
	public function getOrderMarketPlaceLastModificationUtcDate()
	{
		return $this->oOrderMarketPlaceLastModificationUtcDate;
	}

	/**
	 * @param NULL $oOrderMarketPlaceLastModificationUtcDate
	 */
	public function setOrderMarketPlaceLastModificationUtcDate($oOrderMarketPlaceLastModificationUtcDate)
	{
		$this->oOrderMarketPlaceLastModificationUtcDate = $oOrderMarketPlaceLastModificationUtcDate;
		return $this;

	}

	/**
	 * @return the $sOrderMarketPlaceOrderId
	 */
	public function getOrderMarketPlaceOrderId()
	{
		return $this->sOrderMarketPlaceOrderId;
	}

	/**
	 * @param NULL $sOrderMarketPlaceOrderId
	 */
	public function setOrderMarketPlaceOrderId($sOrderMarketPlaceOrderId)
	{
		$this->sOrderMarketPlaceOrderId = $sOrderMarketPlaceOrderId;
		return $this;

	}

	/**
	 * @return the $sOrderMerchantOrderId
	 */
	public function getOrderMerchantOrderId()
	{
		return $this->sOrderMerchantOrderId;
	}

	/**
	 * @param NULL $sOrderMerchantOrderId
	 */
	public function setOrderMerchantOrderId($sOrderMerchantOrderId)
	{
		$this->sOrderMerchantOrderId = $sOrderMerchantOrderId;
		return $this;

	}

	/**
	 * @return the $oOrderPayingUtcDate
	 */
	public function getOrderPayingUtcDate()
	{
		return $this->oOrderPayingUtcDate;
	}

	/**
	 * @param NULL $oOrderPayingUtcDate
	 */
	public function setOrderPayingUtcDate($oOrderPayingUtcDate)
	{
		$this->oOrderPayingUtcDate = $oOrderPayingUtcDate;
		return $this;

	}

	/**
	 * @return the $sOrderPaymentMethod
	 */
	public function getOrderPaymentMethod()
	{
		return $this->sOrderPaymentMethod;
	}

	/**
	 * @param NULL $sOrderPaymentMethod
	 */
	public function setOrderPaymentMethod($sOrderPaymentMethod)
	{
		$this->sOrderPaymentMethod = $sOrderPaymentMethod;
		return $this;

	}

	/**
	 * @return the $oOrderPurchaseUtcDate
	 */
	public function getOrderPurchaseUtcDate()
	{
		return $this->oOrderPurchaseUtcDate;
	}

	/**
	 * @param NULL $oOrderPurchaseUtcDate
	 */
	public function setOrderPurchaseUtcDate(DateTime $oOrderPurchaseUtcDate)
	{
		$this->oOrderPurchaseUtcDate = $oOrderPurchaseUtcDate;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressCity
	 */
	public function getOrderShippingAddressCity()
	{
		return $this->sOrderShippingAddressCity;
	}

	/**
	 * @param NULL $sOrderShippingAddressCity
	 */
	public function setOrderShippingAddressCity($sOrderShippingAddressCity)
	{
		$this->sOrderShippingAddressCity = $sOrderShippingAddressCity;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressCountryIsoCodeAlpha2
	 */
	public function getOrderShippingAddressCountryIsoCodeAlpha2()
	{
		return $this->sOrderShippingAddressCountryIsoCodeAlpha2;
	}

	/**
	 * @param NULL $sOrderShippingAddressCountryIsoCodeAlpha2
	 */
	public function setOrderShippingAddressCountryIsoCodeAlpha2($sOrderShippingAddressCountryIsoCodeAlpha2)
	{
		$this->sOrderShippingAddressCountryIsoCodeAlpha2 = $sOrderShippingAddressCountryIsoCodeAlpha2;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressCountryName
	 */
	public function getOrderShippingAddressCountryName()
	{
		return $this->sOrderShippingAddressCountryName;
	}

	/**
	 * @param NULL $sOrderShippingAddressCountryName
	 */
	public function setOrderShippingAddressCountryName($sOrderShippingAddressCountryName)
	{
		$this->sOrderShippingAddressCountryName = $sOrderShippingAddressCountryName;
		return $this;
	}

	/**
	 * @return the $sOrderShippingAddressLine1
	 */
	public function getOrderShippingAddressLine1()
	{
		return $this->sOrderShippingAddressLine1;
	}

	/**
	 * @param NULL $sOrderShippingAddressLine1
	 */
	public function setOrderShippingAddressLine1($sOrderShippingAddressLine1)
	{
		$this->sOrderShippingAddressLine1 = $sOrderShippingAddressLine1;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressLine2
	 */
	public function getOrderShippingAddressLine2()
	{
		return $this->sOrderShippingAddressLine2;
	}

	/**
	 * @param NULL $sOrderShippingAddressLine2
	 */
	public function setOrderShippingAddressLine2($sOrderShippingAddressLine2)
	{
		$this->sOrderShippingAddressLine2 = $sOrderShippingAddressLine2;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressLine3
	 */
	public function getOrderShippingAddressLine3()
	{
		return $this->sOrderShippingAddressLine3;
	}

	/**
	 * @param NULL $sOrderShippingAddressLine3
	 */
	public function setOrderShippingAddressLine3($sOrderShippingAddressLine3)
	{
		$this->sOrderShippingAddressLine3 = $sOrderShippingAddressLine3;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressName
	 */
	public function getOrderShippingAddressName()
	{
		return $this->sOrderShippingAddressName;
	}

	/**
	 * @param NULL $sOrderShippingAddressName
	 */
	public function setOrderShippingAddressName($sOrderShippingAddressName)
	{
		$this->sOrderShippingAddressName = $sOrderShippingAddressName;
		return $this;

	}

	/**
	 * @return the $sOrderShippingAddressPostalCode
	 */
	public function getOrderShippingAddressPostalCode()
	{
		return $this->sOrderShippingAddressPostalCode;
	}

	/**
	 * @param NULL $sOrderShippingAddressPostalCode
	 */
	public function setOrderShippingAddressPostalCode($sOrderShippingAddressPostalCode)
	{
		$this->sOrderShippingAddressPostalCode = $sOrderShippingAddressPostalCode;
		return $this;

	}

	/**
	 * @return the $sOrderShippingStateOrRegion
	 */
	public function getOrderShippingAddressStateOrRegion()
	{
		return $this->sOrderShippingAddressStateOrRegion;
	}

	/**
	 * @param NULL $sOrderShippingStateOrRegion
	 */
	public function setOrderShippingAddressStateOrRegion($sOrderShippingAddressStateOrRegion)
	{
		$this->sOrderShippingAddressStateOrRegion = $sOrderShippingAddressStateOrRegion;
		return $this;

	}

	/**
	 * @return the $sOrderShippingCivility
	 */
	public function getOrderShippingCivility()
	{
		return $this->sOrderShippingCivility;
	}

	/**
	 * @param NULL $sOrderShippingCivility
	 */
	public function setOrderShippingCivility($sOrderShippingCivility)
	{
		$this->sOrderShippingCivility = $sOrderShippingCivility;
		return $this;

	}

	/**
	 * @return the $sOrderShippingCompanyName
	 */
	public function getOrderShippingCompanyName()
	{
		return $this->sOrderShippingCompanyName;
	}

	/**
	 * @param NULL $sOrderShippingCompanyName
	 */
	public function setOrderShippingCompanyName($sOrderShippingCompanyName)
	{
		$this->sOrderShippingCompanyName = $sOrderShippingCompanyName;
		return $this;

	}

	/**
	 * @return the $oOrderShippingEarliestShipUtcDate
	 */
	public function getOrderShippingEarliestShipUtcDate()
	{
		return $this->oOrderShippingEarliestShipUtcDate;
	}

	/**
	 * @param NULL $oOrderShippingEarliestShipUtcDate
	 */
	public function setOrderShippingEarliestShipUtcDate($oOrderShippingEarliestShipUtcDate)
	{
		$this->oOrderShippingEarliestShipUtcDate = $oOrderShippingEarliestShipUtcDate;
		return $this;

	}

	/**
	 * @return the $sOrderShippingEmail
	 */
	public function getOrderShippingEmail()
	{
		return $this->sOrderShippingEmail;
	}

	/**
	 * @param NULL $sOrderShippingEmail
	 */
	public function setOrderShippingEmail($sOrderShippingEmail)
	{
		$this->sOrderShippingEmail = $sOrderShippingEmail;
		return $this;

	}

	/**
	 * @return the $oOrderShippingLatestShipUtcDate
	 */
	public function getOrderShippingLatestShipUtcDate()
	{
		return $this->oOrderShippingLatestShipUtcDate;
	}

	/**
	 * @param NULL $oOrderShippingLatestShipUtcDate
	 */
	public function setOrderShippingLatestShipUtcDate($oOrderShippingLatestShipUtcDate)
	{
		$this->oOrderShippingLatestShipUtcDate = $oOrderShippingLatestShipUtcDate;
		return $this;

	}

	/**
	 * @return the $sOrderShippingMethod
	 */
	public function getOrderShippingMethod()
	{
		return $this->sOrderShippingMethod;
	}

	/**
	 * @param NULL $sOrderShippingMethod
	 */
	public function setOrderShippingMethod($sOrderShippingMethod)
	{
		$this->sOrderShippingMethod = $sOrderShippingMethod;
		return $this;

	}

	/**
	 * @return the $sOrderShippingMobile
	 */
	public function getOrderShippingMobilePhone()
	{
		return $this->sOrderShippingMobilePhone;
	}

	/**
	 * @param NULL $sOrderShippingMobile
	 */
	public function setOrderShippingMobilePhone($sOrderShippingMobilePhone)
	{
		$this->sOrderShippingMobilePhone = $sOrderShippingMobilePhone;
		return $this;

	}

	/**
	 * @return the $sOrderShippingPhone
	 */
	public function getOrderShippingPhone()
	{
		return $this->sOrderShippingPhone;
	}

	/**
	 * @param NULL $sOrderShippingPhone
	 */
	public function setOrderShippingPhone($sOrderShippingPhone)
	{
		$this->sOrderShippingPhone = $sOrderShippingPhone;
		return $this;

	}

	/**
	 * @return the $fOrderShippingPrice
	 */
	public function getOrderShippingPrice()
	{
		return $this->fOrderShippingPrice;
	}

	/**
	 * @param NULL $fOrderShippingPrice
	 */
	public function setOrderShippingPrice($fOrderShippingPrice)
	{
		$this->fOrderShippingPrice = $fOrderShippingPrice;
		return $this;

	}

	/**
	 * @return the $fOrderShippingTax
	 */
	public function getOrderShippingShippingTax()
	{
		return $this->fOrderShippingShippingTax;
	}

	/**
	 * @param NULL $fOrderShippingTax
	 */
	public function setOrderShippingShippingTax($fOrderShippingShippingTax)
	{
		$this->fOrderShippingShippingTax = $fOrderShippingShippingTax;
		return $this;

	}

	/**
	 * @return the $sOrderStatusBeezUPOrderStatus
	 */
	public function getOrderStatusBeezUPOrderStatus()
	{
		return $this->sOrderStatusBeezUPOrderStatus;
	}

	/**
	 * @param NULL $sOrderStatusBeezUPOrderStatus
	 */
	public function setOrderStatusBeezUPOrderStatus($sOrderStatusBeezUPOrderStatus)
	{
		$this->sOrderStatusBeezUPOrderStatus = $sOrderStatusBeezUPOrderStatus;
		return $this;

	}

	/**
	 * @return the $sOrderStatusMarketPlaceOrderStatus
	 */
	public function getOrderStatusMarketPlaceOrderStatus()
	{
		return $this->sOrderStatusMarketPlaceOrderStatus;
	}

	/**
	 * @param NULL $sOrderStatusMarketPlaceOrderStatus
	 */
	public function setOrderStatusMarketPlaceOrderStatus($sOrderStatusMarketPlaceOrderStatus)
	{
		$this->sOrderStatusMarketPlaceOrderStatus = $sOrderStatusMarketPlaceOrderStatus;
		return $this;

	}

	/**
	 * @return the $fOrderTotalCommision
	 */
	public function getOrderTotalCommission()
	{
		return $this->fOrderTotalCommission;
	}

	/**
	 * @param NULL $fOrderTotalCommision
	 */
	public function setOrderTotalCommission($fOrderTotalCommission)
	{
		$this->fOrderTotalCommission = $fOrderTotalCommission;
		return $this;

	}

	/**
	 * @return the $fOrderTotalPrice
	 */
	public function getOrderTotalPrice()
	{
		return $this->fOrderTotalPrice;
	}

	/**
	 * @param NULL $fOrderTotalPrice
	 */
	public function setOrderTotalPrice($fOrderTotalPrice)
	{
		$this->fOrderTotalPrice = $fOrderTotalPrice;
		return $this;

	}

	/**
	 * @return the $fOrderTotalTax
	 */
	public function getOrderTotalTax()
	{
		return $this->fOrderTotalTax;
	}

	/**
	 * @param NULL $fOrderTotalTax
	 */
	public function setOrderTotalTax($fOrderTotalTax)
	{
		$this->fOrderTotalTax = $fOrderTotalTax;
		return $this;

	}

	/**
	 * Ugly hack as we do not have accountid in order result
	 * @return Ambigous <>|NULL
	 */
	public function getAccountId(){
		$sResult = null;
		if (!empty($this->aLinks) && isset($this->aLinks[0])){
			/**
			 * @var BeezupOMLink
			 */
			$oLink = $this->aLinks[0];
			$aAtoms = explode('/', $oLink->getHref());
			if (isset($aAtoms[6]) && $aAtoms[4] == $this->getMarketPlaceTechnicalCode() && $aAtoms[6]== $this->getBeezupOrderUUID() && is_numeric($aAtoms[5])){
				return $aAtoms[5];
			}

		}
		return null;
	}


	public function getLinkByRel($sRel){
		foreach ($this->getLinks() as $oLink){
			if ($oLink->getRel() === $sRel){
				return $oLink;
			}
		}
		return null;
	}

	public function getTransitionLinkByRel($sRel){
		foreach ($this->getTransitionLinks() as $oLink){
			if ($oLink->getRel() === $sRel){
				return $oLink;
			}
		}
		return null;
	}

	protected function toCamelCase($text) {
		return preg_replace_callback('#_(\S)#', function ($matches) {
			return strtoupper($matches[1]);
		}, $text);
	}

	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMOrderResult();
		foreach ($aData as $sKey=>$mValue){
			$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
			$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
			if ($sCamelCaseKey == 'orderItems' && is_array($mValue)){
				foreach ($mValue as $aOrderItem){
					$oResult->addOrderItem(BeezupOMOrderItem::fromArray($aOrderItem));
				}
			} else if ($sCamelCaseKey == 'links' && is_array($mValue)){
				foreach ($mValue as $aLink){
					$oResult->addLink(BeezupOMLink::fromArray($aLink));
				}
			} else if ($sCamelCaseKey == 'transitionLinks' && is_array($mValue)){
				foreach ($mValue as $aLink){
					$oResult->addTransitionLink(BeezupOMLink::fromArray($aLink));
				}
			} else if (method_exists($oResult,$sSetterMethod) && is_scalar($mValue)){
				call_user_func(array($oResult,$sSetterMethod), stristr($sSetterMethod,'UtcDate') ? new DateTime($mValue, new DateTimeZone('UTC')) : $mValue);
			} // if
		} // foreach
		return $oResult;
	}



}