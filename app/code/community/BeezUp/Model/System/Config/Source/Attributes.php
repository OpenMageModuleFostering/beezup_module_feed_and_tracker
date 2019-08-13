<?php

class BeezUp_Model_System_Config_Source_Attributes
{

    public function toOptionArray()
    {

        $attribute = Mage::getResourceModel('eav/entity_attribute_collection')->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId());
        $attributeArray = array();

        foreach ($attribute as $option) {
            if ($option->getIsUserDefined() && $option->getFrontendLabel()) {
                $attributeArray[] = array('value' => $option->getAttributeCode(), 'label' => $option->getAttributeCode());
            }
        }

        return $attributeArray;
    }

}