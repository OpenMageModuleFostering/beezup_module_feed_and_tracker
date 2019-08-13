<?php
	
	require_once dirname ( __FILE__ ) . "/../../../../../../lib/BeezupOMStatus.php";
	
	class BeezUp_Block_Adminhtml_Sales_Order_View_Tab_Custom
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
	{
		protected $_chat = null;
		
		protected function _construct()
		{
			parent::_construct();
			$this->setTemplate('beezup/custom.phtml');
		}
		
		public function getTabLabel() {
			return $this->__('BeezUP Info');
		}
		
		public function getTabTitle() {
			return $this->__('BeezUP Info');
		}
		
		public function canShowTab() {
			return true;
		}
		
		public function isHidden() {
			return false;
		}
		
		public function getOrder(){
			return Mage::registry('current_order');
		}
		
		public function getBeezupInfo($id_order) {
			$omStatus = new BeezupOmStatus();
			$data = $omStatus->getInfo($id_order);
			return $data;
		}
		
		public function generateInput($values, $shipping_data) {
			$retorno = "<table class='table' style='width:100%'>";
			foreach($values as $data) {
				$valor_input = "";
				if (strpos(strtolower($data->name), 'track') !== false) {
					$valor_input = $shipping_data['tracking'];
					}	elseif (strpos(strtolower($data->name), 'carrier') !== false) {
					$valor_input = $shipping_data['carrier'];
				}	
				
				$required = false;
				$txtLabel = "";
				$inputReq = "";
				if($data->isMandatory==1) {
					$required = true;
					$txtLabel = " <span style='color:red;'>*</span>";
					$inputReq = "required";
				}
				$action_name = $data->name;	
				$label = str_replace("Order_Shipping_", "", $data->name);
				$label = preg_replace('/(?<!\ )[A-Z]/', ' $0', $label);
				$label = str_replace("_", " ", $label);
				//	$label = implode(" ", $label);
				$retorno .= "<tr><td style='text-align:right;'><label>".$label." ".$txtLabel."</label></td>";
				
				if($data->islovRequired ==1) {
					$retorno .= '<td><select '.$inputReq.' onclick="updateOrder(this.value)" name='.$data->name.' >';
					foreach($data->$action_name->values as $value) {
						$texto = $value->TranslationText;
						if(empty($texto) || trim($texto) == "") {
							$texto = ucfirst(str_replace("_", " ", $value->CodeIdentifier));
						}
						$retorno .= '<option value="'.$value->CodeIdentifier.'" >'.$texto.'</option>';
					}
					$retorno .= '</select></td></tr>';
					
					} elseif($data->cSharpType == "System.DateTime") {
					$today_date = date('m/d/y');	
					$retorno .= '<td><input type="text" style="width:169px;" id="'.$data->name."_".$action_name.'" name="'.$data->name.'"  value="'.$today_date.'" />
					<script>// <![CDATA[
					Calendar.setup({
					inputField : \''.$data->name."_".$action_name.'\',
					ifFormat : \'%m/%e/%y\',
					button : \'date_from_trig\',
					align : \'Bl\',
					singleClick : true
					});
					// ]]
					</script>
					</td></tr>';	
					} else {
					$retorno .= '<td><input type="text" name="'.$data->name.'" value="'.$valor_input.'" /></td></tr>';
				}
				
			}
			$retorno .= "</table>";
			return $retorno;
			
		}
		
		
		
	}			