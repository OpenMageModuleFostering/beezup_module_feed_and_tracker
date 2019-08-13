<?php
require_once dirname ( __FILE__ ) . "/../lib/bootstrap.php";
require_once dirname ( __FILE__ ) . "/../lib/BeezupRepository.php";
class BeezUp_CronController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

		$repository = new BeezupRepository();
			if(!$repository->isConnectionOk()) {
            $this->norouteAction();
        }
    }

    public function executeAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/order')->executeCron());
    }


}