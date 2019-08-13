<?php 
	require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."KLogger.php";
	require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."bootstrap.php";
	require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupRepository.php";
	require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupMageOrders.php";
	
	class Beezup_Block_Omstatus extends Mage_core_block_text {
		protected $repository = null;
		protected $oOrderService;
		public $log = null;
		public $log2 = null;
		public $orderid = "";
		public $debug = false;
		private $account_id;
		private $marketplace_code;
		private $beezup_order_id;
		
		
		public function getLoader() {
			$dir = Mage::getModuleDir("etc", "BeezUp");
			$dir = str_replace("etc", "img", $dir);
			header('Content-Type: image/jpeg');
			readfile($dir."/ajax-loader.gif");
			
		}
		
		
		public function getBeezupBuyerAddress($order) {
			$add1=$order->getOrderBuyerAddressLine1();
			$add2=$order->getOrderBuyerAddressLine2();
			$add3=$order->getOrderBuyerAddressLine3();
			$retorno = "";
			if(!empty($add1)) {
				$retorno = $order->getOrderBuyerAddressLine1();
			} 
			if(!empty($add2)) {
				if(empty($add1)) {
					$retorno .= $order->getOrderBuyerAddressLine2();	
					} else {
					$retorno .= " - ". $order->getOrderBuyerAddressLine2();
				}
			} 
			if(!empty($add3)){
				if(empty($add1) && empty($add2)) {
					$retorno .= $order->getOrderBuyerAddressLine3();	
					}  else {
					$retorno .= " - ". $order->getOrderBuyerAddressLine3();
				}
			}	
			return $retorno;
		}
		
		
		public function getBeezupShippingAddress($order) {
			$add1 = $order->getOrderShippingAddressLine1();
			$add2 = $order->getOrderShippingAddressLine2();
			$add3=$order->getOrderBuyerAddressLine3();
			$retorno = "";
			if(!empty($add1 )) {
				$retorno =  $order->getOrderShippingAddressLine1();
			} 
			if(!empty($add2)) {
				if(empty($add1)) {
					$retorno .= $order->getOrderShippingAddressLine2();	
					} else {
					$retorno .= " - ".  $order->getOrderShippingAddressLine2();
				}
			} 
			if(!empty($add3)){
				if(empty($add1) && empty($add2)) {
					$retorno .=  $order->getOrderShippingAddressLine3();	
					} else {
					$retorno .= " - ".  $order->getOrderShippingAddressLine3();
				}
			}	
			return $retorno;
		}
		
		
		public function checkEtagExists($etag) {
			$resource = Mage::getSingleton('core/resource');
			$readConnection = $resource->getConnection('core_read');
			$table = $resource->getTableName('sales/order_grid');
			$query = 'SELECT increment_id FROM ' . $table . ' WHERE beezup_etag = \''
			. $etag . '\' LIMIT 1';	 
			$order = $readConnection->fetchOne($query);
			if($order && !empty($order)) {
				return true;
			}
			return false;
			
		}
		
		public function loadMageOrder() {
			$Mageorder = Mage::getModel('sales/order')->load($this->orderid);
			if ($Mageorder->getId()) {
				return $Mageorder;
				} else {
				//we get order from marketplace orderid
				$orderInc = $this->checkMarketOrderExists($this->orderid);
				if($orderInc) {
					// if exists
					$Mageorder = Mage::getModel('sales/order')->loadByIncrementId($orderInc);
					if ($Mageorder->getId()) {
						return $Mageorder;
					}
				}
			}
			
			
			return false;
		}
		
		
		public function checkMarketOrderExists($orderid) {
			$resource = Mage::getSingleton('core/resource');
			$readConnection = $resource->getConnection('core_read');
			$table = $resource->getTableName('sales/order_grid');
			$query = 'SELECT increment_id FROM ' . $table . ' WHERE beezup_order = 1 and  beezup_market_order_id = \''
			. $orderid . '\' LIMIT 1';	 
			$order = $readConnection->fetchOne($query);
			
			if($order && !empty($order) &&  $this->orderId !== "") {
				return $order;
			}
			return false;
			
		}
		
		public function resynOrder($orderId) {
			
			$this->orderid = $orderId;
			$oBeezupOrderResponse= $this->getBeezupOrderFromMage();
			
			$etag = $oBeezupOrderResponse->getETag();
			
			$final_order = $oBeezupOrderResponse->getResult();	
			$order_status = $final_order->getOrderStatusBeezUPOrderStatus();
			$isPending = $final_order->getIsPendingSynchronization();
			$order_address = $this->getBeezupBuyerAddress($final_order);
			$order_city = $final_order->getOrderBuyerAddressCity();
			$order_region = $final_order->getOrderBuyerStateOrRegion();
			$order_postalCode = $final_order->getOrderBuyerAddressPostalCode();
			$order_customer = $final_order->getOrderBuyerName();
			$order_customer_email = $final_order->getOrderBuyerEmail();
			$order_customer_phone = $final_order->getOrderBuyerPhone();
			$order_customer_mobile = $final_order->getOrderBuyerMobilePhone();
			$order_country = $final_order->getOrderBuyerAddressCountryName();
			$order_country_iso = $final_order->getOrderBuyerAddressCountryIsoCodeAlpha2();
			
			$shipping_city = $final_order->getOrderShippingAddressCity();
			$shipping_country = $final_order->getOrderShippingAddressCountryName();
			$shipping_name = $final_order->getOrderShippingAddressName();
			$shipping_postalCode = $final_order->getOrderShippingAddressPostalCode();
			$shipping_email =  $final_order->getOrderShippingEmail();
			$shipping_phone =  $final_order->getOrderShippingPhone();
			$shipping_mobile = $final_order->getOrderShippingMobilePhone();
			$shipping_company = $final_order->getOrderShippingCompanyName();
			$shipping_region = $final_order->getOrderShippingAddressStateOrRegion();
			$order_currency_code = $final_order->getOrderCurrencyCode();
			//order Info
			$order_totalPrice = $final_order->getOrderTotalPrice();
			$order_shippingPrice = $final_order->getOrderShippingPrice();
			$shipping_address = $this->getBeezupShippingAddress($final_order);
			
			$name_parts = explode(" ", $order_customer);
			$order_first_name = array_shift( $name_parts);
			$order_last_name = implode(" ", $name_parts);
			
			
			$name_parts = explode(" ", $shipping_name);
			$shipping_first_name = array_shift( $name_parts);
			$shipping_last_name = implode(" ", $name_parts);
			
			$etag_exists = $this->checkEtagExists($etag);
			
			if(!$isPending && !$etag_exists ) {
				$order_data = array( 
				"etag" => $etag,
				"order_status" => $order_status,
				"order_address" => $order_adress,
				"order_country" => $order_country,
				"order_country_iso" => $order_country_iso ,
				"order_address" => $order_address ,
				"order_postalCode" => $order_postalCode ,
				"order_customer" => $order_first_name ,
				"order_lastname" => $order_last_name ,
				"order_customer_email" => $order_customer_email ,
				"order_customer_phone" => $this->getPhone($order_customer_phone, $order_customer_mobile) ,
				"shipping_city" => $shipping_city ,
				"shipping_country" => $shipping_country ,
				"shipping_country_iso" => $shipping_country_iso ,
				"shipping_address" => $shipping_address ,
				"shipping_name" => $shipping_first_name ,
				"shipping_lastname" => $shipping_last_name ,
				"shipping_postalCode" => $shipping_postalCode ,
				"shipping_region" =>$shipping_region,
				"shipping_email" => $shipping_email ,
				"shipping_phone" => $this->getPhone($shipping_phone, $shipping_mobile) ,
				"shipping_company" => $shipping_company ,
				"order_totalPrice" => $order_totalPrice ,
				"order_shippingPrice" => $order_shippingPrice ,
				"order_city" => $order_city,
				"order_region" => $order_region,
				);
				
				
				$Mageorder = $this->loadMageOrder();
				if ($Mageorder) {
					//if order exists
					$this->updateEtag($etag);					
					$this->updateBilling($Mageorder, $order_data );
					$this->updateBeezupInfoTab($Mageorder, $final_order, $order_data);
					$status1 = $Mageorder->getStatusLabel();
					$status = $this->getStatus($status1);
					if($status !==  $order_status) {		
						//if order exits and status has changed we update order status
						$this->setStatus( $order_status, $Mageorder);
					}
					
					$id_order = $Mageorder->getId();
					$BeezupMageOrder = new BeezupMageOrders($id_order);
					$BeezupMageOrder->setData(array("shipping" =>(float) $order_data['order_shippingPrice']));		
					$BeezupMageOrder->updateShippingInfo();				
					
					echo 1;
					}  else {
					
					echo 2;
				}
				
				
			}
			else {
				if($isPending) {
					echo 3;
					} else {
					echo 2;
					
				}
			}
			
		}
		
		
		
		public function getStatus($status1) {
			$helper = Mage::helper('beezup');
			$retorno = "";
			$status = strtolower($status1);
			
			if($status == strtolower($helper->getConfig('beezup/marketplace/status_new') )) {
				$retorno =  "New";
				
				} elseif($status == strtolower($helper->getConfig('beezup/marketplace/status_progress') ) ){
				$retorno =  "InProgress";
				
				
			}
			elseif($status == strtolower($helper->getConfig('beezup/marketplace/status_aborted') )) {
				
				$retorno =  "Aborted" ;		
			}
			elseif($status == strtolower($helper->getConfig('beezup/marketplace/status_closed') )) {
				$retorno = "Closed";
				
			}
			elseif($status == strtolower($helper->getConfig('beezup/marketplace/status_cancelled')) ) {
				
				$retorno =  "Canceled";
			}
			elseif($status == strtolower($helper->getConfig('beezup/marketplace/status_shipped') )) {
				
				$retorno =  "Shipped";
			}
			
			
			return $retorno;
			
		}
		
		public function setStatus($status, $order) {
			$helper = Mage::helper('beezup');
			$retorno = "";
			$blnCancel = false;
			$blnHold = false;
			switch($status) {
				case "New" :
				$retorno =  $helper->getConfig('beezup/marketplace/status_new');
				break;
				case "InProgress" :
				$retorno =  $helper->getConfig('beezup/marketplace/status_progress');
				$this->payOrder($order);
				break;
				case "Aborted" :
				$retorno =  $helper->getConfig('beezup/marketplace/status_aborted');
				$blnHold = true;
				
				
				break;
				case "Closed" :
				$blnCancel =true;
				$retorno =  $helper->getConfig('beezup/marketplace/status_closed');
				$this->payOrder($order);
				break;
				case "Canceled" :
				$retorno =  $helper->getConfig('beezup/marketplace/status_cancelled');
				
				break;
				case "Shipped" :
				$retorno =  $helper->getConfig('beezup/marketplace/status_shipped');
				$this->payOrder($order);
				break;
				
			}
			$order->setData('state',$retorno);
			$order->setStatus($retorno);       
			$history = $order->addStatusHistoryComment('Order was set to '.$retorno.' by Beezup.', false);
			$history->setIsCustomerNotified(false);
			$order->save();
			if($blnCancel) {
				$order->cancel()->save();
			}
			if($blnHold) {
				$order->hold()->save();
			}
			
			return $retorno;
			
		}
		public function getPhone($phone, $phone2) {
			$retorno = "";
			if(!empty($phone) && $phone !== "") {
				$retorno .= $phone;
			}
			if(!empty($phone2) && $phone2 !=="") {
				if(empty($phone) || $phone == "") {
					$retorno = $phone2;
					
					} else {
					$retorno .= " - ".$phone2;
				}
			}
			return $retorno;
		}
		
		public function payOrder($order) {
			try {
				
				if($order->canInvoice()) {
					$invoice = $order->prepareInvoice()
					->setTransactionId($order->getId())
					->addComment("Invoice created from Beezup.")
					->register()
					->pay();
					$transaction_save = Mage::getModel('core/resource_transaction')
					->addObject($invoice)
					->addObject($invoice->getOrder());
					$transaction_save->save();
					
				}
			}
			catch(Exception $e){
				
			}
		}
		
		
		public function updateBeezupInfoTab($order, $oLink, $data) {
			$beezup_last_modification_date = $oLink->getOrderLastModificationUtcDate();
			$beezup_last_modification_date = $beezup_last_modification_date->date;	
			$beezup_marketplace_last_modification_date = $oLink->getOrderMarketPlaceLastModificationUtcDate();
			$beezup_marketplace_last_modification_date = 	$beezup_marketplace_last_modification_date->date;	
			$beezup_comission = $oLink->getOrderTotalCommission()." ".$data['order_currency'];
			$tot_comm = $oLink->getOrderTotalCommission();
			if(empty($tot_comm  ) ||  $tot_comm == 0) {
				$beezup_comission = 0;
			}
			$updateData = array("beezup_status" => $data['order_status'],
			"beezup_last_modification_date" =>  $beezup_last_modification_date,
			"beezup_marketplace_last_modification_date" => $beezup_marketplace_last_modification_date ,
			"beezup_total_paid" => $oLink->getOrderTotalPrice()." ".$data['order_currency'],
			"beezup_comission" => $beezup_comission,
			"beezup_marketplace_status" => $oLink->getOrderStatusMarketPlaceStatus());
			$orderId = $order->getId();
			$beezupMageOrder = new BeezupMageOrders($orderId);
			$beezupMageOrder->setData($updateData);
			$beezupMageOrder->updateBeezupInfo();
			
			
		}
		
		
		
		public function updateBilling($order, $data) {
			
			
			$addressData = array(
			'billing_firstname' => ($data['order_customer']) ? $data['order_customer'] : "empty",
			'billing_lastname' => ($data['order_lastname']) ? $data['order_lastname'] : "empty",
			'billing_street' => ($data['order_address']) ? $data['order_address'] : "empty",
			'billing_city' => ($data['order_city']) ? $data['order_city'] : "empty",
			'billing_postcode' => ($data['order_postalCode']) ? $data['order_postalCode'] : "empty",
			'billing_telephone' => ($data['order_customer_phone']) ? $data['order_customer_phone'] : "empty",
			'billing_country_id' => ($data['order_country_iso']) ? $data['order_country_iso'] : "empty",
			'billing_region_id' => ($data['order_region ']) ?  substr($data['order_region '], 0,2) : "EM",
	        'shipping_firstname' => ($data['shipping_name']) ? $data['shipping_name'] : "empty",
			'shipping_lastname' => ($data['shipping_lastname']) ? $data['shipping_lastname'] : "empty",
			'shipping_street' =>  ($data['shipping_address']) ? $data['shipping_address'] : "empty",
			'shipping_city' =>  ($data['shipping_city']) ? $data['shipping_city'] : "empty",
			'shipping_postcode' =>  ($data['shipping_postalCode']) ? $data['shipping_postalCode'] : "empty",
			'shipping_telephone' => ($data['shipping_phone']) ? $data['shipping_phone'] : "empty",
			'shipping_country_id' => ($data['shipping_country_iso']) ? $data['shipping_country_iso'] : "empty",
			'shipping_region_id' => ($data['shipping_region']) ? substr($data['shipping_region'], 0, 2) : "EM"  // id from directory_country_region table
			);
			
			$shippingData = array(
			
			);
			// Get the id of the orders shipping address
			$orderId = $order->getId();
			$beezupMageOrder = new BeezupMageOrders($orderId);
			$beezupMageOrder->setData($addressData);
			$beezupMageOrder->updateAdresses();
		}
		
		
		
		
		public function updateEtag($etag) {
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
			$table = $resource->getTableName('sales/order_grid');
			$query = "UPDATE {$table} SET beezup_etag  = '{$etag}' where entity_id = '{$this->orderid}' ";
			$writeConnection->query($query);	
			
		}
		
		
		public function changeOrder($aData) {
			
			$errmsg = "";
			$this->orderid = $aData['order_id'];
			$aResult = array('errors' => array(), 'warnings' => array(), 'infos' => array(),'successes' => array());
			
			if (!isset($aData['order_id']) || !is_numeric($aData['order_id'])){
				$errmsg .= "ERROR: ".Mage::helper('beezup')->__('Invalid order id')."<br>";
				echo $errmsg;
			}
			
			$oBeezupOrderResponse= $this->getBeezupOrderFromMage();
			$oBeezupOrder = $oBeezupOrderResponse->getResult();
			
			if (!$oBeezupOrder){
				$errmsg .="ERROR: ". Mage::helper('beezup')->__('Unable load BeezUP order')."<br>";
				
				echo $errmsg;
			}
			
			if (!isset($aData['action_id'])){
				$errmsg .= "ERROR: ".Mage::helper('beezup')->__('No action id')."<br>";
				
				echo $errmsg;
			}
			
			$oLink = $oBeezupOrder->getTransitionLinkByRel($aData['action_id']);
			
			if (!$oLink){
				
				$errmsg .= "ERROR: ".Mage::helper('beezup')->__('Invalid action')."<br>";
			}
			$aParams = array(
			'TestMode' => $this->isTestModeActivated() ? 1 : 0,
			'userName' => $aData['adminUser']
			);
			
			if ($oLink){
				
				
				list($bResult, $oResult) = $this->getOrderService()->changeOrder($oLink, $aParams, $aData);
				
				if ($bResult){
					$errmsg .= "SUCCESS: ". Mage::helper('beezup')->__('Order update well executed and is currently resyncing')."<br>";
					$aResult['aResult'] = true;
					$oCachedOrder = $this->getBeezupOrderFromMage();
					
					/**
						* @var BeezupOMOrderResult
					*/			
					$oBeezupOrderResult =  $oCachedOrder->getResult();
					
					$oBeezupOrderResult->setIsPendingSynchronization(true);
					
					} else {
					// how to know what happened?
					
					
					if ($oResult && $oResult->getInfo()){
						
						
						foreach ($oResult->getInfo()->getErrors() as $oError){
							// ie we have 404 because of bad query params, we don't need to display those 404
							if ($oError->getMessage() === 'HTTP Error' && !empty($aResult['errors'])){
								continue;
							}
							$errmsg .= "ERROR: ".$oError->getCode() .' : ' . $oError->getMessage()."<br>";
						}
						} else {
						$errmsg .= "ERROR: ". Mage::helper('beezup')->__('Unable to update')."<br>";
					}
				}
			}
			
			
			
			echo $errmsg;
			
			
		}
		
		public function isTestModeActivated() {
			return false;
		}
		
		//function to get beezup info list
		public function getInfo($order_id) {
			$this->orderid = $order_id;
			try {
				$order = $this->getBeezupOrderFromMage();
				if ($order &&  $order->getResult()) {
					$order_result = $order->getResult();
					$beezup_infos =  $order? $order->getInfo()->getInformations() : array();
					$order_actions = $this->getOrderActions($order_result );
					/*
						echo "<pre>";
						print_r($order_actions);
						
						echo "</pre>";
					*/
					echo "<label>Status</label><br>";
					echo "<select id='status_value'>";
					foreach($order_actions as $action) {
						
						echo "<option value='".$action['id']."'>".$action['translated_name']."</option>";
						echo "<input type='hidden' id='lovs_".$action['id']."' value='".$action['lovs']."' />";
					}
					
					echo "</select>";
					echo "<button class='button' onclick='changeStatus();'>Change Status</button>";
					
					echo '			<div id="closed"></div>
					
					<div class="popup-wrapper" id="popup">
					<div class="popup-container"><!-- Popup Contents, just modify with your own -->
					
					<h2>Update Order Status</h2>
					<hr>
					<div class="input-group"><div id="contenido-form"></div>';
					
					
					echo '<br><button class="button">Update</button>
					</div>
					
					<a class="popup-close"   href="#closed">X</a>
					</div>
					</div>
					';
					
					
				}
				} catch (Exception $ex ) {
				die($ex->getMessage());
			}
			
			
		}
		
		
		
		
		
		public function updateOrder($order_id) {
			$this->orderid = $order_id;
			
		}
		
		
		public function getBeezupOrderFromMage() {
			$order = $this->getMageOrder();
			if($order) {
				$oOrderIdentifier = $this->getBeezupOrderId();
				$oBeezupOMOrderResponse = $this->getOrderService()->getOrder($oOrderIdentifier);
				if ($oBeezupOMOrderResponse && $oBeezupOMOrderResponse->getResult()){
					return $oBeezupOMOrderResponse;
				}
				
			}
			return false;
		}
		
		
		public function getMageOrder() {
			$resource = Mage::getSingleton('core/resource');
			$readConnection = $resource->getConnection('core_read');
			$table = $resource->getTableName('sales/order_grid');
			$query = 'SELECT * FROM ' . $table . ' WHERE entity_id = \''
			. $this->orderid  . '\' ';	 
			$order = $readConnection->fetchAll($query);
			if(!empty($order)) {
				$order = $order[0];
				$this->account_id= $order['beezup_name'];
				$this->marketplace_code = $order['beezup_marketplace'];
				$this->beezup_order_id = $order['beezup_order_id'];
				return $order;
			}
			return false;
		}
		
		
		public function getBeezupOrderId(){
			$oIdentifier = new BeezupOMOrderIdentifier();
			$oIdentifier
			->setAccountId($this->account_id)
			->setMarketplaceTechnicalCode($this->marketplace_code)
			->setBeezupOrderUUID($this->beezup_order_id);
			return $oIdentifier;
		}
		
		
		/**
			* @return BeezupOMOrderService
		*/
		public function getOrderService(){
			if ($this->oOrderService === null){
				$this->oOrderService = $this->createOrderService();
				// enchufamos debug mode, esta activado? false true			$this->oOrderService->setDebugMode(false);
			}
			return $this->oOrderService;
		}
		
        /**
			* @return BeezupOMOrderService
		*/
		protected function createOrderService(){
			
			return new BeezupOMOrderService($this->createRepository() );
		}
		
		protected function createRepository() {
			if ($this->repository == null) {
				$this->repository = new BeezupRepository();
			} 
			return $this->repository;
			
		}
		
		
		/**
			* Returns disponible order actions
			* @param unknown_type $oBeezupOrder
			* @param unknown_type $oPsOrder
			* @return multitype:|multitype:multitype:string NULL unknown
		*/
		public function getOrderActions($oBeezupOrder = null,  $oPsOrder = null){
	    	$aResult = array();
			if (!$oBeezupOrder || !($oBeezupOrder instanceof BeezupOMOrderResult)) {
				return $aResult;
			}
			
			//	$aLovValues =  $this->getOrderService()->getLovValues('OrderChangeBusinessOperationType', Context::getContext()->language->iso_code);
			
			foreach ($oBeezupOrder->getTransitionLinks() as $oLink){
				
				$aResult[] = array(
				'link' 				=> $oLink,
				'href' 				=> $oLink->getHref(),
				'id' 				=> $oLink->getRel(),
				'name' 				=> $oLink->getRel(),
				'translated_name'	=> $oLink->getRel(),
				'fields'			=> json_encode($oLink->toArray()),
				'lovs'				=> json_encode($this->getOrderService()->getLOVValuesForParams($oLink))
				/*,
					'values'			=> json_encode($this->getFieldsValues($oLink, $oPsOrder)),
				'info'				=> json_encode($this->getTransitionLinkInfo($oLink))*/
				);
				/*
					echo "<pre>";
					print_r(json_decode($aResult[0]['lovs']));
					echo "</pre>";
					
					echo "<pre>";
					print_r(json_decode($aResult[0]['fields']));
					echo "</pre>";
				*/
			}
			
			return $aResult;
		}
		
	}	