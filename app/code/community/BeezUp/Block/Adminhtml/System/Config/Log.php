<?php 

class Beezup_Block_Adminhtml_System_Config_Log extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	
	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {

			$logDir =  Mage::getBaseDir('base').'/beezup/';
	//	$log1 = file_get_contents();
	$ret = array();
        if (file_exists($logDir."/log.txt")) {
            $f = fopen($logDir."/log.txt", 'r');

            if ($f) {
                while (!feof($f)) {
                    $ret[] = fgetcsv($f, 0, '|');
                }
                fclose($f);
            }
        }
			 array_slice(array_reverse($ret), 1, 10);

	return $this->_getTable($ret);
	
    }
	
	
	public function _getTable($data) {
			$url = Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_WEB, true );
		$html = "
		<style>
#beezup_flux_description {
		    height: 110px;
		
}
		</style>
		<td></td><td></td><tr></tr></tbody></table>
		
		<div class='grid' style='  height: 400px;overflow-y: scroll;padding: 16px;border: 3px solid #e6e6e6;' id='marketPlaceLogBlock'>";
		$html .= '<p>'. Mage::helper('beezup')->__('For full logs see here:').' <a href="'.$url .'beezup/log/load" target="_blank">'.$url .'beezup/log/load</a></p>';
		$html .= "<table class='data' style='margin-top:0px;'>";
		$html .= "<tr class='headings'>";
		$html .= '<th><span class="nobr">Time</span></th>';
		$html .= '<th><span class="nobr">Type</span></th>';
		$html .= '<th><span class="nobr">Order Id</span></th>';
		$html .= '<th><span class="nobr">Message</span></th>';
		$html .= "</tr>";
		$html .= "<tbody>";
		foreach($data as $d) {
				$background = "  background: rgb(240, 184, 184)";
			if($d[1] == " INFO " ) {
				$background = "  background: rgb(210, 227, 253)";
			}
			$orderId = (isset($d[3])) ? $d[2] : "";
			$message = (isset($d[3])) ? $d[3] : $d[2];
			$html .= "<tr class='even pointer' style='".$background."'>";
				$html .= "<td>".$d[0]."</td>";
				$html .= "<td>".$d[1]."</td>";
				$html .= "<td>".$orderId."</td>";
				$html .= "<td>".$message."</td>";
			$html .= "</tr>";
			
		}
	
		$html .= "<tbody>";
		$html .= '</table>';
		$html .= "</div>";
		
		return $html;
	}
}