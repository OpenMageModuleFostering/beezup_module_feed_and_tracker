<?php

	class BeezupOMSetOrderIdValues extends BeezupOMDataHandler {

		protected $sOrderMerchantOrderId = null;
		protected $sOrderMerchantECommerceSoftwareName = null;
		protected $sOrderMerchantECommerceSoftwareVersion = null;


		public static function fromArray(array $aData = array()){
			$aData = array_change_key_case($aData, CASE_LOWER);
			$oResult = new self();
			$oResult
				->setOrderMerchantOrderId($aData['order_merchantorderid'])
				->setOrderMerchantECommerceSoftwareName($aData['order_merchantecommercesoftwarename'])
				->setOrderMerchantECommerceSoftwareVersion($aData['order_merchantecommercesoftwareversion']);
			return $oResult;
		}

		public function toArray(){
			return array(
				'Order_MerchantOrderId' => $this->getOrderMerchantOrderId(),
				'Order_MerchantECommerceSoftwareName' => $this->getOrderMerchantECommerceSoftwareName(),
				'Order_MerchantECommerceSoftwareVersion' => $this->getOrderMerchantECommerceSoftwareVersion()
			);
		}
		/**
		  * @return $sOrderMerchantOrderId
		  */
		public function getOrderMerchantOrderId()
		{
			return $this->sOrderMerchantOrderId;
		}

			/**
		  * @return $sOrderMerchantECommerceSoftwareName
		  */
		public function getOrderMerchantECommerceSoftwareName()
		{
			return $this->sOrderMerchantECommerceSoftwareName;
		}

			/**
		  * @return $sOrderMerchantECommerceSoftwareVersion
		  */
		public function getOrderMerchantECommerceSoftwareVersion()
		{
			return $this->sOrderMerchantECommerceSoftwareVersion;
		}

			/**
		  * @param NULL $sOrderMerchantOrderId
		  * @return BeezupOMSetOrderIdValues
		  */
		public function setOrderMerchantOrderId($sOrderMerchantOrderId)
		{
			$this->sOrderMerchantOrderId = (string)$sOrderMerchantOrderId;
			return $this;
		}

			/**
		  * @param NULL $sOrderMerchantECommerceSoftwareName
		  * @return BeezupOMSetOrderIdValues
		  */
		public function setOrderMerchantECommerceSoftwareName($sOrderMerchantECommerceSoftwareName)
		{
			$this->sOrderMerchantECommerceSoftwareName = $sOrderMerchantECommerceSoftwareName;
			return $this;
		}

			/**
		  * @param NULL $sOrderMerchantECommerceSoftwareVersion
		  * @return BeezupOMSetOrderIdValues
		  */
		public function setOrderMerchantECommerceSoftwareVersion($sOrderMerchantECommerceSoftwareVersion)
		{
			$this->sOrderMerchantECommerceSoftwareVersion = $sOrderMerchantECommerceSoftwareVersion;
			return $this;
		}

	}