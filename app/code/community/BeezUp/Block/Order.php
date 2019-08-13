<?php
require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."KLogger.php";
require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."bootstrap.php";
require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupRepository.php";
require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupMageOrders.php";

class Beezup_Block_Order extends Mage_core_block_text {

	protected $repository = null;
	protected $oOrderService;
	public $log = null;
	public $log2 = null;
	public $orderid = "";
	public $debug = false;
	public $blnCreateCustomer = false;
	private $account_id;
	private $marketplace_code;
	private $beezup_order_id;
	private $mage_order_id = false;
	private $marketChannelFilters = array();

	private function makeDir() {

		if (file_exists(Mage::getBaseDir('base').'/beezup/tmp'))  { return true;}

		if (!mkdir(Mage::getBaseDir('base').'/beezup/tmp', 0777, true))
		{
			echo "[ERROR] : Seems we can't create 'beezup' directory inside your root directory."."<br/>"
			."You can try one of these solutions :"."<br/>"
			."1 - Create by yourself the beezup/tmp inside your root directory with 777 permissions"."<br/>"
			."2 - Change the permissions on your root directory (777)"."<br/>"
			."3 - Change the 'cache delay' option to 'None' inside beezup plugin settings"."<br/>";
			return false;
		}
		return true;

	}
	public function createOrderFromLink($account_id, $marketplace_code, $beezup_order_id) {
		$this->makeDir();
		$logDir =  Mage::getBaseDir('base').'/beezup/';
		if(file_exists($logDir."log2.txt")) {
			if(filesize($logDir."/log2.txt") >=3000000) {
				unlink($logDir."log2.txt");
			}
		}
		$sync_end_date = new DateTime ( 'now', new DateTimeZone ( 'UTC' ));
		$helper = Mage::helper('beezup');

		$marketChannelFilters = $helper->getConfig('beezup/marketplace/market_channel_filters');
		$this->marketChannelFilters = explode(",", $marketChannelFilters);
		$sync_status = $helper->getConfig('beezup/marketplace/sync_status');
		$debug_mode =  $helper->getConfig('beezup/marketplace/debug_mode');
		$create_customer = $helper->getConfig("beezup/marketplace/create_customers");
		if($create_customer == 0) {
			$this->blnCreateCustomer = true;
		}

		if($debug_mode==1) {
			$this->debug = true;
		}
		if($sync_status!==1) {
			$configModel = Mage::getModel('core/config');
			$configModel->saveConfig('beezup/marketplace/sync_status',1);



			unlink($logDir."log.txt");
			$this->log = new KLogger ( $logDir."log.txt" , KLogger::DEBUG );
			$this->log2 = new KLogger ( $logDir."log2.txt" , KLogger::DEBUG );
			$this->debugLog("Initializing OM Importation");
			$this->account_id = $account_id;
			$this->marketplace_code = $marketplace_code;
			$this->beezup_order_id = $beezup_order_id;
			$orderResponse = $this->getBeezupOrder();
			if($orderResponse) {


				$this->createOrder($orderResponse);

				$configModel->saveConfig('beezup/marketplace/sync_status',0);
				if($this->mage_order_id) {
					echo "<script>window.location='".Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=> $this->mage_order_id))."';</script>";
				}
					//	die("Order Importation finalized");

			}

		}
		$configModel->saveConfig('beezup/marketplace/sync_status',0);
			//die("Error, Order data incorrect");
		echo $this->_showLog();
	}

	public function _showLog() {
		$logDir =  Mage::getBaseDir('base').'/beezup/';
			//	$log1 = file_get_contents();
		$ret = array();
		if (file_exists($logDir."/log.txt")) {
			$f = fopen($logDir."/log.txt", 'r');

			if ($f) {
				while (!feof($f)) {
					$ret[] = fgetcsv($f, 0, '|');
				}
				fclose($f);
			}
		}
		array_slice(array_reverse($ret), 1, 10);

		return $this->_getTable($ret);

	}


	public function _getTable($data) {
		$url = Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_WEB, true );
		$html = "<td></td><td></td><tr></tr></tbody></table>

		<div class='grid' style='  height: 600px;overflow-y: scroll;padding: 16px;border: 3px solid #e6e6e6;' id='marketPlaceLogBlock'>";
			$html .= '<p>'. Mage::helper('beezup')->__('For full logs see here:').' <a href="'.$url .'beezup/log/load" target="_blank">'.$url .'beezup/log/load</a></p>';
			$html .= "<table class='data' style='margin-top:0px;width:100%;'>";
			$html .= "<tr class='headings'>";
			$html .= '<th><span class="nobr">Time</span></th>';
			$html .= '<th><span class="nobr">Type</span></th>';
			$html .= '<th><span class="nobr">Order Id</span></th>';
			$html .= '<th><span class="nobr">Message</span></th>';
			$html .= "</tr>";
			$html .= "<tbody>";
			foreach($data as $d) {
				$background = "  background: rgb(240, 184, 184)";
				if($d[1] == " INFO " ) {
					$background = "  background: rgb(210, 227, 253)";
				}
				$orderId = (isset($d[3])) ? $d[2] : "";
				$message = (isset($d[3])) ? $d[3] : $d[2];
				$html .= "<tr class='even pointer' style='".$background."'>";
				$html .= "<td>".$d[0]."</td>";
				$html .= "<td>".$d[1]."</td>";
				$html .= "<td>".$orderId."</td>";
				$html .= "<td>".$message."</td>";
				$html .= "</tr>";

			}

			$html .= "<tbody>";
			$html .= '</table>';
			$html .= "</div>";

			return $html;
		}



		public function executeCron() {

			$this->makeDir();

			set_time_limit(0);
			$logDir =  Mage::getBaseDir('base').'/beezup/';
			if(file_exists($logDir."log2.txt")) {
				if(filesize($logDir."/log2.txt") >=3000000) {
					unlink($logDir."log2.txt");
				}
			}

			$sync_end_date = new DateTime ( 'now', new DateTimeZone ( 'UTC' ));
			$helper = Mage::helper('beezup');
			$marketChannelFilters = $helper->getConfig('beezup/marketplace/market_channel_filters');
			$this->marketChannelFilters = explode(",", $marketChannelFilters);
			$sync_status = $helper->getConfig('beezup/marketplace/sync_status');
			$debug_mode =  $helper->getConfig('beezup/marketplace/debug_mode');
			$create_customer = $helper->getConfig("beezup/marketplace/create_customers");
			if($create_customer == 0) {
				$this->blnCreateCustomer = true;
			}

			if($debug_mode==1) {
				$this->debug = true;
			}

			if($sync_status!==1) {
				$configModel = Mage::getModel('core/config');
				$configModel->saveConfig('beezup/marketplace/sync_status',1);



				unlink($logDir."log.txt");
				$this->log = new KLogger ( $logDir."log.txt" , KLogger::DEBUG );
				$this->log2 = new KLogger ( $logDir."log2.txt" , KLogger::DEBUG );
				$this->debugLog("Initializing OM Importation");


				$this->getOrderList();

				$this->repository->updateLastSynchronizationDate( $sync_end_date);
				$this->orderid = "";
				$this->debugLog("OM Importation finalized succesfully");
				$configModel->saveConfig('beezup/marketplace/sync_status',0);
				echo "OM Importation finalized succesfully";

			} else {

				echo "Order Importation is already being executed";
			}

		}


		public function getLog() {
			$logDir =  Mage::getBaseDir('base').'/beezup/';
			$log1 = file_get_contents($logDir."/log2.txt");

			echo "<pre>";
			print_r($log1);
			echo "</pre>";

		}
		public function getBeezupOrder() {

			$oOrderIdentifier = $this->getBeezupOrderId();
			$oBeezupOMOrderResponse = $this->getOrderService()->getOrder($oOrderIdentifier);
			if ($oBeezupOMOrderResponse && $oBeezupOMOrderResponse->getResult()){
				return $oBeezupOMOrderResponse;
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


		public function createOrder($oBeezupOrderResponse) {

			$etag = $oBeezupOrderResponse->getETag();
			$final_order = $oBeezupOrderResponse->getResult();

			$orderid = $final_order->getOrderMarketPlaceOrderId();
			$this->orderid = $orderid;
			//customer Info
			$order_address = $final_order->getOrderBuyerAddressCity();
			$order_country = $final_order->getOrderBuyerAddressCountryName();
			$order_country_iso = $final_order->getOrderBuyerAddressCountryIsoCodeAlpha2();
			$order_address = $this->getBeezupBuyerAddress($final_order);
			$order_postalCode = $final_order->getOrderBuyerAddressPostalCode();
			$order_customer = $final_order->getOrderBuyerName();
			$order_customer_email = $final_order->getOrderBuyerEmail();
			$order_customer_phone = $final_order->getOrderBuyerPhone();
			$order_customer_mobile = $final_order->getOrderBuyerMobilePhone();
			$order_comment = $final_order->getOrderComment();
			$order_company = $final_order->getOrderBuyerCompanyName();
			$order_city = $final_order->getOrderBuyerAddressCity();
			$order_region = $final_order->getOrderBuyerStateOrRegion();
			$order_status = $final_order->getOrderStatusBeezUPOrderStatus();
			//shipping information
			$shipping_city = $final_order->getOrderShippingAddressCity();
			$shipping_country = $final_order->getOrderShippingAddressCountryName();
			$shipping_country_iso = $final_order->getOrderShippingAddressCountryIsoCodeAlpha2();
			$shipping_address = $this->getBeezupShippingAddress($final_order);
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

			$marketChannel = $final_order->getOrderMarketplaceChannel();
			$resetStock = false;
			foreach($this->marketChannelFilters as $filter) {
					if($filter == $marketChannel) {
						$resetStock = true;
					}
			}
			$name_parts = explode(" ", $order_customer);
			$order_first_name = array_shift( $name_parts);
			$order_last_name = implode(" ", $name_parts);


			$name_parts = explode(" ", $shipping_name);
			$shipping_first_name = array_shift( $name_parts);
			$shipping_last_name = implode(" ", $name_parts);


			//marketplace information
			$marketplace_business_code = $final_order->getMarketPlaceBusinessCode();
			$marketplace = $final_order->getMarketPlaceTechnicalCode();

			//productInfo

			$mage_productIds = $this->prescanOrder($final_order);


			if(!$this->checkEtagExists($etag)) {
				if(empty($order_customer_email)) {
					$order_customer_email = $this->generateEmail($final_order);
				}
				elseif(!filter_var($order_customer_email, FILTER_VALIDATE_EMAIL)) {
					$order_customer_email = $this->generateEmail($final_order);
				}
				if($order_country_iso == "FX" || $order_country_iso == "fx") {
					$order_country_iso = "FR";
				}
				if($shipping_country_iso == "FX" || $shipping_country_iso == "fx") {
					$shipping_country_iso = "FR";
				}
				$mage_productIds = $this->prescanOrder($final_order);
				if($mage_productIds) {
					$order_data = array(
						"resetStock" => $resetStock,
						"etag" => $etag,
						"account_id" => $account_id,
						"order_status" => $order_status,
						"products" => $mage_productIds['products'],
						"storeid" => $mage_productIds['store'],
						"order_currency" => $order_currency_code ,
						"order_address" => $order_adress,
						"order_country" => $order_country,
						"order_country_iso" => $order_country_iso ,
						"order_address" => $order_address ,
						"order_postalCode" => $order_postalCode ,
						"order_customer" => $order_first_name ,
						"order_lastname" => $order_last_name ,
						"order_customer_email" => $order_customer_email ,
						"order_customer_phone" => $this->getPhone($order_customer_phone, $order_customer_mobile) ,
						"order_comment" => $order_comment ,
						"order_company" => $order_company ,
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
						"marketplace" => $marketplace,
						"discounts" => $mage_productIds['discounts'],
						"marketplace_business_code" => $marketplace_business_code
						);



					//check if order exists
					$Mageorder = $this->loadMageOrder();
					if ($Mageorder) {
						//if order exists
						$this->updateEtag($etag);
						$this->updateBilling($Mageorder, $order_data );
						$this->updateBeezupInfoTab($Mageorder, $final_order, $order_data);
						$this->debugLog("Order Already exists Mage Order ID: " .$Mageorder->getId());
						$status1 = $Mageorder->getStatusLabel();
						$status = $this->getStatus($status1);
						if($status !==  $order_status) {
							//if order exits and status has changed we update order status
							$this->debugLog("Updating Order Status from: ".$status1." to: ".$order_status );
							$this->setStatus( $order_status, $Mageorder);
						}

						$id_order = $Mageorder->getId();
						$BeezupMageOrder = new BeezupMageOrders($id_order);
						$BeezupMageOrder->setData(array("shipping" =>(float) $order_data['order_shippingPrice']));
						$BeezupMageOrder->updateShippingInfo();
					} else {
						//if not we create order


						$this->debugLog("Generating Order");
						$this->addOrder($order_data,$final_order );
						//die();

					}


				} else {
					//order could not be imported


				}


			} else {
				//etag has not changed
				$this->debugLog("Order Etag has not changed");

			}





		}



		public  function generateEmail(BeezupOMOrderResult $oBeezupOrder)
		{
			$sRawValue = $oBeezupOrder->getBeezupOrderUUID ();
			$sFakeDomain = preg_replace ( '/\W/', '', $oBeezupOrder->getMarketPlaceTechnicalCode () ) . '.com';
			return 'fakeemail' . md5 ( $sFakeDomain . $sRawValue ) . '@' . strtolower ( $sFakeDomain );
		}



		public function getOrderList($orderList = null) {
			if($orderList == null) {
				$data = $this->createRepository()->createOrderListRequest();
				$oRequest = $this->getOrderService()->getClientProxy()->getOrderList($data);
				$orderList = $oRequest->getResult();
			}
			$oPagination = $orderList->getPaginationResult();
			if(!empty($oPagination)) {
				$oLinksTotal = $oPagination->getLinks();
			} else {
				$configModel = Mage::getModel('core/config');
				$configModel->saveConfig('beezup/marketplace/sync_status',0);
				die("No more orders to import");
			}

			//$header = $orderList->getOrderHeaders();
			foreach($orderList->getOrderHeaders() as $order) {
				$order_status = $order->getBeezupOrderState();
				$orderLinks = $order->getLinks();
				$etag = $order->getETag();
				$beezup_order_id = $order->getBeezupOrderUUID();
				$account_id = $order->getAccountId();
				$orderdata = $this->getOrderService()->getClientProxy()->getOrderByLink($orderLinks[0]);
				$this->debugLog("Initializing Order - Link: ".$orderLinks[0]->getHref());
				$this->createOrder($orderdata);

			}

			if(!empty($oLinksTotal)) {
				//we check if there is next link and get next orders
				foreach($oLinksTotal as $link) {
					if( $link->getRel() == "next") {

						$this->log->LogInfo("Initializing New Order List ->". $link->getHref());
						$this->log2->LogInfo("Initializing New Order List ->". $link->getHref());
						$oRequest = $this->getOrderService()->getClientProxy()->getOrderListByLink($link);
						$orderList = $oRequest->getResult();
						$this->getOrderList($orderList);
					}

				}
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


		public function updateEtag($etag) {
			$this->debugLog("Updating Etag");
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
			$table = $resource->getTableName('sales/order_grid');
			$query = "UPDATE {$table} SET beezup_etag  = '{$etag}' where beezup_market_order_id = '{$this->orderid}' ";
			$writeConnection->query($query);

		}



		public function updateBilling($order, $data) {


			$addressData = array(
				'billing_company' => ($data['order_company']) ? $data['order_company'] : "",
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
				'shipping_company' => ($data['shipping_company']) ? $data['shipping_company'] : "",
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

		public function updateAddresses($shippingData, $address) {
			if($shippingData['firstname'] !==$address['firstname']) {
				$address->setFirstname($shippingData['firstname']);
			}
			if($shippingData['lastname'] !==$address['lastname']) {
				$address->setLastname($shippingData['lastname']);
			}
			if($shippingData['street'] !==$address['street']) {
				$address->setStreet($shippingData['street']);
			}
			if($shippingData['city'] !==$address['city']) {
				$address->setCity($shippingData['city']);
			}
			if($shippingData['postcode'] !==$address['postcode']) {
				$address->setPostcode($shippingData['postcode']);
			}
			if($shippingData['telephone'] !==$address['telephone']) {
				$address->setTelephone($shippingData['telephone']);
			}
			$address->save();


		}


		public function loadMageOrder() {
			$Mageorder = Mage::getModel('sales/order')->loadByIncrementId($this->orderid);
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


		private function debugLog($message) {

			if($this->orderid !== "") {
				$message = $this->orderid." | ".$message;
			}
			$this->log->LogInfo($message);

			$this->log2->LogInfo($message);
		}

		public function prescanOrder(BeezupOMOrderResult $order) {
			$retorno = array();
			$orderItems = $order->getOrderItems();
			foreach ($orderItems as $item)
			{

				if ($item->getOrderItemOrderItemType () !== 'Product')
				{
					//	continue;
				}
				$beezup_store = $item->getOrderItemBeezUPStoreId(); //beezup storeid
				$mage_storeid = $this->checkOrderStore($beezup_store); //magento storeid
				$marketplace_orderid = $item->getOrderItemMarketPlaceProductId();
				if(!$mage_storeid) {

					if(strpos($marketplace_orderid,'INTERETBCA') !== false  || strpos($marketplace_orderid,'interetbca') !== false) { }
						else {
							$this->log->LogError($this->orderid." | No mapping for store ".$beezup_store);
							$this->log2->LogError($this->orderid." | No mapping for store ".$beezup_store);
							return false;
						}

					}
				//	$retorno['store'] = 1;

					$product_ImportedMerchantId = $item->getOrderItemMerchantImportedProductId();
					$product_MerchantId = $item->getOrderItemMerchantProductId();

					$product_quantity = $item->getOrderItemQuantity();
					$product_price = $item->getOrderItemItemPrice();
					$product_title = $item->getOrderItemTitle();
					$product_image = $item->getOrderItemImageUrl();
					if(strpos($marketplace_orderid,'INTERETBCA') !== false  || strpos($marketplace_orderid,'interetbca') !== false) {
						$retorno['discounts']= $item->getOrderItemTotalPrice();
					} else {
						$retorno['store'] = $mage_storeid;
						$this->debugLog("Store Matching succesful, Beezup Store: ".$beezup_store." , Magento Store Id: ".$mage_storeid);
						$product = $this->getMageProduct($product_ImportedMerchantId , $product_MerchantId , $beezup_store );
						if($product) {
							$mage_productId = $product->getId();
							$stocklevel = (int)Mage::getModel('cataloginventory/stock_item')
							->loadByProduct($product)->getQty();

							$retorno['products'][] = array("id" => $mage_productId, "qty" => $product_quantity, "price" => $product_price, "curr_stock" => $stocklevel);
						//producto existe
						} else {
						//vendria if de si activada opcion de crear producto creamos
							if(!$this->debug) {
								return false;
							}
							$product_data = array(
								"sku" => $product_ImportedMerchantId,
								"sku2" => $product_MerchantId,
								"qty" => $product_quantity,
								"price" => $product_price,
								"title" => $product_title,
								"image" => $product_image,
								"storeId" =>  $mage_storeid
								);
							$product = $this->createProduct($product_data);
							if(!$product) {
								return false;
							}
							$stocklevel = (int)Mage::getModel('cataloginventory/stock_item')
							->loadByProduct($product)->getQty();
							$mage_productId = $product->getId();
							$retorno['products'][] = array("id" => $mage_productId, "qty" => $product_quantity, "price" => $product_price, "curr_stock" => $stocklevel);
						}
					}
				}

				return $retorno;
			}



			public function matchProductAttributes($importedId, $storeId) {
				$product = null;
				$helper = Mage::helper('beezup');
				$attributes = $helper->getConfig('beezup/marketplace/attributes');
				$attributes = unserialize ($attributes);

				foreach($attributes['attributes'][$storeId ] as $attribute) {
					$att = explode("|", $attribute);
					if($storeId == $att[1]) {

						$product=Mage::getModel('catalog/product')->loadByAttribute($att[0],$importedId);
						if($product) {
							break;
						}
					}
				}

				return $product;
			}



			public function getMageProduct($importedId, $merchantId, $storeId){
				try {
					$product=Mage::getModel('catalog/product')->load($importedId);
					if (!$product->getId() || $product->getId()  !== $importedId ){
						$product = $this->matchProductAttributes($importedId, $storeId);
						if($product == null || !is_object($product)) {
							$product=Mage::getModel('catalog/product')->load($merchantId);
							if(!$product->getId() || $product->getId()  !== $merchantId ) {
								$product = $this->matchProductAttributes($merchantId, $storeId);

							}
						}
					}

					if(is_object($product)) {
						if($product->getId()) {
							$this->debugLog("Product Matching succesful, Beezup Imported Id: ".$importedId." , Magento Product Id: ".$product->getId());
							return $product;
						}}
						$this->log->LogError($this->orderid. "| No Product Matching, Product ".$importedId." could not be found");
						$this->log2->LogError($this->orderid. "| No Product Matching, Product ".$importedId." could not be found");
						return false;
					}catch(Exception $e){
						$this->log->LogError($this->orderid. "| Product ".$importedId." could not be found, error: ".$e->getMessage());
						$this->log2->LogError($this->orderid. "| Product ".$importedId." could not be found, error: ".$e->getMessage());
						return false;
					//error no se pudo crear la orden

					}
				}


				public function checkOrderStore($storeId ) {
					$helper = Mage::helper('beezup');
					$stores = $helper->getConfig('beezup/marketplace/stores');
					$stores = unserialize ($stores);
					foreach($stores as $store) {
						if(isset($store[$storeId]) && $store[$storeId] > 0) {
							return $store[$storeId];
						}
					}
					return false;
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


					private $currentStock = null;

					private function createCustomer($customer_email , $data) {
						$password = $this->orderid;
						$this->debugLog("Creating new Customer");
						$customer = Mage::getModel('customer/customer');
						$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
						$customer->loadByEmail($customer_email);
						if(!$customer->getId()) {
							$customer->setEmail($customer_email);
							$customer->setFirstname($data['firstname']);
							$customer->setLastname($data['lastname']);
							$customer->setPassword($password);
							try {
								$customer->save();
								$customer->setConfirmation(null);
								$customer->save();
								$this->debugLog("Customer created succesfully");
								return $customer;
					//Make a "login" of new customer
					//	Mage::getSingleton('customer/session')->loginById($customer->getId());
							}

							catch (Exception $ex) {
					//Zend_Debug::dump($ex->getMessage());
					//GUARDAR ERROR CREACION USUARIO
								$this->log2->LogError($this->orderid. " | Customer importation failed: ".$ex->getMessage());
								return false;
							}

						} else {
							$this->debugLog("Creating already exists, returning customer object");
							return $customer;
						}
					}



					public function addOrder($data, $oLink, $stop = false) {


						try {
							$helper = Mage::helper('beezup');
							$addStock = $helper->getConfig('beezup/marketplace/available_products');
							$baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
							$quote = Mage::getModel('sales/quote')
							->setStoreId($data['storeid']);
							$quote->setCustomerEmail($data['order_customer_email']);


							$currency = Mage::getModel('directory/currency')->load($data['order_currency']);
							$quote->setForcedCurrency($currency);

							$blnCreate = true;
							$total_new_price = 0;
							foreach($data['products'] as $prod) {
								if($prod['qty']==0) {
									$blnCreate = false;
									break;
								}
								$prod_totality_price = $prod['price']*$prod['qty'];
								$total_new_price = $total_new_price + $prod_totality_price;
								$product = Mage::getModel('catalog/product')->load($prod['id']);
								$buyInfo = array(
									'qty' => $prod['qty'],
									);
								$this->debugLog("Adding ".$prod['qty']." product/s with id ".$product->getId()." to order, with Beezup Price: ".$prod['price']);
					//echo "Product ".$product->getId()."<br><br>";
					//para no perder stock:
					//Mage::getModel('cataloginventory/stock')->backItemQty($productId,$new_qty);
							$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
								$this->currentStock[$prod['id']] = $stock->getQty();
								if($addStock == 1 || $data['resetStock'] == true) {


									if (($stock->getQty() < $prod['qty'] && $product->getStockItem()->getMaxSaleQty() >= $prod['qty'] )  || $data['resetStock']
									== true) {
										$stockQty = $this->currentStock[$prod['id']]  + $prod['qty'];
																			$this->debugLog("Product ".$product->getId()." Stock = ".(int)$this->currentStock[$prod['id']] .", Adding 1 to stock to generate Order");
														//	Mage::getModel('cataloginventory/stock')->backItemQty($product->getId(),$prod['qty']);
														//$this->_updateStocks(array("id_product" => $product->getId(), "qty" => $prod['qty']));

																			$product->setStockData(
																				array(
																					'is_in_stock' => 1,
																					'qty' => $stockQty,
																					'manage_stock' => 1,
																					'use_config_notify_stock_qty' => 1
																					)
																				);
										$product->save();
										$product = Mage::getModel('catalog/product')->load($product->getId());

									}
								} elseif($this->currentStock[$prod['id']] == 0) {
									$this->log->LogError($this->orderid. "| Order ".$data['market_place_order_id']." could not be imported, error: Product with id ".$product->getId()." has stock 0");
									$this->restoreStock($data);
									$blnCreate = false;
									return;
									break;

								}
					//fin para no perder stock

					/*
					$tax_class = $product->getTaxClassId();
					$product->setTaxClassId(0);
					$product->getResource()->saveAttribute($product, 'tax_class_id'); */
					$price = $prod['price'];

					$quote_item = Mage::getModel('beezup/quote_item');
					$quote_item
					->setProduct($product)
					->setPrice((float) $price )
					->setCustomPrice((float)$price )
					->setOriginalCustomPrice((float) $price )
					->setQuote($quote)
					->setQty((integer) $prod['qty'])
					->setBeezupPrice((float) $price );


					$quote->addItem($quote_item);

					//$quote->addProduct($product, new Varien_Object($buyInfo))->setOriginalCustomPrice($price)->setCustomPrice($price);

					/*
					$product->setTaxClassId($tax_class);

					$product->getResource()->saveAttribute($product, 'tax_class_id');
					*/
				}

				if($blnCreate) {
					$addressData = array(
						'firstname' => ($data['order_customer']) ? $data['order_customer'] : "empty",
						'lastname' => ($data['order_lastname']) ? $data['order_lastname'] : "empty",
						'street' => ($data['order_address']) ? $data['order_address'] : "empty",
						'city' => ($data['order_city']) ? $data['order_city'] : "empty",
						'postcode' => ($data['order_postalCode']) ? $data['order_postalCode'] : "empty",
						'telephone' => ($data['order_customer_phone']) ? $data['order_customer_phone'] : "empty",
						'country_id' => ($data['order_country_iso']) ? $data['order_country_iso'] : "empty",
						'company' => ($data['order_company']) ? $data['order_company'] : "",
					'region_id' => ($data['order_region ']) ?  substr($data['order_region '], 0,2) : "EM"// id from directory_country_region table
					);

					$shippingData = array(
						'firstname' => ($data['shipping_name']) ? $data['shipping_name'] : "empty",
						'lastname' => ($data['shipping_lastname']) ? $data['shipping_lastname'] : "empty",
						'street' =>  ($data['shipping_address']) ? $data['shipping_address'] : "empty",
						'city' =>  ($data['shipping_city']) ? $data['shipping_city'] : "empty",
						'postcode' =>  ($data['shipping_postalCode']) ? $data['shipping_postalCode'] : "empty",
						'telephone' => ($data['shipping_phone']) ? $data['shipping_phone'] : "empty",
						'country_id' => ($data['shipping_country_iso']) ? $data['shipping_country_iso'] : "empty",
						'shipping_company' => ($data['shipping_company']) ? $data['shipping_company'] : "",
					'region_id' => ($data['shipping_region']) ? substr($data['shipping_region'], 0, 2) : "EM"  // id from directory_country_region table
					);

					if($this->blnCreateCustomer) {

						$mage_customer = $this->createCustomer($data['order_customer_email'], $addressData);
						$quote->assignCustomer($mage_customer);
					}


					$payment_method = $helper->getConfig('beezup/marketplace/payment_method');
					$billingAddress = $quote->getBillingAddress()->addData($addressData);
					$shippingAddress = $quote->getShippingAddress()->addData($shippingData);
					$shipping_cost = (float) $data['order_shippingPrice'];
					if($data['order_shippingPrice'] == 0) {
						$shipping_cost = 20000;
					}
					$total_new_price = $total_new_price + $data['order_shippingPrice'] ;
					Mage::unregister('shipping_cost');
					Mage::register('shipping_cost', $shipping_cost);
					$this->debugLog("Adding Order Shipping Cost: ". $data['order_shippingPrice']);

					$shippingAddress->setCollectShippingRates(true)->collectShippingRates()
					->setShippingMethod('beezup_beezup')
					->setPaymentMethod($payment_method);

					//$shippingAddress->addTotal(array("code" => "specialfee", "title" => "Special Fee", "value" => 20));
					$quote->getPayment()->importData(array('method' => $payment_method));

					$quote->collectTotals()->save();

					$service = Mage::getModel('sales/service_quote', $quote);
					$service->submitAll();
					$order = $service->getOrder();



					$quoteId = $order->getQuoteId();
					//$this->setStatus($data['order_status'], $order);

					$orderid = $order->getId();

					$resource = Mage::getSingleton('core/resource');
					$writeConnection = $resource->getConnection('core_write');
					$table = $resource->getTableName('sales/order_grid');
					$this->debugLog("Adding Beezup Marketplace Information to Order");
					$marketplace = $data['marketplace'];
					$marketplace_business_code = ucfirst(strtolower($data['marketplace_business_code']));
					$beezup_name = $data['order_customer'];
					$market_order_id =$this->orderid;
					$beezup_order_id = $oLink->getBeezupOrderUUID();
					$beezup_status = $data['order_status'];
					$beezup_last_modification_date = $oLink->getOrderLastModificationUtcDate();
					$beezup_last_modification_date = $beezup_last_modification_date->date;
					$beezup_marketplace_status = $oLink->getOrderStatusMarketPlaceStatus();
					$beezup_purchase_date = $oLink->getOrderPurchaseUtcDate();
					$beezup_purchase_date = $beezup_purchase_date->date;
					$beezup_marketplace_last_modification_date = $oLink->getOrderMarketPlaceLastModificationUtcDate();
					$beezup_marketplace_last_modification_date = 	$beezup_marketplace_last_modification_date->date;
					/*	$date = new DateTime($$beezup_marketplace_last_modification_date);
					$beezup_marketplace_last_modification_date = $date->format('d-m-Y H:i:s'). "(UTC Time)";
					*/

					$beezup_total_paid = $oLink->getOrderTotalPrice()." ".$data['order_currency'];
					$beezup_account_id = $oLink->getAccountId();
					$beezup_comission = $oLink->getOrderTotalCommission()." ".$data['order_currency'];
					$tot_comm = $oLink->getOrderTotalCommission();
					if(empty($tot_comm  ) ||  $tot_comm == 0) {
						$beezup_comission = 0;
					}
					$query = "UPDATE {$table} SET beezup_marketplace = '{$marketplace}',   beezup_name = '{$beezup_account_id}', beezup_order = 1, beezup_market_order_id = '{$market_order_id}',
					beezup_order_id = '{$beezup_order_id}', beezup_status = '{$beezup_status}', beezup_last_modification_date = '{$beezup_last_modification_date}',
					beezup_marketplace_status = '{$beezup_marketplace_status}', beezup_purchase_date = '{$beezup_purchase_date}', beezup_marketplace_last_modification_date = '{$beezup_marketplace_last_modification_date}',
					beezup_total_paid = '{$beezup_total_paid}', beezup_etag = '{$data['etag']}' , beezup_comission = '{$beezup_comission}', beezup_marketplace_business_code = '{$marketplace_business_code}'
					WHERE entity_id = ". (int)$orderid;
					$writeConnection->query($query);
					$disc_price = 0;
					if(!empty($data['discounts']) && $data['discounts'] >0) {


						$disc_price = round($data['discounts'],2);
						$total_new_price = $total_new_price+$disc_price;
						$table_address = $resource->getTableName("sales/quote_address");

						$query = "update {$table_address} set beezup_fee = '{$disc_price}' where quote_id = '{$quoteId}' and address_type = 'shipping'";
						$writeConnection->query($query);
						$this->debugLog("Adding CDISCOUNT products with total price: ".$disc_price);
					}

					//if order id exists and has been created
					if ($orderid)
					{
					//we send order id to beezup
						$this->debugLog("Sending Magento Order Id to Beezup, Magento Order Id: ".$orderid);
						$oResult = new BeezupOMSetOrderIdValues ();
						$oResult->setOrderMerchantOrderId ( $orderid )->setOrderMerchantECommerceSoftwareName ( 'Magento' )->setOrderMerchantECommerceSoftwareVersion ( Mage::getVersion() );
						$sendRequest = $this->getOrderService()->getClientProxy()->setOrderMerchantIdByLink($oLink->getLinkByRel('setMerchantOrderId'), $oResult);
					}

					$grand_total = $order->getGrandTotal();
					$beezup_price = $oLink->getOrderTotalPrice() - $disc_price;
					if($grand_total != (float) $beezup_price && $beezup_price > 0) {
						$order->setGrandTotal((float) $beezup_price);
						$order->setBaseGrandTotal((float) $beezup_price);
						$diff = (((float) $beezup_price) - $grand_total);

						$order->setTaxAmount($order->getTaxAmount() + $diff);
						$order->save();
					}elseif($grand_total != (float)$beezup_price && $beezup_price < 1) {

						$order->setGrandTotal((float) $total_new_price);
						$order->setBaseGrandTotal((float) $total_new_price);
						$diff = (((float) $total_new_price) - $grand_total);

						$order->setTaxAmount($order->getTaxAmount() + $diff);
						$order->save();
					}


					if($order->getShippingInclTax() != $data['order_shippingPrice']) {
						$shipping_cost =  (float)$data['order_shippingPrice'];
						$diff_shipping = ($shipping_cost - $order->getShippingInclTax());
						//$order->setShippingAmount($order->getShippingAmount() + $diff_shipping);
						//$order->setBaseShippingAmount($order->getShippingAmount());
						$order->setShippingInclTax($shipping_cost);
						$order->setBaseShippingInclTax($order->getShippingInclTax());
						/*
						$order->setSubtotalInclTax($order->getSubtotalInclTax() +  $diff_shipping);
						$order->setBaseSubtotalInclTax($order->getSubtotalInclTax());
						$order->setSubtotal($order->getSubtotal() +  $diff_shipping);
						$order->setBaseSubtotal($order->getSubtotal());
						*/
						$order->save();
					}

					$products = Mage::getResourceModel('sales/order_item_collection')
					->setOrderFilter($orderid);
					foreach($products as $product) {
						$product->setBaseOriginalPrice($product->getOriginalPrice());
						$product->setBaseTaxAmount($product->getTaxAmount());
						$product->setBaseTaxInvoiced($product->getTaxAmount());
						$product->setBasePriceInclTax($product->getPriceInclTax());
						$product->setBaseRowTotalInclTax($product->getRowTotalInclTax());
						$product->save();
					}
					$order->setBaseTaxAmount($order->getTaxAmount());
					$order->setBaseTaxInvoiced($order->getTaxAmount());
					$order->setBaseTotalInvoiced($order->getTotalPaid());
					$order->setBaseTotalPaid($order->getTotalPaid());
					$order->setBaseGrandTotal($order->getTotalPaid());
					$order->setBaseSubtotalInclTax($order->getSubtotalInclTax());
					$order->save();




					$this->setStatus($data['order_status'], $order);
					$this->mage_order_id = $orderid;
					$this->debugLog("Order imported succesfully, Magento Order Id: ".$orderid);
				}  else {
					//product stock = 0 we dont create order
					$this->log->LogError($this->orderid. "| Order ".$data['market_place_order_id']." could not be imported, error: Stock from Beezup product = 0 ");
					$this->log2->LogError($this->orderid. "| Order ".$data['market_place_order_id']." could not be imported, error: Stock from Beezup product = 0 ");
				}
			}catch(Exception $e){


				if($stop) {
					$this->log->LogError($this->orderid. "| Order ".$data['market_place_order_id']." could not be imported, error: ".$e->getMessage());
					$this->log2->LogError($this->orderid. "| Order ".$data['market_place_order_id']." could not be imported, error: ".$e->getMessage());
					$this->restoreStock($data);
				} else {
					$this->debugLog("Order Import failed, Trying to import Order Again");
					$this->addOrder($data, $oLink, true);

				}
					//error no se pudo crear la orden

			}



		}



		public function restoreStock($data) {

			try {
				foreach($data['products'] as $prod) {
					if(!isset($this->currentStock[$prod['id']])) {
						continue;
					}
					$product = Mage::getModel('catalog/product')->load($prod['id']);
					$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
					if ($stock->getQty() != $prod['curr_stock'] ) {
						$this->debugLog("Restoring Stock from Product ".$product->getId()." to: ".$prod['curr_stock'] ." due to Order Fail");
						$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
						$stockItem->setData('is_in_stock', (int)$this->currentStock[$prod['id']]);
						$stockItem->setData('qty', $prod['curr_stock']);
						$stockItem->save();
						$product->save();
					}

				}
			} catch(Exception $e){
				$this->log->LogError($this->orderid. "| Failed Restoring Product Stock: ".$e->getMessage());
				$this->log2->LogError($this->orderid. "| Failed Restoring Product Stock: ".$e->getMessage());
			}

		}



		private function createProduct($data) {

			$sku = $data['sku'];
			if(empty($data['sku'])) {
				$sku = $data['sku2'];
			}

			Mage::app()->setCurrentStore($data['storeId']);
			$product = Mage::getModel('catalog/product');
					//    if(!$product->getIdBySku('testsku61')):

			try{
				$product
					//    ->setStoreId(1) //you can set data in store scope
					->setWebsiteIds(array($data['storeId'])) //website ID the product is assigned to, as an array
					->setAttributeSetId($product->getDefaultAttributeSetId()) //ID of a attribute set named 'default'
					->setTypeId('simple') //product type
					->setCreatedAt(strtotime('now')) //product creation time
					->setSku($sku ) //SKU
					->setWeight(0)
					->setName($data['title']) //product name
					->setStatus(1) //product status (1 - enabled, 2 - disabled)
					->setTaxClassId(4) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
					->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE ) //catalog and search visibility
					->setPrice($data['price']) //price in form 11.22
					->setMsrpEnabled(1) //enable MAP
					->setMsrpDisplayActualPriceType(1) //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
					->setMsrp(0) //Manufacturer's Suggested Retail Price
					->setMetaTitle('')
					->setMetaKeyword('')
					->setMetaDescription('')
					->setDescription($data['title'])
					->setShortDescription($data['title'])
					// ->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
					//->addImageToMediaGallery('media/catalog/product/1/0/10243-1.png', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery
					->setStockData(array(
					'use_config_manage_stock' => 0, //'Use config settings' checkbox
					'manage_stock'=>1, //manage stock
					'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
					'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
					'is_in_stock' => 1, //Stock Availability
					'qty' => 1 //qty
					)
					);
					$product->save();
					return $product;

				}catch(Exception $e){
					//log exception
					$this->log->LogError($this->orderid."| Product ".$sku." could not be created, error: ".$e->getMessage());
					$this->log2->LogError($this->orderid."| Product ".$sku." could not be created, error: ".$e->getMessage());
					return false;
				}
			}



			public function setStatus($status, $order) {
				$helper = Mage::helper('beezup');
				$retorno = "";
				$blnCancel = false;
				$blnHold = false;
				switch($status) {
					case "New" :
					$this->debugLog("Setting Order Status to New");
					$retorno =  $helper->getConfig('beezup/marketplace/status_new');
					break;
					case "InProgress" :
					$this->debugLog("Setting Order Status to InProgress");
					$retorno =  $helper->getConfig('beezup/marketplace/status_progress');
					$this->payOrder($order);
					break;
					case "Aborted" :
					$this->debugLog("Setting Order Status to Aborted");
					$retorno =  $helper->getConfig('beezup/marketplace/status_aborted');
					$blnHold = true;


					break;
					case "Closed" :
					$this->debugLog("Setting Order Status to Closed");
					$blnCancel =true;
					$retorno =  $helper->getConfig('beezup/marketplace/status_closed');
					$this->payOrder($order);
					break;
					case "Canceled" :
					$this->debugLog("Setting Order Status to Cancelled");
					$retorno =  $helper->getConfig('beezup/marketplace/status_cancelled');

					break;
					case "Shipped" :
					$this->debugLog("Setting Order Status to Shipped");
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

			public function payOrder($order) {
				try {
					$this->debugLog("Generating Order Payment Invoice");
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
						$this->debugLog("Order Payment Invoice Generated Succesfully");
					}
				}
				catch(Exception $e){
					//log exception
					$this->log->LogError($this->orderid."| Order Payment Invoice could not be generated, error: ".$e->getMessage());
					$this->log2->LogError($this->orderid."| Order Payment Invoice could not be generated, error: ".$e->getMessage());
				}
			}






		}
