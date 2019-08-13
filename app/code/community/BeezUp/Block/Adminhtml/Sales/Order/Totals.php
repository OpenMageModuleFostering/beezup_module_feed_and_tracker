<?php 

class BeezUp_Block_Adminhtml_Sales_Order_Totals extends  Mage_Adminhtml_Block_Sales_Order_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
		
$id =$this->getSource()->getQuoteId();
$fee = 0;
	if(is_numeric($id) && $id > 0) {
		 $resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$table = $resource->getTableName('sales/quote_address');
		$query = 'SELECT beezup_fee FROM ' . $table . ' WHERE   quote_id = '.$id.' and address_type = \'shipping\' LIMIT 1';	 
		$fee = $readConnection->fetchOne($query);
	}
	   parent::_initTotals();
      
 
        if ($fee>0) {
            $this->addTotalBefore(new Varien_Object(array(
                'code'      => 'turnkeye_insurance',
                'value'     => $fee,
                'base_value'=> $fee,
                'label'     => 'Frais de Gestion Cdiscount',
            ), array('shipping', 'tax')));
        }
		        $this->_totals['paid'] = new Varien_Object(array(
            'code'      => 'paid',
            'strong'    => true,
            'value'     => $this->getSource()->getTotalPaid() +$fee,
            'base_value'=> $this->getSource()->getBaseTotalPaid() + $fee,
            'label'     => $this->helper('sales')->__('Total Paid'),
            'area'      => 'footer'
        ));
		
		        $this->_totals['grand_total'] = new Varien_Object(array(
            'code'      => 'grand_total',
            'strong'    => true,
            'value'     => $this->getSource()->getGrandTotal() +$fee,
            'base_value'=> $this->getSource()->getBaseGrandTotal() +$fee,
            'label'     => $this->helper('sales')->__('Grand Total'),
            'area'      => 'footer'
        ));
		
 //     $this->setGrandTotal($this->getGrandTotal() + 20);
        return $this;
    }
 
}