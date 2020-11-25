<?php

namespace AJH\Fitment\Model;

//use AJH\Fitment\Model\Fitment\Cache as FitmentCache;
use AJH\Fitment\Model\MakesFactory as FitmentMakesCollection;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;        

class Makes extends \Magento\Framework\Model\AbstractModel {

//    const CACHE_TAG = 'ajh_fitment_makes';
//
//    protected $_cacheTag = 'ajh_fitment_makes';
//    protected $_eventPrefix = 'ajh_fitment_makes';
//    protected $_fitmentCache;
    protected $_fitmentMakesCollection;
//
    public function __construct(
            Context $context,
            Registry $registry,            
            FitmentMakesCollection $fitmentMakesCollection
    ) {
        parent::__construct($context, $registry);
        
        $this->_fitmentMakesCollection = $fitmentMakesCollection;
        
//        die('test');
//
//        $this->_loadMakes();
    }
//
    protected function _construct() {
        $this->_init('AJH\Fitment\Model\ResourceModel\Makes');
    }
//
//    public function getIdentities() {
//        return [self::CACHE_TAG . '_' . $this->getId()];
//    }
//
//    public function getDefaultValues() {
//        $values = [];
//
//        return $values;
//    }
//
//    protected function _loadMakes() {
//        $this->_fitmentCache->cacheFitmentMakes();
//    }
//
//    public function getMakesByYear($year) {
//        $collection = $this->_fitmentMakesCollection->create()->getCollection();
//
//        $_yearMakes = [];
//
//        if ($collection->count()) {
//            foreach ($collection as $_make) {
//                if (intval($year) === intval($_make->getYear())) {
//                    $_yearMakes[$_make->getMakeID()] = $_make;
//                }
//            }
//        }
//
//        return $_yearMakes;
//    }

}
