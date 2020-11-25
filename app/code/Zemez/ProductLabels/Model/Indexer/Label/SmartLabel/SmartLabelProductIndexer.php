<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Model\Indexer\Label\SmartLabel;

class SmartLabelProductIndexer extends \Zemez\ProductLabels\Model\Indexer\AbstractIndexer
{
    /**
     * @param \int[] $ids
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function doExecuteList($ids)
    {
        $this->_indexBuilder->reindexFull();
    }

    /**
     * @param int $id
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function doExecuteRow($id)
    {
        $this->_indexBuilder->reindexFull();
    }
}
