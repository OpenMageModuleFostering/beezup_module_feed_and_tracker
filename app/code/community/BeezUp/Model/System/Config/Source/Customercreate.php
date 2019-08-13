<?php

class BeezUp_Model_System_Config_Source_Customercreate
{

    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => Mage::helper('beezup')->__('Enable')),
            array('value' => 1, 'label' => Mage::helper('beezup')->__('Disable')),
        );
    }

}