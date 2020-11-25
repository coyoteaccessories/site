<?php

namespace Inchoo\Search\Block\Relearn;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Inchoo\Search\Helper\Vehicle as SearchVehicle;

class Header extends Template {

    protected $_searchVehicle;

    public function __construct(Context $context, SearchVehicle $searchVehicle) {
        parent::__construct($context);

        $this->_searchVehicle = $searchVehicle;
    }

    protected function _construct() {
        $this->setTemplate('fitment/relearn/header.phtml');
    }

    public function getRelearnUrl() {
        $year = $this->getRequest()->getParam('year');
        $make = $this->getRequest()->getParam('make');
        $model = $this->getRequest()->getParam('model');
        $submodel = $this->getRequest()->getParam('submodel');
        return $this->_searchVehicle->getRelearnUrl($year, $make, $model, $submodel);
    }

}
