<?php

class BeezUp_Model_System_Config_Source_Marketchannel
{

    public function toOptionArray()
    {

        $retorno = array();
        $retorno[] = array("value" => "-1", "label" => '');
        $retorno[] = array("value" => "AFN", "label" => "Amazon FBA Orders");

        return $retorno;
    }

}
