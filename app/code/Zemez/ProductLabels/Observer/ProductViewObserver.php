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

class ProductViewObserver implements ObserverInterface
{
    /**
     * @var
     */
    protected $_productLabelRule;

    /**
     * ProductViewObserver constructor.
     *
     * @param \Zemez\ProductLabels\Helper\ProductLabelRule $productLabelRule
     */
    public function __construct(
        \Zemez\ProductLabels\Helper\ProductLabelRule $productLabelRule
    ) {
        $this->_productLabelRule = $productLabelRule;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $collection ProductCollection */
        $product = $observer->getEvent()->getProduct();
        //Get product ids on collection
        $productIds = [$product->getId() => $product->getId()];
        if (!$productIds) {
            return $this;
        }
        $productRulesIds = $this->_productLabelRule->getProductRulesIds($productIds);
        if (!$productRulesIds) {
            return $this;
        }
        //TODO: Need to test performance with: overwrite of public function AbstractCollection::getAllIds()
        if (isset($productRulesIds[$product->getid()])) {
            $product->setAppliedRules($productRulesIds[$product->getid()]);
        }
    }
}
