<?php
class Beezup_Model_System_Config_Backend_Marketchannel
    extends Mage_Core_Model_Config_Data
{
    public function beforeSave($object)
    {
        $value = $this->getValue();
        if ($value == "-1")
        {
            $this->setValue("");
        } else {
            $this->setValue($value);
        }

    }
}
