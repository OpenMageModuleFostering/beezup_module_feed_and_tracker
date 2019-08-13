<?php

class BeezUp_CacheController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();


    }

    public function executeAction()
    {
   apc_clear_cache() . "\n"; apc_clear_cache('user') . "\n";

	apc_clear_cache('opcode') . "\n";
    }


}