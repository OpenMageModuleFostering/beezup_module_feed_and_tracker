<?php 
$installer = $this;
$installer->startSetup();
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
    'beezup_marketplace',
    'varchar(255) DEFAULT NULL'
	
);
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_name',
	'varchar(355) NULL'
	
);
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_order',
	'int (1) DEFAULT 0'
	
);
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_market_order_id',
	'varchar(255) NULL'
	
);
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_etag',
	'varchar(355) NULL'
	
);

$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_status',
	'varchar(355) NULL'
	
);
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_purchase_date',
	'varchar(355) NULL'
	
);

$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_last_modification_date',
	'varchar(355) NULL'
	
);


$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_marketplace_last_modification_date',
	'varchar(355) NULL'
	
);

$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_total_paid',
	'varchar(355) NULL'
	
);

$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_comission',
	'varchar(355) NULL'
	
);

$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_order_id',
	'varchar(355) NULL'
	
);

$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_marketplace_status',
	'varchar(355) NULL'
	
);


$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
	'beezup_comission',
	'varchar(355) NULL'
	
);


$this->getConnection()->addColumn(
    $this->getTable('sales/quote_address'),
	'beezup_fee',
	'decimal(10,2) NOT NULL DEFAULT 0'
	
);

$dir = Mage::getModuleDir("etc", "BeezUp");
$dir = str_replace("etc", "log", $dir);

$io = new Varien_Io_File();
$io->checkAndCreateFolder($dir);

$oLastSynchronizationDate = new DateTime ( 'now', new DateTimeZone ( 'UTC' ));
$helper = Mage::getModel('core/config');
$helper->saveConfig('beezup/marketplace/syncro_time',$oLastSynchronizationDate->getTimestamp());


$helper->saveConfig('beezup/marketplace/status_new', 'pending');
$helper->saveConfig('beezup/marketplace/status_progress', 'processing');
$helper->saveConfig('beezup/marketplace/status_cancelled', 'canceled');
$helper->saveConfig('beezup/marketplace/status_aborted', 'holded');
$helper->saveConfig('beezup/marketplace/status_closed', 'closed');
$helper->saveConfig('beezup/marketplace/status_shipped', 'complete');

$installer->endSetup();
