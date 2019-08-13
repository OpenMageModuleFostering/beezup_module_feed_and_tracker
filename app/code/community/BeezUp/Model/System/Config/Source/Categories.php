<?php

class BeezUp_Model_System_Config_Source_Categories
{

    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('beezup')->__('Logic 1')),
            array('value' => 0, 'label' => Mage::helper('beezup')->__('Logic 2')),
        );
    }

}