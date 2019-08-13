<?php 
require_once dirname ( __FILE__ ) . "/KLogger.php";
require_once dirname ( __FILE__ ) . "/bootstrap.php";
require_once dirname ( __FILE__ ) . "/BeezupRepository.php";
require_once dirname ( __FILE__ ) . "/BeezupMageOrders.php";

class BeezupOmStatus {
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
	


public function formatActions($order_actions) {
$return = array();

foreach($order_actions as $action) {
	$fields = json_decode($action['fields']);
	$lovs = json_decode($action['lovs']);

$retorno = array();
	//$retorno['fields'][] = $fields;
		//$retorno['lovs'][] = $lovs;
	$action_name =$action['id'];
	$data = array();
//echo $action_name;

	foreach($fields->parameters as $field) {
		$rel_status = $field->name;
		$field->$rel_status = $lovs->$rel_status;
		$field->action = $action_name;
		$retorno[] =$field;
	}
$return[] = array("action"=> $fields->rel, "parameters" => $retorno);

}

	return $return;
	
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
			$order_actions = $this->formatActions($order_actions);
			$order_actions['is_pending'] = $order_result->getIsPendingSynchronization();
			return $order_actions;
				
				
			}
		} catch (Exception $ex ) {
		
			return $ex->getMessage();
		}
		
		
	}
	
		//function to change order status of marketplace
	public function updateStatus($order_id) {
		$this->orderid = $order_id;
		try {
			$order = $this->getBeezupOrderFromMage();
			
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
				//$oOrderIdentifier = $order['beezup_order_id'];
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