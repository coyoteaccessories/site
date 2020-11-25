<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Model\Filter;

class Stock extends \Magento\CatalogInventory\Helper\Stock
{
    protected $scopeConfig;
    protected $_stock;

    /**
     * Stock constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\CatalogInventory\Helper\Stock             $stock
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\CatalogInventory\Helper\Stock $stock
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_stock = $stock;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    public function addIsInStockFilterToCollection($collection)
    {
        $stockFlag = 'has_stock_status_filter';
        if (!$collection->hasFlag($stockFlag)) {
            $resource = $this->_stock->getStockStatusResource();
            $resource->addStockDataToCollection(
                $collection,
                1
            );

            $collection->setFlag($stockFlag, true);
        }
    }
}
