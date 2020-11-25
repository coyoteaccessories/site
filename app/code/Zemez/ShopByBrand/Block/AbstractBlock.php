<?php

namespace Zemez\ShopByBrand\Block;

use Zemez\ShopByBrand\Helper\Data as ShopByBrandHelper;
use Magento\Framework\View\Element\Template;

/**
 * Abstract block class.
 *
 * @package Zemez\ShopByBrand\Block
 */
abstract class AbstractBlock extends Template
{
    /**
     * @var ShopByBrandHelper
     */
    protected $_helper;

    /**
     * AbstractBlock constructor.
     *
     * @param ShopByBrandHelper $helper
     * @param Template\Context  $context
     * @param array             $data
     */
    public function __construct(
        ShopByBrandHelper $helper,
        Template\Context $context,
        array $data = []
    )
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        if (!$this->_helper->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}