<?php 
class BeezupRepository implements BeezupOMRepositoryInterface {
	
		protected $oProductIdentityMapper = null;
	protected $aFirstNamesCache = null;
	protected $oCredential = null;
	protected $oModule = null;
	protected $bDebugMode = null;

	protected $bConnectionTestCache = null;
	
	
	public function getStores() {
		$service = new BeezupOMOrderService($this);
		return $service->getStores();	
	}
	
		public function isConnectionOk(){
			if ($this->bConnectionTestCache===null){
					        $helper = Mage::helper('beezup');

        /* Initially load the useful elements */
		$helper = Mage::helper('beezup');

		 $userid = $helper->getConfig('beezup/marketplace/userid');
		 $usertoken = $helper->getConfig('beezup/marketplace/usertoken');
				
				
				if (!$userid || !$usertoken){
					$this->bConnectionTestCache = false;
				}
				try {
					$service = new BeezupOMOrderService($this);
					$this->bConnectionTestCache = ((int)$service->isCredentialValid());
				} catch (Exception $oException){
					$this->bConnectionTestCache = false;
				}
			}
			return $this->bConnectionTestCache;
		}
	
	
	
	
		/**
	 *
	 * @param BeezupOMCredential $oCredential        	
	 * @return BeezupOMController
	 */
	public function setCredential(BeezupOMCredential $oCredential)
	{
		$this->oCredential = $oCredential;
		return $this;
	}
	/**
	 *
	 * @return BeezupOMCredential
	 */
	public function getCredential()
	{
		if ($this->oCredential === null)
		{
			$this->setCredential ( $this->createCredential () );
		}
		return $this->oCredential;
	}
	
		protected function createCredential()
	{
		$helper = Mage::helper('beezup');

		 $userid = $helper->getConfig('beezup/marketplace/userid');
		 $usertoken = $helper->getConfig('beezup/marketplace/usertoken');
		
		return new BeezupOMCredential ( $userid, $usertoken);
	}
	
	# DEBUG HANDLING

		/**
		 * Sets debug mode
		 * @param boolean $bDebugMode
		 */
			
	public function isDebugModeActivated()
	{
		return $this->bDebugMode;
	}
	public function setDebugMode($bDebugMode)
	{
		$this->bDebugMode = $bDebugMode;
		return $this;
	}
		
	# CONFIGURATION
		
		/**
		 * Checks configuration
		 * @return boolean True if configuration is OK
		 */
		public function isConfigurationOk() {
			
			return true;
		}



	# ORDER HANDLING

		/**
		 * Creates new Order
		 * @param BeezupOMOrderResponse $oBeezupOMOrderResponse
		 * @return BeezupOMSetOrderIdValues|bool BeezupOMSetOrderIdValues instance or false on fail
		 */
		public function createOrder(BeezupOMOrderResponse $oBeezupOMOrderResponse) {
			
			
			return false;
		}

		/**
		 * Updates existing order
		 * @param BeezupOMOrderResponse $oBeezupOMOrderResponse
		 * @return integer New platform order status id
		 */
		public function updateOrder(BeezupOMOrderResponse $oBeezupOMOrderResponse) {
			
			return 1;
		}

		/**
		 * Gets cached Beezup order identifier associated with merchant order id
		 * @param integer $nMerchantOrderId
		 * @return BeezupOMOrderIdentifier|null
		 */
		public function getImportedOrderIdentifier($nMerchantOrderId) {
			
			return null;
		}
		
		/**
		 * Gets cached Beezup order associated with beezup order id
		 * @param BeezupOMOrderIdentifier $oOrderIdentifier
		 * @return BeezupOMOrderResponse|null
		*/
		public function getCachedBeezupOrderResponse(BeezupOMOrderIdentifier $oOrderIdentifier) {
			
			return null;
		}
		
	# SYNCHRONIZATION 
		
		/**
		 * @return DateTime $oLastSynchronizationDate
		 */
		public function getLastSynchronizationDate() {
		$oResult = new DateTime ( 'now', new DateTimeZone ( 'UTC' ) );
		$helper = Mage::helper('beezup');
		$nTime = (int)$helper->getConfig('beezup/marketplace/syncro_time');
		$nTime = strtotime($nTime) - 7200; 
		if ($nTime)
		{
			$oResult->setTimestamp ( $nTime );
		}
		return $oResult;			
			
		}

		/**
		 * Sets new LastSynchronizationDate
		 * @param DateTime $oLastSynchronizationDate
		 */
		public function updateLastSynchronizationDate(DateTime $oLastSynchronizationDate) {
			$helper = Mage::getModel('core/config');
			$helper->saveConfig('beezup/marketplace/syncro_time',$oLastSynchronizationDate->getTimestamp());
			return $this;
		}

		/**
		 * Returns current (not finished) BeezupOMHarvestClientReporting
		 * @return BeezupOMHarvestClientReporting|null Current synchronization or null
		 */
		public function getCurrentHarvestSynchronization() {
		$helper = Mage::helper('beezup');
			return $helper->getConfig('beezup/marketplace/syncro_time');
		}

		/**
		 * Changes all synchronization with IN_PROGRESS TO TIMEOUT
		 */
		public function purgeSync() {
		foreach (BeezupHarvestClient::getByProcessingStatus ( BeezupOMProcessingStatus::IN_PROGRESS ) as $aHarvest)
		{
			$oHarvestClient = new BeezupHarvestClient ( (int)$aHarvest['id_harvest_client'] );
			$oHarvestClient->processing_status = BeezupOMProcessingStatus::TIMEOUT;
			$oHarvestClient->update ();
		}			
			
		}
		
	# REPORTING
		
		/**
		 * Saves BeezupOMHarvestClientReporting (using execution_id as key unique). If $sNewExecutionId is given, it also updates execution_id 
		 * @param BeezupOMHarvestClientReporting $oSource
		 * @param string $sNewExecutionId
		 */
		public function saveHarvestClientReporting(BeezupOMHarvestClientReporting $oSource, $sNewExecutionId = null) {
		$oBeezupHarvestClient = BeezupHarvestClient::createFromOMObject ( $oSource );
		if ($oBeezupHarvestClient && is_string ( $sNewExecutionId ) && $sNewExecutionId != $oBeezupHarvestClient->execution_id)
		{
			$oBeezupHarvestClient->execution_id = $sNewExecutionId;
			$oBeezupHarvestClient->update ();
		}
		return (bool)$oBeezupHarvestClient;			
			
		}

		/**
		 * Saves BeezupOMHarvestOrderReporting (using execution_id as key unique). If $sNewExecutionId is given, it also updates execution_id 
		 * @param BeezupOMHarvestOrderReporting $oSource
		 * @param string $sNewExecutionId
		 */
		public function saveHarvestOrderReporting(BeezupOMHarvestOrderReporting $oSource, $sNewExecutionId = null) {
		$oBeezupHarvestOrder = BeezupHarvestOrder::createFromOMObject ( $oSource );
		if ($oBeezupHarvestOrder && is_string ( $sNewExecutionId ) && $sNewExecutionId != $oBeezupHarvestOrder->execution_id)
		{
			$oBeezupHarvestOrder->execution_id = $sNewExecutionId;
			$oBeezupHarvestOrder->update ();
		}
		return (bool)$oBeezupHarvestOrder;			
			
		}
	
	
	
	
	
	/**
	 *
	 * @param BeezupOMOrderItem $oItem        	
	 * @return array
	 */
	 /*
	protected function findProduct(BeezupOMOrderItem $oItem)
	{
		$aResult = array();
		$aMappingCallbacks = $this->getProductIdentityMapper ()->getMappingCallbacks ( $oItem->getOrderItemBeezUPStoreId () );
		$aSearch = array_unique ( array_filter ( array(
			$oItem->getOrderItemMerchantImportedProductId (),
			$oItem->getOrderItemMerchantProductId ()
		) ) );
		foreach ($aMappingCallbacks as $oMappingCallback)
		{
	
			$aFound = array();
			foreach ($aSearch as $sTerm)
			{
				$aFound = array_merge ( $aFound, $oMappingCallback->findAll ( $sTerm ) );
			}
			$aResult = array_merge ( $aResult, array_unique ( $aFound, SORT_REGULAR ) );
		}
		$aResult = array_unique ( $aResult, SORT_REGULAR );
		foreach ($aResult as $nKey => $aRow)
		{
			if ($aRow[0] == 0)
			{
				unset ( $aResult[$nKey] );
			}
		}
		return $aResult;
	}
	*/
	
	
	
			public function createOrderListRequest($sStartTime = null, $sEndTime = null, array $aBeezupOrderStates = array(), array $aMarketPlaces = array()){
			$helper = Mage::helper('beezup');
			$oRequest = new BeezupOMOrderListRequest();
			$oBeginPeriodUtcDate = new DateTime('now', new DateTimeZone('UTC'));
			$nLastSynchro = ($sStartTime && strtotime($sStartTime)) ? strtotime($sStartTime) : (int)$helper->getConfig('beezup/marketplace/syncro_time');
			if ($nLastSynchro){
				$oBeginPeriodUtcDate->setTimestamp($nLastSynchro); // @todo Datetime::getTimestamp if PHP >5.3.0
			}

			$oEndPeriodUtcDate = new DateTime('now', new DateTimeZone('UTC'));
			if ($sEndTime && strtotime($sEndTime)){
				$oEndPeriodUtcDate->setTimestamp(strtotime($sEndTime));
			}

			$oRequest
			    ->setBeginPeriodUtcDate($oBeginPeriodUtcDate)
			    ->setEndPeriodUtcDate($oEndPeriodUtcDate);

			if (!empty($aMarketPlaces)){
				$oRequest->setMarketPlaceTechnicalCodes($aMarketPlaces);
			}

			if (!empty($aBeezupOrderStates)){
				$oRequest->setBeezupOrderStates($aBeezupOrderStates);
			}

			return $oRequest;
		}
	
	
	
	public function getOrderList() {
		
		
		
	}
	
	
	
	
}