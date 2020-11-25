<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Cron;

use Zemez\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor;

class DailySmartLabelUpdate
{

    /**
     * @var SmartLabelProductProcessor
     */
    protected $_smartLabelProductProcessor;

    /**
     * DailySmartLabelUpdate constructor.
     * @param SmartLabelProductProcessor $smartLabelProductProcessor
     */
    public function __construct(SmartLabelProductProcessor $smartLabelProductProcessor)
    {
        $this->_smartLabelProductProcessor = $smartLabelProductProcessor;
    }

    public function execute()
    {
        $this->_smartLabelProductProcessor->markIndexerAsInvalid();
    }
}
