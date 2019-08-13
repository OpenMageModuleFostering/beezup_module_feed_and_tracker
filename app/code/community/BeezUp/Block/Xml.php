<?php
	ini_set('memory_limit','1024M');

	class BeezUp_Block_Xml extends Mage_Core_Block_Text
	{
		/**
			Xml permet de r�cup�rer tous les produits simples
		**/
		public function getXml($paginate = false)
		{

			$base_url = Mage::getBaseUrl();
			/* Load Model and Helper */
			$beezup = Mage::getModel('beezup/products');
			$helper = Mage::helper('beezup');
			$category_logic = $helper->getConfig('beezup/flux/category_logic');
			/* Initially load the useful elements */

			$shipping_logic = $helper->getConfig('beezup/flux/carrier_method');
			if($shipping_logic == 1) {
				$shipping_carrier = $helper->getConfig('beezup/flux/shipping_carrier');
				$default_country = $helper->getConfig('beezup/flux/default_country');
			}

			$default_shipping_cost = (int)$helper->getConfig('beezup/flux/default_shipping_cost');
			$many_images = $helper->getConfig('beezup/flux/images');
			$_ht = $helper->getConfig('beezup/flux/ht');
			$_description = $helper->getConfig('beezup/flux/description');
			$_description = explode(",", $_description);
			$enable_html = $helper->getConfig('beezup/flux/description_html');
			$_tablerates =0;
			$cat_logic = false;
			if($category_logic == 1) {
				//$_categories = $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
				$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('*')
				->addAttributeToSort('path', 'asc')
					->load()
					->toArray();

					$_categories = $beezup->getCategoryLogic1Tree($categories);

				} else {
				$cat_logic = true;
				$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('*')//or you can just add some attributes
				->addAttributeToFilter('level', 2)
				->addAttributeToFilter('is_active', 1);
				$_categories = $beezup->getCategoriesAsArray( $categories, true);

			}

			$_attributes = $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
			$_vat = ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
			//	$_catalog_rules = $helper->getConfig('beezup/flux/catalog_rules');
			/* Build file */
			$xml = "\xEF\xBB\xBF";
			$xml .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<catalog>' . PHP_EOL;

			$products = $beezup->getProducts(false, $paginate);
			if($many_images == 1) {
				$backendModel = $products->getResource()->getAttribute('media_gallery')->getBackend();
			}

			//$productModel = Mage::getModel('catalog/product');
			foreach ($products as $p) {
				if($category_logic == 1) {
					$categories = $beezup->getProductsCategories($p, $_categories);
					} else {

					$categories = $beezup->getProductsCategories2($p, $_categories);
				}

				if (count($categories)) {
					$qty = $beezup->getQty($p->getId());
					$stock = $beezup->getIsInStock($qty);
					$shipping = $beezup->getDelivery($qty);
					$price = $p->getPrice();
					$final_price = $p->getFinalPrice();
					if (($image = $p->getImage()) == "no_selection" || ($image = $p->getImage()) == "") // Si on ne trouve pas d'image avec getImage on r�cup�re la smallImage
					$image = $p->getSmallImage();


					//$precio = Mage::getModel('catalogrule/rule')->calcProductPriceRule($p,$p->getPrice());


					$xml .= '<product>';
					//	$productModel->load($p->getId());
					$xml .= $helper->tag($this->__('b_unique_id'), $p->getId());
					$xml .= $helper->tag($this->__('b_sku'), trim($p->getSku()), 1);
					$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
					//	$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);

					foreach($_description  as $desc) {
						if($enable_html == 1) {
							$xml .= $helper->tag($this->__('b_'.$desc), $p->getData($desc), 1);
							} else {
							$xml .= $helper->tag($this->__('b_'.$desc), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($desc))), 1);
						}
					}

					$xml .= $helper->tag($this->__('b_product_url'), $p->getProductUrl(), 1);
					$special_price = $p->getSpecialPrice();
					if(!empty($special_price) && $special_price > 0) {
						$special_date = $p->getSpecialToDate();
						if (strtotime($special_date) >= time() || empty($special_date)) {
							$final_price = $p->getSpecialPrice();
							//$xml .= $helper->tag($this->__('precio'), $p->getSpecialPrice(), 1);
						}
					}




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


					$xml .= $helper->tag($this->__('b_availability'), $stock, 1);
					$xml .= $helper->tag($this->__('b_qty'), $qty);
					$xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);

					if($shipping_logic == 1) {
						$shipping_rate = $beezup->_getShippingPrice($p, $shipping_carrier, $default_country);
						if($shipping_rate == 0 && $default_shipping_cost > 0) {
							$shipping_rate = $default_shipping_cost;
						}
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($shipping_rate));
						} else {
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($p->getWeight(), $_tablerates)));
					}


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
			if(isset($paginate['page'])) {
				if($paginate['page'] == 1) {
					$xml .= $this->getAssociatedProducto(false);
				}

				} else {
				$xml .= $this->getAssociatedProducto(false);
			}

			$xml .= '</catalog>';

			return $xml;
		}

		/**
			Configurable permet de r�cup�rer tous les produits (p�re, enfant et simple)
		**/
		public function getXmlConfigurable($paginate = false)
		{
			$base_url = Mage::getBaseUrl();
			/* Load Model and Helper */
			$beezup = Mage::getModel('beezup/products');
			$helper = Mage::helper('beezup');
			$category_logic = $helper->getConfig('beezup/flux/category_logic');
			/* Initially load the useful elements */
			$shipping_logic = $helper->getConfig('beezup/flux/carrier_method');
			if($shipping_logic == 1) {
				$shipping_carrier = $helper->getConfig('beezup/flux/shipping_carrier');
				$default_country = $helper->getConfig('beezup/flux/default_country');
			}
			$default_shipping_cost = (int)$helper->getConfig('beezup/flux/default_shipping_cost');
			$many_images = $helper->getConfig('beezup/flux/images');
			$_ht 				= $helper->getConfig('beezup/flux/ht');
			$_description = $helper->getConfig('beezup/flux/description');
			$_description = explode(",", $_description);
			$enable_html = $helper->getConfig('beezup/flux/description_html');
			$_tablerates 		=  0;
			$cat_logic = false;
			if($category_logic == 1) {
				//$_categories = $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
				$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('*')
				->addAttributeToSort('path', 'asc')
					->load()
					->toArray();
					$_categories = $beezup->getCategoryLogic1Tree($categories);
				} else {
				$cat_logic = true;
				$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('*')//or you can just add some attributes
				->addAttributeToFilter('level', 2)
				->addAttributeToFilter('is_active', 1);
				$_categories = $beezup->getCategoriesAsArray( $categories, true);

			}
			$_attributes 		= $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
			$_vat 				= ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
			//	$_catalog_rules 	= $helper->getConfig('beezup/flux/catalog_rules');

			/* Build file */
			$xml =  "\xEF\xBB\xBF";
			$xml .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<catalog>' . PHP_EOL;

			//r�cup�re tous les produits
			$products = $beezup->getProducts(false, $paginate);
			$childs = $beezup->getConfigurableProducts(true);
			$backendModel = $products->getResource()->getAttribute('media_gallery')->getBackend();
			$products->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);


			$mediaBackend = Mage::getModel('catalog/product_attribute_backend_media');
			$mediaGalleryAttribute = Mage::getModel('eav/config')->getAttribute(Mage::getModel('catalog/product')->getResource()->getTypeId(), 'media_gallery');
			$mediaBackend->setAttribute($mediaGalleryAttribute);

			//parcours les produits
			foreach ($products as $p) {

				if($many_images == 1) {
					$backendModel->afterLoad($p); //adding media gallery to the product object
					$datos = $p->getData();

				}
				if($category_logic == 1) {
					$categories = $beezup->getProductsCategories($p, $_categories);
					} else {

					$categories = $beezup->getProductsCategories2($p, $_categories);
				}
				//	$varationTheme = $beezup->getOptions($p);
				//we get product object from catalog/product reason(beezup/products gets products from catalog/product_collection, didn't find the way to get image collection from there *will check)

				if (count($categories)) {
					//si l'�l�ment est un p�re, on va traiter ces enfants
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
								//			echo "finalprice : ".$final_price 						."<br/>";
								echo "specialprice : ".$c->getSpecialPrice() 			."<br/>";
							}


							$xml .= '<product>';
							$xml .= $helper->tag($this->__('b_unique_id'), $c->getId());
							$xml .= $helper->tag($this->__('b_sku'), trim($c->getSku()), 1);

							$xml .= $helper->tag($this->__('parent_or_child'), 'child', 1);
							$xml .= $helper->tag($this->__('parent_id'), $p->getId());
							//$xml .= $helper->tag($this->__('variation-theme'), $varationTheme, 1);

							$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
							//$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
							foreach($_description  as $desc) {
								if($enable_html == 1) {
									$xml .= $helper->tag($this->__('b_'.$desc), $p->getData($desc), 1);
									} else {
									$xml .= $helper->tag($this->__('b_'.$desc), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($desc))), 1);
								}
							}

							$xml .= $helper->tag($this->__('b_product_url'), $p->getProductUrl(), 1);

							$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1);
							$mediaBackend->afterLoad($c);


							if($many_images==1)		{
								$cDatos = $c->getData();
								$inc = 1;
								foreach ($cDatos['media_gallery']['images'] as $img) {

									if($img['disabled']==0 && $image !== $img['file']) {
										$inc++;
										$xml .= $helper->tag($this->__('url_image')."_".$inc, $helper->getImageDir() .$img['file'], 1);

									}
								}


								if($inc==1 && ($c->getImage() == "no_selection" || $c->getImage()=="" || $c->getSmallImage() == "no_selection" || $c->getSmallImage() == "")) { //if there are no child pictures

									foreach ($datos['media_gallery']['images'] as $img) {

										if($img['disabled']==0 && $image !== $img['file']) {
											$inc++;
											$xml .= $helper->tag($this->__('url_image')."_".$inc, $helper->getImageDir() .$img['file'], 1);

										}
									}

								}
							}
							$xml .= $helper->tag($this->__('b_availability'), $stock, 1);
							$xml .= $helper->tag($this->__('b_qty'), $qty);
							$xml .= $helper->tag($this->__('b_delivery'), $shipping, 1);

							if($shipping_logic == 1) {
								$shipping_rate = $beezup->_getShippingPrice($c, $shipping_carrier, $default_country);
								if($shipping_rate == 0 && $default_shipping_cost > 0) {
									$shipping_rate = $default_shipping_cost;
								}
								$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($shipping_rate));
								} else {
								$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($c->getWeight(), $_tablerates)));
							}

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
						if (($image = $p->getImage()) == "no_selection" || ($image = $p->getImage()) == "") // Si on ne trouve pas d'image avec getImage on r�cup�re la smallImage
						$image = $p->getSmallImage();


						// si c'est un �l�ment parent
						$xml .= '<product>';
						$xml .= $helper->tag($this->__('b_unique_id'), $p->getId());
						$xml .= $helper->tag($this->__('b_sku'), trim($p->getSku()), 1);

						$xml .= $helper->tag($this->__('parent_or_child'), 'parent', 1);
						$xml .= $helper->tag($this->__('parent_id'), '');
						//	$xml .= $helper->tag($this->__('variation-theme'), $varationTheme, 1);

						$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
						//$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
						foreach($_description  as $desc) {
							if($enable_html == 1) {
								$xml .= $helper->tag($this->__('b_'.$desc), $p->getData($desc), 1);
								} else {
								$xml .= $helper->tag($this->__('b_'.$desc), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($desc))), 1);
							}
						}
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

						if($shipping_logic == 1) {
							$shipping_rate = $beezup->_getShippingPrice($p, $shipping_carrier, $default_country);
							if($shipping_rate == 0 && $default_shipping_cost > 0) {
								$shipping_rate = $default_shipping_cost;
							}
							$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($shipping_rate));
							} else {
							$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($p->getWeight(), $_tablerates)));
						}

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
			if($paginate == false || (isset($paginate['page']) && $paginate['page'] == 1)) {
				$product_simple = $beezup->getProductsSimple();
				$backendModelSimple = $product_simple->getResource()->getAttribute('media_gallery')->getBackend();
				foreach ($product_simple as $p) {

					$prodAttributeSet = Mage::getModel('eav/entity_attribute_set')->load($p->getAttributeSetId())->getAttributeSetName();

					if($category_logic == 1) {
						$categories = $beezup->getProductsCategories($p, $_categories);
						} else {

						$categories = $beezup->getProductsCategories2($p, $_categories);
					}

					if (count($categories)) {
						$qty = $beezup->getQty($p->getId());
						$stock = $beezup->getIsInStock($qty);
						$shipping = $beezup->getDelivery($qty);
						$price = $p->getPrice();
						$final_price = $p->getFinalPrice();
						if (($image = $p->getImage()) == "no_selection" || ($image = $p->getImage()) == "") // Si on ne trouve pas d'image avec getImage on r�cup�re la smallImage
						$image = $p->getSmallImage();


						$xml .= '<product>';
						$xml .= $helper->tag($this->__('b_unique_id'), $p->getId());
						$xml .= $helper->tag($this->__('b_sku'), trim($p->getSku()), 1);

						$xml .= $helper->tag($this->__('parent_or_child'), 'simple', 1);
						$xml .= $helper->tag($this->__('parent_id'), '');
						$xml .= $helper->tag($this->__('variation-theme'), '', 1);

						$xml .= $helper->tag($this->__('b_title'), trim($p->getName()), 1);
						//$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($_description))), 1);
						foreach($_description  as $desc) {
							if($enable_html == 1) {
								$xml .= $helper->tag($this->__('b_'.$desc), $p->getData($desc), 1);
								} else {
								$xml .= $helper->tag($this->__('b_'.$desc), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($p->getData($desc))), 1);
							}
						}
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
						if($shipping_logic == 1) {
							$shipping_rate = $beezup->_getShippingPrice($p, $shipping_carrier, $default_country);
							if($shipping_rate == 0 && $default_shipping_cost > 0) {
								$shipping_rate = $default_shipping_cost;
							}
							$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($shipping_rate));
							} else {
							$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($p->getWeight(), $_tablerates)));
						}
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
				//}
			}
			if(isset($paginate['page'])) {
				if($paginate['page'] == 1) {
					$xml .= $this->getAssociatedProducto(true);
				}

				} else {
				$xml .= $this->getAssociatedProducto(true);
			}
			$xml .= '</catalog>';

			return $xml;
		}


		/**
			Children permet de r�cup�rer tous les produits enfants
		**/
		public function getXmlChild($paginate = false)
		{
			/* Load Model and Helper */
			$beezup = Mage::getModel('beezup/products');
			$helper = Mage::helper('beezup');
			$category_logic = $helper->getConfig('beezup/flux/category_logic');
			/* Initially load the useful elements */
			$shipping_logic = $helper->getConfig('beezup/flux/carrier_method');
			if($shipping_logic == 1) {
				$shipping_carrier = $helper->getConfig('beezup/flux/shipping_carrier');
				$default_country = $helper->getConfig('beezup/flux/default_country');
			}
			$default_shipping_cost = (int)$helper->getConfig('beezup/flux/default_shipping_cost');
			$many_images = $helper->getConfig('beezup/flux/images');
			$_ht = $helper->getConfig('beezup/flux/ht');
			$_description = $helper->getConfig('beezup/flux/description');
			$_description = explode(",", $_description);
			$enable_html = $helper->getConfig('beezup/flux/description_html');
			$_tablerates =  0;
			$cat_logic = false;
			if($category_logic == 1) {
				//$_categories = $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
				$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('*')
				->addAttributeToSort('path', 'asc')
					->load()
					->toArray();
					$_categories = $beezup->getCategoryLogic1Tree($categories);
				} else {
				$cat_logic = true;
				$categories = Mage::getModel('catalog/category')->getCollection()
				->addAttributeToSelect('*')//or you can just add some attributes
				->addAttributeToFilter('level', 2)
				->addAttributeToFilter('is_active', 1);
				$_categories = $beezup->getCategoriesAsArray( $categories, true);

			}
			$_attributes = $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
			$_vat = ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
			//	$_catalog_rules = $helper->getConfig('beezup/flux/catalog_rules');

			/* Build file */
			$xml = "\xEF\xBB\xBF";
			$xml .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<catalog>' . PHP_EOL;

			$childs = $beezup->getConfigurableProducts(false, $paginate);

			foreach ($childs as $c) {

				//r�cup�rer l'image sur le p�re
				$productParentIds=Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($c->getId());
				foreach($productParentIds as $productParentId){
					$productParent = Mage::getModel('catalog/product')->load($productParentId);
					$image=$productParent->getImage();
					if($category_logic == 1) {
						$categories = $beezup->getProductsCategories($p, $_categories);
						} else {

						$categories = $beezup->getProductsCategories2($p, $_categories);
					}
					$url = $productParent->getProductUrl();
					$name = $productParent->getName();
					$description_short = $productParent->getData("short_description");
					$description =  $productParent->getData("description");

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
					//$xml .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($description)), 1);
					$xml .= $helper->tag($this->__('b_description_short'), $description_short, 1);
					$xml .= $helper->tag($this->__('b_description'), $description, 1);
					$xml .= $helper->tag($this->__('b_product_url'), $url, 1);


					$xml .= $helper->tag($this->__('url_image'), $helper->getImageDir() . $image, 1); //r�cup�re l'image sur le p�re

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
					if($shipping_logic == 1) {
						$shipping_rate = $beezup->_getShippingPrice($c, $shipping_carrier, $default_country);
						if($shipping_rate == 0 && $default_shipping_cost > 0) {
							$shipping_rate = $default_shipping_cost;
						}
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($shipping_rate));
						} else {
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($c->getWeight(), $_tablerates)));
					}

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

		public function getAssociatedProducto($configurable )
		{

			/* Load Model and Helper */
			$beezup = Mage::getModel('beezup/products');
			$helper = Mage::helper('beezup');
			$category_logic = $helper->getConfig('beezup/flux/category_logic');
			/* Initially load the useful elements */
			$shipping_logic = $helper->getConfig('beezup/flux/carrier_method');
			if($shipping_logic == 1) {
				$shipping_carrier = $helper->getConfig('beezup/flux/shipping_carrier');
				$default_country = $helper->getConfig('beezup/flux/default_country');
			}

			$default_shipping_cost = (int)$helper->getConfig('beezup/flux/default_shipping_cost');
			$many_images = $helper->getConfig('beezup/flux/images');
			$_ht 				= $helper->getConfig('beezup/flux/ht');
			$_description = $helper->getConfig('beezup/flux/description');
			$_description = explode(",", $_description);
			$enable_html = $helper->getConfig('beezup/flux/description_html');
			$_tablerates 		=  0;
			$cat_logic = false;

			//$_categories = $beezup->getCategoriesAsArray(Mage::helper('catalog/category')->getStoreCategories());
			$categories = Mage::getModel('catalog/category')->getCollection()
			->addAttributeToSelect('*')
			->addAttributeToSort('path', 'asc')
				->load()
				->toArray();
				$_categories = $beezup->getCategoryLogic1Tree($categories);

			$_vat 				= ($_ht && is_numeric($helper->getConfig('beezup/flux/vat'))) ? (preg_replace('(\,+)', '.', $helper->getConfig('beezup/flux/vat')) / 100) + 1 : 1;
			$_attributes 		= $helper->getConfig('beezup/flux/attributes') ? explode(',', $helper->getConfig('beezup/flux/attributes')) : array();
			//	$_catalog_rules 	= $helper->getConfig('beezup/flux/catalog_rules');

			$products = $beezup->getGroupedProduct();


			$buf = "\xEF\xBB\xBF";
			foreach ($products as $product) {
				$associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);

				$parentCategories 	= $beezup->getProductsCategories($product, $_categories);
				//$parentDesc 		= $product->getData($_description);
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


					//if (($image = $g->getImage()) == "no_selection" || ($image = $g->getImage()) == "") // Si on ne trouve pas d'image avec getImage on r�cup�re la smallImage
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
						echo "Cat�gorie ".$i." : ".$v."<br/>";
					}


					$buf .= "<product>";
					$buf .= $helper->tag($this->__('b_unique_id'), $g->getId());
					$buf .= $helper->tag($this->__('b_sku'), trim($g->getSku()), 1);
					if ($configurable){
						$buf .= $helper->tag($this->__('parent_or_child'), 'grouped_child', 1);
						$buf .= $helper->tag($this->__('parent_id'), $parentId);
					}
					$buf .= $helper->tag($this->__('b_title'), $g->getName()/*trim($name)*/, 1);
					//$buf .= $helper->tag($this->__('b_description'), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($parentDesc)), 1);

					foreach($_description  as $desc) {
						if($enable_html == 1) {
							$buf .= $helper->tag($this->__('b_'.$desc), $product->getData($desc), 1);
							} else {
							$buf .= $helper->tag($this->__('b_'.$desc), preg_replace("/(\r\n|\n|\r)/", ' ', strip_tags($product->getData($desc))), 1);
						}
					}


					$buf .= $helper->tag($this->__('b_product_url'), $parentUrl, 1);
					$buf .= $helper->tag($this->__('b_product_image'), $helper->getImageDir() . $image, 1); //r�cup�re l'image sur le p�re



					$buf .= $helper->tag($this->__('b_availability'), $stock, 1);
					$buf .= $helper->tag($this->__('b_qty'), $qty);
					$buf .= $helper->tag($this->__('b_delivery'), $shipping, 1);
					if($shipping_logic == 1) {
						if($shipping_rate == 0 && $default_shipping_cost > 0) {
							$shipping_rate = $default_shipping_cost;
						}
						$shipping_rate = $beezup->_getShippingPrice($g, $shipping_carrier, $default_country);
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($shipping_rate));
						} else {
						$xml .= $helper->tag($this->__('b_shipping'), $helper->currency($beezup->getShippingAmount($g->getWeight(), $_tablerates)));
					}

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
			if ($image == "no_selection" || $image == "") // Si on ne trouve pas d'image avec getImage on r�cup�re la smallImage
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
			$fp = fopen(Mage::getBaseDir('base').'/beezup/tmp/'.$type, 'w');
			if ($fp == false)
			{
				echo 'Fail to create file';
			}
			fwrite($fp, $xmlData);
			fclose($fp);
		}

		protected function deleteFeed($type)
		{
			unlink(Mage::getBaseDir('base').'/beezup/tmp/'.$type);
		}

		protected function needRefreshing($type)
		{
			$helper = Mage::helper('beezup');

			$delay = $helper->getConfig('beezup/flux/cachedelay') * 60;
			$nowtime = time();
			$fileTime = filemtime(Mage::getBaseDir('base').'/beezup/tmp/'.$type);
			if (($nowtime - $fileTime) >= $delay)
			return (true);
			else
			return (false);
		}

		protected function createFolder()
		{
			$helper = Mage::helper('beezup');
			if (!$helper->getConfig('beezup/flux/cachedelay')) // Si option cache desactiv�e, pas besoin du dossier
		return (true);
		if (file_exists('beezup/tmp')) // Si le dossier existe deja, pas besoin de le recr�er
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
		$storeId = Mage::app()->getStore()->getStoreId();
		$websiteId = Mage::app()->getStore()->getWebsiteId();
		//dbg
		/*
		$this->getAssociatedProducto(true);
		return;*/
		$paginate = $this->getPagination();
		if (!$this->createFolder()) // Si on rencontre des probl�mes de cr�ation de dossier on retourne rien
		return;
		if ($this->getConfigurable()){ // Appel de l'url http://site.com/beezup/catalog/configurable
		if ($this->needRefreshing('configurable_'.$storeId.'_'.$websiteId)){
		if (file_exists('beezup/tmp/configurable_'.$storeId.'_'.$websiteId))
		$this->deleteFeed('configurable_'.$storeId.'_'.$websiteId);
		$xmlData = $this->getXmlConfigurable($paginate);
		$this->addText($xmlData);
		if ($helper->getConfig('beezup/flux/cachedelay') != 0)
		$this->createFile('configurable_'.$storeId.'_'.$websiteId, $xmlData);
		}
		else
		echo file_get_contents('beezup/tmp/configurable_'.$storeId.'_'.$websiteId);
		}
		else if ($this->getChildXML()){ // Appel de l'url http://site.com/beezup/catalog/child
		if ($this->needRefreshing('child_'.$storeId.'_'.$websiteId)){
		if (file_exists('beezup/tmp/child_'.$storeId.'_'.$websiteId))
		$this->deleteFeed('child_'.$storeId.'_'.$websiteId);
		$xmlData =  $this->getXmlChild($paginate);
		$this->addText($xmlData);
		if ($helper->getConfig('beezup/flux/cachedelay') != 0)
		$this->createFile('child_'.$storeId.'_'.$websiteId, $xmlData);
		}
		else
		echo file_get_contents('beezup/tmp/child_'.$storeId.'_'.$websiteId);
		}
		else { // Appel de l'url http://site.com/beezup/catalog/xml
		if ($this->needRefreshing('xml_'.$storeId.'_'.$websiteId)){
		if (file_exists('beezup/tmp/xml_'.$storeId.'_'.$websiteId))
		$this->deleteFeed('xml_'.$storeId.'_'.$websiteId);
		$xmlData = $this->getXml($paginate);
		$this->addText($xmlData);
		if ($helper->getConfig('beezup/flux/cachedelay') != 0)
		$this->createFile('xml_'.$storeId.'_'.$websiteId, $xmlData);
		}
		else
		echo file_get_contents('beezup/tmp/xml_'.$storeId.'_'.$websiteId);
		}
		return parent::_toHtml();
		}
		}
