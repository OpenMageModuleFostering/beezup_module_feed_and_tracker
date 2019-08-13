<?php
ini_set('memory_limit','1024M');
class BeezUp_Block_Xml extends Mage_Core_Block_Text
{
	/**
		Xml permet de récupérer tous les produits simples 
	**/
    public function getXml()
    {
		$base_url = Mage::getBaseUrl();
        /* Load Model and Helper */
        $beezup = Mage::getModel('beezup/products');
        $helper = Mage::helper('beezup');

        /* Initially load the useful elements */
		$many_images = $helper->getConfig('beezup/flux/images');
        $_ht = $helper->getConfig('beezup/flux/ht');
        $_description = $helper->getConfig('beezup/flux/description');
        $_tablerates = $helper->getConfig('beezup/flux/tablerates_weight_destination') ? $beezup->getTablerates() : 0;
        $_categories = $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
        $_attributes = $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
        $_vat = ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
        $_catalog_rules = $helper->getConfig('beezup/flux/catalog_rules');

        /* Build file */
        $xml = $helper->getConfig('beezup/flux/bom') ? "\xEF\xBB\xBF" : '';
        $xml .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<catalog>' . PHP_EOL;

        $products = $beezup->getProducts();
		if($many_images == 1) {
$backendModel = $products->getResource()->getAttribute('media_gallery')->getBackend();
		}
        foreach ($products as $p) {
            $categories = $beezup->getProductsCategories($p, $_categories);

            if (count($categories)) {
                $qty = $beezup->getQty($p->getId());
                $stock = $beezup->getIsInStock($qty);
                $shipping = $beezup->getDelivery($qty);
				$price = $p->getPrice();
				$final_price = $p->getFinalPrice();
				if (($image = $p->getImage()) == "no_selection" || ($image = $p->getImage()) == "") // Si on ne trouve pas d'image avec getImage on récupère la smallImage
					$image = $p->getSmallImage();



				
                $xml .= '<product>';
                $xml .= $helper->tag($this->__('b_unique_id'), $p->getId());
                $xml .= $helper->tag($this->__('b_sku'), trim($p->getSku()), 1);
                $xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
                $xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
                $xml .= $helper->tag($this->__('b_product_url'), $p->getProductUrl(), 1);
				
		
		
				$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1);	
		if($many_images == 1) {
		$inc = 1;
				//we get product object from catalog/product reason(beezup/products gets products from catalog/product_collection, didn't find the way to get image collection from there *will check)
				//$product = Mage::getModel('catalog/product')->load( $p->getId());
			 $backendModel->afterLoad($p); //adding media gallery to the product object
			 $datos = $p->getData();
				foreach ($datos['media_gallery']['images'] as $img) {
				
				if($img['disabled']==0 && $image !== $img['file']) {
				$inc++;
				$xml .= $helper->tag($this->__('url_image')."_".$inc, $helper->getImageDir() .$img['file'], 1);
			
					}
				}
				
		} 
		
				/*
			//we get all category id's from product
				$currentCatIds = $product->getCategoryIds();
				//we get a collection of the categories from the ids
				$categoryCollection = Mage::getResourceModel('catalog/category_collection')
									->addAttributeToSelect('name')
									->addAttributeToSelect('url')
									->addAttributeToFilter('entity_id', $currentCatIds)
									->addIsActiveFilter();
				$inc_cat = 0;
				//loop through category collection to add to xml categories
				foreach($categoryCollection as $cat){
				$inc_cat++;
				$xml .= $helper->tag($this->__('b_product_category_'.$inc_cat), $cat->getUrl(), 1);
				}		*/
				
				
             //   $xml .= $helper->tag($this->__('b_product_image'), $helper->getImageDir() . $image, 1);
                $xml .= $helper->tag($this->__('b_availability'), $stock, 1);
                $xml .= $helper->tag($this->__('b_qty'), $qty);
                $xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);
                $xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($p->getWeight(), $_tablerates)));
                $xml .= $helper->tag($this->__('b_weight'), $helper->currency($p->getWeight()));
                $xml .= $helper->tag($this->__('b_price'), $helper->currency($final_price*$_vat));
                if ($price != $final_price) $xml .= $helper->tag($this->__('b_regular_price'), $helper->currency($price*$_vat));
                $i = 1;
                foreach ($categories as $v) $xml .= $helper->tag($this->__('b_category_%s', $i++), $v, 1);
                foreach ($_attributes as $a) {
                    $value = $p->getResource()->getAttribute($a)->getFrontend()->getValue($p);
                    $xml .= $helper->tag($a, is_float($value) ? $helper->currency($value) : $value, is_float($value) ? 0 : 1);
                }

                $xml .= '</product>' . PHP_EOL;
            }
        }
        $xml .= $this->getAssociatedProducto(false);


        $xml .= '</catalog>';

        return $xml;
    }

	/**
		Configurable permet de récupérer tous les produits (père, enfant et simple)
	**/
    public function getXmlConfigurable()
    {	
	$base_url = Mage::getBaseUrl();
        /* Load Model and Helper */
        $beezup = Mage::getModel('beezup/products');
        $helper = Mage::helper('beezup');

        /* Initially load the useful elements */
		$many_images = $helper->getConfig('beezup/flux/images');
        $_ht 				= $helper->getConfig('beezup/flux/ht');
        $_description 		= $helper->getConfig('beezup/flux/description');
        $_tablerates 		= $helper->getConfig('beezup/flux/tablerates_weight_destination') ? $beezup->getTablerates() : 0;
        $_categories 		= $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
        $_attributes 		= $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
        $_vat 				= ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
        $_catalog_rules 	= $helper->getConfig('beezup/flux/catalog_rules');

        /* Build file */
        $xml = $helper->getConfig('beezup/flux/bom') ? "\xEF\xBB\xBF" : '';
        $xml .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<catalog>' . PHP_EOL;
		
		//récupére tous les produits
        $products = $beezup->getProducts();
        $childs = $beezup->getConfigurableProducts(true);
$backendModel = $products->getResource()->getAttribute('media_gallery')->getBackend();
$products->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
		//parcours les produits
       foreach ($products as $p) {
		   if($many_images == 1) {
			   	 $backendModel->afterLoad($p); //adding media gallery to the product object
			 $datos = $p->getData();
			   
		   }
		   
            $categories = $beezup->getProductsCategories($p, $_categories);
            $varationTheme = $beezup->getOptions($p);
			//we get product object from catalog/product reason(beezup/products gets products from catalog/product_collection, didn't find the way to get image collection from there *will check)
				
            if (count($categories)) {
				//si l'élément est un père, on va traiter ces enfants
				if(isset($childs[$p->getId()])) {	
					$childrens = $childs[$p->getId()];
						
					foreach($childrens as $c) {
						$qty 			= $beezup->getQty($c->getId());
						$stock 			= $beezup->getIsInStock($qty);
						$shipping 		= $beezup->getDelivery($qty);
						$price 			= $c->getPrice();
						$final_price 	= $c->getFinalPrice();
						$image 			= $this->fillImageUrl($p, $c);

						//DBG
							if (0)
							{
				        		echo "----------------------------"						."<br/>";
				        		echo "Name : "		.$c->getName()						."<br/>";
				        		echo "Id : "		.$c->getId()						."<br/>";
				        		echo "Parent Id : "	.$p->getId()							."<br/>";
				        		echo "Url : "		.$p->getProductUrl()				."<br/>";
				        		echo "Image : "		.$helper->getImageDir().$image 		."<br/>";


				        		echo "SKU : "		.$c->getSku()						."<br/>";
				        		echo "Quantity : " 	.$qty 								."<br/>";
				        		echo "Stock : " 	.$stock 							."<br/>";
								echo "Shipping : " 	.$shipping 							."<br/>";
								echo "price : "		.$price 							."<br/>";
								echo "finalprice : ".$final_price 						."<br/>";
								echo "specialprice : ".$c->getSpecialPrice() 			."<br/>";
							}

						
						$xml .= '<product>';
						$xml .= $helper->tag($this->__('b_unique_id'), $c->getId());
						$xml .= $helper->tag($this->__('b_sku'), trim($c->getSku()), 1);

						$xml .= $helper->tag($this->__('parent_or_child'), 'child', 1);
						$xml .= $helper->tag($this->__('parent_id'), $p->getId());
						$xml .= $helper->tag($this->__('variation-theme'), $varationTheme, 1);

						$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
						$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
						$xml .= $helper->tag($this->__('b_product_url'), $p->getProductUrl(), 1);
			
		$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1);
	if($many_images==1)		{
		
			$product = Mage::getModel('catalog/product')->load( $c->getId());
						$inc = 1;
				foreach ($product->getMediaGalleryImages() as $img) {
			if( $helper->getImageDir() . $image !== $img->getUrl()) {
			$inc++;
				$xml .= $helper->tag($this->__('url_image')."_".$inc, $img->getUrl(), 1);
			}
					
				}
			
				if($inc==1 && ($c->getImage() == "no_selection" || $c->getImage()=="" || $c->getSmallImage() == "no_selection" || $c->getSmallImage() == "")) { //if there are no child pictures
									
				$product = Mage::getModel('catalog/product')->load( $p->getId());
						$inc = 1;
						foreach ($product->getMediaGalleryImages() as $img) {
							if( $helper->getImageDir() . $image !== $img->getUrl()) {
							$inc++;
							$xml .= $helper->tag($this->__('url_image')."_".$inc, $img->getUrl(), 1);
							}
						}
			
			
				}
			} 		
						$xml .= $helper->tag($this->__('b_availability'), $stock, 1);
						$xml .= $helper->tag($this->__('b_qty'), $qty);
						$xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($c->getWeight(), $_tablerates)));
						$xml .= $helper->tag($this->__('b_weight'), $helper->currency($c->getWeight()));
						$xml .= $helper->tag($this->__('b_price'), $helper->currency($final_price*$_vat));
						if ($price != $final_price) $xml .= $helper->tag($this->__('b_regular_price'), $helper->currency($price*$_vat));
						$i = 1;
						foreach ($categories as $v) $xml .= $helper->tag($this->__('b_category_%s', $i++), $v, 1);
						foreach ($_attributes as $a) {
							$value = $c->getResource()->getAttribute($a)->getFrontend()->getValue($c);
							$xml .= $helper->tag($a, is_float($value) ? $helper->currency($value) : $value, is_float($value) ? 0 : 1);
						}
						$xml .= '</product>' . PHP_EOL;
					}
					
					$qty = $beezup->getQty($p->getId());
					$stock = $beezup->getIsInStock($qty);
					$shipping = $beezup->getDelivery($qty);
					$price = $p->getPrice();
					$final_price = $p->getFinalPrice();
					if (($image = $p->getImage()) == "no_selection" || ($image = $p->getImage()) == "") // Si on ne trouve pas d'image avec getImage on récupère la smallImage
						$image = $p->getSmallImage();
					
					
					// si c'est un élément parent
					$xml .= '<product>';
					$xml .= $helper->tag($this->__('b_unique_id'), $p->getId());
					$xml .= $helper->tag($this->__('b_sku'), trim($p->getSku()), 1);

					$xml .= $helper->tag($this->__('parent_or_child'), 'parent', 1);
					$xml .= $helper->tag($this->__('parent_id'), '');
					$xml .= $helper->tag($this->__('variation-theme'), $varationTheme, 1);

					$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
					$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
					$xml .= $helper->tag($this->__('b_product_url'), $p->getProductUrl(), 1);
						$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1);		
		if($many_images == 1) {
		$inc = 1;
				//we get product object from catalog/product reason(beezup/products gets products from catalog/product_collection, didn't find the way to get image collection from there *will check)
				//$product = Mage::getModel('catalog/product')->load( $p->getId());
		
				foreach ($datos['media_gallery']['images'] as $img) {
				
				if($img['disabled']==0 && $image !== $img['file']) {
				$inc++;
				$xml .= $helper->tag($this->__('url_image')."_".$inc, $helper->getImageDir() .$img['file'], 1);
			
					}
				}
				
		} 
		
					$xml .= $helper->tag($this->__('b_availability'), $stock, 1);
					$xml .= $helper->tag($this->__('b_qty'), $qty);
					$xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);
					$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($p->getWeight(), $_tablerates)));
					$xml .= $helper->tag($this->__('b_weight'), $helper->currency($p->getWeight()));
					$xml .= $helper->tag($this->__('b_price'), $helper->currency($final_price*$_vat));
					if ($price != $final_price) $xml .= $helper->tag($this->__('b_regular_price'), $helper->currency($price*$_vat));
					$i = 1;
					foreach ($categories as $v) $xml .= $helper->tag($this->__('b_category_%s', $i++), $v, 1);
					foreach ($_attributes as $a) {
						$value = $p->getResource()->getAttribute($a)->getFrontend()->getValue($p);
						$xml .= $helper->tag($a, is_float($value) ? $helper->currency($value) : $value, is_float($value) ? 0 : 1);
					}

					$xml .= '</product>' . PHP_EOL;
				}
            }
        }
		
		$product_simple = $beezup->getProductsSimple();
		$backendModelSimple = $product_simple->getResource()->getAttribute('media_gallery')->getBackend();
		foreach ($product_simple as $p) {
		 
			$prodAttributeSet = Mage::getModel('eav/entity_attribute_set')->load($p->getAttributeSetId())->getAttributeSetName(); 
		 
				$categories = $beezup->getProductsCategories($p, $_categories);
				
				if (count($categories)) {
					$qty = $beezup->getQty($p->getId());
					$stock = $beezup->getIsInStock($qty);
					$shipping = $beezup->getDelivery($qty);
					$price = $p->getPrice();
					$final_price = $p->getFinalPrice();
				if (($image = $p->getImage()) == "no_selection" || ($image = $p->getImage()) == "") // Si on ne trouve pas d'image avec getImage on récupère la smallImage
					$image = $p->getSmallImage();
					
							
					$xml .= '<product>';
					$xml .= $helper->tag($this->__('b_unique_id'), $p->getId());
					$xml .= $helper->tag($this->__('b_sku'), trim($p->getSku()), 1);
							
					$xml .= $helper->tag($this->__('parent_or_child'), 'simple', 1);
					$xml .= $helper->tag($this->__('parent_id'), '');
					$xml .= $helper->tag($this->__('variation-theme'), '', 1);
							
					$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
					$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
					$xml .= $helper->tag($this->__('b_product_url'), $p->getProductUrl(), 1);
					$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1);
		if($many_images == 1) {
		$inc = 1;
				//we get product object from catalog/product reason(beezup/products gets products from catalog/product_collection, didn't find the way to get image collection from there *will check)
				//$product = Mage::getModel('catalog/product')->load( $p->getId());
			 $backendModelSimple->afterLoad($p); //adding media gallery to the product object
			 $datos = $p->getData();
				foreach ($datos['media_gallery']['images'] as $img) {
				
				if($img['disabled']==0 && $image !== $img['file']) {
				$inc++;
				$xml .= $helper->tag($this->__('url_image')."_".$inc, $helper->getImageDir() .$img['file'], 1);
			
					}
				}
				
		} 			
				
					$xml .= $helper->tag($this->__('b_availability'), $stock, 1);
					$xml .= $helper->tag($this->__('b_qty'), $qty);
					$xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);
					$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($p->getWeight(), $_tablerates)));
					$xml .= $helper->tag($this->__('b_weight'), $helper->currency($p->getWeight()));
					$xml .= $helper->tag($this->__('b_price'), $helper->currency($final_price*$_vat));
					if ($price != $final_price) $xml .= $helper->tag($this->__('b_regular_price'), $helper->currency($price*$_vat));
					$i = 1;
					foreach ($categories as $v) $xml .= $helper->tag($this->__('b_category_%s', $i++), $v, 1);
					foreach ($_attributes as $a) {
						$value = $p->getResource()->getAttribute($a)->getFrontend()->getValue($p);
						$xml .= $helper->tag($a, is_float($value) ? $helper->currency($value) : $value, is_float($value) ? 0 : 1);
					}

					$xml .= '</product>' . PHP_EOL;
				}
			//}
		}
		$xml .= $this->getAssociatedProducto(true);
        $xml .= '</catalog>';

        return $xml;
    }
	

	/**
		Children permet de récupérer tous les produits enfants 
	**/
    public function getXmlChild()
    {
        /* Load Model and Helper */
        $beezup = Mage::getModel('beezup/products');
        $helper = Mage::helper('beezup');

        /* Initially load the useful elements */
		$many_images = $helper->getConfig('beezup/flux/images');
        $_ht = $helper->getConfig('beezup/flux/ht');
        $_description = $helper->getConfig('beezup/flux/description');
        $_tablerates = $helper->getConfig('beezup/flux/tablerates_weight_destination') ? $beezup->getTablerates() : 0;
        $_categories = $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
        $_attributes = $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
        $_vat = ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
        $_catalog_rules = $helper->getConfig('beezup/flux/catalog_rules');

        /* Build file */
        $xml = $helper->getConfig('beezup/flux/bom') ? "\xEF\xBB\xBF" : '';
        $xml .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<catalog>' . PHP_EOL;
		
        $childs = $beezup->getConfigurableProducts(false);

        foreach ($childs as $c) {

			//récupérer l'image sur le père
			$productParentIds=Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($c->getId());
			foreach($productParentIds as $productParentId){
				$productParent = Mage::getModel('catalog/product')->load($productParentId);
				$image=$productParent->getImage(); 
				$categories = $beezup->getProductsCategories($productParent, $_categories);
				$url = $productParent->getProductUrl();
				$name = $productParent->getName();
				$description = $productParent->getData($_description);
			}
			
			if(count($categories)){
				$qty = $beezup->getQty($c->getId());
				$stock = $beezup->getIsInStock($qty);
				$shipping = $beezup->getDelivery($qty);
				$price = $c->getPrice();
				$final_price = $c->getFinalPrice();
				$image = $this->fillImageUrl($productParent, $c);

				$xml .= '<product>';
				$xml .= $helper->tag($this->__('b_unique_id'), $c->getId());
				$xml .= $helper->tag($this->__('b_sku'), trim($c->getSku()), 1);

				$xml .= $helper->tag($this->__('parent_or_child'), 'child', 1);
				$xml .= $helper->tag($this->__('parent_id'), $c->getParentId());			

				$xml .= $helper->tag($this->__('b_title'), trim($name), 1);
				$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($description)), 1);
				$xml .= $helper->tag($this->__('b_product_url'), $url, 1);

				/*$inc = 0;						
						$product = Mage::getModel('catalog/product')->load( $c->getId());
			
				foreach ($product->getMediaGalleryImages() as $image) {
				$inc++;
			if($inc==1) {
				$xml .= $helper->tag($this->__('url_image'), $image->getUrl(), 1);
			} else {
				$xml .= $helper->tag($this->__('url_image')."_".$inc, $image->getUrl(), 1);
			}
				}*/
						$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1); //récupère l'image sur le père
				
				if($many_images == 1) {
				$product = Mage::getModel('catalog/product')->load( $c->getId());
						$inc = 1;
				foreach ($product->getMediaGalleryImages() as $img) {
					if($helper->getImageDir() . $image!== $img->getUrl()) {
						$inc++;
					$xml .= $helper->tag($this->__('url_image')."_".$inc, $img->getUrl(), 1);
					}
				}
							
							
							
						} 
				
					$xml .= $helper->tag($this->__('b_availability'), $stock, 1);
				$xml .= $helper->tag($this->__('b_qty'), $qty);
				$xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);
				$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($c->getWeight(), $_tablerates)));
				$xml .= $helper->tag($this->__('b_weight'), $helper->currency($c->getWeight()));
				$xml .= $helper->tag($this->__('b_price'), $helper->currency($final_price*$_vat));
				if ($price != $final_price) $xml .= $helper->tag($this->__('b_regular_price'), $helper->currency($price*$_vat));
				$i = 1;
				foreach ($categories as $v) $xml .= $helper->tag($this->__('b_category_%s', $i++), $v, 1);
				foreach ($_attributes as $a) {
					$value = $c->getResource()->getAttribute($a)->getFrontend()->getValue($c);
					$xml .= $helper->tag($a, is_float($value) ? $helper->currency($value) : $value, is_float($value) ? 0 : 1);
				}

				$xml .= '</product>' . PHP_EOL;
			}
        }

        $xml .= '</catalog>';

        return $xml;
    }

	/**
		Produit groupes
	**/

    public function getAssociatedProducto($configurable)
    {
				
        /* Load Model and Helper */
        $beezup = Mage::getModel('beezup/products');
        $helper = Mage::helper('beezup');

        /* Initially load the useful elements */
		$many_images = $helper->getConfig('beezup/flux/images');
        $_ht 				= $helper->getConfig('beezup/flux/ht');
        $_description 		= $helper->getConfig('beezup/flux/description');
        $_tablerates 		= $helper->getConfig('beezup/flux/tablerates_weight_destination') ? $beezup->getTablerates() : 0;
        $_categories 		= $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
        $_vat 				= ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
        $_attributes 		= $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
        $_catalog_rules 	= $helper->getConfig('beezup/flux/catalog_rules');

        $products = $beezup->getGroupedProduct();

        $buf = $helper->getConfig('beezup/flux/bom') ? "\xEF\xBB\xBF" : '';
	    foreach ($products as $product) {
	        $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);

        	$parentCategories 	= $beezup->getProductsCategories($product, $_categories);
			$parentDesc 		= $product->getData($_description);
			$parentId 			= $product->getId();
        	$parentImage 		= $product->getImage();
		 	$parentUrl			= $product->getProductUrl();
	
	        foreach ($associatedProducts as $g) {
				$qty 				= $beezup->getQty($g->getId());
				$stock 				= $beezup->getIsInStock($qty);
				$shipping 			= $beezup->getDelivery($qty);
				$price 				= $g->getPrice();
				$final_price 		= $g->getFinalPrice();

				$image 				= $this->fillImageUrl($product, $g);

				
			//if (($image = $g->getImage()) == "no_selection" || ($image = $g->getImage()) == "") // Si on ne trouve pas d'image avec getImage on récupère la smallImage
			//	$image = $g->getSmallImage();
				

				//DBG
					if (0)
					{
		        		echo "----------------------------"						."<br/>";
		        		echo "Name : "		.$g->getName()						."<br/>";
		        		echo "Description : ".$parentDesc						."<br/>";
		        		echo "Id : "		.$g->getId()						."<br/>";
		        		echo "Parent Id : "	.$parentId							."<br/>";
		        		echo "Url : "		.$parentUrl							."<br/>";
		        		echo "Image : "		.$helper->getImageDir().$image 		."<br/>";


		        		echo "SKU : "		.$g->getSku()						."<br/>";
		        		echo "Quantity : " 	.$qty 								."<br/>";
		        		echo "Stock : " 	.$stock 							."<br/>";
						echo "Shipping : " 	.$shipping 							."<br/>";
						echo "price : "		.$price 							."<br/>";
						echo "finalprice : ".$final_price 						."<br/>";
						echo "weight : "	.$g->getWeight() 					."<br/>";

						$i = 1;
						foreach ($parentCategories as $v) 
							echo "Catégorie ".$i." : ".$v."<br/>";					
					}


        		$buf .= "<product>";
				$buf .= $helper->tag($this->__('b_unique_id'), $g->getId());
				$buf .= $helper->tag($this->__('b_sku'), trim($g->getSku()), 1);
				if ($configurable){
					$buf .= $helper->tag($this->__('parent_or_child'), 'grouped_child', 1);
					$buf .= $helper->tag($this->__('parent_id'), $parentId);			
				}
				$buf .= $helper->tag($this->__('b_title'), $g->getName()/*trim($name)*/, 1);
				$buf .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($parentDesc)), 1);
				$buf .= $helper->tag($this->__('b_product_url'), $parentUrl, 1);
			
	/*							
	if($many_images==1)		{
		
			$gprod = Mage::getModel('catalog/product')->load( $g->getId());
						$inc = 0;
				foreach ($gprod->getMediaGalleryImages() as $image) {
				$inc++;
			if($inc==1) {
				$buf .= $helper->tag($this->__('url_image'), $image->getUrl(), 1);
			} else {
				$buf .= $helper->tag($this->__('url_image')."_".$inc, $image->getUrl(), 1);
			}
				}
	} else {
						
						$buf .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1);
	}*/
			
			$buf .= $helper->tag($this->__('b_product_image'), $helper->getImageDir() . $image, 1); //récupère l'image sur le père
				$buf .= $helper->tag($this->__('b_availability'), $stock, 1);
				$buf .= $helper->tag($this->__('b_qty'), $qty);
				$buf .= $helper->tag($this->__('b_delivery'), $shipping, 1);
				$buf .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($g->getWeight(), $_tablerates)));
				$buf .= $helper->tag($this->__('b_weight'), $helper->currency($g->getWeight()));
				$buf .= $helper->tag($this->__('b_price'), $helper->currency($final_price * $_vat));
				if ($price != $final_price) $buf .= $helper->tag($this->__('b_regular_price'), $helper->currency($price*$_vat));
				$i = 1;
				foreach ($parentCategories as $v) $buf .= $helper->tag($this->__('b_category_%s', $i++), $v, 1);
				foreach ($_attributes as $a) {
					$value = $g->getResource()->getAttribute($a)->getFrontend()->getValue($g);
					$buf .= $helper->tag($a, is_float($value) ? $helper->currency($value) : $value, is_float($value) ? 0 : 1);
				}
				$buf .= "</product>".PHP_EOL;
	        }
    	}
    	return $buf;
    }


    protected function fillImageUrl($p, $c)
    {
		$image 			= $c->getImage();
		if ($image == "no_selection" || $image == "") // Si on ne trouve pas d'image avec getImage on récupère la smallImage
		{
			$image = $c->getSmallImage();
			if ($image == "no_selection" || $image == "")
				{
					$image = $p->getImage();
					if ($image == "no_selection" || $image == "")
						$image = $p->getSmallImage();						
				}
		}
    	return ($image);
    }

	protected function createFile($type, $xmlData)
	{
		$fp = fopen('beezup/tmp/'.$type, 'w');
		if ($fp == false)
		{
			echo 'Fail to create file';
		}
			fwrite($fp, $xmlData);
		fclose($fp);
	}
	
	protected function deleteFeed($type)
	{
		unlink('beezup/tmp/'.$type);
	}
	
	protected function needRefreshing($type)
	{
		$helper = Mage::helper('beezup');
		
		$delay = $helper->getConfig('beezup/flux/cachedelay') * 60;
		$nowtime = time();
		$fileTime = filemtime('beezup/tmp/'.$type);
		if (($nowtime - $fileTime) >= $delay)
			return (true);
		else
			return (false);
	}

	protected function createFolder()
	{
		$helper = Mage::helper('beezup');
		if (!$helper->getConfig('beezup/flux/cachedelay')) // Si option cache desactivée, pas besoin du dossier
			return (true);
		if (file_exists('beezup/tmp')) // Si le dossier existe deja, pas besoin de le recréer
			return (true);
		if (!mkdir('beezup/tmp', 0777, true))
		{
			echo "[ERROR] : Seems we can't create 'beezup' directory inside your root directory."."<br/>" 
			."You can try one of these solutions :"."<br/>" 
			."1 - Create by yourself the beezup/tmp inside your root directory with 777 permissions"."<br/>"
			."2 - Change the permissions on your root directory (777)"."<br/>"
			."3 - Change the 'cache delay' option to 'None' inside beezup plugin settings"."<br/>";		
			return (false);
		}
		return (true);
	}
	


	/**
		C'est ici que tout commence ...
	**/

    protected function _toHtml()
    {
		set_time_limit(0);
    	 $helper = Mage::helper('beezup');
        $this->setCacheLifetime(null);
		//dbg
		/*
				$this->getAssociatedProducto(true);
				return;*/

		if (!$this->createFolder()) // Si on rencontre des problèmes de création de dossier on retourne rien
			return;
		if ($this->getConfigurable()){ // Appel de l'url http://site.com/beezup/catalog/configurable
			if ($this->needRefreshing('configurable')){
				if (file_exists('beezup/tmp/configurable'))
					$this->deleteFeed('configurable');
				$xmlData = $this->getXmlConfigurable();
				$this->addText($xmlData);
				if ($helper->getConfig('beezup/flux/cachedelay') != 0)
					$this->createFile('configurable', $xmlData);
			}
			else
				echo file_get_contents('beezup/tmp/configurable');
		}
		else if ($this->getChildXML()){ // Appel de l'url http://site.com/beezup/catalog/child
			if ($this->needRefreshing('child')){
				if (file_exists('beezup/tmp/child'))
					$this->deleteFeed('child');
				$xmlData =  $this->getXmlChild();
				$this->addText($xmlData);
				if ($helper->getConfig('beezup/flux/cachedelay') != 0)
					$this->createFile('child', $xmlData);
			}
			else
				echo file_get_contents('beezup/tmp/child');
		}
		else { // Appel de l'url http://site.com/beezup/catalog/xml
			if ($this->needRefreshing('xml')){
				if (file_exists('beezup/tmp/xml'))
					$this->deleteFeed('xml');
				$xmlData = $this->getXml();
				$this->addText($xmlData);
				if ($helper->getConfig('beezup/flux/cachedelay') != 0)
					$this->createFile('xml', $xmlData);
			}
			else
				echo file_get_contents('beezup/tmp/xml');
		}
		return parent::_toHtml();
	}
}
