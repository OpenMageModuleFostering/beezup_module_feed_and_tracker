<?php

/**
 * Adminhtml sales orders grid
 *
 * @author      Inchoo <ivan.galambos@inchoo.net>
 */
class BeezUp_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
 
    public function __construct()
    {
	
 parent::__construct();

        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
 
		if ($this->getRequest()->getParam('prepared') === 'beezup') {
        	$this->setDefaultFilter( array(
	        	'beezup_order' => '1'
				) );
        }
    }

    /**
     * Modify column filter if needed by custom implementation of IN() and NOT IN() MySQL statement + prepared filter functionality
     *
     */
    protected function _addColumnFilterToCollection($column)
    {
        $filterArr = Mage::registry('preparedFilter');
 
    	if (($column->getId() === 'store_id' || $column->getId() === 'status') && $column->getFilter()->getValue() && strpos($column->getFilter()->getValue(), ',')) {
 
    		$_inNin = explode(',', $column->getFilter()->getValue());
    		$inNin = array();
 
    		foreach ($_inNin as $k => $v) {
    			if (is_string($v) && strlen(trim($v))) {
    				$inNin[] = trim($v);
    			}
    		}
 
    		if (count($inNin)>1 && in_array($inNin[0], array('in', 'nin'))) {
    			$in = $inNin[0];
    			$values = array_slice($inNin, 1);
    			$this->getCollection()->addFieldToFilter($column->getId(), array($in => $values));
    		} else {
    			parent::_addColumnFilterToCollection($column);
 
    		}
    	} elseif (is_array($filterArr) && array_key_exists($column->getId(), $filterArr) && isset($filterArr[$column->getId()])) {
    		$this->getCollection()->addFieldToFilter($column->getId(), $filterArr[$column->getId()]);
 
    	} else {
    		parent::_addColumnFilterToCollection($column);
 
    	}
    //	Zend_Debug::dump((string)$this->getCollection()->getSelect(), 'Prepared filter:');
    	return $this;
    }
    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
//                 'type'      => 'store',
//                 'store_view'=> true,
//                 'display_deleted' => true,
            ));
        }
        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));
        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));
        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

		if ($this->getRequest()->getParam('prepared') === 'beezup') {
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
		
		}
        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));
        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '100px',
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
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));
        return parent::_prepareColumns();
    }
 
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem('hold_order', array(
                 'label'=> Mage::helper('sales')->__('Hold'),
                 'url'  => $this->getUrl('*/sales_order/massHold'),
            ));
        }
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem('unhold_order', array(
                 'label'=> Mage::helper('sales')->__('Unhold'),
                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
            ));
        }
        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
             'label'=> Mage::helper('sales')->__('Print Invoices'),
             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
        ));
        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
             'label'=> Mage::helper('sales')->__('Print Packingslips'),
             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
        ));
        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
        ));
        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
             'label'=> Mage::helper('sales')->__('Print All'),
             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
        ));
        return $this;
    }
 
    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}