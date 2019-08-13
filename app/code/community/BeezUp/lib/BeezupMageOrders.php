<?php

class BeezupMageOrders {

	public $id_order;
	public $data = array();
	public $Mageresource = null;
	public $conection = null;
	public $order = null;
	public function __construct($id_order) {
		$this->id_order = $id_order;
		$this->order =  Mage::getModel('sales/order')->load($this->id_order);
	}

	public function setData($data) {
		$this->data = $data;
	}


	public function updateShippingInfo() {
		$order = $this->order;
		if($order->getShippingInclTax() == $this->data['shipping']) { return; }
		$order_total = ($order->getGrandTotal() - $order->getShippingInclTax()) + $this->data['shipping'];
		$order->setGrandTotal((float) $order_total);
		$order->setBaseGrandTotal((float) $order_total);
		$diff = (((float) $order_total) - $order->getGrandTotal());
		$order->setTaxAmount($order->getTaxAmount() + $diff);
		$order->setShippingAmount($this->data['shipping']);
		$order->setBaseShippingAmount($this->data['shipping']);
		$order->setShippingInclTax($this->data['shipping']);
		$order->setBaseShippingInclTax($this->data['shipping']);
		$order->setSubtotalInclTax($order->getSubtotalInclTax() +  $this->data['shipping']);
		$order->setBaseSubtotalInclTax($order->getSubtotalInclTax() + $this->data['shipping']);
		$order->setSubtotal($order->getSubtotal() +  $this->data['shipping']);
		$order->setBaseSubtotal($order->getSubtotal() + $this->data['shipping']);
		$order->setTotalPaid($order->getTotalPaid() + $this->data['shipping']);
		$order->save();
		$order->setBaseTaxAmount($order->getTaxAmount());
		$order->setBaseTaxInvoiced($order->getTaxAmount());
		$order->setBaseTotalInvoiced($order->getTotalPaid());
		$order->setBaseTotalPaid($order->getTotalPaid());
		$order->setBaseGrandTotal($order->getTotalPaid());
		$order->setBaseSubtotalInclTax($order->getSubtotalInclTax());
		$order->save();

	}


	public function escape($string) {
	return Mage::getSingleton('core/resource')->getConnection('default_write')->quote($string);
	}

	public function updateBeezupInfo() {
		if(!empty($this->data)) {
			$this->getConnection();
				$table = $this->getTableName("sales/order_grid");
				$query = "select * from ".$table."  where entity_id = '".$this->id_order."'  ";
				$results = $this->connection->fetchAll($query);
				if(!empty($results)) {
					$result = $results[0];
						$query = $this->updateInfoTab($result, $table);
						if($query) {
							$this->getConnection("core_write");
							$this->connection->query($query);
						}
				}
		}

	}

private function updateInfoTab($result, $table) {
		$query = "update {$table} set ";
		$blndentro = false;

		if($this->data['beezup_status'] != $result['beezup_status']) {
		$blndentro = true;
		$query .= " beezup_status = '{$this->data['beezup_status'] }' ,";
		}

		if($this->data['beezup_last_modification_date'] != $result['beezup_last_modification_date']) {
		$blndentro = true;
		$query .= " beezup_last_modification_date = '{$this->data['beezup_last_modification_date'] }' ,";
		}

		if($this->data['beezup_marketplace_last_modification_date'] != $result['beezup_marketplace_last_modification_date']) {
		$blndentro = true;
		$query .= " beezup_marketplace_last_modification_date = '{$this->data['beezup_marketplace_last_modification_date'] }' ,";
		}

		if($this->data['beezup_total_paid'] != $result['beezup_total_paid']) {
		$blndentro = true;
		$query .= " beezup_total_paid = '{$this->data['beezup_total_paid'] }' ,";
		}

		if($this->data['beezup_comission'] != $result['beezup_comission']) {
		$blndentro = true;
		$query .= " beezup_comission = '{$this->data['beezup_comission'] }' ,";
		}

		if($this->data['beezup_marketplace_status'] != $result['beezup_marketplace_status']) {
		$blndentro = true;
		$query .= " beezup_marketplace_status = '{$this->data['beezup_marketplace_status'] }' ,";
		}
		if($blndentro) {
			$query = substr($query, 0, -1);
			$query .= " where entity_id = '{$this->id_order}'  ";
			return $query;

		}
		return false;
}



	public function updateAdresses() {
		if(!empty($this->data)) {
			$this->updateBilling();
			$this->updateShipping();
		}
	}


	private function updateShipping() {
		$shippingAddress = Mage::getModel('sales/order_address')->load($this->order->getShippingAddress()->getId());
		$blndentro = false;
		if($this->data['shipping_country'] != $shippingAddress->getCountry_id()) {
			$blndentro = true;
			$shippingAddress->setCountry_id($this->data['shipping_country']);
		}
		if($this->data['shipping_postcode'] != $shippingAddress->getPostcode()) {
			$blndentro = true;
			$shippingAddress->setPostcode($this->data['shipping_postcode']);
		}
		if($this->data['shipping_lastname'] != $shippingAddress->getLastname()) {
			$blndentro = true;
			$shippingAddress->setLastname($this->data['shipping_lastname']);
		}
		if($this->data['shipping_street'] != $shippingAddress->getStreet()) {
			$blndentro = true;
			$shippingAddress->setStreet($this->data['shipping_street']);
		}
		if($this->data['shipping_city'] != $shippingAddress->getCity()) {
			$blndentro = true;
			$shippingAddress->setCity($this->data['shipping_city']);
		}
		if($this->data['shipping_telephone'] != $shippingAddress->getTelephone()) {
			$blndentro = true;
			$shippingAddress->setTelephone($this->data['shipping_telephone']);
		}
		if($this->data['shipping_firstname'] !=$shippingAddress->getFirstname()) {
			$blndentro = true;
			$shippingAddress->setFirstname($this->data['shipping_firstname']);

		}

		if($this->data['shipping_company'] !=$shippingAddress->getCompany()) {
			$blndentro = true;
			$shippingAddress->setCompany($this->data['shipping_company']);

		}

		if($blndentro) {
			$shippingAddress->save();
		}
		/*
		$shippingAddress
		->setFirstname("value")
		->setMiddlename("value")
		->setLastname("value")
		->setSuffix("value")
		->setCompany("value")
		->setStreet("value"))
		->setCity("value")
		->setCountry_id("value")
		->setRegion("value")
		->setRegion_id("value")
		->setPostcode("value")
		->setTelephone("value")
		->setFax("value")->save();
		*/
	}



	private function updateBilling() {
		$billingAddress = Mage::getModel('sales/order_address')->load($this->order->getBillingAddress()->getId());
		$blndentro = false;
		if($this->data['billing_country'] != $billingAddress->getCountry_id()) {
		$blndentro = true;
		$billingAddress->setCountry_id();
		}
		if($this->data['billing_postcode'] != $billingAddress->getPostcode()) {
		$blndentro = true;
		$billingAddress->setPostcode($this->data['billing_postcode']);
		}
		if($this->data['billing_lastname'] != $billingAddress->getLastname()) {
		$blndentro = true;
		$billingAddress->setLastname($this->data['billing_lastname']);
		}
		if($this->data['billing_street'] != $billingAddress->getStreet()) {
		$blndentro = true;
		$billingAddress->setStreet($this->data['billing_street']);
		}
		if($this->data['billing_city'] != $billingAddress->getCity()) {
		$blndentro = true;
		$billingAddress->setCity($this->data['billing_city']);
		}
		if($this->data['billing_telephone'] != $billingAddress->getTelephone()) {
		$blndentro = true;
		$billingAddress->setTelephone($this->data['billing_telephone']);
		}
		if($this->data['billing_firstname'] != $billingAddress->getFirstname()) {
		$blndentro = true;
		$billingAddress->setFirstname($this->data['billing_firstname']);
		}


			if($this->data['billing_company'] !=$billingAddress->getCompany()) {
					$blndentro = true;
					$billingAddress->setCompany($this->data['billing_company']);

			}
		if($blndentro) {
			$billingAddress->save();
		}
		/*
		$billingAddress
		->setFirstname("value")
		->setMiddlename("value")
		->setLastname("value")
		->setSuffix("value")
		->setCompany("value")
		->setStreet("value"))
		->setCity("value")
		->setCountry_id("value")
		->setRegion("value")
		->setRegion_id("value")
		->setPostcode("value")
		->setTelephone("value")
		->setFax("value")->save();
		*/
	}





	private function getConnection($connection = "core_read") {
		$this->Mageresource = Mage::getSingleton('core/resource');
		$this->connection = $this->Mageresource->getConnection($connection);
	}

	private function getTableName($table) {
		return $this->Mageresource->getTableName($table);
	}




}
