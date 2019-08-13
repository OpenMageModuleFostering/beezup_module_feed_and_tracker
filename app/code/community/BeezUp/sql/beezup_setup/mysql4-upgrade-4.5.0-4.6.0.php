<?php 

$installer = $this;
$installer->startSetup();
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
    'beezup_marketplace_business_code',
    'varchar(255) DEFAULT NULL'
	
);
$installer->endSetup();
