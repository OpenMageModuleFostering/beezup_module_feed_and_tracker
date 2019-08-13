<?php

	/**
	 * Beezup order  identifier
	 * @todo Add variables validation
	 */
	class BeezupOMOrderIdentifier	{

	# PROTECTED VARIABLES

		/**
		 * For example "Amazon" or "CDiscount"
		 * @var string
		 */
		protected $sMarketplaceTechnicalCode = '';

		/**
		 * Account id, for example "1234"
		 * @var string Numeric string
		 */
		protected $sAccountId = '';

		/**
		 * Unique order id, for example "8D1DE1CE0BCC5AB98758345fbd14f95a6e17431458ecdd6"
		 * @var string 47 characters
		 */
		protected $sBeezupOrderUUID = '';

	# STATIC METHODS

		/**
		 * Creates identifier object from Beezup order
		 * @param BeezupOMOrderResult $oBeezupOrder
		 * @return BeezupOMOrderIdentifier New identifier
		 */
		public static function fromBeezupOrder(BeezupOMOrderResult $oBeezupOrder){
			$oResult = new self();
			return $oResult
				->setMarketplaceTechnicalCode($oBeezupOrder->getMarketPlaceTechnicalCode())
				->setAccountId($oBeezupOrder->getAccountId())
				->setBeezupOrderUUID($oBeezupOrder->getBeezupOrderUUID());
			 $oResult;
		} // fromBeezupOrder
		
		public static function fromBeezupOrderLink(BeezupOMLink $oLink){
			$oResult = new self();
			$aData = explode('/', $oLink->getHref());
			if (count($aData) >= 6 && is_numeric($aData[4])){
				return $oResult
				->setMarketplaceTechnicalCode($aData[3])
				->setAccountId($aData[4])
				->setBeezupOrderUUID($aData[5]);
			} else {
				throw new Exception(sprintf('Unable to convert %s into order id', $oLink->getHref()));
			}
			return $oResult;
		} // fromBeezupOrder
		
		/**
		 * Creates identifier object from array
		 * @param array $aData Should contain 3 keys: accountid, beezuporderuuid and marketplacetechnicalcode (key casing is insensitive)
		 * @throws InvalidArgumentException When array do not contain all required keys (values can be empty, though)
		 * @return BeezupOMOrderIdentifier New identifier
		 */
		public static function fromArray(array $aData = array()){
			$aData = array_change_key_case($aData, CASE_LOWER);
			if (!array_key_exists('accountid', $aData) || !array_key_exists('beezuporderuuid', $aData) || !array_key_exists('marketplacetechnicalcode', $aData)){
				$sErrorMessage = 'Array should have accountid, beezuporderuuid and marketplacetechnicalcode keys, ' . (count($aData) ? implode(' ,', array_keys($aData)) : ' no key') . ' given';
				throw new InvalidArgumentException($sErrorMessage);
			}
			$oResult = new BeezupOMOrderIdentifier();
			$oResult
				->setMarketplaceTechnicalCode($aData['marketplacetechnicalcode'])
				->setAccountId($aData['accountid'])
				->setBeezupOrderUUID($aData['beezuporderuuid']);
			return $oResult;
		} // fromArray

	# MAGIC METHODS

		/**
		 * Return order identifier as string
		 * @return string
		 */
		public function __toString(){
			return $this->getMarketplaceTechnicalCode() . '/' . $this->getAccountId() . '/' . $this->getBeezupOrderUUID();
		} // __toString

	# PUBLIC METHODS

		/**
		 * Returns order identifier as array
		 * @return array of <string:key => string:value>
		 */
		public function toArray(){
			return array(
				'MarketPlaceTechnicalCode' => $this->getMarketplaceTechnicalCode(),
				'AccountId' => $this->getAccountId(),
				'BeezUPOrderUUID' => $this->getBeezupOrderUUID()
			);
		} // toArray

	# SETTERS & GETTERS

		/**
		 * Sets marketplace technical code
		 * @param string $sMarketplaceTechnicalCode
		 * @return BeezupOMOrderIdentifier
		 */
		public function setMarketplaceTechnicalCode($sMarketplaceTechnicalCode){
			$this->sMarketplaceTechnicalCode = (string)$sMarketplaceTechnicalCode;
			return $this;
		} // setMarketplaceTechnicalCode

		/**
		 * Gets marketplace technical code
		 * @return string marketplace technical code
		 */
		public function getMarketplaceTechnicalCode(){
			return $this->sMarketplaceTechnicalCode;
		} // getMarketplaceTechnicalCode

		/**
		 * Gets account id
		 * @param string $sAccountId
		 * @return BeezupOMOrderIdentifier Self
		 */
		public function setAccountId($sAccountId){
			$this->sAccountId = (string)$sAccountId;
			return $this;
		} // setAccountId

		/**
		 * Sets account id
		 * @return string
		 */
		public function getAccountId(){
			return $this->sAccountId;
		} // getAccountId

		/**
		 * Sets unique order id
		 * @param string $sBeezupOrderUUID
		 * @return BeezupOMOrderIdentifier
		 */
		public function setBeezupOrderUUID($sBeezupOrderUUID){
			$this->sBeezupOrderUUID = (string)$sBeezupOrderUUID;
			return $this;
		} // setBeezupOrderUUID

		/**
		 * Gets unique order id
		 * @return string
		 */
		public function getBeezupOrderUUID(){
			return $this->sBeezupOrderUUID;
		} // getBeezupOrderUUID

	}
