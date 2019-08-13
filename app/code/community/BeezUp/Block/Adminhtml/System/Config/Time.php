<?php 

class Beezup_Block_Adminhtml_System_Config_Time extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
$this->setElement($element);
$html = gmdate("Y-m-d H:i:s", $this->getElement()->getValue())." (UTC Time) <a class='form-button' style='padding:5px;margin-left:10px;'onclick='showInput()'>".$this->__("Change")."</a><br>";
$html .= "<input type='text' class='input-text' style='margin-top:7px;display:none;' id='".$this->getElement()->getId()."' name='".$this->getElement()->getName()."'  value='".gmdate("Y-m-d H:i:s", $this->getElement()->getValue())."'/>";
$html .= '<script>
					function showInput() {	
				    var contentId = document.getElementById("'.$this->getElement()->getId().'");
					contentId.style.display == "block" ? contentId.style.display = "none" : 
					contentId.style.display = "block"; 	
					}
				</script>
';
	return $html;
    }
	
	
	
}