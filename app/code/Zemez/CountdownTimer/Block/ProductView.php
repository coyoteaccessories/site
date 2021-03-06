<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 * @copyright Zemez
 * @license http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

namespace Zemez\CountdownTimer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;

use Zemez\CountdownTimer\Helper\Data;
use Zemez\CountdownTimer\Model\Timer;

class ProductView extends Template
{

    protected $_coreRegistry;

    public function __construct(
        Data $helper,
        Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->_coreRegistry->registry('product'));
        }
        return $this->getData('product');
    }

    public function toHtml()
    {
        return $this->_helper->getTimerHtml($this->getProduct(), Timer::PRODUCT_PAGE);
    }
}