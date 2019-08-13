<?php
	
	class BeezUp_Helper_Data extends Mage_Core_Helper_Abstract
	{
		
		public function _getProductPrice($objProduct){
			
			$price = '';
			$classId = $objProduct->getTaxClassId();
			
			$catalogRulePrice = "";
			$taxprice = Mage::helper('tax')->getPrice($objProduct, $objProduct->getFinalPrice());
			$catalogRulePrice = Mage::getModel('catalogrule/rule')->calcProductPriceRule($objProduct, $taxprice);
			$price = $taxprice;
			// Added logc to consider speacial price in feed if it is available        
			if ($objProduct->getSpecialPrice()) {
				$today = mktime(0, 0, 0, date('m'), date('d'), date('y'));
				$todaytimestamp = strtotime(date('Y-m-d 00:00:00', $today));
				$spcl_pri_time = strtotime($objProduct->getSpecialToDate());
				if ($spcl_pri_time <= $todaytimestamp) {
					$_price = number_format($objProduct->getSpecialPrice(), 2, ".", "");
					} else {
					$_price = number_format($objProduct->getPrice(), 2, ".", "");
				}
				
				
				} else if ($catalogRulePrice) {
				$price = number_format($catalogRulePrice, 2, ".", "");            
				} else {
				$_price = number_format($objProduct->getPrice(), 2, ".", "");
				
			}
			
			return $price;
			
		}
		
		
		/*
			* Retrieve product image directory
			*
			* @return string
		*/
		public function getImageDir()
		{
			return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product';
		}
		
		/*
			* Retrieve config path value
			*
			* @return string
		*/
		public function getConfig($path = '')
		{
			if($path) {
				return Mage::getStoreConfig($path);
			}
			return '';
		}
		
		/*
			* Retrieve Remote Address
			*
			* @return string
		*/
		public function getRemoteAddr()
		{
			return $_SERVER['REMOTE_ADDR'];
		}
		
		/*
			* Retrieve tag
			*
		* @param string $tagName
		* @param string $content
		* @param bool $cdata
		* @return string
		*/
		public function tag($tagName, $content, $cdata = 0)
		{
        $result = '<' . $tagName . '>';
        if ($cdata) $result .= '<![CDATA[';
        $result .= preg_replace('(^' . $this->__('No') . '$)', '', trim($content));
        if ($cdata) $result .= ']]>';
        $result .= '</' . $tagName . '>';
        return $result;
		}
		
		/*
		* Format currency
		*
		* @param float $v
		* @return string
		*/
		public function currency($v)
		{
        return number_format($v, 2, '.', '');
		}
		
		}		