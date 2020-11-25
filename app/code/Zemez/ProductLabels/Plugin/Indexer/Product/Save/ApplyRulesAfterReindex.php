<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Plugin\Indexer\Product\Save;

use Zemez\ProductLabels\Model\Indexer\Label\Product\ProductSmartLabelProcessor;

class ApplyRulesAfterReindex
{
    /**
     * @var ProductSmartLabelProcessor
     */
    protected $_productSmartLabelProcessor;

    /**
     * ApplyRulesAfterReindex constructor.
     *
     * @param ProductSmartLabelProcessor $productSmartLabelProcessor
     */
    public function __construct(ProductSmartLabelProcessor $productSmartLabelProcessor)
    {
        $this->_productSmartLabelProcessor = $productSmartLabelProcessor;
    }

    /**
     * Apply catalog rules after product resource model save
     *
     * @param \Magento\Catalog\Model\Product $subject
     * @param callable                       $proceed
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function aroundReindex(
        \Magento\Catalog\Model\Product $subject,
        callable $proceed
    ) {
        $proceed();
        $this->_productSmartLabelProcessor->reindexRow($subject->getId());

        return;
    }
}
