<?php

/**
 *
 * Copyright © Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\AjaxCompare\Block\Compare\Product;

use Magento\Framework\View\Element\Template\Context;
use Zemez\AjaxCompare\Helper\Product\Compare\AjaxResponse;

class Status extends \Magento\Framework\View\Element\Template
{

    /**
     * @var AjaxResponse
     */
    protected $_helper;

    /**
     * Status constructor.
     * @param Context $context
     * @param array $data
     * @param AjaxResponse $helper
     */
    public function __construct(Context $context,
                                AjaxResponse $helper,
                                array $data = [])
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getStatusValue()
    {
        return 'var compareProductAddAjax'.' = '.$this->_helper->getModuleStatus().';';
    }
}
