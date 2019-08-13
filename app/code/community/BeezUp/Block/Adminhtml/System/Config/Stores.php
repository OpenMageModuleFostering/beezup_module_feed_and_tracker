<?php 
require_once dirname ( __FILE__ ) . "/../../../../lib/bootstrap.php";
require_once dirname ( __FILE__ ) . "/../../../../lib/BeezupRepository.php";
class Beezup_Block_Adminhtml_System_Config_Stores  extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
	
	
	
		    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$this->setElement($element);
		$html = "<div id='".$this->getElement()->getId()."'>";
		$repository = new BeezupRepository();
		$stores = $repository->getStores();
		$i = 0;
			foreach ($stores as $_eachStoreId => $val)
				{
				$html .= '<li>';
				$html .= '<div style="margin:5px 0 10px;">';	

 				$html .=  "<label>".$val."</label>";
			$html .= $this->_getBeezupStores( $_eachStoreId,$i);
				$html .= '</div>';
				$html .= '</li>';
				$i++;
				}
		$html .= "</div>";
		return $html;

	 
    }
	
	
	protected function _getBeezupStores($bzupStore, $rowIndex =0)
   {  
	$html = "";

		$allStores = Mage::app()->getStores();
		    $html .= '<select style="width:100%;" name="'.$this->getElement()->getName() . '[stores]['.$bzupStore.']" /> ';
		$html .= "<option value='0'>".$this->__("Select Value...")."</option>";
	foreach($allStores as $key => $store) {
	$selected = "";
	$_storeName = Mage::app()->getStore($key)->getName();
	$_storeId = Mage::app()->getStore($key)->getId();
	$value = $this->_getValue('stores/' . $bzupStore);
	if($value ==$_storeId) {
		$selected = "selected";
	}
	$html .= "<option value='".$_storeId."' ".$selected.">".$_storeName."</option>";
	}


 $html .= "</select>";
       $html .= '</div>';
       $html .= '</li>';
 
       return $html;
   }
	
   protected function _getValue($key)
   {
       return $this->getElement()->getData('value/' . $key);
   }	
	
	
	
	
	
}