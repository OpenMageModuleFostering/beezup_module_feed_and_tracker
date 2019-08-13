<?php 

class BeezupMageOrders {
	
	public $id_order;
	public $data = array();
	public $Mageresource = null;
	public $conection = null;
	
	public function __construct($id_order) {
		$this->id_order = $id_order;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	
	public function updateShippingInfo() {
		$this->updateSalesFlat();
		$this->updateSalesInvoice();
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
			$this->getConnection();
			$table = $this->getTableName("sales/order_address");
			$query = "select * from ".$table."  where parent_id = '".$this->id_order."'  ";
			$results = $this->connection->fetchAll($query);
			foreach($results as $result) {
				if($result['address_type'] == "billing") {
					//update billing
					$query = $this->updateBilling($result, $table);
						if($query) {
							$this->getConnection("core_write");
							$this->connection->query($query);						
						}
				} else {
					//update shipping
					$query = $this->updateShipping($result, $table);

						if($query) {
							$this->getConnection("core_write");
							$this->connection->query($query);						
						}					
				}
				
			}
		}
		
	}

	private function updateShipping($result, $table) {
		$query = "update {$table} set ";
		$blndentro = false;
		
		if($this->data['shipping_country'] != $result['country_id']) {
		$blndentro = true;	
		$query .= " country_id = '{$this->data['shipping_country'] }' ,";
		} 
		if($this->data['shipping_postcode'] != $result['postcode']) {
		$blndentro = true;		
			$query .= " postcode = '{$this->data['shipping_postcode'] }' ,";
		}
		
		if($this->data['shipping_lastname'] != $result['lastname']) {
		$blndentro = true;		
			$query .= " lastname = {$this->escape($this->data['shipping_lastname']) } ,";
		}
		if($this->data['shipping_street'] != $result['street']) {
		$blndentro = true;		
			$query .= " street = {$this->escape($this->data['shipping_street']) } ,";
		}
		if($this->data['shipping_city'] != $result['city']) {
		$blndentro = true;		
			$query .= " city = {$this->escape($this->data['shipping_city']) } ,";
		}
		if($this->data['shipping_telephone'] != $result['telephone']) {
		$blndentro = true;		
			$query .= " telephone = '{$this->data['shipping_telephone'] }' ,";
		}
		if($this->data['shipping_firstname'] != $result['firstname']) {
		$blndentro = true;	
	$query .= " firstname = {$this->escape($this->data['shipping_firstname']) } ,";		
		}
		
		if($blndentro) {
			$query = substr($query, 0, -1);
			$query .= " where parent_id = '{$this->id_order}' and address_type = 'shipping'";
			return $query;
			
		}
		return false;
	}  
	
	private function updateBilling($result, $table) {
				$query = "update {$table} set ";
		$blndentro = false;
		
		if($this->data['billing_country'] != $result['country_id']) {
		$blndentro = true;	
		$query .= " country_id = '{$this->data['billing_country'] }' ,";
		} 
		if($this->data['billing_postcode'] != $result['postcode']) {
		$blndentro = true;		
			$query .= " postcode = '{$this->data['billing_postcode'] }' ,";
		}
		
		if($this->data['billing_lastname'] != $result['lastname']) {
		$blndentro = true;		
			$query .= " lastname = {$this->escape($this->data['billing_lastname'] )} ,";
		}
		if($this->data['billing_street'] != $result['street']) {
		$blndentro = true;		
			$query .= " street = {$this->escape($this->data['billing_street'] )} ,";
		}
		if($this->data['billing_city'] != $result['city']) {
		$blndentro = true;		
			$query .= " city = {$this->escape($this->data['billing_city']) },";
		}
		if($this->data['billing_telephone'] != $result['telephone']) {
		$blndentro = true;		
			$query .= " telephone = '{$this->data['billing_telephone'] }' ,";
		}
		if($this->data['billing_firstname'] != $result['firstname']) {
		$blndentro = true;	
	$query .= " firstname = {$this->escape($this->data['billing_firstname']) } ,";		
		}
		
		if($blndentro) {
						$query = substr($query, 0, -1);
			$query .= " where parent_id = '{$this->id_order}' and address_type = 'billing'";
			return $query;
			
		}
		return false;
		
	}
	
	private function updateSalesInvoice() {
		if(!empty($this->data)) {
			$this->getConnection();
			$table = $this->getTableName("sales/invoice");
			$query = "select * from ".$table."  where transaction_id = '".$this->id_order."'  ";
			
			$results = $this->connection->fetchAll($query);
			if(!empty($results)) {
				$result = $results[0];
				if($result['shipping_amount'] != $this->data['shipping']) {
				$query = $this->getSalesInvoiceWriteQuery($result, $table);
				$this->getConnection("core_write");
				$this->connection->query($query);
				}
			}
			
		}
		
	}
	
		private function getSalesInvoiceWriteQuery($result, $table) {
		$query = "update {$table} set base_shipping_amount = '{$this->data['shipping']}', shipping_amount = '{$this->data['shipping']}'" ;
		$query .= $this->setQueryParameters($result, "base_grand_total", true);
		$query .= $this->setQueryParameters($result, "grand_total", true);
		$query .= "  where transaction_id = '{$this->id_order}'";
		return $query;
	}
	
	
	
	
	
	private function  updateSalesFlat() {
		if(!empty($this->data)) {
			$this->getConnection();
			$table = $this->getTableName("sales/order");
			$query = "select * from ".$table." where entity_id = '".$this->id_order."'  ";
			$results = $this->connection->fetchAll($query);
			if(!empty($results)) {	
				$result = $results[0];

				if($result['shipping_amount'] != $this->data['shipping']) {
	
				$query = $this->getSalesFlatWriteQuery($result, $table);
				$this->getConnection("core_write");
				$this->connection->query($query);
				}
			}
			
		}
		
	}
	
	
	private function getSalesFlatWriteQuery($result, $table) {
		$query = "update {$table} set base_shipping_amount = '{$this->data['shipping']}', shipping_amount = '{$this->data['shipping']}'" ;
		$query .= $this->setQueryParameters($result, "base_shipping_invoiced");
		$query .= $this->setQueryParameters($result, "shipping_invoiced");
		$query .= $this->setQueryParameters($result, "base_grand_total", true);
		$query .= $this->setQueryParameters($result, "base_total_invoiced", true);
		$query .= $this->setQueryParameters($result, "base_total_paid", true);
		$query .= $this->setQueryParameters($result, "grand_total", true);
		$query .= $this->setQueryParameters($result, "total_invoiced", true);
		$query .= $this->setQueryParameters($result, "total_paid", true);
		$query .= $this->setQueryParameters($result, "base_total_paid", true);
		$query .= $this->setQueryParameters($result, "total_paid", true);
		$query .= "  where entity_id = '{$this->id_order}'";
		return $query;
	}
	
	
	private function setQueryParameters($result, $column, $isTotal = false) {
		$query = "";
		if($result[$column] > 0 && !empty($result[$column])) {
		if($isTotal) {
			$data = ($result[$column] - $result['shipping_amount']) + $this->data['shipping'];
			$query = " ,  ".$column." = '{$data}'";
		} else {	
			$query = " ,  ".$column." = '{$this->data['shipping']}'";
		}
		
		}
		return $query;
	}
	
	
	
	private function getConnection($connection = "core_read") {
		$this->Mageresource = Mage::getSingleton('core/resource');
		$this->connection = $this->Mageresource->getConnection($connection);
	}
	
	private function getTableName($table) {
		return $this->Mageresource->getTableName($table);
	}
	
	
	
	
} 