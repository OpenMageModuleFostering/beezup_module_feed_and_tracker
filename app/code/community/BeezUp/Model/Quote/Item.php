<?php

class BeezUp_Model_Quote_Item extends Mage_Sales_Model_Quote_Item {

 
    public function setBeezupPrice($value) {
        $shop = $this->getQuote()->getStore();
        
		if (!Mage::helper('tax')->priceIncludesTax($shop)) {
        
            $amount_deee = 0;
            if (!Mage::helper('tax')->priceIncludesTax($shop)) {
                $wee_helper = Mage::helper('weee');
                $amount_deee = $wee_helper->getAmount($this->getProduct());
            }
			 $shippingaddress = $this->getQuote()->getShippingAddress();
			
            $billingaddress = $this->getQuote()->getBillingAddress();

            $beezup_address = $this->getAddress();
            if ($beezup_address) {
                switch ($beezup_address->getAddressType()) {
					case Mage_Sales_Model_Quote_Address::TYPE_SHIPPING:
                        $shippingaddress = $beezup_address;
                        break;
                    case Mage_Sales_Model_Quote_Address::TYPE_BILLING:
                        $billingaddress = $beezup_address;
                        break;

                }
            }

            if ($this->getProduct()->getIsVirtual()) {
                $shippingaddress = $billingaddress;
            }

            $PriceTaxExcluded = Mage::helper('tax')->getPrice(
                $this->getProduct()->setTaxPercent(null),
                $value,
                false,
                $shippingaddress,
                $billingaddress,
                $this->getQuote()->getCustomerTaxClassId(),
                $shop,
                true
            ) - $amount_deee;
        
            $PriceTaxIncluded = Mage::helper('tax')->getPrice(
                $this->getProduct()->setTaxPercent(null),
                $value,
                true,
                $shippingaddress,
                $billingaddress,
                $this->getQuote()->getCustomerTaxClassId(),
                $shop,
                true
            );
			
		    $this->setCustomPrice($PriceTaxExcluded);
        
            $this->setOriginalPrice($PriceTaxExcluded);

			$this->setOriginalCustomPrice($PriceTaxExcluded);
				
            $quantity = $this->getQty();
            if ($this->getParentItem()) {
                $quantity = $quantity*$this->getParentItem()->getQty();
            }

                $beezup_rowTotal = $value * $quantity;
                $beezup_rowTotalExcTax = Mage::helper('tax')->getPrice(
                    $this->getProduct()->setTaxPercent(null),
                    $beezup_rowTotal,
                    false,
                    $shippingaddress,
                    $billingaddress,
                    $this->getQuote()->getCustomerTaxClassId(),
                    $shop,
                    true
                ) - ($amount_deee * $quantity);
                $beezup_rowTotalIncTax = Mage::helper('tax')->getPrice(
                    $this->getProduct()->setTaxPercent(null),
                    $beezup_rowTotal,
                    true,
                    $shippingaddress,
                    $billingaddress,
                    $this->getQuote()->getCustomerTaxClassId(),
                    $shop,
                    true
                );
                $beezup_totalBaseTax = $beezup_rowTotalIncTax-$beezup_rowTotalExcTax;
				$taxAmount = $PriceTaxIncluded - $PriceTaxExcluded ;
                $beezup_totalBaseTax = $taxAmount*$quantity;
				$totalTax = $this->getStore()->convertPrice($beezup_totalBaseTax);

                $this->setTaxPercent($this->getProduct()->getTaxPercent());
				 $this->setBaseRowTotalInclTax($beezup_rowTotal);
                $this->setBaseRowTotal($beezup_rowTotal);
            $this->setBaseTaxBeforeDiscount($beezup_totalBaseTax);		
            $this->setTaxBeforeDiscount($totalTax);
            $this->setBaseTaxAmount($beezup_totalBaseTax);
			$this->setTaxAmount($totalTax);
            $this->setBaseOriginalPrice($PriceTaxExcluded);
            $this->setTaxAmount($totalTax);
            $this->setPriceInclTax($PriceTaxIncluded);
            $this->setBasePriceInclTax($PriceTaxIncluded);
			$this->setPrice($PriceTaxExcluded);
            return $this;
        } else {
            return $this;
        }
    }
    
    
}