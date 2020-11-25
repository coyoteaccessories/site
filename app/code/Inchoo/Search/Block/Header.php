<?php

namespace Inchoo\Search\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Inchoo\Search\Helper\Vehicle as SearchVehicleHelper;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Header extends Template {

    protected $_searchVehicleHelper;

    public function __construct(Context $context, SearchVehicleHelper $searchVehicleHelper, Registry $registry, ScopeConfigInterface $scopeConfig, DateTime $dateTime) {
        $this->_searchVehicleHelper = $searchVehicleHelper;

        $this->_registry = $registry;
        $this->_scopeConfig = $scopeConfig;
        $this->_dateTime = $dateTime;

        parent::__construct($context);
    }

    protected function _construct() {
        $this->setTemplate('fitment/header.phtml');
    }

    public function getVehicleHelper() {
        return $this->_searchVehicleHelper;
    }

    public function getOeSensors() {
        $params = $this->getRequest()->getParams();
        $yearId = (int) $params['year'];
        $makeId = (int) $params['make'];
        $modelId = (int) $params['model'];
        $subModelId = (int) $params['submodel'];
        $additionalCriteriaPartmasterId = isset($params['criteria']) && !empty($params['criteria']) ? $params['criteria'] : null;
        $eoSensors = $this->getVehicleHelper()->getOeSensors($yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId);
        if ($eoSensors && $eoSensors->getSize() > 0) {
            return $eoSensors;
        } else {
            return null;
        }
    }

    public function getOeSensorsNoResultMsg() {
        return $this->getVehicleHelper()->getOeNoResultMsg();
    }

    public function getImageUrl($imageFileName) {
        if ($imageFileName && $imagePath = $this->getVehicleHelper()->getImagePath()) {
            return $imagePath . '' . $imageFileName;
        }
        return '';
    }

}
