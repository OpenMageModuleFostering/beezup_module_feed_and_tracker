<?php

class BeezUp_Model_System_Config_Source_Montant
{

    public function toOptionArray()
    {
        return array(
            array('value' => "HT", 'label' => Mage::helper('beezup')->__('Excl. Tax - without shipping costs')),
			array('value' => "HT_port", 'label' => Mage::helper('beezup')->__('Excl. Tax - with shipping costs')),
			array('value' => "TTC", 'label' => Mage::helper('beezup')->__('Incl. Tax - without shipping costs')),
            array('value' => "TTC_port", 'label' => Mage::helper('beezup')->__('Incl. Tax - with shipping costs')),
        );
    }

}