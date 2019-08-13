<?php
class Beezup_Block_Adminhtml_System_Config_Attributes  extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

	  protected $_addRowButtonHtml = array();
   protected $_removeRowButtonHtml = array();
 
   /**
    * Returns html part of the setting
    *
    * @param Varien_Data_Form_Element_Abstract $element
    * @return string
    */
   protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
   {
	   

	   
       $this->setElement($element);
 

 		$repository = new BeezupRepository();
		$stores = $repository->getStores();
		$inc = 0;
		   $html = '';
			foreach ($stores as $_eachStoreId => $val)
				{
		       $html .= '<div id="attributes_template'.$inc.'" style="display:none">';
       $html .= $this->_getRowTemplateHtml($_eachStoreId);
       $html .= '</div>';			
		$html .= "<li style='margin-top:20px;'>".$val."</li>";			
       $html .= '<ul id="attributes_container'.$inc.'" >';
       if ($this->_getValue('attributes/'.$_eachStoreId)) {
           foreach ($this->_getValue('attributes/'.$_eachStoreId) as $i => $f) {
               if ($i) {

	
                   $html .= $this->_getRowTemplateHtml($_eachStoreId, $i, $inc);
				   
               }
           }
       }
       $html .= '</ul>';
	          $html .= $this->_getAddRowButtonHtml('attributes_container'.$inc,
           'attributes_template'.$inc, $this->__('Add New Attribute'));
			$inc++;	}

 
       return $html;
	   
   }
 
   /**
    * Retrieve html template for setting
    *
    * @param int $rowIndex
    * @return string
    */
   protected function _getRowTemplateHtml($storeid = "", $rowIndex = 0)
   {
       $html = '<li>';
 
       $html .= '<div style="margin:5px 0 10px;">';
	   
	   $attributes = $this->_getAttributes();

		       $html .= '<select style="width:70%;" name="'
           . $this->getElement()->getName() . '[attributes]['.$storeid.'][]" '. $this->_getDisabled() . '/> ';
		
foreach ($attributes as $attribute){
	
	IF($attribute['label']!=="" && !empty($attribute['label'])) {
	$selected = "";

		$datos = $this->_getValue('attributes/'.$storeid."/" . $rowIndex);
		$data = explode("|" ,$datos);
		$inc = 0;
		
	if( $data[0]== $attribute['code'] && $data[1]==$storeid) {
		$selected = "selected";
	}

	$html .= "<option value='".$attribute['code']."|".$storeid."' ".$selected.">".$attribute['label']."</option>";
	
	}

}

 $html .= "</select>";
       $html .= $this->_getRemoveRowButtonHtml();
       $html .= '</div>';
       $html .= '</li>';
 
       return $html;
   }
 
 public function _getAttributes() {
	 $atributos = array();
	 	   $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
    ->getItems();
	 foreach ($attributes as $attribute){
		
			$atributos[] = array("code" => $attribute->getAttributecode(), "label" =>$attribute->getFrontendLabel()) ;
				
			
		  
	 }
	 
	 foreach ($atributos as  $key => $att) {
		 if($att['code'] == "sku") {	 
			unset($atributos[$key]);         // unset the $array with id $id
            array_unshift($atributos, $att); // unshift the array with $val to push in the beginning of array
          
			
			// $atributos = array_merge(array($key => $att), $atributos);
		 }
	 }
	 
	 return $atributos;
 }
 
 
   protected function _getDisabled()
   {
       return $this->getElement()->getDisabled() ? ' disabled' : '';
   }
 
   protected function _getValue($key)
   {
       return $this->getElement()->getData('value/' . $key);
   }
 
   protected function _getSelected($key, $value)
   {
       return $this->getElement()->getData('value/' . $key) == $value ? 'selected="selected"' : '';
   }
 
   protected function _getAddRowButtonHtml($container, $template, $title='Add')
   {
       if (!isset($this->_addRowButtonHtml[$container])) {
           $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
               ->setType('button')
               ->setClass('add ' . $this->_getDisabled())
               ->setLabel($this->__($title))
               ->setOnClick("Element.insert($('" . $container . "'), {bottom: $('" . $template . "').innerHTML})")
               ->setDisabled($this->_getDisabled())
               ->toHtml();
       }
       return $this->_addRowButtonHtml[$container];
   }
 
   protected function _getRemoveRowButtonHtml($selector = 'li', $title = 'Delete')
   {
       if (!$this->_removeRowButtonHtml) {
           $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
               ->setType('button')
               ->setClass('delete v-middle ' . $this->_getDisabled())
               ->setLabel($this->__($title))
               ->setOnClick("Element.remove($(this).up('" . $selector . "'))")
               ->setDisabled($this->_getDisabled())
               ->toHtml();
       }
       return $this->_removeRowButtonHtml;
   }
	
}