<?php

	class BeezupOMMarketplace {

		protected $sMarketplaceTechnicalCode = null;
		protected $sAccountId = null;
		protected $sMarketPlaceIsoCountryCodeAlpha2 = null;
		protected $sMarketPlaceMarketPlaceId = null;
		protected $sBeezUPMarketPlaceName = null;
		protected $sMarketPlaceMerchandId = null;
		protected $nBeezUPChannelId = null;
		protected $sBeezUPStoreId = null;
		protected $sBeezUPStoreName = null;
		protected $sMarketPlaceBusinessCode = null;

		public static function fromArray(array $aData = array()){
			$oValue = new BeezupOMMarketplace();
			foreach ($aData as $sKey=>$mValue){
				$sCamelCaseKey = preg_replace_callback('#_(\S)#', function ($matches) {return strtoupper($matches[1]);}, $sKey);
				$sSetterMethod = 'set' . ucfirst($sCamelCaseKey);
				if (!method_exists($oValue,$sSetterMethod)){
					continue;
				}
				$cCallback = array($oValue,$sSetterMethod);
				if (is_scalar($mValue)){
					call_user_func($cCallback, $mValue);
				} // if
			} // foreach
			return $oValue;
		}

		public function toArray(){
			return array(
				'marketPlaceTechnicalCode' => $this->getMarketPlaceTechnicalCode(),
				'accountId' => $this->getAccountId(),
				'marketPlaceIsoCountryCodeAlpha2' => $this->getMarketPlaceIsoCountryCodeAlpha2(),
				'marketPlaceMarketPlaceId' => $this->getMarketPlaceMarketPlaceId(),
				'beezUPMarketPlaceName' => $this->getBeezUPMarketPlaceName(),
				'marketPlaceMerchandId' => $this->getMarketPlaceMerchandId(),
				'beezUPChannelId' => $this->getBeezUPChannelId(),
				'beezUPStoreId' => $this->getBeezUPStoreId(),
				'beezUPStoreName' => $this->getBeezUPStoreName(),
				'marketPlaceBusinessCode' => $this->getMarketPlaceBusinessCode()
			);
		}

		public function getUUID(){
			return md5(implode('|', $this->toArray()));
		}

		public function getMarketplaceTechnicalCode()
		{
			return $this->sMarketplaceTechnicalCode;
		}

		public function setMarketplaceTechnicalCode($sMarketplaceTechnicalCode)
		{
			$this->sMarketplaceTechnicalCode = $sMarketplaceTechnicalCode;
			return $this;
		}

		public function getAccountId()
		{
			return $this->sAccountId;
		}

		public function setAccountId($sAccountId)
		{
			$this->sAccountId = $sAccountId;
			return $this;
		}

		public function getMarketPlaceIsoCountryCodeAlpha2()
		{
			return $this->sMarketPlaceIsoCountryCodeAlpha2;
		}

		public function setMarketPlaceIsoCountryCodeAlpha2($sMarketPlaceIsoCountryCodeAlpha2)
		{
			$this->sMarketPlaceIsoCountryCodeAlpha2 = $sMarketPlaceIsoCountryCodeAlpha2;
			return $this;
		}

		public function getMarketPlaceMarketPlaceId()
		{
			return $this->sMarketPlaceMarketPlaceId;
		}

		public function setMarketPlaceMarketPlaceId($sMarketPlaceMarketPlaceId)
		{
			$this->sMarketPlaceMarketPlaceId = $sMarketPlaceMarketPlaceId;
			return $this;
		}

		public function getBeezUPMarketPlaceName()
		{
			return $this->sBeezUPMarketPlaceName;
		}

		public function setBeezUPMarketPlaceName($sBeezUPMarketPlaceName)
		{
			$this->sBeezUPMarketPlaceName = $sBeezUPMarketPlaceName;
			return $this;
		}

		public function getMarketPlaceMerchandId()
		{
			return $this->sMarketPlaceMerchandId;
		}

		public function setMarketPlaceMerchandId($sMarketPlaceMerchandId)
		{
			$this->sMarketPlaceMerchandId = $sMarketPlaceMerchandId;
			return $this;
		}

		public function getBeezUPChannelId()
		{
			return $this->nBeezUPChannelId;
		}

		public function setBeezUPChannelId($nBeezUPChannelId)
		{
			$this->nBeezUPChannelId = (int)$nBeezUPChannelId;
			return $this;
		}

		public function getBeezUPStoreId()
		{
			return $this->sBeezUPStoreId;
		}

		public function setBeezUPStoreId($sBeezUPStoreId)
		{
			$this->sBeezUPStoreId = $sBeezUPStoreId;
			return $this;
		}

		public function getBeezUPStoreName()
		{
			return $this->sBeezUPStoreName;
		}

		public function setBeezUPStoreName($sBeezUPStoreName)
		{
			$this->sBeezUPStoreName = $sBeezUPStoreName;
			return $this;
		}

		public function getMarketPlaceBusinessCode()
		{
			return $this->sMarketPlaceBusinessCode;
		}

		public function setMarketPlaceBusinessCode($sMarketPlaceBusinessCode)
		{
			$this->sMarketPlaceBusinessCode = $sMarketPlaceBusinessCode;
			return $this;
		}

	}