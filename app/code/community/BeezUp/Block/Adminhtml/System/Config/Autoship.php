<?php


require_once Mage::getModuleDir('', 'BeezUp') . DS . 'lib' . DS ."BeezupOMStatus.php";

class Beezup_Block_Adminhtml_System_Config_Autoship extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

	    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {


        $beezup = new BeezupOmStatus();
        $marketplaces = $beezup->getMarketplacCarriersUp();
        $helper = Mage::helper('beezup');
    	$autoship = $helper->getConfig('beezup/marketplace/autoship_order_map');
        $autoship = unserialize($autoship);
        $shipping_methods = $this->getAllShippingMethods();

        $html = "<div class='grid'>";
        $html .= "
            <script>
                function addMarketCarrierMap(map_key) {
                    var master = document.getElementById('master-' + map_key);
                    var append = document.getElementById('append-' + map_key);
                    var html = master.innerHTML;
                    append.innerHTML =  append.innerHTML + '<tr>' +  html + '</tr>';
                }

                function removeMarketTr(element) {
                     element.parentNode.parentNode.removeChild(element.parentNode);
                }

            </script>
        ";
        foreach($marketplaces as $key => $marketplace) {
            $tmpval = false;

            $html .= "<table class='data' style='margin-top:0px;margin-bottom:10px;'>";
            $html .= "<tbody>
                        <tr class='headings'>
                        <th><span class='nobr'>".$key." Carriers</span></th>
                        <th><span class='nobr'>Magento Carriers</span><button style='float: right;
margin-top: -20px;' class='scalable add 'onclick='addMarketCarrierMap(\"".$key."\")' type='button'>+</button></th>
                        </tr>
                        </tbody>
                        <tbody id='append-".$key."'>";
            $html .= "<tr id='master-".$key."' style='display:none;'>";
        //    $html .= "<td>".$key."</td>";
            $html .= "<td><select name='groups[marketplace][fields][autoship_order_map][value][".$key."][beezup][]'>";
            //$marketplace = array_unique($marketplace);
            foreach($marketplace as $values) {

                    $html .= "<option value='".$key."|".$values['name']."|".$values['code']."'>".$values['name']."</option>";
            }
            $html .= "</select></td>";

            $html .= "<td><select name='groups[marketplace][fields][autoship_order_map][value][".$key."][mage][]'>";
            foreach($shipping_methods as $method) {
                $html .= "<option value='".$method['value']."'>".$method['label']."</option>";
            }
            $html .= "</select><button style='float: right;
margin-top: -20px;position: relative;
left: 28px;
background: red;
top: 2px;' class='scalable add 'onclick='removeMarketTr(this.parentNode)' type='button'>-</button></td>";
            $html .= "</tr>";



            foreach($autoship[$key] as $ship) {

                    $html .= "<tr>";
                    $html .= "<td><select name='groups[marketplace][fields][autoship_order_map][value][".$key."][beezup][]'>";
                    foreach($marketplace as $values) {
                        $selected = "";

                        if($ship['beezup_carrierCode'] == $values['code']) {
                            $selected = "selected";
                        }
                        $html .= "<option ".$selected." value='".$key."|".$values['name']."|".$values['code']."'>".$values['name']."</option>";
                    }
                    $html .= "</select></td>";


                    $html .= "<td><select name='groups[marketplace][fields][autoship_order_map][value][".$key."][mage][]'>";
                    foreach($shipping_methods as $method) {
                        $selected = "";
                        if($method['value'] == $ship['mage_carrierValue']) {
                            $selected = "selected";
                        }
                        $html .= "<option ".$selected." value='".$method['value']."'>".$method['label']."</option>";
                    }
                    $html .= "</select><button style='float: right;
        margin-top: -20px;position: relative;
        left: 28px;
        background: red;
        top: 2px;' class='scalable add 'onclick='removeMarketTr(this.parentNode)' type='button'>-</button></td>";
                    $html .= "</tr>";


                    $html .= "<tr>";



            }



                $html .= "</table>";
        }

        $html .= '</div>';
        return $html;
    }


    private function getAllShippingMethods()
    {
        $methods = Mage::getSingleton('shipping/config')->getAllCarriers();

        $options = array();

        foreach($methods as $_ccode => $_carrier)
        {
            $_methodOptions = array();


                if(!$_title = Mage::getStoreConfig("carriers/$_ccode/title"))
                $_title = $_ccode;

                $options[] = array('value' => $_title, 'label' => $_title);

        }

        return $options;
    }


}
