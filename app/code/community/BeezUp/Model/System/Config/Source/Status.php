<?php

class BeezUp_Model_System_Config_Source_Status
{

    public function toOptionArray()
    {
		
$orderStatusCollection = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
$status = array();
$status = array(
    '-1'=>'Please Select..'
);

foreach($orderStatusCollection as $orderStatus) {
    $status[] = array (
        'value' => $orderStatus['status'], 'label' => $orderStatus['label']
    );
}
return $status;
    }

}