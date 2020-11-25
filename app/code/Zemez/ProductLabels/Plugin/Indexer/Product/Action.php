<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Plugin\Indexer\Product;

use Magento\Catalog\Model\Product\Action as ProductAction;
use Zemez\ProductLabels\Model\Indexer\Label\Product\ProductSmartLabelProcessor;

class Action
{
    /**
     * @var ProductSmartLabelProcessor
     */
    protected $_productSmartLabelProcessor;

    /**
     * Action constructor.
     *
     * @param ProductSmartLabelProcessor $productSmartLabelProcessor
     */
    public function __construct(ProductSmartLabelProcessor $productSmartLabelProcessor)
    {
        $this->_productSmartLabelProcessor = $productSmartLabelProcessor;
    }

    /**
     * @param ProductAction $object
     * @param ProductAction $result
     *
     * @return ProductAction
     *
     * @SuppressWarnings(PHPMD.UnusedFormatParameter)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterUpdateAttributes(ProductAction $object, ProductAction $result)
    {
        $data = $result->getAttributesData();
        if (!empty($data['price'])) {
            $this->_productSmartLabelProcessor->reindexList($result->getProductIds());
        }

        return $result;
    }
}
