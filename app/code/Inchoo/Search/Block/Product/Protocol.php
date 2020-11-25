<?php

namespace Inchoo\Search\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Inchoo\Search\Helper\Vehicle as SearchVehicle;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Protocol extends Template {

    const XPATH_IS_PROTOCOL_ENABLED = 'vehicle/protocol/enable';
    const XPATH_PRODUCT_CLASSES = 'vehicle/protocol/product_class';

    protected $_searchVehicle, $_registry, $_scopeConfig, $_dateTime;

    public function __construct(Context $context, SearchVehicle $searchVehicle, Registry $registry, ScopeConfigInterface $scopeConfig, DateTime $dateTime) {
        $this->_searchVehicle = $searchVehicle;

        $this->_registry = $registry;
        $this->_scopeConfig = $scopeConfig;
        $this->_dateTime = $dateTime;

        parent::__construct($context);
    }

    protected function _construct() {
        if ($this->_scopeConfig->getValue(self::XPATH_IS_PROTOCOL_ENABLED)) {
            $this->setTemplate('ajh/product/protocol.phtml');
        }
    }

    public function getVehicleHelper() {
        return $this->_searchVehicle;
    }

    public function getProtocolDates() {
        return $this->getVehicleHelper()->getProtocolDates();
    }

    public function getCurrentStoreDate() {
        return $this->_dateTime->date('m/d/Y');
    }

    public function getFormatDate($date) {
        $dateTimestamp = $this->_dateTime->timestamp(strtotime($date));
        return date('m/d/Y', $dateTimestamp);
    }

    public function getProduct() {
        if ($product = $this->_registry->registry('current_product')) {
            return $product;
        }
        return null;
    }

    public function isAllowedDisplayPDP() {
        $product = $this->getProduct();
        if ($product !== null) {
            return true;
        }
        return false;
    }

}
