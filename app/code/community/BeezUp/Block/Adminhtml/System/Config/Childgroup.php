<?php 

class Beezup_Block_Adminhtml_System_Config_Childgroup extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
$this->setElement($element);
$html = "<style>#row_".$this->getElement()->getId()." {
							background:#6F8992;
						}
						#row_".$this->getElement()->getId()." td label {
							color:white;
						}
						#row_".$this->getElement()->getId()." td  .scope-label {
							display:none;
						}	
						#beezup_marketplace table {margin-top: -30px;}
</style>
<script>
var theParent = document.getElementById('row_".$this->getElement()->getId()."');
var table_row  = document.createElement('tr');
table_row.innerHTML = '<td style=\'height:25px;\'></td>'; 
theParent.parentNode.insertBefore(table_row, theParent);

</script>
";
	return $html;

	}
	
	
}