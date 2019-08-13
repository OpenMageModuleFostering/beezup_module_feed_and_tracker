<?php

	class BeezupOMOrderItem {
		
		protected $sBeezUPOrderItemId = null;
		protected $sOrderItemOrderItemType = null;
		protected $sOrderItemTitle = null;
		protected $sOrderItemImageUrl = null;
		protected $sOrderItemMerchantProductId = null;
		protected $sOrderItemMarketPlaceProductId = null;
		protected $fOrderItemItemPrice = null;
		protected $nOrderItemQuantity  = null;
		protected $fOrderItemShippingPrice = null;
		protected $fOrderItemTotalPrice= null;
		protected $sOrderItemMerchantImportedProductId= null;
		protected $sOrderItemMerchantImportedProductIdColumnName= null;
		protected $sOrderItemMerchantImportedProductUrl= null;
		protected $sOrderItemMerchantProductIdColumnName= null;
		protected $sOrderItemBeezUPStoreId= null;
		
		
		
		/**
	 * @return the $sBeezUPOrderItemId
	 */
	public function getBeezUPOrderItemId()
	{
		return $this->sBeezUPOrderItemId;
	}

		/**
	 * @param NULL $sBeezUPOrderItemId
	 */
	public function setBeezUPOrderItemId($sBeezUPOrderItemId)
	{
		$this->sBeezUPOrderItemId = $sBeezUPOrderItemId;
		return $this;
	}

		/**
	 * @return the $sOrderItemBeezUPStoreId
	 */
	public function getOrderItemBeezUPStoreId()
	{
		return $this->sOrderItemBeezUPStoreId;
	}

		/**
	 * @param NULL $sOrderItemBeezUPStoreId
	 */
	public function setOrderItemBeezUPStoreId($sOrderItemBeezUPStoreId)
	{
		$this->sOrderItemBeezUPStoreId = $sOrderItemBeezUPStoreId;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemImageUrl
	 */
	public function getOrderItemImageUrl()
	{
		return $this->sOrderItemImageUrl;
	}

		/**
	 * @param NULL $sOrderItemImageUrl
	 */
	public function setOrderItemImageUrl($sOrderItemImageUrl)
	{
		$this->sOrderItemImageUrl = $sOrderItemImageUrl;
		return $this;
		
	}

		/**
	 * @return the $fOrderItemItemPrice
	 */
	public function getOrderItemItemPrice()
	{
		return $this->fOrderItemItemPrice;
	}

		/**
	 * @param NULL $fOrderItemItemPrice
	 */
	public function setOrderItemItemPrice($fOrderItemItemPrice)
	{
		$this->fOrderItemItemPrice = $fOrderItemItemPrice;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemMarketPlaceProductId
	 */
	public function getOrderItemMarketPlaceProductId()
	{
		return $this->sOrderItemMarketPlaceProductId;
	}

		/**
	 * @param NULL $sOrderItemMarketPlaceProductId
	 */
	public function setOrderItemMarketPlaceProductId($sOrderItemMarketPlaceProductId)
	{
		$this->sOrderItemMarketPlaceProductId = $sOrderItemMarketPlaceProductId;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemMerchantImportedProductId
	 */
	public function getOrderItemMerchantImportedProductId()
	{
		return $this->sOrderItemMerchantImportedProductId;
	}

		/**
	 * @param NULL $sOrderItemMerchantImportedProductId
	 */
	public function setOrderItemMerchantImportedProductId($sOrderItemMerchantImportedProductId)
	{
		$this->sOrderItemMerchantImportedProductId = $sOrderItemMerchantImportedProductId;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemMerchantImportedProductIdColumnName
	 */
	public function getOrderItemMerchantImportedProductIdColumnName()
	{
		return $this->sOrderItemMerchantImportedProductIdColumnName;
	}

		/**
	 * @param NULL $sOrderItemMerchantImportedProductIdColumnName
	 */
	public function setOrderItemMerchantImportedProductIdColumnName($sOrderItemMerchantImportedProductIdColumnName)
	{
		$this->sOrderItemMerchantImportedProductIdColumnName = $sOrderItemMerchantImportedProductIdColumnName;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemMerchantImportedProductUrl
	 */
	public function getOrderItemMerchantImportedProductUrl()
	{
		return $this->sOrderItemMerchantImportedProductUrl;
	}

		/**
	 * @param NULL $sOrderItemMerchantImportedProductUrl
	 */
	public function setOrderItemMerchantImportedProductUrl($sOrderItemMerchantImportedProductUrl)
	{
		$this->sOrderItemMerchantImportedProductUrl = $sOrderItemMerchantImportedProductUrl;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemMerchantProductId
	 */
	public function getOrderItemMerchantProductId()
	{
		return $this->sOrderItemMerchantProductId;
	}

		/**
	 * @param NULL $sOrderItemMerchantProductId
	 */
	public function setOrderItemMerchantProductId($sOrderItemMerchantProductId)
	{
		$this->sOrderItemMerchantProductId = $sOrderItemMerchantProductId;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemMerchantProductIdColumnName
	 */
	public function getOrderItemMerchantProductIdColumnName()
	{
		return $this->sOrderItemMerchantProductIdColumnName;
	}

		/**
	 * @param NULL $sOrderItemMerchantProductIdColumnName
	 */
	public function setOrderItemMerchantProductIdColumnName($sOrderItemMerchantProductIdColumnName)
	{
		$this->sOrderItemMerchantProductIdColumnName = $sOrderItemMerchantProductIdColumnName;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemOrderItemType
	 */
	public function getOrderItemOrderItemType()
	{
		return $this->sOrderItemOrderItemType;
	}

		/**
	 * @param NULL $sOrderItemOrderItemType
	 */
	public function setOrderItemOrderItemType($sOrderItemOrderItemType)
	{
		$this->sOrderItemOrderItemType = $sOrderItemOrderItemType;
		return $this;
		
	}

		/**
	 * @return the $nOrderItemQuantity
	 */
	public function getOrderItemQuantity()
	{
		return $this->nOrderItemQuantity;
	}

		/**
	 * @param NULL $nOrderItemQuantity
	 */
	public function setOrderItemQuantity($nOrderItemQuantity)
	{
		$this->nOrderItemQuantity = $nOrderItemQuantity;
		return $this;
		
	}

		/**
	 * @return the $fOrderItemShippingPrice
	 */
	public function getOrderItemShippingPrice()
	{
		return $this->fOrderItemShippingPrice;
	}

		/**
	 * @param NULL $fOrderItemShippingPrice
	 */
	public function setOrderItemShippingPrice($fOrderItemShippingPrice)
	{
		$this->fOrderItemShippingPrice = $fOrderItemShippingPrice;
		return $this;
		
	}

		/**
	 * @return the $sOrderItemTitle
	 */
	public function getOrderItemTitle()
	{
		return $this->sOrderItemTitle;
	}

		/**
	 * @param NULL $sOrderItemTitle
	 */
	public function setOrderItemTitle($sOrderItemTitle)
	{
		$this->sOrderItemTitle = $sOrderItemTitle;
		return $this;
		
	}

		/**
	 * @return the $fOrderItemTotalPrice
	 */
	public function getOrderItemTotalPrice()
	{
		return $this->fOrderItemTotalPrice;
	}

		/**
	 * @param NULL $fOrderItemTotalPrice
	 */
	public function setOrderItemTotalPrice($fOrderItemTotalPrice)
	{
		$this->fOrderItemTotalPrice = $fOrderItemTotalPrice;
		return $this;
		
	}
	public static function fromArray(array $aData = array()){
		$oResult = new BeezupOMOrderItem();
		foreach ($aData as $sKey=>$mValue){
			$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
			$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
			if (method_exists($oResult,$sSetterMethod) && is_scalar($mValue)){
				call_user_func(array($oResult,$sSetterMethod), stristr($sKey,'UtcDate') ? new DateTime($mValue, new DateTimeZone('UTC')) : $mValue);
			} // if
		} // foreach
		return $oResult;
	}
	public function toArray(){
		$aResult = array();
		$oReflection = new ReflectionClass($this);
		foreach ($oReflection->getMethods() as $oMethod){
			$sName = $oMethod->getName();
			if (substr($sName,0, 3) === 'get'){
				$sName = str_replace(array('BeezUP', 'UUID', 'ECommerce'), array('Beezup', 'Uuid', 'Ecommerce'), $sName);
				$sExportName = trim(strtolower(preg_replace('/([A-Z])/', '_$1',  substr($sName, 3))), '_');
				$aResult[$sExportName] = $this->convert(call_user_func(array($this, $oMethod->getName())));
			}
		}
		return $aResult;
	}
	
	protected function convert($mValue){
		if (is_object($mValue)){
			if (method_exists($mValue, 'toArray')){
				$mValue = call_user_func(array($mValue, 'toArray'));
			} else if ($mValue instanceof DateTime){
				$mValue = $mValue->format($this->sDateFormat);
			} else if (method_exists($mValue, '__toString')){
				$mValue = (string)$mValue;
			}
		} else if (is_array($mValue)){
			foreach ($mValue as $mKey => $mElement){
				$mValue[$mKey] = $this->convert($mElement);
			}
		}
		return $mValue;
	}
	}