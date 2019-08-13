<?php 

class BeezUp_Model_Sales_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice {

	
	  protected function insertTotals($page, $source){
        $order = $source->getOrder();
        $totals = $this->_getTotalsList($source);
        $lineBlock = array(
            'lines'  => array(),
            'height' => 15
        );
		
		$id = $order->getQuoteId();	
	$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$table = $resource->getTableName('sales/quote_address');
		$query = 'SELECT beezup_fee FROM ' . $table . ' WHERE   quote_id = '.$id.' and address_type = \'shipping\' LIMIT 1';	 
		$fee = $readConnection->fetchOne($query);

 $grand_total = $order->getGrandTotal();
    $grand_format_total = Mage::helper('core')->currency($grand_total, true, false);
			$i = 0;
        foreach ($totals as $total) {
			  if($i == 1 && $fee>0) {
						$format_fee = Mage::helper('core')->currency($fee, true, false);
						$lineBlock['lines'][] = array(
                        array(
                            'text'      => "Frais de Gestion Cdiscount",
                            'feed'      => 475,
                            'align'     => 'right',
                            'font_size' => $totalData['font_size'],
                            'font'      => 'bold'
                        ),
                        array(
                            'text'      => $format_fee,
                            'feed'      => 565,
                            'align'     => 'right',
                            'font_size' =>$totalData['font_size'] ,
                            'font'      => 'bold'
                        ),
                    );
					}
            $total->setOrder($order)
                ->setSource($source);

            if ($total->canDisplay()) {
                $total->setFontSize(10);
			$c = 0;
                foreach ($total->getTotalsForDisplay() as $totalData) {
                  
					$amount = $totalData['amount'];
					if( $grand_format_total  == $amount && $fee >0  && $i > 0) {
				
						$amount = $grand_total + $fee;
						$amount =  Mage::helper('core')->currency($amount, true, false);
					}
					$lineBlock['lines'][] = array(
                        array(
                            'text'      => $totalData['label'],
                            'feed'      => 475,
                            'align'     => 'right',
                            'font_size' => $totalData['font_size'],
                            'font'      => 'bold'
                        ),
                        array(
                            'text'      => $amount,
                            'feed'      => 565,
                            'align'     => 'right',
                            'font_size' => $totalData['font_size'],
                            'font'      => 'bold'
                        ),
                    );
					$c++;
                }
            }
			$i++;
        }

        $this->y -= 20;
        $page = $this->drawLineBlocks($page, array($lineBlock));
        return $page;
    }

	
	
}