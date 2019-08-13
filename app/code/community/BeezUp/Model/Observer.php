<?php
require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupOMStatus.php";
class BeezUp_Model_Observer
{

    public function addBlockTracking($observer)
    {
        if (!Mage::getStoreConfigFlag('beezup/tracking/active') || !Mage::getStoreConfig('beezup/tracking/storeid')) return '';

        $layout = $observer->getLayout();
        $blockParent = $layout->getXpath("//block[@name='".Mage::getStoreConfig('beezup/tracking/position')."']");

        if (!$blockParent) return $this;

        $block = $blockParent[0]->addChild('block');
        $block->addAttribute('type', 'beezup/tracking');
        $block->addAttribute('name', 'beezup_tracking');
        $block->addAttribute('as', 'beezup_tracking');
    }

    public function addOrder($observer)
    {
        if (!Mage::getStoreConfigFlag('beezup/tracking/active') || !Mage::getStoreConfig('beezup/tracking/storeid')) return '';

        $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
        $beezupBlock = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('beezup_tracking');
        if ($order && $beezupBlock) {
            $beezupBlock->setOrder($order);
        }
    }


    public function autoShip($observer) {

     $helper = Mage::helper('beezup');
     $blnAutoship = $helper->getConfig("beezup/marketplace/autoship_order");
    if($blnAutoship != 1) { return; }
    $default_carrier = $helper->getConfig("beezup/marketplace/autoship_carrier");
     $shipment = $observer->getEvent()->getShipment();
     $order = $shipment->getOrder();
     //$order->getIncrementId();
     $stateShip = $helper->getConfig('beezup/marketplace/status_shipped'); //poner estado que consideres shipped
     // Only trigger when an order enters processing state.
         $shipment_collection = Mage::getResourceModel('sales/order_shipment_collection')
         ->setOrderFilter($order)
         ->load();
         $shipping_data['tracking'] = "";
         $shipping_data['carrier'] = "";
         foreach($shipment_collection as $shipment){
            foreach($shipment->getAllTracks() as $tracking_number){
                $shipping_data['tracking'] = $tracking_number->getNumber();
                $shipping_data['carrier'] = $tracking_number->getTitle();


                break;
            }
            break;
         }
         if(empty($shipping_data['carrier'])) {
            $shipping_data['carrier'] = $default_carrier;
         }
        if(!empty( $shipping_data['tracking']) && !empty($shipping_data['carrier'])) {
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $table = $resource->getTableName('sales/order_grid');
            $query = 'SELECT * FROM ' . $table . ' WHERE beezup_order = 1 and  entity_id = \''
            . $order->getId() . '\' LIMIT 1';
            $results = $readConnection->fetchAll($query);
            //var_dump($results);
            //

            if($results[0]['beezup_order'] == 1) {
                    //it is a beezup order
                    $marketplace_business_code = $results[0]['beezup_marketplace_business_code'];
                    $marketplace_technical_code = $results[0]['beezup_marketplace'];

                    $shipping_data['marketplace_business_code'] = $marketplace_business_code;
                    $shipping_data['marketplace_technical_code'] = $marketplace_technical_code;

                    //if(isset($autoship_map[$marketplace_business_code])) {
                        $this->__updateBeezUPOrderStatus($order, $shipping_data);
                    //}

            }
        }



    }


    private function _MarketplaceTechnicalCodeCarriers($shipping_data ) {
    $helper = Mage::helper('beezup');
    $autoship_map = $helper->getConfig("beezup/marketplace/autoship_order_map");
    $autoship_map = unserialize($autoship_map);
    $code = $shipping_data['marketplace_technical_code'];
    $business_code = strtoupper($shipping_data['marketplace_business_code']);
    $id_carrier = $shipping_data['carrier'];
    $retorno = "";

    if($code =="PriceMinister") {
               //PriceMinisterCarrierName
               $retorno =  $this->_getOMStatusCarrier($autoship_map['PriceMinister'], $id_carrier);
               } elseif($code=="Fnac") {
               //FnacCarrierName
               $retorno =  $this->_getOMStatusCarrier( $autoship_map['Fnac'], $id_carrier);;

               } elseif($code == "Mirakl") {
               if($business_code == "DARTY") {
                  //DartyCarrierCode
                  $retorno =  $this->_getOMStatusCarrier( $autoship_map['DARTY'], $id_carrier);;
                  } elseif($business_code == "BOULANGER") {
                  //BoulangerCarrierCode
                  $retorno =  $this->_getOMStatusCarrier( $autoship_map['BOULANGER'], $id_carrier);;
                  } elseif($business_code == "LEQUIPE") {
                  //LEquipeCarrierCode
                  $retorno =   $this->_getOMStatusCarrier($autoship_map['LEQUIPE'], $id_carrier);;
                  } elseif($business_code == "COMPTOIRSANTE") {
                  //ComptoirSanteCarrierCode
                  $retorno =   $this->_getOMStatusCarrier($autoship_map['COMPTOIRSANTE'], $id_carrier);;
                  } elseif($business_code == "RUEDUCOMMERCE") {
                  //RuedDuCommerceCarrierCode
                  $retorno =   $this->_getOMStatusCarrier($autoship_map['RUEDUCOMMERCE'], $id_carrier);;
               }  elseif($business_code == "OUTIZ") {
                     $retorno =   $this->_getOMStatusCarrier($autoship_map['OUTIZ'], $id_carrier);
               } else {
                        $retorno =   $this->_getOMStatusCarrier($autoship_map[$business_code], $id_carrier);
               }
            }
            return $retorno;
            }

            private function _getOMStatusCarrier($autoship_map, $carrier) {
                foreach($autoship_map as $map) {

                    if($map['mage_carrierValue'] == $carrier) {
                            return array("name" => $map['beezup_carrierName'], "code" => $map['beezup_carrierCode']);
                    }
                }
                return false;
            }

    private function __updateBeezUPOrderStatus($order, $shipping_data) {
        $today = date("Y-m-d H:i:s");
        $post_data = array();
        $omStatus = new BeezupOmStatus();
        $orderOptions = $omStatus->getInfo($order->getId());
        $post_data['order_id'] = $order->getId();

        foreach($orderOptions as $key => $action) {
            if(isset($action['action'])) {
                if($action['action'] == "ShipOrder") {
                  $post_data['action_id'] = $action['action'];
                    $parameters = $action['parameters'];

                    foreach($parameters as $parameter) {
                        $post_data[$parameter->name] = "";
                        if($parameter->name == "Order_Shipping_FulfillmentDate") {
                           $post_data["Order_Shipping_FulfillmentDate"] = $today;
                        }
                        elseif($parameter->name == "Order_Shipping_ShipperTrackingNumber") {
                           $post_data['Order_Shipping_ShipperTrackingNumber'] = $shipping_data['tracking'];
                        }
                        elseif($parameter->name == "Order_Shipping_CarrierName" || $parameter->name == "Order_Shipping_CarrierCode") {

                           $carrier_name = $this->_MarketplaceTechnicalCodeCarriers($shipping_data);

                           if($carrier_name && $carrier_name != "") {
                              if($parameter->name == "Order_Shipping_CarrierName" ) {
                              $post_data[$parameter->name] = $carrier_name['code'];
                           } else {
                              $post_data[$parameter->name] = $carrier_name['code'];
                           }
                           } else {
                              $post_data[$parameter->name] = $shipping_data['carrier'];
                           }

                        }
                        elseif($parameter->name == "Order_Shipping_EstimatedDeliveryDate") {
                           $post_data["Order_Shipping_EstimatedDeliveryDate"] = $today;
                        }

                    }
                        $post_data['adminUser'] = "autoship";

                        $ret = $omStatus->changeOrder($post_data);

                        break;
                }
            }
        }

    }


}
