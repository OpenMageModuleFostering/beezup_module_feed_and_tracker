<?php
	
	class BeezUp_Model_Products extends Mage_Core_Model_Abstract
	{
		
		/*
			* Retrieve products collection
			*
			* @param bool $configurable
			* @return Mage_Catalog_Model_Resource_Product_Collection?
		*/
		public function getProducts($configurable = false, $pagination= false)
		{
			$products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('price', array('neq' => 0))
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('weight')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('special_price')
			->addAttributeToSelect('special_from_date')
            ->addAttributeToSelect('special_to_date')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('image')
            //->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
			->addAttributeToSelect("description")
			->addAttributeToSelect("short_description")
			->addAttributeToSelect("meta_description")
			->addAttributeToSelect("meta_title")
			->addAttributeToSelect("meta_keyword")
			->addStoreFilter();
			
			$visibility = Mage::getStoreConfig('beezup/flux/visibility');
			switch($visibility) {
				case 1:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG)));
				break;
				case 2:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH)));
				break;
				case 3:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
				break;
				case 4:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
				break;
				case 5:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)));
				break;
				case 6:
				breaK;
				case 7:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
				break;
			}
			
			if($configurable) $products->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
			
			if(Mage::getStoreConfig('beezup/flux/stock')){
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
				//$products=	$products->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
				//->addAttributeToFilter('inventory_in_stock', array('neq' => 0));
			}
			
			$attributes = explode(',', Mage::getStoreConfig('beezup/flux/attributes'));
			foreach ($attributes as $a) $products->addAttributeToSelect($a);
			
			if (Mage::getStoreConfig('beezup/flux/debug_flux')) $products->setPageSize(10);
			
			if($pagination) {
	
				$products->setPageSize((int)$pagination['limit'])->setCurPage((int)$pagination['page']);

			}
			
			return $products;
		}
		
		public function getGroupedProduct()
		{
			$products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
            ->addAttributeToFilter('type_id', array('eq' => 'grouped'));
			
			return $products;
		}
		
		public function getProductsSimple($pagination= false)
		{
			$products = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToFilter('status', 1)
			->addAttributeToFilter('price', array('neq' => 0))
			->addAttributeToSelect('name')
			->addAttributeToSelect('weight')
			->addAttributeToSelect('sku')
			->addAttributeToSelect('special_price')
			->addAttributeToSelect('special_from_date')
			->addAttributeToSelect('special_to_date')
			->addAttributeToSelect('small_image')
			->addAttributeToSelect('image')
			// ->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
			->addAttributeToSelect("description")
			->addAttributeToSelect("short_description")
			->addAttributeToSelect("meta_description")
			->addAttributeToSelect("meta_title")
			->addAttributeToSelect("meta_keyword")
			->addStoreFilter();
			
			$visibility = Mage::getStoreConfig('beezup/flux/visibility');
			switch($visibility) {
				case 1:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG)));
				break;
				case 2:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH)));
				break;
				case 3:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
				break;
				case 4:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
				break;
				case 5:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)));
				break;
				case 6:
				breaK;
				case 7:
				$products->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
				break;
			}
			
			
			$products->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);		
			
			if(Mage::getStoreConfig('beezup/flux/stock')){
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
				//$products=	$products->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
				//->addAttributeToFilter('inventory_in_stock', array('neq' => 0));
			}
			
			$attributes = explode(',', Mage::getStoreConfig('beezup/flux/attributes'));
			foreach ($attributes as $a) $products->addAttributeToSelect($a);
			
			if (Mage::getStoreConfig('beezup/flux/debug_flux')) $products->setPageSize(10);
			
			if($pagination) {
				$products->setPageSize($pagination['limit'])->setCurPage($pagination['page']);
				
			}
			
			return $products;
		}
		
		/*
			* Retrieve configurable products collection
			*
			* @return Mage_Catalog_Model_Resource_Product_Collection?
		*/
		public function getConfigurableProducts($config = true, $pagination= false) {
			$products = Mage::getResourceModel('catalog/product_type_configurable_product_collection')
			->addAttributeToFilter('status', 1)
			->addAttributeToFilter('price', array('neq' => 0))
			->addAttributeToSelect('name')
			->addAttributeToSelect('weight')
			->addAttributeToSelect('sku')
			->addAttributeToSelect('special_from_date')
			->addAttributeToSelect('special_to_date')
			->addAttributeToSelect('special_price')
			->addAttributeToSelect('small_image')
			->addAttributeToSelect('image')
			//->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
			->addAttributeToSelect("description")
			->addAttributeToSelect("short_description")
			->addAttributeToSelect("meta_description")
			->addAttributeToSelect("meta_title")
			->addAttributeToSelect("meta_keyword")
			->addStoreFilter();
			
			if(Mage::getStoreConfig('beezup/flux/stock')){
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
				//$products=	$products->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
				//->addAttributeToFilter('inventory_in_stock', array('neq' => 0));
			}
			
			$attributes = explode(',', Mage::getStoreConfig('beezup/flux/attributes'));
			foreach ($attributes as $a) $products->addAttributeToSelect($a);
			
			if($pagination) {
				$products->setPageSize($pagination['limit'])->setCurPage($pagination['page']);
			}
			
			$productsArray = $products;
			
			//si on est dans le cas où on veut les pères et les fils
			if($config){
				$productsArray = array();
				
				foreach($products as $p) {
					$productsArray[$p->getParentId()][] = $p;
				}
			}
			
			return $productsArray;
		}
		
		
		/*
			* Collect options applicable to the configurable product for Varation Theme
			*
			* @param Mage_Catalog_Model_Product $product
			* @return String
		*/
		
		public function getOptions($product) {
			$childs = $this->getConfigurableProducts();
			
			//si c'est un parent
			if(isset($childs[$product->getId()])) {
				$productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
				
				$attributeOptions = array();
				
				foreach ($productAttributeOptions as $productAttribute) {
					$attributeOptions[] = ucfirst($productAttribute['attribute_code']);
				}
				
				return implode('',$attributeOptions);
			}
		}
		
		/*
			* Retrieve products stock
			*
			* @param int $id
			* @param int $qty
			* @return int
		*/
		public function getQty($productId, $qty = 0)
		{
			$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
			if ($stockItem->getManageStock()) {
				if ($stockItem->getIsInStock()) {
					$qty = intval($stockItem->getQty());
					$qty = ($qty <= 0) ? 0 : $qty;
				}
				} else {
				$qty = 100;
			}
			return $qty;
		}
		
		/*
			* Retrieve product shipping amount
			*
			* @param float $weight
			* @param Mage_Shipping_Model_Mysql4_Carrier_Tablerate_Collection $tablerates
			* @return float
		*/
		public function getShippingAmount($weight, $tablerates)
		{
			$shipping_amount = 0;
			
			if ($tablerates && $tablerates instanceof Mage_Shipping_Model_Mysql4_Carrier_Tablerate_Collection) {
				foreach ($tablerates as $t) {
					if ($weight <= $t->getConditionValue()) $shipping_amount = $t->getPrice();
				}
				} else {
				$shipping_amount = preg_replace('(\,+)', '.', trim(Mage::getStoreConfig('beezup/flux/ship')));
				if (!is_numeric($shipping_amount)) $shipping_amount = 0;
			}
			return $shipping_amount;
		}
		
		/*
			* Retrieve Tablerates
			*
			* @return Mage_Shipping_Model_Mysql4_Carrier_Tablerate_Collection
		*/
		public function getTablerates()
		{
			return Mage::getResourceModel('shipping/carrier_tablerate_collection')->setOrder('condition_value', 'desc');
		}
		
		/*
			* Retrieve product is in stock
			*
			* @param float $qty
			* @return string
		*/
		public function getIsInStock($qty)
		{
			return ($qty > 0) ? Mage::helper('beezup')->__('In Stock') : Mage::helper('beezup')->__('Out of Stock');
		}
		
		/*
			* Retrieve product delivery
			*
			* @param float $qty
			* @return string
		*/
		public function getDelivery($qty)
		{
			return ($qty > 0) ? Mage::getStoreConfig('beezup/flux/days_in') : Mage::getStoreConfig('beezup/flux/days_out');
		}
		
		/*
			* Retrieve store categories as array (recursive)
			*
			* @param Varien_Data_Tree_Node_Collection $categories
			* @param string $parent
			* @param array $cats
			* @return string
		*/
		public function getCategoriesAsArray($categories, $logic = false,  $parent = '', &$cats = array())
		{
			if($logic) {
				$parent = 0;
				$i = 0;
				$tl_name = '';
				$_categories = $categories;
				foreach($_categories as $_category){
					
					if($i==0 ) {
						$par_cat = Mage::getModel('catalog/category')->load($_category->getId())->getParentCategory();
						
						$cats[$par_cat->getId()] =	array("name" => $tl_name.$par_cat->getName(), "id" => $par_cat->getId(), "parent" => $par_cat->getParentId());
					}
					
					
					//$_category = Mage::getModel('catalog/category')->load($_category->getId());
					$cats[$_category->getId()] = array("name" => $tl_name.$_category->getName(), "id" => $_category->getId(), "parent" => $_category->getParentId() ); //Toplevel auslesen
					$i++;
					$_category = Mage::getModel('catalog/category')->load($_category->getId());
					$subcats = $this->getChildCategories($_category);
					
					foreach($subcats as $c) {
						
						$cats[$c['id']] = array("name" => $tl_name.$c['name'], "id" => $c['id'] , "parent" => $c['parent']);
					} 
				}
				return $cats;
				
				} else {
				foreach ($categories as $c) {
					$cats[$c['entity_id']] = $parent . $c['name'];
					
					if (!Mage::helper('catalog/category_flat')->isEnabled()) {
						if ($childs = $c->getChildren()) {
							$this->getCategoriesAsArray($childs, $logic, $parent . $c['name'] . '||', $cats);
						}
						} else {
						if (isset($c['children_nodes'])) {
							$this->getCategoriesAsArray($c['children_nodes'], $logic, $parent . $c['name'] . '||', $cats);
						}
					}
				}
			}
			return $cats;
		}
		
		
		public $_catIds = array();
		public function getChildCategories($categoryObject){
			$categories = $categoryObject->getChildrenCategories();
			foreach ($categories as $catgory){
				if($catgory->hasChildren()){
					$this->getChildCategories($catgory);
				}
				$this->_catIds[] = array("id" => $catgory->getId(), "name" => $catgory->getName(), "parent" => $catgory->getParentId());
			}
			return $this->_catIds;
		}
		
		/*
			* Retrieve product categories
			*
			* @param Mage_Catalog_Model_Product $product
			* @param array $categories
			* @return array
		*/
		public function getProductsCategories($product,$categories)
		{
			
			
			$_categories = $product->getCategoryIds();
			
			sort($_categories);
			$result = array();
			if(count($_categories)) {
				$_count = 0;
				foreach($_categories as $c) {
					if(isset($categories[$c])) {
						if(count(explode('||',$categories[$c])) > $_count) $result = explode('||',$categories[$c]);
						$_count = count($result);
					}
				}
			}
			
			return $result;
		}
		
		
		/*
			* Retrieve product categories
			*
			* @param Mage_Catalog_Model_Product $product
			* @param array $categories
			* @return array
		*/
		public function getProductsCategories2($product,$categories)
		{
			$result = array();
			$_categories = $product->getCategoryIds();
			$parent_id = 0;
			$parent_id = 0;
			$i = 0;
			sort($_categories);
			
			if(count($_categories)) {
				$_count = 0;
				foreach($_categories as $c) {
					if(isset($categories[$c])) {
						if( $parent_id ==  $categories[$c]['parent'] || $i <= 1) {
							$result[] = $categories[$c]['name'];
							
							
							$parent_id = $categories[$c]['id'];
							
						}
						$i++;
						//   if(count(explode('||',$categories[$c])) > $_count) $result = explode('||',$categories[$c]);
						$_count = count($result);
					}
				}
			}
			return $result;
		}
		
		
		/*
			* Retrieve current store
			*
			* @return Mage_Core_Model_Store
		*/
		public function getStore()
		{
			return Mage::app()->getStore();
		}
		
		
		
		
		
		/**
			* Get shipping price
			*
			* @param Mage_Catalog_Model_Product    $product_instance
			* @param string                        $carrierValue
			* @param string                        $countryCode 
			*  
			* @return mixed
		*/
		public function _getShippingPrice($product_instance, $carrierValue, $countryCode = 'FR')
		{
			$carrierTab = explode('_', $carrierValue);
			list($carrierCode, $methodCode) = $carrierTab;
			$shipping = Mage::getModel('shipping/shipping');
			$methodModel = $shipping->getCarrierByCode($carrierCode);
			if($methodModel) {
				$result = $methodModel->collectRates($this->_getShippingRateRequest($product_instance, $countryCode));
				if($result != NULL) {
					if($result->getError()) {
						return 0;
						} else {
						foreach($result->getAllRates() as $rate) {
							return $rate->getPrice();
						}
					}
					} else {
					return 0;
				}
			}
			return 0;
		}
		
		/**
			* Get Shipping rate request
			*
			* @param Mage_Catalog_Model_Product    $product_instance
			* @param string                        $countryCode 
			*  
			* @return Mage_Shipping_Model_Rate_Request
		*/
		protected function _getShippingRateRequest($product_instance, $countryCode = 'FR')
		{
			/** @var $request Mage_Shipping_Model_Rate_Request */
			$request = Mage::getModel('shipping/rate_request');
			$storeId = $request->getStoreId();
			if (!$request->getOrig()) {
				$request->setCountryId($countryCode)
				->setRegionId('')
				->setCity('')
				->setPostcode('');
			}
			$item = Mage::getModel('sales/quote_item');
			$item->setStoreId($storeId);
			$item->setOptions($product_instance->getCustomOptions())
			->setProduct($product_instance);
			$request->setAllItems(array($item));
			$request->setDestCountryId($countryCode);
			$request->setDestRegionId('');
			$request->setDestRegionCode('');
			$request->setDestPostcode('');
			$request->setPackageValue($product_instance->getPrice());
			$request->setPackageValueWithDiscount($product_instance->getFinalPrice());
			$request->setPackageWeight($product_instance->getWeight());
			$request->setFreeMethodWeight(0);
			$request->setPackageQty(1);
			$request->setStoreId(Mage::app()->getStore()->getId());
			$request->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
			$request->setBaseCurrency(Mage::app()->getStore()->getBaseCurrency());
			$request->setPackageCurrency(Mage::app()->getStore()->getCurrentCurrency());
			return $request;
		}
		
		
	}																																					