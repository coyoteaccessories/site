<?php

namespace Inchoo\Search\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Inchoo\Search\Helper\Vehicle as SearchVehicle;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Protocol extends Template {

    const XPATH_IS_PROTOCOL_ENABLED = 'vehicle/protocol/enable';
    const XPATH_PRODUCT_CLASSES = 'vehicle/protocol/product_class';
    
    protected $_searchVehicle, $_registry, $_scopeConfig, $_dateTime, $_storeManager;
    
    public function __construct(Context $context, SearchVehicle $searchVehicle, Registry $registry, ScopeConfigInterface $scopeConfig, DateTime $dateTime, StoreManagerInterface $storeManager){
        $this->_searchVehicle = $searchVehicle;
        
        $this->_registry = $registry;
        $this->_scopeConfig = $scopeConfig;
        $this->_dateTime = $dateTime;
        
        $this->_storeManager = $storeManager;
        
        parent::__construct($context);
    }

    protected function _construct() {
                
        if ($this->_scopeConfig->getValue(self::XPATH_IS_PROTOCOL_ENABLED, ScopeInterface::SCOPE_STORE)) {
            $this->setTemplate('fitment/vehicle/protocol.phtml');
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

}
