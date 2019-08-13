<?php 
	
	class Beezup_Block_Adminhtml_System_Config_Orderlink extends Mage_Adminhtml_Block_System_Config_Form_Field
	{
		
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
		{
			
			$url_link = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL) . 'beezup/cron/orderlink';
			$url_order = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL) . 'beezup/cron/order';
			
			$retorno = "<input type='radio' name='order-link' onclick='openOrderLink(1);' /> ".$this->__('Separated data')." &nbsp;&nbsp;&nbsp;<input type='radio' name='order-link' onclick='openOrderLink(2);' /> ".$this->__('go.beezup.com order link')." ";
			
			$retorno .= "
				<div id='info-order-link' style='display:none;margin-top:50px;'>
					<table class='table'>
					<tr>
						<td>".$this->__('Account ID').":</td>
						<td><input type='text' class='input-text'  id='info-order-account' /></td>
					</tr>
					<tr>
						<td>".$this->__('Marketplace ID').":</td>
						<td><input type='text' class='input-text' id='info-order-marketplace' /></td>
					</tr>
					<tr>
						<td>".$this->__('Order ID').":</td>
						<td><input type='text' class='input-text' id='info-order-orderid' /></td>
					</tr>
					<tr>
						<td></td>
						<td><a class='form-button' style='float: right;' onclick='sendOrderInfo();'>".$this->__('Get Order')."</a></td>
					</tr>
					</table>
				</div>
				
				<div id='order-order-link' style='display:none;margin-top:50px;'>
				<table class='table'>
					<tr>
						<td>".$this->__('go.beezup.com order link').":</td>
						<td><input type='text' class='input-text' id='order-link-enlace' /></td>
					</tr>
					<tr>
						<td></td>
						<td><a class='form-button'  style='float: right;' onclick='sendOrderLink();'>".$this->__('Get Order')."</a></td>
					</tr>
				</table>
				</div>
			";
			
			$retorno .= "
			<script>
			function openOrderLink(valor) {
			if(valor == 1) {
			document.getElementById('order-order-link').style.display = 'none';
			document.getElementById('info-order-link').style.display = 'block';
			} else {
			document.getElementById('order-order-link').style.display = 'block';
			document.getElementById('info-order-link').style.display = 'none';
			}
			}
			
			
			function sendOrderInfo() {
					var account = document.getElementById('info-order-account').value;
					var marketplace = document.getElementById('info-order-marketplace').value;
					var orderid = document.getElementById('info-order-orderid').value;
					var orderlink = '".$url_order."?acount_id=' + account + '&marketplace=' + marketplace + '&order_id=' + orderid;
					window.open(orderlink,'_blank');
			}
			
			function sendOrderLink() {
			var link = document.getElementById('order-link-enlace').value;
			var link = encodeURIComponent(link);
			var orderlink = '".$url_link."?url=' + link;
			window.open(orderlink,'_blank');
			}
			
			</script>
			";
			
			return $retorno;
		}
		
		
		
	}				