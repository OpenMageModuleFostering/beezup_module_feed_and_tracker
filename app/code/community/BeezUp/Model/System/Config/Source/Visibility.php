<?php

class BeezUp_Model_System_Config_Source_Visibility
{

    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('beezup')->__('Catalog')),
            array('value' => 2, 'label' => Mage::helper('beezup')->__('Search')),
			array('value' => 3, 'label' => Mage::helper('beezup')->__('Both')),
			array('value' => 7, 'label' => Mage::helper('beezup')->__('Catalog and Both')),
			array('value' => 4, 'label' => Mage::helper('beezup')->__('Catalog, Search and Both')),
			array('value' => 5, 'label' => Mage::helper('beezup')->__('Not Visible')),
			array('value' => 6, 'label' => Mage::helper('beezup')->__('Not Apply Filter')),
        );
    }

}