<?php


class BeezUp_LogController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

    }

    public function loadAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('beezup/order')->getLog());
    }


}