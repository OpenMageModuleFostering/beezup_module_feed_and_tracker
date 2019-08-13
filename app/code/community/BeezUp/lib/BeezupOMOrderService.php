<?php
	
	/**
		* Service class
	*/
	class BeezupOMOrderService  {
		
		/**
			* @var BeezupOMRepository
		*/
		protected $oRepository = null;
		
		/**
			* @var BeezupOMServiceClientProxy
		*/
		protected $oClientProxy = null;
		
		protected $bDebugMode = false;
		
		protected $aCache = array();
		
		# MAGIC METHODS
		
		/**
			* @param BeezupOMRepositoryInterface $oRepository
			* @param BeezupOMServiceClientProxy $oProxy
		*/
		public function __construct(BeezupOMRepositoryInterface $oRepository){
			$this
			->setRepository($oRepository)
			->setClientProxy(new BeezupOMServiceClientProxy($oRepository->getCredential()));
		}
		
		# GETTERS AND SETTERS
		
		public function setDebugMode($bDebugMode = true){
			$this->bDebugMode = (bool)$bDebugMode;
			$this->getClientProxy()->setDebugMode($this->isDebugModeActivated());
			$this->getRepository()->setDebugMode($this->isDebugModeActivated());
			
			return $this;
		}
		
		/**
			*
			* @return boolean True if debug is activated
		*/
		public function isDebugModeActivated(){
			return $this->bDebugMode;
		}
		
		/**
			* @return BeezupOMRepository
		*/
		public function getRepository(){
			return $this->oRepository;
		}
		
		/**
			* @param BeezupOMRepositoryInterface $oRepository
			* @return BeezupOMOrderService
		*/
		public function setRepository(BeezupOMRepositoryInterface $oRepository){
			$this->oRepository = $oRepository;
			return $this;
		}
		
		/**
			* @return BeezupOMServiceClientProxy
		*/
		public function getClientProxy(){
			return $this->oClientProxy;
		}
		
		/**
			* @param BeezupOMServiceClientProxy $oClientProxy
			* @return BeezupOMOrderService
		*/
		public function setClientProxy(BeezupOMServiceClientProxy $oClientProxy){
			$this->oClientProxy = $oClientProxy;
			return $this;
		}
		
		# PUBLIC API
		
		public function purgeSync(){
			
			$this->getRepository()->purgeSync();
			
		}
		
		/**
			* Tests if credential is valid
			* @return boolean
		*/
		public function isCredentialValid(){
			$oResult = $this->getClientProxy()->validate();
			return $oResult && $oResult->getHttpStatus() === 204;
		}
		
        /**
			*
			* @param BeezupOMLink $oLink
			* @param unknown_type $aParams
			* @param unknown_type $aData
			* @return boolean
		*/
		public function changeOrder(BeezupOMLink $oLink, $aParams, $aData){
			
			if (!$this->getRepository()->isConfigurationOk() || !$this->isCredentialValid()){
				return array(false, null);
			}
			
			$aPostData = $this->processData($oLink, $aData);
			
			$oResponse = $this->getClientProxy()->changeOrderByLink($oLink, $aParams, $aPostData);
			
			return array(($oResponse && (int)$oResponse->getHttpStatus() === 200) , $oResponse);
		}
		
		/**
			*
			* @param BeezupOMOrderListRequest $oRequest
			* @return boolean
		*/
		public function synchronizeOrders(BeezupOMOrderListRequest $oRequest = null){
			
			if ($oRequest === null){
				$oRequest = $this->createOrderListRequest();
			}
			
			if (!$this->isOrderListRequestValid($oRequest)){
				throw new Exception('BeezUP OM : Invalid request');
			}
			if (!$this->isConfigurationOk()){
				throw new Exception('BeezUP OM : Invalid configuration');
			}
			if (!$this->isCredentialValid()){
				throw new Exception('BeezUP OM : Invalid credentials');
			}
			if ($this->isSynchronizationAlreadyInProgress()){
				throw new Exception('BeezUP OM : Synchronization in progress already');
			}
			
			
			$oLink = null;
			$bHasErrors = false;
			$nOrders = 0;
			
			$oHarvestClientReporting = $this->createHarvestClientReporting($this->getRepository()->getCredential(), $oRequest->getBeginPeriodUtcDate(), $oRequest->getEndPeriodUtcDate());
			try {
				do {
					if ($oLink){
						$oResponse = $this->getClientProxy()->getOrderListByLink($oLink);
						} else {
						$oResponse = $this->getClientProxy()->getOrderList($oRequest);
					}
					
					if ($oResponse && $oResponse->getExecutionId()){
						$this->getRepository()->saveHarvestClientReporting($oHarvestClientReporting, $oResponse->getExecutionId());
						$oHarvestClientReporting->setExecutionId($oResponse->getExecutionId());
					}
					
					if ($oResponse && $oResponse->getResult() ){
						if ($this->pushErrors($oResponse, $oHarvestClientReporting)){
							break;
						}
						// getting response
						$oOrderList = $oResponse->getResult();
						$oPagination = $oOrderList->getPaginationResult();
						if (!$oPagination){
							break;
						}
						if ($oHarvestClientReporting){
    						// on first request only
    						if ($oLink === null){
    							$oHarvestClientReporting
								->setEntriesPerPage($oResponse->getRequest()->getEntriesPerPage())
								->setRemainingPageCount($oPagination->getTotalNumberOfPages())
								->getTotalOrderCount($oPagination->getTotalNumberOfEntries());
							}
    						// on every request
    						$oHarvestClientReporting
							->setLastUpdateUtcDate(new DateTime('now', new DateTimeZone('UTC')))
							->setRemainingPageCount($oHarvestClientReporting->getRemainingPageCount() - 1);
    						// update reporting
    						$this->getRepository()->saveHarvestClientReporting($oHarvestClientReporting, $oResponse->getExecutionId());
						}
						// synchronizing page
						
						$this->synchronizeOrderListPage($oOrderList, $oHarvestClientReporting);
						
						// link to next page or null
						$oLink = $oOrderList->getPaginationResult()->getLinkByRel('next');
						} else {
						$oLink = null;
					}
				} while($oLink);
				
				} catch (Exception $oException){
				$bHasErrors = true;
				if ($oHarvestClientReporting){
					$oHarvestClientReporting->setErrorMessage($oException->getMessage());
				}
			}
			if ($oHarvestClientReporting){
    			// update & save reporting
    			$oHarvestClientReporting
				->setLastUpdateUtcDate(new DateTime('now', new DateTimeZone('UTC')))
				->setProcessingStatus($oHarvestClientReporting->getRemainingPageCount() == 0 ? BeezupOMProcessingStatus::SUCCEED : BeezupOMProcessingStatus::ABORTED);
				
    			$this->getRepository()->saveHarvestClientReporting($oHarvestClientReporting);
			}
			// update synchro date if we fetched at list one page so any error is not global (ie. no connexion errors ... )
			if (!$bHasErrors && $oRequest->getEndPeriodUtcDate()){
				$this->getRepository()->updateLastSynchronizationDate($oRequest->getEndPeriodUtcDate());
			}
			return true;
		}
		
		/**
			*
			* @param unknown_type $oBeezupOrderLinkOrId
		*/
		public function synchronizeOrder($oBeezupOrderLinkOrId){
			$bResult = false;
			$sOperation = '';
			$oHarvestOrderReporting = $this->createHarvestOrderReporting();
			$oHarvestOrderReporting->setProcessingStatus(BeezupOMProcessingStatus::IN_PROGRESS);
			$this->getRepository()->saveHarvestOrderReporting($oHarvestOrderReporting);
			try {
				$oBeezupOMOrderResponse = $this->getOrder($oBeezupOrderLinkOrId);
				if ($oBeezupOMOrderResponse && $oBeezupOMOrderResponse->getResult()){
					$oBeezupOrder = $oBeezupOMOrderResponse->getResult();
					$oHarvestOrderReporting
					->setBeezupOrderUUID($oBeezupOrder->getBeezupOrderUUID())
					->setOrderDetailJson(json_encode($oBeezupOrder->toArray()));
					$this->getRepository()->saveHarvestOrderReporting($oHarvestOrderReporting, $oBeezupOMOrderResponse->getExecutionId());
					$oHarvestOrderReporting->setExecutionId($oBeezupOMOrderResponse->getExecutionId());
					
					$oImportedOrderIdentifier = $this->getImportedOrderIdentifier($oBeezupOrder);
					$oCurrentOrderIdentifier = BeezupOMOrderIdentifier::fromBeezupOrder($oBeezupOrder);
					$bWasIdentifierImported = $this->wasIdentifierImported($oCurrentOrderIdentifier);
					
					if (!$oImportedOrderIdentifier && !$bWasIdentifierImported){                       	 
						// no order associated
						$mResult = $this->getRepository()->createOrder($oBeezupOMOrderResponse);
						if ($mResult){
							try {
								if ($mResult instanceof BeezupOMSetOrderIdValues && $oBeezupOrder->getLinkByRel('setMerchantOrderId')){
									$oSetOrderIdResponse = $this->getClientProxy()
									->setOrderMerchantIdByLink($oBeezupOrder->getLinkByRel('setMerchantOrderId'), $mResult);
									$this->pushErrors($oSetOrderIdResponse, $oHarvestOrderReporting);
									$bResult = true;
									$sOperation = 'create';
								} // if
                        		} catch (Exception $oException){
								$oHarvestOrderReporting->setErrorMessage($oException->getMessage());
							}
                        	
						}
                        } else if (($oImportedOrderIdentifier && $this->isSameIdentifier($oCurrentOrderIdentifier, $oImportedOrderIdentifier) )|| $bWasIdentifierImported){
						$mResult = $this->getRepository()->updateOrder($oBeezupOMOrderResponse);
						if ($mResult){
							try {
								if ($mResult instanceof BeezupOMSetOrderIdValues && $oBeezupOrder->getLinkByRel('setMerchantOrderId')){
									$oSetOrderIdResponse = $this->getClientProxy()
									->setOrderMerchantIdByLink($oBeezupOrder->getLinkByRel('setMerchantOrderId'), $mResult);
									$this->pushErrors($oSetOrderIdResponse, $oHarvestOrderReporting);
									$bResult = true;
									$sOperation = 'update';
								} // if
                        		} catch (Exception $oException){
								$oHarvestOrderReporting->setErrorMessage($oException->getMessage());
							}
							$bResult = true;
						}
						
                        } else {
						$oHarvestOrderReporting->setErrorMessage(sprintf('Order id mismatch %s', $oCurrentOrderIdentifier));
						
					}                        
					$oHarvestOrderReporting->setProcessingStatus($bResult ? BeezupOMProcessingStatus::SUCCEED : BeezupOMProcessingStatus::FAILED);
					$this->getRepository()->saveHarvestOrderReporting($oHarvestOrderReporting);
					} else{
					$oHarvestOrderReporting->setProcessingStatus(BeezupOMProcessingStatus::FAILED);
					$oHarvestOrderReporting->setErrorMessage('Unable to fetch order ' . $oBeezupOrderLinkOrId);
					$this->getRepository()->saveHarvestOrderReporting($oHarvestOrderReporting);
				}
				} catch (Exception $oException) {
				$oHarvestOrderReporting->setErrorMessage($oException->getMessage());
				$oHarvestOrderReporting->setProcessingStatus(BeezupOMProcessingStatus::FAILED);
			} // try
			$this->getRepository()->saveHarvestOrderReporting($oHarvestOrderReporting);
			return array($bResult, $sOperation);
		}
		
		/**
			*
			* @param BeezupOMLink|BeezupOMOrderIdentifier $oOrderLinkOrId
			* @return NULL|Ambigous <NULL, BeezupOMOrderResponse, BeezupOMResponse>
		*/
		public function getOrder($oOrderLinkOrId){
			if ($oOrderLinkOrId instanceof BeezupOMOrderIdentifier){
				$oOrderIdentifier = $oOrderLinkOrId;
				$oOrderRequest = new BeezupOMOrderRequest();
				$oOrderRequest
				->setOrderIdentifier($oOrderIdentifier);
				
				$oCachedOrderResponse = $this->getRepository()->getCachedBeezupOrderResponse($oOrderIdentifier);
				if ($oCachedOrderResponse && $oCachedOrderResponse->getEtag()){
					$oOrderRequest->setETagIfNoneMatch($oCachedOrderResponse->getEtag());
				}
				$oResponse = $this->getClientProxy()->getOrder($oOrderRequest);

				} else if ($oOrderLinkOrId instanceof BeezupOMLink){
				$oResponse = $this->getClientProxy()->getOrderByLink($oOrderLinkOrId);
				$oCachedOrderResponse = null;
				$oOrderIdentifier = null;
				} else {
				return null;
			}
			if ($oResponse && $oResponse->getHttpStatus() === 304){
				if ($oOrderLinkOrId instanceof BeezupOMLink){
					$oOrderIdentifier = BeezupOMOrderIdentifier::fromBeezupOrderLink($oOrderLinkOrId);
					$oCachedOrderResponse = $this->getRepository()->getCachedBeezupOrderResponse($oOrderIdentifier);
				}
				return $oCachedOrderResponse;
			}
			return ($oResponse && count($oResponse->getInfo()->getErrors()) == 0 && $oResponse->getResult()) ? $oResponse : null;
		}
		
		public function getOrderDirect(BeezupOMOrderIdentifier $oOrderIdentifier){
			$oOrderIdentifier = $oOrderLinkOrId;
			$oOrderRequest = new BeezupOMOrderRequest();
			$oOrderRequest
			->setOrderIdentifier($oOrderIdentifier);
			$oResponse = $this->getClientProxy()->getOrder($oOrderRequest);
			return ($oResponse && count($oResponse->getInfo()->getErrors()) == 0 && $oResponse->getResult()) ? $oResponse : null;
		}
		
		public function getStores(){
			$aResult = array();
			$oResponse = $this->getClientProxy()->stores();
			if ($oResponse && $oResponse->getResult()){
				foreach ($oResponse->getResult()->getStores() as $oStore){
					$aResult[$oStore->getBeezupStoreId()] = $oStore->getBeezupStoreName();
				}
			}
			return $aResult;
		}
		
		
		/**
			* @param string $sLOVListName
			* @param string $sCultureName
		* @return array
		*/
		public function getLovValues($sLOVListName, $sCultureName = 'en') {
		$aResult = array();
		$oResult = $this->getLov($sLOVListName, $sCultureName);
		if ($oResult){
		foreach($oResult->getValues() as $oLovValue){
		$aResult[$oLovValue->getCodeIdentifier()] = $oLovValue;
		}
		}
		return $aResult;
		}
		
		public function getLOVValuesForParams(BeezupOMLink $oLink, $bRequired = true){
		$aResult = array();
		foreach ($oLink->getParameters() as $oParam){
		if ($oParam->getLovLink() && (!$bRequired || $oParam->isLovRequired())) {
		$oLovResult = $this->getLOVByLink($oParam->getLovLink());
		$aResult[$oParam->getName()] = $oLovResult ? $oLovResult->toArray() : array();
		} // if
		} // foreach
		return $aResult;
		}
		
		# PAGE SYNC
		
		protected function synchronizeOrderListPage($oOrderList, $oHarvestClientReporting){
		
		foreach ($oOrderList->getOrderHeaders() as $oOrderHeader){
		$oOrderLink = $oOrderHeader->getLinkByRel('self');
		if ($oOrderLink){
		try {
		$this->synchronizeOrder($oOrderLink);
		} catch (Exception $oException){
		if ($oHarvestClientReporting){
		$oHarvestClientReporting->setErrorMessage($oException->getMessage());
		}
		
		}
		} // if
		}// foreach
		
		}
		
		# LOV helper
		
		protected function getLovByLink(BeezupOMLink $oLink) {
		$aAtoms = explode('cultureName=', $oLink->getHref(), 2);
		$sCultureName = isset($aAtoms[1])  	? $aAtoms[1] : '';
		$sCacheKey = sprintf('%s-%s', $oLink->getRel(), $sCultureName);
		if (!isset($this->aCache[$sCacheKey])){
		$oLovResponse = $this->getClientProxy()->getLOVByLink($oLink);
		if ($oLovResponse && $oLovResponse->getResult()){
		$this->aCache[$sCacheKey] = $oLovResponse->getResult();
		}
		}
		return $this->aCache[$sCacheKey];
		}
		
		/**
		* @param string $sLOVListName
		* @param string $sCultureName
		* @return multitype:
		*/
		protected function getLov($sLOVListName, $sCultureName = 'en'){
		$sCacheKey = sprintf('LOV_%s-%s', $sLOVListName, $sCultureName);
		if (!isset($this->aCache[$sCacheKey])){
		$aResult = array();
		$oLovRequest = new BeezupOMLOVRequest();
		$oLovRequest
		->setListName($sLOVListName)
		->setCultureName($sCultureName);
		$oLovResponse = $this->getClientProxy()->getLOV($oLovRequest);
		if ($oLovResponse && $oLovResponse->getResult()){
		$this->aCache[$sCacheKey] = $oLovResponse->getResult();
		}
		}
		return $this->aCache[$sCacheKey];
		}
		
		# VALIDATION FOR CHANGE ORDER
		
		protected function processData(BeezupOMLink $oLink, array $aData = array()){
		$aResult = array();
		if ($oLink){
		foreach ($oLink->getParameters() as $oParam){
		$sName = $oParam->getName();
		if ((!isset($aData[$sName]) || empty($aData[$sName]) && $oParam->isMandatory())){
		die(sprintf('Param %s cannot be empty',$sName));
		}
		if (!$this->validateParam($oParam, $aData[$sName])){
		die(sprintf('Param %s cannot be empty has invalid value %s', $sName,  strval($aData[$sName])));
		}
		if ($oParam->getCSharpType() === 'System.DateTime'){
		$formatDate = date("Y-m-d", strtotime($aData[$sName]));
		$aResult[$sName] = substr($formatDate,0,10) . 'T' . gmdate('H:i:s', time()-300) . '.000Z';
		//	echo $aResult[$sName]."<br>";
		} else {
		$aResult[$sName] = $aData[$sName];
		}
		}
		}
		return $aResult;
		}
		
		protected function validateLOVRequired(BeezupOMExpectedOrderChangeMetaInfo $oParam, $mValue){
		
		if ($oParam->isLovRequired()){
		$aValues = $this->getLOVCodes($oParam);
		if (empty($aValues) || !in_array($mValue, $aValues)){
		return false;
		}
		// @todo smart mapping / exchange as in lengow
		}
		return true;
		
		}
		
		protected function validateParam(BeezupOMExpectedOrderChangeMetaInfo $oParam, $mValue){
		return $this->validateParamType($oParam, $mValue) && $this->validateLOVRequired($oParam, $mValue);
		}
		
		protected function validateParamType(BeezupOMExpectedOrderChangeMetaInfo $oParam, $mValue){
		switch ($oParam->getCSharpType()){
		default:
		case 'System.String':
		if (!is_string($mValue)){
		return false;
		}
		break;
		case 'System.Int32':
		if (preg_match('/^[0-9]+$/', $mValue) !== 1){
		return false;
		}
		break;
		case 'System.Decimal':
		case 'System.Money':
		if (preg_match('/^[0-9]+(,[0-9]{0,3})?$/', $mValue) !== 1){
		return false;
		}
		break;
		case 'System.DateTime':
		if (!strtotime($mValue)){
		return false;
		}
		break;
		}
		return true;
		}
		
		protected function createOrderListRequest(){
		$oResult = new BeezupOMOrderListRequest();
		$oResult
		->setBeginPeriodUtcDate($this->getRepository()->getLastSynchronizationDate());
		return $oResult;
		}
		
		protected function getLOVCodes(BeezupOMExpectedOrderChangeMetaInfo $oParam){
		if ($oParam->isLovRequired()){
		$oLovResponse = $this->getClientProxy()->getLOVByLink($oParam->getLovLink());
		if ($oLovResponse && $oLovResponse->getResult()){
		return $oLovResponse->getResult()->getCodeIdentifiers();
		}
		}
		return array();
		}
		
		protected function pushErrors(BeezupOMResponse $oResponse, BeezupOMHarvestAbstractReporting $oReporting){
		if ($oResponse->getInfo()->getErrors()) {
		foreach($oResponse->getInfo()->getErrors() as $oError){
		// @todo add error
		$oReporting->setErrorMessage($oError->getCode() . ' ' . $oError->getMessage());
		}
		return true;
		}
		return false;
		
		}
		
		# REPORTING HELPERS
		
		/**
		* @return BeezupOMHarvestClientReporting
		*/
		protected function createHarvestClientReporting(BeezupOMCredential $oCredential, DateTime $oBeginPeriodUtcDate, DateTime $oEndPeriodUtcDate, $sExecutionId = null){
		$oResult = $this->initReportingObject(new BeezupOMHarvestClientReporting(), $sExecutionId);
		$oResult
		->setBeginPeriodUtcDate($oBeginPeriodUtcDate)
		->setEndPeriodUtcDate($oBeginPeriodUtcDate)
		->setBeezUPApiToken($oCredential->getBeezupApiToken())
		->setBeezUPUserId($oCredential->getBeezupUserId());
		$this->getRepository()->saveHarvestClientReporting($oResult);
		
		return $oResult;
		}
		
		/**
		* @return BeezupOMHarvestOrderReporting
		*/
		protected function createHarvestOrderReporting($sExecutionId = null){
		$oResult = $this->initReportingObject(new BeezupOMHarvestOrderReporting(), $sExecutionId);
		$this->getRepository()->saveHarvestOrderReporting($oResult);
		return $oResult;
		}
		
		/**
		*
		* @param BeezupOMHarvestAbstractReporting $oHarvestObject
		* @param string $sProcessingStatus
		*/
		protected function initReportingObject(BeezupOMHarvestAbstractReporting $oHarvestObject, $sExecutionId = null){
		if ($sExecutionId === null){
		$sExecutionId = md5(microtime(true) . spl_object_hash($oHarvestObject) . uniqid('bom'));
		}
		return $oHarvestObject
		->setExecutionId($sExecutionId)
		->setCreationUtcDate(new DateTime('now', new DateTimeZone('UTC')))
		->setProcessingStatus(BeezupOMProcessingStatus::IN_PROGRESS);
		}
		
		# CHECKS
		
		protected function isOrderListRequestValid(BeezupOMOrderListRequest $oRequest){
		return $oRequest->getBeginPeriodUtcDate() && $oRequest->getBeginPeriodUtcDate()->getTimestamp();
		}
		
		protected function isConfigurationOk(){
		return $this->getRepository()->isConfigurationOk();
		}
		
		protected function isSynchronizationAlreadyInProgress(){
		return $this->getRepository()->getCurrentHarvestSynchronization() !== null;
		}
		
		/**
		*
		* @param BeezupOMOrderResult $oBeezupOrder
		*/
		protected function getImportedOrderIdentifier(BeezupOMOrderResult $oBeezupOrder){
		return $this->getRepository()->getImportedOrderIdentifier((int)$oBeezupOrder->getOrderMerchantOrderId()) ;
		}
		
		/**
		* Compares two identifiers
		* @todo BeezupOMOrderIdentifier::compare ?
		* @param BeezupOMOrderIdentifier $oOrderIdentifier1
		* @param BeezupOMOrderIdentifier $oOrderIdentifier2
		* @return boolean True if both identifiers are same
		*/
		protected function isSameIdentifier(BeezupOMOrderIdentifier $oOrderIdentifier1, BeezupOMOrderIdentifier $oOrderIdentifier2){
		return ($oOrderIdentifier1->getAccountId() === $oOrderIdentifier2->getAccountId() &&
		$oOrderIdentifier1->getMarketplaceTechnicalCode() === $oOrderIdentifier2->getMarketplaceTechnicalCode() &&
		$oOrderIdentifier1->getBeezupOrderUUID() === $oOrderIdentifier2->getBeezupOrderUUID());
		}
		
		protected function wasIdentifierImported(BeezupOMOrderIdentifier $oOrderIdentifier){
		return $this->getRepository()->wasIdentifierImported($oOrderIdentifier) ;			
		}
		
		}		