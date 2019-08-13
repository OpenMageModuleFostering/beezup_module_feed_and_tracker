<?php

class BeezUp_Model_System_Config_Source_Shipping
{

    public function toOptionArray()
    {

        return $this->getActiveShippingMethods();

    }

	public function getActiveShippingMethods()
    {
        $methods = array(array('value'=>'','label'=>Mage::helper('beezup')->__('--Please Select--')));

        $activeCarriers = Mage::getSingleton('shipping/config')->getActiveCarriers();
        foreach($activeCarriers as $carrierCode => $carrierModel)
        {
           $options = array();
           if( $carrierMethods = $carrierModel->getAllowedMethods() )
           {
               foreach ($carrierMethods as $methodCode => $method)
               {
                    $code= $carrierCode.'_'.$methodCode;
                    $options[]=array('value'=>$code,'label'=>$method);

               }

               $carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title');

           }

            $methods[]=array('value'=>$options,'label'=>$carrierTitle);
        }
        return $methods;
    }

}
