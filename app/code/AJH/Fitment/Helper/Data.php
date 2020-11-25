<?php

namespace AJH\Fitment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper {

    public $_client;
    public $_params;
    public $x_catids = array();
    
    public $_storeid;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;
    protected $_scopeConfig;

    public function __construct(StoreManagerInterface $storeManager, ScopeConfigInterface $scopeConfig) {
        $this->storeManager = $storeManager;
                
        $this->_storeid = $storeManager->getStore()->getId();

        $wsdl = 'https://www.revolutionsupply.com/RS_webservice.asmx?WSDL'; //Mage::getStoreConfig('fitment_api/general/soap_client_wsdl');
        $username = 'ajhcreative'; //Mage::getStoreConfig('fitment_api/general/soap_client_username');
        $password = 'AJHcr3at1v3'; //Mage::getStoreConfig('fitment_api/general/soap_client_password');


        if ($wsdl) {
            $this->_client = new \SoapClient($wsdl, array("soap_version" => SOAP_1_2));
            $this->_params = array(
                'UserID' => $username,
                'Password' => $password
            );
        }
        
        $this->_scopeConfig = $scopeConfig;
        
    }//__construct

    /**
     * Get an associative array of [store_id => root_category_id] values for all stores
     * @return array
     */
    public function getAllStoreRootCategories() {
        $storeroots = [];
        foreach ($this->storeManager->getStores() as $store) {
            $storeroots[$store->getId()] = $store->getRootCategoryId();
        }
        return $storeroots;
    }

//getAllStoreRootCategories

    /**
     * Get the root category id of a store
     * @param int|string|\Magento\Store\Model\Store $store The store to get category from, either by store_id, store_code or the \Magento\Store\Model\Store instance itself
     * @return int root category of store
     * @throws \Exception if no such store was found
     */
    public function getStoreRootCategoryId($store) {
        # Get \Magento\Store\Model\Store instance by id        
        if (is_int($store)) {
            $store = $this->storeManager->getStore($store);
            
        }

        # Get \Magento\Store\Model\Store instance by code
        if (is_string($store)) {
            foreach ($this->storeManager->getStores() as $storeModel) {
                if ($storeModel->getCode() == $store) {
                    $store = $storeModel;
                    break;
                }
            }
        }

        # Get root category id from \Magento\Store\Model\Store instance
        if ($store instanceof \Magento\Store\Model\Store) {
            return $store->getRootCategoryId();
        }

        # If no \Magento\Store\Model\Store instance was supplied or found by id/code
        throw new \Exception('No such store found: ' . var_export($store, true));
    }

//getStoreRootCategoryId

    public function getExcludedCatIds() {

        if (!$this->x_catids) {
            $cat_ids = $this->_scopeConfig->getValue( 'fitment_api/categories/exclude_categories', \Magento\Store\Model\ScopeInterface::SCOPE_STORE ); //Mage::getStoreConfig('fitment_api/categories/exclude_categories');
            $x_cat_ids = explode(",", $cat_ids);

            foreach ($x_cat_ids as $x_cat_id) {
                array_push($this->x_catids, $x_cat_id);
            }
        }

        return $this->x_catids;
    }

}
