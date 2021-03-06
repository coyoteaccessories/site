<?php
namespace Zemez\SiteMaintenance\Block;

use Magento\Framework\View\Element\Template;

class Styles extends Template
{
    public $helper;

    public function __construct(
        \Zemez\SiteMaintenance\Helper\Data $helper,
        Template\Context $context,
        array $data = []
    ) {
        $this->helper = $helper;

        parent::__construct($context, $data);
    }
}
