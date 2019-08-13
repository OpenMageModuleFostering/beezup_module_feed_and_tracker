<?php


class Beezup_Model_System_Config_Backend_Autoship extends Mage_Core_Model_Config_Data
{
    protected function _afterLoad()
    {

        if (!is_array($this->getValue())) {
            $value = $this->getValue();
            $this->setValue(empty($value) ? false : unserialize($value));
        }
    }

    protected function _beforeSave()
    {

		//    Mage::getSingleton('core/session')->addError("Error");
        if (is_array($this->getValue())) {
            $retorno = array();
            $datos = $this->getValue();

            foreach($datos as $key => $dato) {
                foreach($dato['beezup'] as $b_key => $b) {
                    if($b_key == 0) { continue; }
                    $b = explode("|", $b);
                    if($key == "Mirakl") {
                        $key = $b[3];
                    }

                    $retorno[$key][$b_key]['beezup_marketplace'] = $b[3];
                    $retorno[$key][$b_key]['beezup_carrierCode'] = $b[2];
                    $retorno[$key][$b_key]['beezup_carrierName'] = $b[1];
                }

                foreach($dato['mage'] as $m_key =>  $m) {
                    if($m_key == 0) { continue;}
                    $retorno[$key][$m_key]['mage_carrierValue'] = $m;
                }

            }


	       $this->setValue(serialize($retorno));
       }
    }




}
