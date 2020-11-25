<?php

namespace AJH\ProductVehicle\Controller\Search;

class Index extends \Magento\Framework\App\Action\Action {

    public function __construct(\Magento\Framework\App\Action\Context $context) {
        return parent::__construct($context);
    }

    public function execute() {
        $this->loadLayout();
        $vehicleListHtml = $this->getLayout()->createBlock('AJH\ProductVehicle\Block\Product\View\Vehicle')->toHtml();
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($vehicleListHtml);
        return;
    }

}
