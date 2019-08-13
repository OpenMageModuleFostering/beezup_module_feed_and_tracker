<?php 

class BeezUp_Block_Adminhtml_Sales_Order_Grid extends  Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('beezup_order_grid');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
		  	$this->setDefaultFilter( array(
	        	'beezup_order' => '1'
				) );
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
		   $collection = Mage::getResourceModel("sales/order_grid_collection");
        $this->setCollection($collection);
        return parent::_prepareCollection();
		
		/*
        $collection = Mage::getResourceModel('sales/order_collection')
            ->join(array('a' => 'sales/order_address'), 'main_table.entity_id = a.parent_id AND a.address_type != \'billing\'', array(
                'city'       => 'city',
                'country_id' => 'country_id'
            ))
            ->join(array('c' => 'customer/customer_group'), 'main_table.customer_group_id = c.customer_group_id', array(
                'customer_group_code' => 'customer_group_code'
            ))
            ->addExpressionFieldToSelect(
                'fullname',
                'CONCAT({{customer_firstname}}, \' \', {{customer_lastname}})',
                array('customer_firstname' => 'main_table.customer_firstname', 'customer_lastname' => 'main_table.customer_lastname'))
            ->addExpressionFieldToSelect(
                'products',
                '(SELECT GROUP_CONCAT(\' \', x.name)
                    FROM sales_flat_order_item x
                    WHERE {{entity_id}} = x.order_id
                        AND x.product_type != \'configurable\')',
                array('entity_id' => 'main_table.entity_id')
            )
        ;
 
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;*/
    }
 
    protected function _prepareColumns()
    {
        $helper = Mage::helper('beezup');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
 
        $this->addColumn('increment_id', array(
            'header' => $helper->__('Order #'),
            'index'  => 'increment_id'
        ));
 
        $this->addColumn('purchased_on', array(
            'header' => $helper->__('Purchased On'),
            'type'   => 'datetime',
            'index'  => 'created_at'
        ));
 
        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));
        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));
			  $this->addColumn('beezup_marketplace', array(
            'header' =>Mage::helper('beezup')->__('Marketplace'),
            'index' => 'beezup_marketplace',
        ));

		
		$this->addColumn('beezup_order', array(
            'header' => Mage::helper('beezup')->__('Beezup Order'),
            'index' => 'beezup_order',
		    'column_css_class'=>'no-display',//this sets a css class to the column row item
			'header_css_class'=>'no-display',//this sets a css class to the column header
        ));
		
				$this->addColumn('beezup_market_order_id', array(
            'header' => Mage::helper('beezup')->__('Marketplace Order Id'),
            'index' => 'beezup_market_order_id',
        ));
		
		
 
        $this->addColumn('grand_total', array(
            'header'        => $helper->__('Grand Total'),
            'index'         => 'grand_total',
            'type'          => 'currency',
            'currency_code' => $currency
        ));

 
        $this->addColumn('order_status', array(
            'header'  => $helper->__('Status'),
            'index'   => 'status',
            'type'    => 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
 
     if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action',
                array(
                    'header'    => Mage::helper('sales')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
                    'actions'   => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),
                            'url'     => array('base'=>'*/sales_order/view'),
                            'field'   => 'order_id'
                        )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));
        }
 
        $this->addExportType('*/*/exportInchooCsv', $helper->__('CSV'));
        $this->addExportType('*/*/exportInchooExcel', $helper->__('Excel XML'));
 
        return parent::_prepareColumns();
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}