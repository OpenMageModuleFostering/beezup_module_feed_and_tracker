<?php

class BeezUp_Model_System_Config_Source_Price
{

    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('beezup')->__('Excl. Tax')),
            array('value' => 0, 'label' => Mage::helper('beezup')->__('Incl. Tax')),
        );
    }

}