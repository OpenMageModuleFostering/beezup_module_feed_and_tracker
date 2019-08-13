<?php

class BeezUp_Block_Tracking extends Mage_Core_Block_Text
{

    public function getStoreId()
    {
        return Mage::getStoreConfig('beezup/tracking/storeid');
    }

    public function getOrderTracker()
    {
        if (!$this->getOrder() || !$this->getStoreId()) return '';

        $order = $this->getOrder();
        $infos = $this->getProductsInformations($order);

        $marge = '';
        if (Mage::getStoreConfig('beezup/tracking/marge')) {
            $marge = '&ListProductMargin=' . $infos['margin'] . '';
        }
		
		//montant HT sans frais de port
		if(Mage::getStoreConfig('beezup/tracking/montant')=="HT"){
			$totalCost = number_format($order->getSubtotal(), 2, '.', '');
		}
		//montant HT avec frais de port
		else if(Mage::getStoreConfig('beezup/tracking/montant')=="HT_port"){
			$totalCost = number_format($order->getSubtotal()+$order->getShippingInclTax(), 2, '.', '');
		}
		//montant TTC sans frais de port
		else if(Mage::getStoreConfig('beezup/tracking/montant')=="TTC"){
			$totalCost = number_format($order->getBaseGrandTotal()-$order->getShippingInclTax(), 2, '.', '');
		}
		//montant TTC avec frais de port
		else if(Mage::getStoreConfig('beezup/tracking/montant')=="TTC_port"){
			$totalCost = number_format($order->getBaseGrandTotal(), 2, '.', '');
		}
			
		if(Mage::app()->getStore()->isCurrentlySecure()) {
			$script = 	'<img src="https://tracker.beezup.com/SO?StoreId='. trim($this->getStoreId()) . 
						'&OrderMerchantId=' . $order->getIncrementId() .
						'&TotalCost=' . $totalCost .
						'&ValidPayement=true'.
						'&ListProductId='. $infos['id'] .
						'&ListProductQuantity='. $infos['qty'] .
						'&ListProductUnitPrice=' . $infos['price'] .
						$marge .
						'" />' .
						PHP_EOL; 
		}
		else {
			$script = 	'<img src="http://tracker.beezup.com/SO?StoreId='. trim($this->getStoreId()) . 
						'&OrderMerchantId=' . $order->getIncrementId() .
						'&TotalCost=' . $totalCost .
						'&ValidPayement=true'.
						'&ListProductId='. $infos['id'] .
						'&ListProductQuantity='. $infos['qty'] .
						'&ListProductUnitPrice=' . $infos['price'] .
						$marge .
						'" />' .
						PHP_EOL; 
		}

        if (Mage::getStoreConfigFlag('beezup/tracking/debug')) Mage::log($script, 7, 'beezup.log');

        return $script;
    }

    public function getProductsInformations($order)
    {
        $id = '';
        $qty = '';
        $price = '';
        $margin = '';
        $items = $order->getAllItems();
		
        foreach ($items as $itemId => $item) {
			if(number_format($item->getBasePrice(), 2, '.', '')!=0.00){
				$product = Mage::getModel('catalog/product')->load($item->getProductId());
				$id .= $item->getProductId() . '|';
				$qty .= intval($item->getQtyOrdered()) . '|';
				$price .= number_format($item->getBasePrice(), 2, '.', '') . '|';
				$margin .= number_format($item->getBasePrice() - $product->getCost(), 2, '.', '') . '|';
			}
	   }
        return array('id' => substr($id, 0, -1), 'qty' => substr($qty, 0, -1), 'price' => substr($price, 0, -1), 'margin' => substr($margin, 0, -1));
    }

    protected function _toHtml()
    {
        $this->setCacheLifetime(null);
        $this->addText($this->getOrderTracker());
        return parent::_toHtml();
    }

}