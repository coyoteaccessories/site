<?php
namespace Zemez\SiteMaintenance\Block\Page;

use Zemez\SiteMaintenance\Block\Page;

use Magento\Framework\View\Element\Template;

class Content extends Page
{

    public function getPageDescription()
    {
        return $this->filterContent($this->helper->getPageDescription());
    }

}
