<?php

class BeezUp_Model_System_Config_Source_Availableproducts
{


    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('beezup')->__('Yes')),
            array('value' => 0, 'label' => Mage::helper('beezup')->__('No')),
        );
    }
}