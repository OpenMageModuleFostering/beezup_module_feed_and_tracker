<?php

class BeezUp_Model_System_Config_Source_Position
{

    public function toOptionArray()
    {
        return array(
            array('value' => 'head', 'label' => Mage::helper('beezup')->__('Head')),
            array('value' => 'before_body_end', 'label' => Mage::helper('beezup')->__('Before body end')),
        );
    }

}