<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Plugin\Indexer;

use Zemez\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor;
use Magento\ImportExport\Model\Import;

class ImportExport
{
    /**
     * @var SmartLabelProductProcessor
     */
    protected $_smartLabelProductProcessor;

    /**
     * ImportExport constructor.
     *
     * @param SmartLabelProductProcessor $smartLabelProductProcessor
     */
    public function __construct(SmartLabelProductProcessor $smartLabelProductProcessor)
    {
        $this->_smartLabelProductProcessor = $smartLabelProductProcessor;
    }

    /**
     * Invalidate catalog price rule indexer
     *
     * @param Import $subject
     * @param bool   $result
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterImportSource(Import $subject, $result)
    {
        if (!$this->_smartLabelProductProcessor->isIndexerScheduled()) {
            $this->_smartLabelProductProcessor->markIndexerAsInvalid();
        }

        return $result;
    }
}
