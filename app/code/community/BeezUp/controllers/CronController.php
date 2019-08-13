<?php
	require_once dirname ( __FILE__ ) . "/../lib/bootstrap.php";
	require_once dirname ( __FILE__ ) . "/../lib/BeezupRepository.php";
	class BeezUp_CronController extends Mage_Core_Controller_Front_Action
	{
		
		public function preDispatch()
		{
			parent::preDispatch();
			
			$repository = new BeezupRepository();
			if(!$repository->isConnectionOk()) {
				$this->norouteAction();
			}
		}
		
		public function executeAction()
		{
			$this->getResponse()->setBody($this->getLayout()->createBlock('beezup/order')->executeCron());
		}
		
		
		public function orderlinkAction() 
		{
			$url = $_GET['url'];
			$url = urldecode($url);
			if($this->_stringContains($url, "go.beezup.com/OrderManagement/Informations?")) {
				$data = substr($url, strpos($url, "go.beezup.com/OrderManagement/Informations?") + 1);    
				if($this->_stringContains($data, "BeezUPOrderUUId=") && $this->_stringContains($data, "AccountId=")) {
					$order_id = $this->_getStringbetween($data, "BeezUPOrderUUId=", "&");
					$account_id = substr($url, strpos($url, "AccountId=") + 1);    
					$account_id = str_replace("ccountId=", "", $account_id);
					$marketplace = false;
					if($this->_stringContains($data, "MarketplaceTechnicalCode=") ) {
						$marketplace = $this->_getStringbetween($data, "MarketplaceTechnicalCode=", "&");
					} 
					elseif( $this->_stringContains($data, "MarketPlaceBusinessCode=") ) {
						$marketplace = $this->_getStringbetween($data, "MarketPlaceBusinessCode=", "&");
					}
					if($marketplace) {
						$this->getResponse()->setBody($this->getLayout()->createBlock('beezup/order')->createOrderFromLink($account_id, $marketplace, $order_id));
					}
				}
				
			}
		}
		
		
		private function _getStringbetween($string, $start, $end){
			$string = ' ' . $string;
			$ini = strpos($string, $start);
			if ($ini == 0) return '';
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}
		
		private function _stringContains($string, $value) {
			if (strpos($string, $value) !== false) {
				return true;
			}
			die("Error, data incorrect");
		}
		
		
		public function orderAction() {
			$account_id =	$_GET['acount_id'];
			$order_id =	$_GET['order_id'];
			$marketplace =$_GET['marketplace'];
			if(!is_numeric($account_id) ) {
				die("Error, Order data incorrect");
			}
			$this->getResponse()->setBody($this->getLayout()->createBlock('beezup/order')->createOrderFromLink($account_id, $marketplace, $order_id));
		}
		
	}									