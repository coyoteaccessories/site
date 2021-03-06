<?php

namespace Zemez\ShopByBrand\Model\ResourceModel\SoldBrand;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'item_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Zemez\ShopByBrand\Model\SoldBrand', 'Zemez\ShopByBrand\Model\ResourceModel\SoldBrand');
    }
}