<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Observer;

use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\Rule;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Customer\Model\Session as CustomerModelSession;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Event\ObserverInterface;
use Zemez\ProductLabels\Model\ResourceModel\ProductLabel;
use Zemez\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory;

class ProductListCollectionObserver implements ObserverInterface
{
    /**
     * @var
     */
    protected $_productLabelRule;

    /**
     * ProductListCollectionObserver constructor.
     *
     * @param \Zemez\ProductLabels\Helper\ProductLabelRule $productLabelRule
     *
     */
    public function __construct(
        \Zemez\ProductLabels\Helper\ProductLabelRule $productLabelRule
    ) {
        $this->_productLabelRule = $productLabelRule;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $collection ProductCollection */
        $collection = $observer->getEvent()->getCollection();
        //Get product ids on collection
        $productIds = [];
        //TODO: Need to test perfomace with: overwrite of public function AbstractCollection::getAllIds()
        foreach ($collection->getItems() as $item) {
            $productIds[$item->getid()] = $item->getid();
        }
        if (!$productIds) {
            return $this;
        }

        $productRulesIds = $this->_productLabelRule->getProductRulesIds($productIds);
        if (!$productRulesIds) {
            return $this;
        }
        //TODO: Need to test perfomace with: overwrite of public function AbstractCollection::getAllIds()
        foreach ($collection->getItems() as $item) {
            if (isset($productRulesIds[$item->getid()])) {
                $item->setAppliedRules($productRulesIds[$item->getid()]);
            }
        }
    }
}
