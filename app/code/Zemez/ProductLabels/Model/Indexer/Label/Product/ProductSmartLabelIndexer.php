<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Model\Indexer\Label\Product;

class ProductSmartLabelIndexer extends \Zemez\ProductLabels\Model\Indexer\AbstractIndexer
{

    /**
     * @param \int[] $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function doExecuteList($ids)
    {
        $this->_indexBuilder->reindexByIds(array_unique($ids));
    }

    /**
     * @param int $id
     */
    protected function doExecuteRow($id)
    {
        $this->_indexBuilder->reindexById($id);
    }
}
