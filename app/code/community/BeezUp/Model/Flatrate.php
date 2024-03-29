<?php 


class BeezUp_Model_Flatrate extends Mage_Shipping_Model_Carrier_Abstract  implements Mage_Shipping_Model_Carrier_Interface
   {

    protected $_code = 'flatrate';
    protected $_isFixed = true;


  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {

                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeBoxes += $item->getQty() * $child->getQty();
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        $this->setFreeBoxes($freeBoxes);

        $result = Mage::getModel('shipping/rate_result');
        if ($this->getConfigData('type') == 'O') { // per order
            $shippingPrice = $this->getConfigData('price');
        } elseif ($this->getConfigData('type') == 'I') { // per item
            $shippingPrice = ($request->getPackageQty() * $this->getConfigData('price')) - ($this->getFreeBoxes() * $this->getConfigData('price'));
        } else {
            $shippingPrice = false;
        }

        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

        if ($shippingPrice !== false) {
            $method = Mage::getModel('shipping/rate_result_method');

            $method->setCarrier('flatrate');
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod('flatrate');
            $method->setMethodTitle($this->getConfigData('name'));

            if ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
                $shippingPrice = '0.00';
            }

if(Mage::registry('shipping_cost'))
{
	if(Mage::registry('shipping_cost') == 20000) {
		$method->setPrice('0.00');
 $method->setCost('0.00');
	} else {
 $method->setPrice(Mage::registry('shipping_cost'));
 $method->setCost(Mage::registry('shipping_cost'));
	}
 } else {
 $method->setPrice($shippingPrice);
 $method->setCost($shippingPrice);
}

            $result->append($method);
        }

        return $result;
    }

	
    public function getAllowedMethods()
    {
		
        return array('flatrate'=>$this->getConfigData('name'));
    }	
	
	
	
   }
