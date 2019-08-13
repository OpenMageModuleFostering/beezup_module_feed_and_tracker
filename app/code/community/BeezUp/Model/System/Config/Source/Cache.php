<?php

class BeezUp_Model_System_Config_Source_Cache
{

    public function toOptionArray()
    {
        return array(
			array('value' => 0, 'label' => Mage::helper('beezup')->__('None')),
			array('value' => 30, 'label' => Mage::helper('beezup')->__('30 minutes')),
            array('value' => 60, 'label' => Mage::helper('beezup')->__('1 hour')),
            array('value' => 120, 'label' => Mage::helper('beezup')->__('2 hours')),
			array('value' => 240, 'label' => Mage::helper('beezup')->__('4 hours')),
			array('value' => 480, 'label' => Mage::helper('beezup')->__('8 hours')),
            array('value' => 720, 'label' => Mage::helper('beezup')->__('12 hours')),
			array('value' => 1440, 'label' => Mage::helper('beezup')->__('24 hours')),
        );
    }
}
