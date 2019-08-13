<?php

class BeezUp_Model_System_Config_Source_Description
{

    public function toOptionArray()
    {
        return array(
            array('value' => 'short_description', 'label' => Mage::helper('beezup')->__('Short Description')),
            array('value' => 'description', 'label' => Mage::helper('beezup')->__('Description')),
            array('value' => 'meta_description', 'label' => Mage::helper('beezup')->__('Meta Description')),
        );
    }

}