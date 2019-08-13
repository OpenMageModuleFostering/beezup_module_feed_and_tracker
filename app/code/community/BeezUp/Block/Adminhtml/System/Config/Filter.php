<?php 
class Beezup_Block_Adminhtml_System_Config_Filter extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/*
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
 $helper = Mage::helper('beezup');
$token = $helper->getConfig('beezup/marketplace/usertoken'); 
if($token == 2) {
	return "Hola Mundo";
} else {
	return "<style>#row_beezup_marketplace_new_value {display:none;}</style>";
}
        $date = new Varien_Data_Form_Element_Date;
        $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $data = array(
            'name'      => $element->getName(),
            'html_id'   => $element->getId(),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
        );
        $date->setData($data);
        $date->setValue($element->getValue(), $format);
        $date->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
        $date->setClass($element->getFieldConfig()->validate->asArray());
        $date->setForm($element->getForm());

        return $date->getElementHtml();
    }
	*/
	
	
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
	   
	    $helper = Mage::helper('beezup');
$token = $helper->getConfig('beezup/marketplace/usertoken'); 
if(!empty($token)) {
	   return "<style>#row_".$element->getId()." {display:none;}</style>";
} else {


	   
       $this->setElement($element);
 
       $html = '<div id="emailblocker_addresses_template" style="display:none">';
       $html .= $this->_getRowTemplateHtml();
       $html .= '</div>';
 
       $html .= '<ul id="emailblocker_addresses_container">';
       if ($this->_getValue('addresses')) {
           foreach ($this->_getValue('addresses') as $i => $f) {
               if ($i) {
                   $html .= $this->_getRowTemplateHtml($i);
               }
           }
       }
       $html .= '</ul>';
       $html .= $this->_getAddRowButtonHtml('emailblocker_addresses_container',
           'emailblocker_addresses_template', $this->__('Add New Attribute'));
 
       return $html;
	   }
   }
 
   /**
    * Retrieve html template for setting
    *
    * @param int $rowIndex
    * @return string
    */
   protected function _getRowTemplateHtml($rowIndex = 0)
   {
       $html = '<li>';
 
       $html .= '<div style="margin:5px 0 10px;">';
	   
	   $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
    ->getItems();
     //  $html .= '<select style="width:100px;" name="'
       //    . $this->getElement()->getName() . '[addresses][]" value="'
        //   . $this->_getValue('addresses/' . $rowIndex) . '" ' . $this->_getDisabled() . '/> ';
		
		       $html .= '<select style="width:70%;" name="'
           . $this->getElement()->getName() . '[addresses][]" '. $this->_getDisabled() . '/> ';
		
foreach ($attributes as $attribute){
	$selected = "";
	if(!empty($attribute->getFrontendLabel()) && $attribute->getFrontendLabel() !== "") {
	if($this->_getValue('addresses/' . $rowIndex) == $attribute->getAttributecode()) {
		$selected = "selected";
	}
	$html .= "<option value='".$attribute->getAttributecode()."' ".$selected.">".$attribute->getFrontendLabel()."</option>";
	}
 //   echo $attribute->getAttributecode();

  //  echo $attribute->getFrontendLabel();
}

 $html .= "</select>";
       $html .= $this->_getRemoveRowButtonHtml();
       $html .= '</div>';
       $html .= '</li>';
 
       return $html;
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


