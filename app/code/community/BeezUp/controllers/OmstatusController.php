<?php
require_once dirname ( __FILE__ ) . "/../lib/bootstrap.php";
require_once dirname ( __FILE__ ) . "/../lib/BeezupRepository.php";
class BeezUp_OmstatusController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {

        parent::preDispatch();

		$repository = new BeezupRepository();
			if(!$repository->isConnectionOk()) {
            $this->norouteAction();
        }
	
    }
	
			public function showPage() {
		$helper = Mage::helper('beezup');
		 $userid = $helper->getConfig('beezup/marketplace/userid');
		 $usertoken = $helper->getConfig('beezup/marketplace/usertoken');
			if(isset($_GET['uid']) && $_GET['uid'] == $userid && isset($_GET['token']) && $_GET['token'] == $usertoken) {
			return true;
			}
			return false;
			
			}
	

	    public function updateAction()
    {
	if($this->showPage()) {
		if(isset($_GET['order_id'])  && is_numeric($_GET['order_id'])) {
			$order_id = (int)$_GET['order_id'];
			  $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/omstatus')->changeOrder($_GET));
			
		} else {
			die("Error Order is not correct");		}
		} else {
		die();
		}

	}
	
		public function resyncAction() {
		
if($this->showPage()) {
		if(isset($_GET['order_id'])  && is_numeric($_GET['order_id'])) {
			$order_id = (int)$_GET['order_id'];
			  $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/omstatus')->resynOrder($order_id));
			
		} else {
			die("Error Order is not correct");		}
		} else {
		die();
		}		
		
		}
	


		public function loaderAction() {
		 $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/omstatus')->getLoader());
		}
	
}