<?php

namespace Zemez\Amp\Model\Plugin\LayeredNavigation\Navigation;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;


class FilterRenderer
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @param \Zemez\Amp\Helper\Data $dataHelper
     */
    public function __construct(
        \Zemez\Amp\Helper\Data $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\LayeredNavigation\Block\Navigation\FilterRenderer $subject
     * @param callable $proceed
     * @param FilterInterface $filter
     * @return string
     */
    public function aroundRender(
        \Magento\LayeredNavigation\Block\Navigation\FilterRenderer $subject,
        callable $proceed,
        FilterInterface $filter
    )
    {
        if ($this->_dataHelper->isAmpCall()) {
            $filters = $filter->getItems();
            $subject->assign('filterItems', $filters);
            $subject->setTemplate('Magento_LayeredNavigation::layer/filter.phtml');
            $html = $subject->toHtml();
            $subject->assign('filterItems', []);
            return $html;
        }
        return $proceed($filter);
    }
}