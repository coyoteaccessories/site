<?php

namespace Custom\Coyote\Block\Adminhtml\Log;
use Custom\Coyote\Model\ProductlogFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid {

    protected $_productlog;
    protected $_backendUrlManager;
    
    public function __construct(
            \Magento\Backend\Model\Url $backendUrlManager,
            ProductlogFactory $productlog) {
        parent::__construct();
        $this->setId('logGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->_backendUrlManager  = $backendUrlManager;
        $this->_productlog = $productlog;
        
        // $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = $this->_productlog->create()->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header' => 'ID',
            'align' => 'right',
            'width' => '50px',
            'index' => 'entity_id',
        ));
        
        $product_id = 0;
        
        $link = $this->_backendUrlManager->getUrl('catalog/product/edit/') . 'id/'.$product_id;
        $this->addColumn('product_id', array(
            'header' => 'Product Id',
            'align' => 'left',
            'index' => 'product_id',
            'type' => 'action',
            'actions' => array(
                array(
                    'url' => $link,
                    'caption' => __($product_id),
                    'target' => '_blank'
                )
            )
        ));

        $this->addColumn('product_sku', array(
            'header' => 'Product Sku',
            'align' => 'left',
            'index' => 'product_sku',
        ));
        $this->addColumn('database', array(
            'header' => 'Database Name',
            'align' => 'left',
            'index' => 'database',
        ));
        $this->addColumn('status', array(
            'header' => 'Status',
            'align' => 'left',
            'index' => 'status',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        // return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl() {
        // return $this->getUrl('*/log/grid', array('_current'=>true));
    }

}

?>