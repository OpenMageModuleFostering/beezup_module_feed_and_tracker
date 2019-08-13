<?php

	interface BeezupOMRepositoryInterface {

	# DEBUG HANDLING

		/**
		 * Sets debug mode
		 * @param boolean $bDebugMode
		 */
		public function setDebugMode($bDebugMode);
		
		/**
		 * Tests if debug mode is activated
		 * @return boolean
		*/
		public function isDebugModeActivated();
		
	# CONFIGURATION
		
		/**
		 * Checks configuration
		 * @return boolean True if configuration is OK
		 */
		public function isConfigurationOk();

		/**
		 * Returns credential object
		 * @return BeezupOMCredential
		*/
		public function getCredential();

	# ORDER HANDLING

		/**
		 * Creates new Order
		 * @param BeezupOMOrderResponse $oBeezupOMOrderResponse
		 * @return BeezupOMSetOrderIdValues|bool BeezupOMSetOrderIdValues instance or false on fail
		 */
		public function createOrder(BeezupOMOrderResponse $oBeezupOMOrderResponse);

		/**
		 * Updates existing order
		 * @param BeezupOMOrderResponse $oBeezupOMOrderResponse
		 * @return integer New platform order status id
		 */
		public function updateOrder(BeezupOMOrderResponse $oBeezupOMOrderResponse);

		/**
		 * Gets cached Beezup order identifier associated with merchant order id
		 * @param integer $nMerchantOrderId
		 * @return BeezupOMOrderIdentifier|null
		 */
		public function getImportedOrderIdentifier($nMerchantOrderId);
		
		/**
		 * Gets cached Beezup order associated with beezup order id
		 * @param BeezupOMOrderIdentifier $oOrderIdentifier
		 * @return BeezupOMOrderResponse|null
		*/
		public function getCachedBeezupOrderResponse(BeezupOMOrderIdentifier $oOrderIdentifier);
		
	# SYNCHRONIZATION 
		
		/**
		 * @return DateTime $oLastSynchronizationDate
		 */
		public function getLastSynchronizationDate();

		/**
		 * Sets new LastSynchronizationDate
		 * @param DateTime $oLastSynchronizationDate
		 */
		public function updateLastSynchronizationDate(DateTime $oLastSynchronizationDate);

		/**
		 * Returns current (not finished) BeezupOMHarvestClientReporting
		 * @return BeezupOMHarvestClientReporting|null Current synchronization or null
		 */
		public function getCurrentHarvestSynchronization();

		/**
		 * Changes all synchronization with IN_PROGRESS TO TIMEOUT
		 */
		public function purgeSync();
		
	# REPORTING
		
		/**
		 * Saves BeezupOMHarvestClientReporting (using execution_id as key unique). If $sNewExecutionId is given, it also updates execution_id 
		 * @param BeezupOMHarvestClientReporting $oSource
		 * @param string $sNewExecutionId
		 */
		public function saveHarvestClientReporting(BeezupOMHarvestClientReporting $oSource, $sNewExecutionId = null);

		/**
		 * Saves BeezupOMHarvestOrderReporting (using execution_id as key unique). If $sNewExecutionId is given, it also updates execution_id 
		 * @param BeezupOMHarvestOrderReporting $oSource
		 * @param string $sNewExecutionId
		 */
		public function saveHarvestOrderReporting(BeezupOMHarvestOrderReporting $oSource, $sNewExecutionId = null);

	}