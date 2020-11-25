<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Block\Product;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\CatalogSearch\Block\Result;
use Magento\CatalogSearch\Block\Advanced\Result as AdvancedResult;
use Magento\Framework\View\LayoutInterface;
use Zemez\ProductLabels\Helper\Data;
use Zemez\ProductLabels\Model\ProductLabel;


/**
 * Config edit plugin.
 *
 * @package Zemez\ProductLabels\Block\Product
 */
class ListProductPlugin
{

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @var \Zemez\ProductLabels\Helper\Data
     */
    protected $_helper;

    public function __construct(
        Data $helper,
        LayoutInterface $layoutInterface
    )
    {
        $this->_helper = $helper;
        $this->_layout = $layoutInterface;
    }

    public function aroundGetProductPrice(ListProduct $subject, \Closure $proceed, Product $product)
    {
        $resultHtml = $proceed($product);
        if (!$this->_helper->isActive() ) {
            return $resultHtml;
        }
        $html = $this->_layout->createBlock('Magento\Framework\View\Element\Template')
            ->setProduct($product)
            ->setTemplate('Zemez_ProductLabels::catalog-labels.phtml')->toHtml();
        return $resultHtml.$html;
    }

}

