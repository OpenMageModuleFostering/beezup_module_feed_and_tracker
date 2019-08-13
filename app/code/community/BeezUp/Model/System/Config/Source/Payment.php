<?php

class BeezUp_Model_System_Config_Source_Payment
{

    public function toOptionArray()
    {
$payments = Mage::getSingleton('payment/config')->getActiveMethods();
//$methods = array(array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('–Please Select–')));

foreach ($payments as $paymentCode=>$paymentModel) {
$paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
$methods[$paymentCode] = array(
   'label'   => $paymentTitle,
   'value' => $paymentCode,
);
}
return $methods;
    }

}

