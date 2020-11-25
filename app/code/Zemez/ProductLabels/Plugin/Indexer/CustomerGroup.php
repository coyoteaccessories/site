<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Plugin\Indexer;

use Zemez\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor;

class CustomerGroup
{
    /**
     * @var SmartLabelProductProcessor
     */
    protected $_smartLabelProductProcessor;

    /**
     * CustomerGroup constructor.
     *
     * @param SmartLabelProductProcessor $smartLabelProductProcessor
     */
    public function __construct(SmartLabelProductProcessor $smartLabelProductProcessor)
    {
        $this->_smartLabelProductProcessor = $smartLabelProductProcessor;
    }

    /**
     * @param Group $subject
     * @param Group $result
     *
     * @return Group
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        Group $subject,
        Group $result
    ) {
        $this->_smartLabelProductProcessor->markIndexerAsInvalid();

        return $result;
    }
}
