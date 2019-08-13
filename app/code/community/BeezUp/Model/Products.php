<?php

class BeezUp_Model_Products extends Mage_Core_Model_Abstract
{

    /*
     * Retrieve products collection
     *
     * @param bool $configurable
     * @return Mage_Catalog_Model_Resource_Product_Collection?
     */
    public function getProducts($configurable = false)
    {
    $products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)))
            ->addAttributeToFilter('price', array('neq' => 0))
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('weight')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('special_price')
			->addAttributeToSelect('special_from_date')
            ->addAttributeToSelect('special_to_date')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
            ->addStoreFilter();
	/*	$products = Mage::getModel('catalog/product')
                ->getCollection();
			
*/
        if($configurable) $products->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
		
		if(Mage::getStoreConfig('beezup/flux/stock')){
			$products=	$products->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
						->addAttributeToFilter('inventory_in_stock', array('neq' => 0));
		}
		
        $attributes = explode(',', Mage::getStoreConfig('beezup/flux/attributes'));
        foreach ($attributes as $a) $products->addAttributeToSelect($a);

        if (Mage::getStoreConfig('beezup/flux/debug_flux')) $products->setPageSize(10);

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
	
	public function getProductsSimple()
    {
		$products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)))
            ->addAttributeToFilter('price', array('neq' => 0))
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('weight')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('special_price')
			->addAttributeToSelect('special_from_date')
            ->addAttributeToSelect('special_to_date')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
            ->addStoreFilter();
			
		$products->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);		
		
		if(Mage::getStoreConfig('beezup/flux/stock')){
			$products=	$products->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
						->addAttributeToFilter('inventory_in_stock', array('neq' => 0));
		}

        $attributes = explode(',', Mage::getStoreConfig('beezup/flux/attributes'));
        foreach ($attributes as $a) $products->addAttributeToSelect($a);

        if (Mage::getStoreConfig('beezup/flux/debug_flux')) $products->setPageSize(10);

        return $products;
    }

    /*
     * Retrieve configurable products collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection?
     */
    public function getConfigurableProducts($config = true) {
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
            ->addAttributeToSelect(Mage::getStoreConfig('beezup/flux/description'))
            ->addStoreFilter();
			
		if(Mage::getStoreConfig('beezup/flux/stock')){
			$products=	$products->joinField('inventory_in_stock', 'cataloginventory_stock_item', 'is_in_stock', 'product_id=entity_id','is_in_stock>=0', 'left')
						->addAttributeToFilter('inventory_in_stock', array('neq' => 0));
		}

        $attributes = explode(',', Mage::getStoreConfig('beezup/flux/attributes'));
        foreach ($attributes as $a) $products->addAttributeToSelect($a);

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
    public function getCategoriesAsArray($categories, $parent = '', &$cats = array())
    {
        foreach ($categories as $c) {
            $cats[$c['entity_id']] = $parent . $c['name'];

            if (!Mage::helper('catalog/category_flat')->isEnabled()) {
                if ($childs = $c->getChildren()) {
                    $this->getCategoriesAsArray($childs, $parent . $c['name'] . '||', $cats);
                }
            } else {
                if (isset($c['children_nodes'])) {
                    $this->getCategoriesAsArray($c['children_nodes'], $parent . $c['name'] . '||', $cats);
                }
            }
        }
        return $cats;
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
    * Retrieve current store
    *
    * @return Mage_Core_Model_Store
    */
    public function getStore()
    {
        return Mage::app()->getStore();
    }

}