<?php

class Beezup_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface {

      protected $_code = 'beezup';
      private $_shipPrice = 0;

      public function getAllowedMethods()
      {
          /*
          return array(
              'standard'    =>  'Standard delivery',
              'express'     =>  'Express delivery',
          );*/
          return array(
              'beezup'    =>  'Beezup Carrier'
          );
      }

      public function collectRates(Mage_Shipping_Model_Rate_Request $request)
      {
          if(!Mage::registry('shipping_cost'))
          {
              return false;
          }

          if(Mage::registry('shipping_cost') == 20000) {
              $this->_shipPrice = 0;
          	} else {
              $this->_shipPrice = Mage::registry('shipping_cost');
          	}
        

      	/** @var Mage_Shipping_Model_Rate_Result $result */
      	$result = Mage::getModel('shipping/rate_result');
      	$result->append($this->_getStandardRate());

      	return $result;
      }



      protected function _getStandardRate()
      {
          /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
          $rate = Mage::getModel('shipping/rate_result_method');

          $rate->setCarrier($this->_code);
          $rate->setCarrierTitle($this->getConfigData('title'));
          $rate->setMethod('beezup');
          $rate->setMethodTitle('Beezup Carrier');
          $rate->setPrice($this->_shipPrice);
          $rate->setCost($this->_shipPrice);
          return $rate;
      }



    }
