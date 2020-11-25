<?php
/*© Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content.

*/
namespace Webgility\EccM2\Model;
ini_set("display_errors","Off");
// error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// error_reporting(E_ALL);

use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;

class Desktop
{

    private $_objectManager;

    public function __construct(
    \Magento\Framework\ObjectManagerInterface $objectmanager) {
        $this->_objectManager = $objectmanager;
    }

    private $iscompress = true;
    private $types = [
                    'AE' => 'American Express',
                    'VI' => 'Visa',
                    'MC' => 'MasterCard',
                    'DI' => 'Discover',
                    'OT' => 'Other',
                    '' => ''
                    ];

    private $carriers = [
                        'dhl' => 'DHL',
                        'fedex' => 'FedEx',
                        'ups' => 'UPS',
                        'usps' => 'USPS',
                        'freeshipping' => "Free Shipping",
                        'flatrate' => "Flat Rate",
                        'tablerate' => "Best Way"
                        ];
    private $carriers_ = [
                        'DHL' => 'dhl',
                        'FEDEX'=>'fedex',
                        'UPS' => 'ups',
                        'USPS' => 'usps',
                        'FEDERAL EXPRESS' => 'fedex',
                        'UNITED PARCEL SERVICE' => 'ups',
                        'UNITED STATES POSTAL SERVICE' => 'usps',
                        "FREE SHIPPING" => 'freeshipping',
                        'FLAT RATE' => "flatrate",
                        "BEST WAY" => 'tablerate'
                        ];
    public function OpenSSLConnection(){
            $WgBaseResponse = $this->_objectManager->get('Webgility\EccM2\Model\EccWgBaseResponse');    
            $WgBaseResponse->setStatusCode('999');
            $WgBaseResponse->setStatusMessage("function mcrypt_decrypt() is deprecated!");
            return $this->WgResponse($WgBaseResponse->getBaseResponce());
    }
    public function parseRequest($wgrequest, $compress)
    {
        $request ='';
        $this->iscompress = $compress;
        
        $wgrequest =  $this->getRequestData($wgrequest);
        if ($wgrequest) {

            $wgrequest = json_decode($wgrequest, true);

            foreach ($wgrequest as $k => $v) {
                $$k = $v;
            }
        }

        $others = isset($others) ? $others : "";
        $itemid = isset($itemid) ? $itemid : "";
        if (!empty($method)) {

            switch ($method) {
                case 'OpenSSLConnection':
                return $str = $this->$method();
                break;
                
                case 'checkAccessInfo':
                case 'getStores':
                case 'isAuthorized':
                case 'getVersions':
                    return $str = $this->$method($username, $password, $others);
                    break;

                case 'UpgradeVersions':
                    return $str = $this->$method($username, $password, $others, $url);
                    break;

                case 'getItems':
                $DownloadType = isset($DownloadType) ? $DownloadType : "";
                $UpdatedDate = isset($UpdatedDate) ? $UpdatedDate : "";
                $start_item_no = isset($start_item_no) ? $start_item_no : "";
                $limit = isset($limit) ? $limit : "20";
                $datefrom = isset($datefrom) ? $datefrom : "";
                $storeid = isset($storeid) ? $storeid : "";
                $others = isset($others) ? $others : "";
                    return $str = $this->$method($username, $password, $DownloadType, $UpdatedDate, $start_item_no, $limit, $datefrom, $storeid, $others);
                    break;

                case 'getAttributesets':
                    return $str = $this->$method($username, $password, $storeid, $others);
                    break;
                case 'getShippingMethods':
                    return $str = $this->$method($username, $password, $storeid, $others);
                    break;
                case 'getManufacturers':
                    return $str = $this->$method($username, $password, $storeid, $others);
                    break;
                case 'getTaxes':
                    return $str = $this->$method($username, $password, $storeid, $others);
                    break;
                case 'getOrderStatusForOrder':
                    return $str = $this->$method($username, $password, $storeid, $others);
                    break;
                case 'getShippingMethods':
                case 'getPaymentMethods':
                case 'getCompanyInfo':
                case 'getOrderStatus':
                case 'getCustomerGroup':
                case 'getCategory':
                    return $str = $this->$method($username, $password, $storeid, $others);
                    break;

                case 'getSelectedOrders':
                case 'getOrders':
                    $method = 'getOrders';
                    $SelectedOrders = isset($SelectedOrders) ? $SelectedOrders : "";
                    $ProductType = isset($ProductType) ? $ProductType : "";
                    $datefrom = isset($datefrom) ? $datefrom : "";
                    $start_order_no = isset($start_order_no) ? $start_order_no : "0";
                    $ecc_excl_list = isset($ecc_excl_list) ? $ecc_excl_list : "";
                    $order_per_response = isset($order_per_response) ? $order_per_response : "25";
                    $LastModifiedDate = isset($LastModifiedDate) ? $LastModifiedDate : "";
                    $others['CCDetails'] = isset($others['CCDetails']) ? $others['CCDetails'] : "";
                    $others['optionAsItemBundled'] = isset($others['optionAsItemBundled']) ? $others['optionAsItemBundled'] : "";
                    $others['optionAsItemConfigurable'] = isset($others['optionAsItemConfigurable']) ? $others['optionAsItemConfigurable'] : "";
                    $others['discountAsItem'] = isset($others['discountAsItem']) ? $others['discountAsItem'] : "";
                    $others['optionAsItem'] = isset($others['optionAsItem']) ? $others['optionAsItem'] : "";
                    $others['DonwloadStateCode'] = isset($others['DonwloadStateCode']) ? $others['DonwloadStateCode'] : "";

                    return $str = $this->$method($username, $password, $datefrom, $start_order_no, $ecc_excl_list, $order_per_response, $storeid, $SelectedOrders, $others['CCDetails'], $others['optionAsItemBundled'], $others['optionAsItemConfigurable'], $others['discountAsItem'],  $others['optionAsItem'], $others['DonwloadStateCode'], $LastModifiedDate, $ProductType);
                    break;

                case 'synchronizeItems':
                    return $str = $this->$method($username, $password, $data, $storeid, $Other['SyncType']);
                    break;
                case 'UpdateOrdersShippingStatus':
                    return $str = $this->$method($username, $password, $data, $emailAlert = 'N', $statustype, $storeid, $others);
                    break;

                case 'addProduct':
                case 'addCustomers':
                    return $str = $this->$method($username, $password, $data, $storeid);
                    break;
            
                case 'addOrderShipment':
                    return $str = $this->$method($username, $password, $data, $storeid, $others['optionAsItem']);
                    break;
                case 'getItemsByName':
                    $start_item_no = isset($start_item_no) ? $start_item_no : "0";
                    $limit = isset($limit) ? $limit : "20";
                    $itemname = isset($itemname) ? $itemname : "";
                    $storeid = isset($storeid) ? $storeid : "1";
                    $others = isset($others) ? $others : "";

                    return $str =$this->$method($username, $password, $start_item_no, $limit, $itemname, $storeid, $others);
                    break;

                case 'getItemsQuantity':
                    $itemid = isset($itemid) ? $itemid : "";
                    $storeid = isset($storeid) ? $storeid : "1";
                    $others = isset($others) ? $others : "";
                    return $str = $this->$method($username, $password, $itemid, $storeid, $others);
                    break;

                case 'getCustomers':
                    $method = 'getCustomers';
                    return $str = $this->$method($username, $password, $datefrom, $customerid, $limit, $storeid);
                    break;

                case 'getCustomersNew':
                    $method = 'getCustomers';
                    return $str = $this->$method($username, $password, $datefrom, $customerid, $limit, $storeid);
                    break;

                case 'getVisibilityStatus':
                    return $str = $method($username, $password, $storeid, $others);
                    break;

                case 'GetImage':
                    $data = isset($data) ? $data : "";
                    $storeid = isset($storeid) ? $storeid : "";
                    $others = isset($others) ? $others : "";
                    return $str = $this->$method($username, $password, $data, $storeid, $others);
                    break;

                case 'AutoSyncOrder':
                    return $str = $this->$method($username, $password, $data, $statustype, $storeid, $others);
                    break;

                case 'getModuleVersion':
                    return $str = $this->$method($username, $password, $storeid, $SelectedOrders);
                    break;
            }
        }
    }

    function getRequestData($s1)
    {

        if (isset($this->iscompress) && ($this->iscompress =='false')) {
            return $s1;
        }

    }
    function WgResponse($responseArray) 
    {
        $str = json_encode($responseArray); 
       
        if (isset($this->iscompress) && ($this->iscompress == 'false')) {
             return $str;
        }

    }
    function stringToHex($str)
    {
        $hex = "";
        $zeros = "";
        $len = 2 * strlen($str);
        for ($i = 0; $i < strlen($str); $i++) {
            $val = dechex(ord($str{$i}));
            if (strlen($val) < 2) {
                $val = "0".$val;
                $hex .= $val;
            }
        }
        for ($i = 0; $i < $len - strlen($hex); $i++) {
            $zeros .= '0';
        }
        return $hex.$zeros;
    }

    function hexToString($hex)
    {
        $str = "";
        for ($i = 0; $i < strlen($hex); $i = $i + 2) {
            $temp = hexdec(substr($hex, $i, 2));
            if (!$temp) {
                continue;
            }
            $str .= chr($temp);
        }
        return $str;
    }

    public function checkUser($username, $password)
    {

        $WgBaseResponse = $this->_objectManager->get('Webgility\EccM2\Model\EccWgBaseResponse');

        try {
                $user = $this->_objectManager->get('Magento\User\Model\User');
                $temp=$user->authenticate($username, $password);
                if ($temp) {
                    return 0;
                } else {
                    $WgBaseResponse->setStatusCode('2');
                    $WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
                    return $this->WgResponse($WgBaseResponse->getBaseResponce());
                }

            } catch (\Exception $e) {
                $WgBaseResponse->setStatusCode('1');
                $WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
                return $this->WgResponse($WgBaseResponse->getBaseResponce());
            }
    }

    function getVersion()
    {
        $productMetadata = $this->_objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        if($productMetadata->getVersion()!="")
        {
            return $productMetadata->getVersion();
        }else{
            return "0";
        }
    }
    
    function checkAccessInfo($username, $password, $others)
    {

        $responseArray = [];
        $status = $this->checkUser($username, $password);

        if ($status != "0") {
            return $status;
        }

        $version = $this->getVersion();
        $WgBaseResponse = $this->_objectManager->get('Webgility\EccM2\Model\EccWgBaseResponse');
        $WgBaseResponse->setStatusCode('0');
        $code = "0";
        $message = "Successfully connected to your online store.";
        $responseArray['StatusCode'] = $code;
        if ($version !== 0) {
            $WgBaseResponse->setStatusMessage($message);
        } else {
            $WgBaseResponse->setStatusMessage($message." However, eCC is unable to detect your store version. If you'd still like to continue, click OK to continue or contact Webgility to confirm compatibility.");
        }
        return $this->WgResponse($WgBaseResponse->getBaseResponce());
    }

    function getCompanyInfo($username, $password, $storeid = 1, $others)
    {
 
        $storeId = $this->getDefaultStore($storeid);
        $WgBaseResponse = $this->_objectManager->get('Webgility\EccM2\Model\EccWgBaseResponse');
        $CompanyInfo = $this->_objectManager->get('Webgility\EccM2\Model\CompanyInfo');
        $status = $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $config = $this->_getStoreDetails();
        
        if (isset($config['shipping']['origin']['region_id'])) {
            $origRegionCode = $this->_objectManager->get('Magento\Directory\Model\Region')->load($config['shipping']['origin']['region_id'])->getCode();
        
        } else {
            $origRegionCode = $config["storeRegion"];
        }
        if (isset($config['shipping']['origin']['country_id'])) {
            $country = $this->_objectManager->get('Magento\Directory\Model\Country')->load($config['shipping']['origin']['country_id'])->getIso2Code();

        } else {
            $country = $config["storeCountry"];
        }
        $CompanyInfo->setStatusCode('0');
        $CompanyInfo->setStatusMessage('All Ok');
        $CompanyInfo->setStoreID('1');
        $CompanyInfo->setStoreName($config['storeName']);
        $CompanyInfo->setAddress(htmlspecialchars($config['storeAddress1'], ENT_NOQUOTES));
        $CompanyInfo->setAddress1(htmlspecialchars($config['storeAddress1'], ENT_NOQUOTES));
        $CompanyInfo->setAddress2(htmlspecialchars($config['storeAddress2'], ENT_NOQUOTES));
        if (isset($config['shipping']['origin']['city'])) {
            $CompanyInfo->setcity($config['shipping']['origin']['city']);
        } else {
            $CompanyInfo->setcity($config["storeCity"]);
        }
        $CompanyInfo->setState($origRegionCode);
        $CompanyInfo->setCountry($country);
        if (isset($config['shipping']['origin']['postcode'])) {
            $CompanyInfo->setZipcode($config['shipping']['origin']['postcode']);
        } else {
            $CompanyInfo->setZipcode($config["storePostCode"]);
        }
        $CompanyInfo->setPhone($config["storePhone"]);
        $CompanyInfo->setFax('');
        $storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');

        $CompanyInfo->setWebsite($storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK));
        return $this->WgResponse($CompanyInfo->getCompanyInfo());
    }

    function getStores($username, $password, $others)
    {
        $responseArray = [];
        $status =  $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $Storesinfo = $this->_objectManager->get('Webgility\EccM2\Model\Stores');
        $Storesinfo->setStatusCode('0');
        $Storesinfo->setStatusMessage('All Ok');
        $stores = $this->getStoresData();
        if (count($stores)>0) {
            $s = 0;
            for ($i = 0; $i < count($stores['items']); $i++) {
                if ($stores['items'][$i]['group_id'] > 0) {
                   $views = $this->_objectManager->get('\Magento\Store\Model\Store')
                                           ->getCollection()
                                           ->addGroupFilter($stores['items'][$i]['group_id'])
                                           ->load();
                    $views = $views->toArray();
                    foreach ($views['items'] as $view) {
                        $Store = $this->_objectManager->get('Webgility\EccM2\Model\Store');
                        $Store->setStoreID($view['store_id']);
                        $Store->setStoreName($stores['items'][$i]['name']."->".$view['name']);
                        $Store->setStoreWebsiteId($stores['items'][$i]['website_id']);
                        $Store->setStoreWebsiteName($stores['items'][$i]['website_name']);
                        $Store->setStoreRootCategoryId($stores['items'][$i]['root_category_id']);
                        $Store->setStoreDefaultStoreId($stores['items'][$i]['default_store_id']);
                        $Storesinfo->setstores($Store->getStore());
                    }
                }
            }
        }
        return $this->WgResponse($Storesinfo->getStoresInfo());
    }

    function getStoresData()
    {
        $websites = $this->_objectManager->get('\Magento\Store\Model\Website')
                ->getResourceCollection()
                ->setLoadDefault(true)
                ->load();
        $websites1 = $websites->toArray();
        unset($websites);
        $stores = $this->_objectManager->get('\Magento\Store\Model\Group')
                ->getResourceCollection()
                ->setLoadDefault(true)
                ->load();
        $stores = $stores->toArray();
        for ($i = 0; $i < count($websites1['items']); $i++) {
            if ($websites1['items'][$i]['website_id'] > 0) {
                $websites[$websites1['items'][$i]['website_id']] = $websites1['items'][$i]['name'];
            }
        }
        for ($i = 0; $i<count($stores['items']); $i++) {
            if ($stores['items'][$i]['group_id']>0) {
                $stores['items'][$i]['website_name'] = $websites[$stores['items'][$i]['website_id']];
            }
        }
        return $stores;
    }

    function getPaymentMethods($username, $password, $storeid = 1, $others)
    {

        $status = $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }

        $PaymentMethods = $this->_objectManager->get('Webgility\EccM2\Model\PaymentMethods');
        $PaymentMethods->setStatusCode('0');
        $PaymentMethods->setStatusMessage('All Ok');
    
        $config = $this->getPaymentArray(1);
        $i = 1;

        foreach ($config as $k=>$v) {
            if ($config[$k]['value'] != '' && $config[$k]['label'] != '') {

                $PaymentMethod = $this->_objectManager->get('Webgility\EccM2\Model\PaymentMethod');
                $PaymentMethod->setMethodId($i);
                $PaymentMethod->setMethod($config[$k]['label']);
                $PaymentMethod->setDetail($config[$k]['value']);
                $PaymentMethods->setPaymentMethods($PaymentMethod->getPaymentMethod());
            }
            $i++;
        }
        return $this->WgResponse($PaymentMethods->getPaymentMethods());
    }

    public function getPaymentArray($store = null)
    {

        $methods = [['value' => '', 'label' => '']];
        foreach ($this->_getPaymentMethods() as $paymentCode=>$paymentModel) {
        
         $scopeConfig = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        
         $paymentTitle = $scopeConfig->getValue('payment/'.$paymentCode.'/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            
            $methods[$paymentCode] = [
                'label'   => $paymentTitle,
                'value' => $paymentCode,
            ];
        }
        return $methods;
    }

    function getShippingMethods($username, $password, $storeid = 1, $others)
    {
        $status =  $this->checkUser($username, $password);
        if($status != "0") {
            return $status;
        }

        $ShippingMethods = $this->_objectManager->get('Webgility\EccM2\Model\ShippingMethods');
        $ShippingMethods->setStatusCode('0');
        $ShippingMethods->setStatusMessage('All Ok');
        $carriers = $this->_getshippingMethods($storeid);
        
        if ($carriers == "error_msg") {
            $ShippingMethods->setStatusCode('1');
            $ShippingMethods->setStatusMessage('Please enable at least one shipping method on online store.');
            $ShippingMethod = $this->_objectManager->get('Webgility\EccM2\Model\ShippingMethod');
            $ShippingMethod->setCarrier("");
            $ShippingMethod->setMethods("");
            $ShippingMethods->setShippingMethods($ShippingMethod->getShippingMethod());
        } else {
        if (is_array($carriers)) {
            $j = 0;
            foreach ($carriers as $k=>$v) {
                if($carriers[$k]['value'] != "") {

                $ShippingMethod = $this->_objectManager->get('Webgility\EccM2\Model\ShippingMethod');
                    $ShippingMethod->setCarrier($carriers[$k]['label']);
                    for ($i = 0; $i < count($carriers[$k]['value']); $i++) {
                        $ShippingMethod->setMethods($carriers[$k]['value'][$i]['label']);
                    }
                    $j++;
                    $ShippingMethods->setShippingMethods($ShippingMethod->getShippingMethod());
                }
            }
        }
        }
        return $this->WgResponse($ShippingMethods->getShippingMethods());
    }

    public function _getshippingMethods($storeid = 1, $isActiveOnlyFlag = false)
    {
          global $get_Active_Carriers;

            $isActiveOnlyFlag = $get_Active_Carriers;

            $shippingObj = $this->_objectManager->get('\Magento\Shipping\Model\Config');
            $scopeConfigObj = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
            
            $methods = [['value' => '', 'label' => '']];
            $carriers = $shippingObj->getAllCarriers();
        
            try {
                foreach ($carriers as $carrierCode => $carrierModel) {
                if (!$carrierModel->isActive() && (bool)$isActiveOnlyFlag === true) {
                    continue;
                }
                $carrierMethods = $carrierModel->getAllowedMethods();
                if (!$carrierMethods) {
                    continue;
                }
                $carrierTitle = $scopeConfigObj->getValue(
                    'carriers/' . $carrierCode . '/title',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $methods[$carrierCode] = ['label' => $carrierTitle, 'value' => []];
                foreach ($carrierMethods as $methodCode => $methodTitle) {
                    $methods[$carrierCode]['value'][] = [
                        'value' => $carrierCode . '_' . $methodCode,
                        'label' => $methodTitle
                    ];
                }
            }
            return $methods;
        } catch (\Exception $e)
        {
            return "error_msg";
        }
    }

    function getCategory($username, $password, $storeid = 1, $others)
    {

        $storeid = $this->getDefaultStore($storeid);
        $status = $this->checkUser($username,$password);
        if ($status != "0") {
            return $status;
        }
        $Categories = $this->_objectManager->get('Webgility\EccM2\Model\Categories');
        $Categories->setStatusCode('0');
        $Categories->setStatusMessage('All Ok');
        $categoriesData = $this->_getcategory($storeid);
        if ($categoriesData) {
            for ($i = 0;$i <count($categoriesData); $i++) {
                if ($categoriesData[$i]['category_id'] == '' || $categoriesData[$i]['name'] == '') {
                } else {
                    $Category = $this->_objectManager->get('Webgility\EccM2\Model\Category');
                    $Category->setCategoryID($categoriesData[$i]['category_id']);
                    $Category->setCategoryName($categoriesData[$i]['name']);
                    $Category->setParentID($categoriesData[$i]['parent_id']);
                    $Categories->setCategories($Category->getCategory());
                }
            }
        }
        return $this->WgResponse($Categories->getCategories());
    }

    public function _getcategory($storeId = 1)
    {
            $categoryObj = $this->_objectManager->get('Magento\Catalog\Model\Category');
            $categories = $categoryObj->getCollection()->getAllIds();
            $result = [];
            foreach ($categories as $catVal) {
                $category = $categoryObj->load($catVal);

                if ($category->getParentId()!=0) {

                    $rootId[] = $category->getId();
                    $result[] = [
                        'category_id' => $category->getId(),
                        'parent_id'   => $category->getParentId(),
                        'name'        => $category->getName(),
                        'is_active'   => $category->getIsActive(),
                        'position'    => $category->getPosition(),
                        'level'       => $category->getLevel()
                    ];
                }
            }
        return $result;
    }

    function getTaxes($username, $password, $storeid = 1, $others)
    {
        $storeId = $this->getDefaultStore($storeid);
        $status = $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $Taxes = $this->_objectManager->get('Webgility\EccM2\Model\Taxes');
        $Taxes->setStatusCode('0');
        $Taxes->setStatusMessage('All Ok');
        $taxesData = $this->_gettaxes($storeId);
        if ($taxesData) {
            for ($i = 0; $i< count($taxesData); $i++) {
                $Tax = $this->_objectManager->get('Webgility\EccM2\Model\Tax');
                if (isset($taxesData[$i]['value'])) {
                   $Tax->setTaxID($taxesData[$i]['value']);
                   $Tax->setTaxName($taxesData[$i]['label']);
                   $Taxes->setTaxes($Tax->getTax());
                }
            }
        }
        return $this->WgResponse($Taxes->getTaxes());
    }

    public function _gettaxes($storeId = 1)
    {

        $searchCriteria = $this->_objectManager->get('\Magento\Framework\Api\SearchCriteriaInterface');
        $taxRepository = $this->_objectManager->get('Magento\Tax\Model\TaxClass\Repository')->getList($searchCriteria);

        $taxClassData = [];
        foreach ($taxRepository->getItems() as $taxItem) {
                
            $taxClassData[$taxItem->getClassId()] = $taxItem->getClassName();
        }
        return $taxClassData;
    }

    function getManufacturers($username, $password, $storeid = 1, $others)
    {

        $storeId = $this->getDefaultStore($storeid);
        $status = $this->checkUser($username,$password);
        if ($status != "0") {
            return $status;
        }
        $Manufacturers = $this->_objectManager->get('Webgility\EccM2\Model\Manufacturers');
        $Manufacturers->setStatusCode('0');
        $Manufacturers->setStatusMessage('All Ok');
        $manufacturersData = $this->_getmanufacturers($storeid);
        if ($manufacturersData) {
            for ($i = 0; $i < count($manufacturersData['items']); $i++) {
                $Manufacturer = $this->_objectManager->get('Webgility\EccM2\Model\Manufacturer');
                $Manufacturer->setManufacturerID($manufacturersData['items'][$i]['option_id']);
                $Manufacturer->setManufacturerName($manufacturersData['items'][$i]['value']);
                $Manufacturers->setManufacturers($Manufacturer->getManufacturer());
            }
        }
        return $this->WgResponse($Manufacturers->getManufacturers());
    }

    public function _getmanufacturers() 
    {
        $optionCollection = [];

        $eavConfig = $this->_objectManager->get('\Magento\Eav\Model\Config');
        $attribute = $eavConfig->getAttribute('catalog_product', 'manufacturer');
            if (!$attribute || !$attribute->getAttributeId()) {
                $manufAttributeId = 80;
            } else {
                 $manufAttributeId = $attribute->getAttributeId();
            }
            $attrOptionCollectionFactory = $this->_objectManager->get('\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection');
            $valuesCollection = $attrOptionCollectionFactory->setAttributeFilter($manufAttributeId)->setStoreFilter(null,true)->load();
            $manufacturer = [];
            foreach ($valuesCollection as $item) {
                $manufacturer[$item->getId()] = $item->getValue();
            }
            return($manufacturer);
    }

    public function getItems($username, $password, $DownloadType, $UpdatedDate, $start_item_no = 0, $limit = 500, $datefrom, $storeId = 1, $others)
    {
          
        global $set_Special_Price,$set_Short_Description;
   
        $storeId = $this->getDefaultStore($storeId);
        $status =  $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
         $stockRegistryInterface = $this->_objectManager->get('\Magento\CatalogInventory\Api\StockRegistryInterface');
        $Items = $this->_objectManager->get('Webgility\EccM2\Model\Items');
    
        if ($UpdatedDate != "") {
            $UpdatedDate = explode(".0", $UpdatedDate);
            $ini_date = explode(" ", $UpdatedDate[0]);
            $mid_date = explode("/", $ini_date[0]);
            $final_date = $mid_date[2]."-".$mid_date[0]."-".$mid_date[1];
            $date_time_final = $final_date." ".$ini_date[1];
            $date_time = $date_time_final;
        }

        if ($DownloadType == "byupdateddate" && $date_time != "") {
            $items_query_product = $this->getProduct($storeId, $start_item_no, $limit, $date_time, $others);        
            $count_query_product = $items_query_product->getSize();
        } else {
            $items_query_product = $this->getProduct($storeId, $start_item_no, $limit, $datefrom, $others);     
            $count_query_product = $items_query_product->getSize();
        }
        $system_date_val = date("m/d/Y , H:i:s");
        $Items->setServertime($system_date_val);
        $Items->setStatusCode('0');
        $Items->setStatusMessage('All Ok');
        if (count($items_query_product)>0) {
            $Items->setTotalRecordFound($count_query_product?$count_query_product:'0');
            $Items->setTotalRecordSent(count($items_query_product->getItems()) ? count($items_query_product->getItems()) : '0');
            #get the manufacturer
            $manufacturer = $this->_getmanufacturers();
            if (isset($manufacturer['totalRecords'])) {
                if ($manufacturer['totalRecords']>0) {
                    foreach ($manufacturer['items'] as $manufacturer1) {
                        $manufacturer2[$manufacturer1['option_id']] = $manufacturer1['value'];
                    }
                }
            }
            unset($manufacturer, $manufacturer1);
            $itemI = 0;
            foreach ($items_query_product->getItems() as $iInfo11) {

                $iInfo['category_ids'] = $iInfo11->getCategoryIds();
                $options = $this->_getoptions($iInfo11);
                $iInfo = $iInfo11->toArray();

                if ($iInfo['type_id'] == 'simple' || $iInfo['type_id'] == 'virtual' || $iInfo['type_id'] == 'downloadable') {

                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');

                    $stockItem = $stockRegistryInterface->getStockItem($iInfo['entity_id'])->toArray();
                     
                    $Item->setItemID($iInfo['entity_id']);
                    $Item->setItemCode($iInfo['sku']);
                    $Item->setItemDescription(strip_tags($iInfo['name']));
                    if (isset($iInfo['description'])) {
                        $desc = substr($iInfo['description'], 0, 4000);
                        $Item->setItemShortDescr(strip_tags($desc));
                    }

                    $catArray = array();
                    
                    if (is_array($iInfo['category_ids'])) {
                        $categoriesI = 0;
                        foreach ($iInfo['category_ids'] as $category) {
                            $categoryObj = $this->_objectManager->get('Magento\Catalog\Model\Category')->load($category);
                            $categories = $categoryObj->toArray();
                            $catArray['CategoryId'] = $category;
                            $catArray['CategoryName'] = $categories['name'];
                            $Item->setCategories($catArray);
                            unset($catArray);
                            $categoriesI++;
                        }
                    } 
                    if (!isset($categoriesI)) {
                            $Item->setCategories($catArray);
                    }       

                    if (isset($iInfo['manufacturer'])) {
                        $iInfo['manufacturer'] = $iInfo['manufacturer']?$manufacturer2[$iInfo['manufacturer']]:$iInfo['manufacturer'];
                        $Item->setManufacturer(isset($iInfo['manufacturer']) ? $iInfo['manufacturer'] : '');
                    }

                    $date_create_1 = explode(" ", $iInfo["created_at"]);
                    $date_create = explode("-", $date_create_1[0]);
                    $final_date = $date_create[2]."-".$date_create[1]."-".$date_create[0];
                    $Item->setQuantity($stockItem['qty']);
                    $Item->setCostPrice($iInfo['cost']);
                    if ($set_Special_Price) {
                        $Item->setUnitPrice($iInfo11->getSpecialPrice());
                    } else { 
                        $Item->setUnitPrice($iInfo11->getPrice());
                    } 
  
                    $Item->setListPrice($iInfo['cost']);
                    $Item->setWeight($iInfo11->getWeight());
                    $Item->setLowQtyLimit($stockItem['min_qty']);
                    $Item->setFreeShipping('N');
                    $Item->setDiscounted('');
                    $Item->setShippingFreight('');
                    $Item->setWeightSymbol('lbs');
                    $Item->setWeightSymbolGrams('453.6');
                        
                    if (empty($iInfo['tax_class_id']) || $iInfo['tax_class_id'] == "0") {
                        $Item->setTaxExempt('N');
                    } else {
                        $Item->setTaxExempt('Y');
                    }
  
                    $Item->setUpdatedAt($iInfo["updated_at"]);
                    $Item->setCreatedAt($final_date);
                    $Item->setItemType($iInfo['type_id']);
                    $Items->setItems($Item->getItem());
                }
                $itemI++;
            }
        }
        return $this->WgResponse($Items->getItems());
    }

    public function getOrderStatus($username, $password, $storeid = 1, $others)
    {

        $storeId=$this->getDefaultStore($storeid);
        $status = $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $OrderStatuses = $this->_objectManager->get('Webgility\EccM2\Model\OrderStatuses');

        $OrderStatuses->setStatusCode('0');
        $OrderStatuses->setStatusMessage('All Ok');
        $orderstatusesData = $this->_getorderstatuses($storeid);
        if ($orderstatusesData) {
            foreach ($orderstatusesData as $id=>$val) {
                $OrderStatus = $this->_objectManager->get('Webgility\EccM2\Model\OrderStatus');
                $OrderStatus->setOrderStatusID($id);
                $OrderStatus->setOrderStatusName($val);
                $OrderStatuses->setOrderStatuses($OrderStatus->getOrderStatus());
            }
        }
        return $this->WgResponse($OrderStatuses->getOrderStatuses());
    }

    public function _getorderstatuses($storeId = 1)
    {
        $orderStatusData = $this->_objectManager->get('Magento\Sales\Model\Order\Config')->getStatuses();
        return($orderStatusData);
    }

    function getAttributesets($username, $password, $storeid = 1, $others)
    {

        $status = $this->checkUser($username,$password);
        if ($status != "0") {
            return $status;
        }
        $Attributesets = $this->_objectManager->get('Webgility\EccM2\Model\Attributesets');
        $Attributesets->setStatusCode('0');
        $Attributesets->setStatusMessage('All Ok');
        $attributesetsData = $this->_getattributesets($storeid);
        
        if (!empty($attributesetsData)) {
            $i =0;
            foreach ($attributesetsData as $aSet_value) {
                $Attributeset = $this->_objectManager->get('Webgility\EccM2\Model\Attributeset');
                $Attributeset->setAttributesetID($aSet_value['attribute_set_id']);
                $Attributeset->setAttributesetName($aSet_value['attribute_set_name']);
                $Attributesets->setAttributesets($Attributeset->getAttributeset());
                $i++;
            }
        }
        return $this->WgResponse($Attributesets->getAttributesets());
    }

    public function _getattributesets($storeId = 1)
    {

        $entityTypeCode = 'catalog_product';
            $searchCriteriaBuilder = $this->_objectManager->get('\Magento\Framework\Api\SearchCriteriaBuilder');
            $filterBuilder = $this->_objectManager->get('\Magento\Framework\Api\FilterBuilder');
            
            $searchCriteriaBuilder->addFilters(
                [
                    $filterBuilder
                        ->setField('entity_type_code')
                            ->setValue($entityTypeCode)
                        ->setConditionType('eq')
                        ->create(),
                ]);
            
            $attributeSetData = $this->_objectManager->get('Magento\Eav\Model\AttributeSetRepository')->getList($searchCriteriaBuilder->create());
            
            $attributeSet = [];
            foreach($attributeSetData->getItems() as $val){
                $attributeSet[] = $val->toArray();

            }
            return $attributeSet;
    }

    public function _addproduct($storeId = 1)
    {

        $Product = $this->_objectManager->create('Magento\Catalog\Model\Product');
        $Product  = $Product->setStoreId($storeId);
        $Product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $Product->setWebsiteIds([$this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface')->getStore()->getWebsiteId()]);
        return $Product;
    }

    function addProduct($username, $password, $item_json_array, $storeid = 1, $others='')
    {
         global $Set_ReorderPoint;
          $stockRegistryInterface = $this->_objectManager->get('\Magento\CatalogInventory\Api\StockRegistryInterface');
        $version = $this->getVersion();
        if ($version != '1.2.1.2') {
            $storeId = $this->getDefaultStore($storeid);
        }   
        $stores = $this->_objectManager->get('Magento\Store\Model\Store')->getResourceCollection()->setLoadDefault(true)->addGroupFilter($storeId)->load();
        $stores = $stores->toArray();
            
        $website_id = $stores['items'][0]['website_id'];
        unset($stores);

        $status = $this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $Items = $this->_objectManager->get('Webgility\EccM2\Model\Items');

        $Items->setStatusCode('0');
        $Items->setStatusMessage('All Ok');

        $requestArray = $item_json_array;
        if (!is_array($requestArray)) {
            $Items->setStatusCode('9997');
            $Items->setStatusMessage('Unknown request or request not in proper format');
            return $this->WgResponse($Items->getItems());
        }

        if (count($requestArray) == 0) {
            $Items->setStatusCode('9996');
            $Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
            return $this->WgResponse($Items->getItems());
        }

        foreach ($requestArray as $k=>$vItem) {
            $productcode = $vItem['ItemCode'];
            $product = $vItem['ItemName'];
            $descr = $vItem['ItemDesc'];
            $free_shipping = $vItem['FreeShipping'];
            $free_tax = $vItem['TaxExempt'];
            $tax_id = $vItem['TaxID'];
            $item_match = $vItem['ItemMatchBy'];
            $manufacturerid = $vItem['ManufacturerID'];
            $avail_qty = $vItem['Quantity'];
            $price = $vItem['ListPrice'];
            $cost = $vItem['CostPrice'];
            $weight = $vItem['Weight'];
            $visibility = $vItem['ItemVisibility'];
            $ItemStatus = $vItem['ItemStatus'];
            $attributesetid=$vItem['AttributeSetID'];
            if ($Set_ReorderPoint) {
                $ReorderPoint=$vItem['ReorderPoint'];
            }
            if (isset($vItem['ItemVariants']) && is_array($vItem['ItemVariants'])) {
                $arrayVarients = $vItem['ItemVariants'];
                $v = 0;
                foreach ($arrayVarients as $k3=>$vVarients) {
                    $b = 0;
                    foreach ($vVarients as $k4=>$vVariant) {
                        $variantid=$vVariant['variantid'];
                        if (isset($variantid) && $variantid != '') {
                            $variant_data[$b]['variantid'] = $variantid;
                        }
                        $variantqty=$vVariant['variantqty'];
                        if (isset($variantqty) && $variantqty != '') {
                            $variant_data[$b]['variantqty'] = $variantqty;
                        }
                        $variantUnitprice = $vVariant['variantUnitprice'];
                        if (isset($variantUnitprice) && $variantUnitprice != '') {
                            $variant_data[$b]['variantUnitprice'] = $variantUnitprice;
                        }
                        $b++;
                    }
                    $V++;
                }
            }
            if (isset($vItem['ItemOptions']) && is_array($vItem['ItemOptions'])) {
                $arrayOptions = $vItem['ItemOptions'];
                $o = 0;
                $all_options = '';
                foreach ($arrayOptions as $k3=>$vOptions) {
                    foreach ($vOptions as $k4=>$vOption) {
                        $optionname = $vOption['optionname'];
                        if (isset($optionname) && $optionname != '') {
                            $all_options[$br]['optionname'] =  $optionname;
                            if (!in_array($optionname,$uniq_options)) {
                                $uniq_options[$bk] = $optionname;
                                $bk++;
                            }
                        }
                        $optionvalue = $vOption['optionvalue'];
                        if (isset($optionvalue)&& $optionvalue != '') {
                            $all_options[$br]['optionvalue'] =  $optionvalue;
                            if (!in_array($optionvalue,$uniq_options_vals[$optionname])) {
                                $uniq_options_vals[$optionname][] = $optionvalue;
                            }
                        }
                        $optionprz = $vOption['optionprz'];
                        if (isset($optionprz) && $optionprz != '') {
                            $all_options[$br]['optionprz'] =  $optionprz;
                            $uniq_options_vals1[$optionname][$optionvalue] = $optionprz;
                        }
                        $br++;
                    }
                    $o++;
                }
            } 
            //end

            /*$categoryid='';
            $cateId = array();
            if (isset($vItem['Categories']) && is_array($vItem['Categories'])) {
                $arrayCategories = $vItem['Categories'];
                foreach ($arrayCategories as $k3 => $vCategories) {
                    $categoryid .= $vCategories['CategoryId'].",";
                    $cateId[] =$vCategories['CategoryId'];
                    if ($vCategories['ParentID'] == '0') {
                        $vCategories['ParentID'] = 1;
                    }
                }
                $categoryid = strrev(substr($categoryid, 0, -1));
                $categoryid = strrev($categoryid);
            }*/

            if(is_array($vItem['Categories']))
            {
                $arrayCategories=$vItem['Categories'];
                $categoryid = array();
                foreach($arrayCategories as $k3=>$vCategories)//Categories
                {
                    $categoryid[] = $vCategories['CategoryId'];
                }
            }

            ///print_r($cateId); die;
            //die($categoryid);
            $forsale = "Y";
            $provider = "master";
            $list_price = $price;
            $fulldescr = $descr;
            $min_amount = "1";
            if (isset($attributesetid) && $attributesetid !='') {
                $attributeSet['attribute_set_id'] = $attributesetid;
            } else {    
                $entityTypeId = $this->_objectManager->get('Magento\Eav\Model\Config')->getEntityType('catalog_product');
                $attributeSet = $this->_objectManager->get('\Magento\Eav\Model\Entity\Attribute\Set')->getResourceCollection()
                                        ->setEntityTypeFilter($entityTypeId)
                                        ->addFilter('attribute_set_name', 'Default')
                                        ->getLastItem();
                                                                    
                $attributeSet = $attributeSet->toArray();
            }

            if ($this->getduplicaterecord($product,$productcode) == 0) {
                $data = [];
                if (isset($variant_data) && $variant_data != "" && is_array($variant_data)) {
                    $controlerData = [];
                    foreach ($uniq_options_vals as $atk => $atv) {
                        $attributeCode  = $atk;
                        if ($attribute->getId() && !$attributeId) {
                            $SetIds = $eavSetId->query("SELECT * FROM `eav_entity_attribute` where `entity_type_id` = 4 AND `attribute_set_id` = '".$attributeSet['attribute_set_id']."' AND `attribute_id` = '".$attribute->getId()."'");
                            $SetId = '';
                            $attid = $attribute->getId();
                            while ($row = $SetIds->fetch()) {
                                $SetId = $row['attribute_set_id'];
                                $attid = $row['attribute_id'];
                            }
                                $attrType = $attribute->getFrontendInput();
                                if ($attrType == 'select') {
                                    $attrVals = $attribute->getSource()->getAllOptions(false);
                                    $counter = 0;
                                    foreach ($attrVals as $attrVal) {
                                        if (strtoupper($attrVal['label']) != strtoupper($atv[0])) {
                                            $counter++;
                                        }
                                    }
                                }

                                if ($SetId != $attributeSet['attribute_set_id']) {
                                    $readresult=$eavSetId->query("SELECT * FROM `eav_attribute_group` WHERE `attribute_set_id` = '".$attributeSet['attribute_set_id']."' ORDER BY `attribute_group_id`  ASC limit 1");
                                    while ($row = $readresult->fetch()) {
                                        $group = $row['attribute_group_id'];
                                    }
                                    $readresult2=$eavSetId->query("SELECT entity_attribute_id FROM `eav_entity_attribute` order by entity_attribute_id desc limit 1");
                                    while ($row2 = $readresult2->fetch()) {
                                        $entity_attribute_id = $row2['entity_attribute_id']+1;
                                    }
                                    $eavSetId->query("insert into eav_entity_attribute(`entity_attribute_id` ,`entity_type_id` ,`attribute_set_id` ,`attribute_group_id` ,`attribute_id` ,`sort_order`) values ('".$entity_attribute_id."','4','".$attributeSet['attribute_set_id']."','".$group."','".$attid."','100')");
                                }
                                $data[$atk] = $atv[0];
                        } else {
                            $controlerData['attribute_code'] = $atk;
                            $controlerData['is_global'] = 1;
                            $controlerData['frontend_input'] = 'select';
                            $controlerData['default_value_text'] = '';
                            $controlerData['default_value_yesno'] = 0;
                            $controlerData['default_value_date'] = '';
                            $controlerData['default_value_textarea'] = '';
                            $controlerData['is_unique'] = 0;
                            $controlerData['is_required'] = 0;
                            $controlerData['is_configurable'] = 0;
                            $controlerData['is_searchable'] = 0;
                            $controlerData['is_visible_in_advanced_search'] = 0;
                            $controlerData['is_comparable'] = 0;
                            $controlerData['is_used_for_promo_rules'] = 0;
                            $controlerData['is_html_allowed_on_front'] = 1;
                            $controlerData['is_visible_on_front'] = 1;
                            $controlerData['used_in_product_listing'] = 0;
                            $controlerData['used_for_sort_by'] = 0;
                            $controlerData['frontend_label'][0] = $atk;
                            $controlerData['frontend_label'][1] = $atk;
                            $controlerData['option']['value']['option_0'][] = $atv[0];
                            $controlerData['option']['order']['option_0'] = 1;
                            $controlerData['option']['delete']['option_0'] = '';
                            $controlerData['is_filterable'] = 0;
                            $controlerData['is_filterable_in_search'] = 0;
                            $controlerData['backend_type'] = 'int';
                            $controlerData['default_value'] = '';
                            $controlerData['apply_to'][0] = 'simple';
                            $model->addData($controlerData);
                            $model->setEntityTypeId(4);
                            $model->setIsUserDefined(1);
                            $model->setAttributeSetId($attributeSet['attribute_set_id']);
                            $model->setAttributeGroupId("");
                            $model->save();
                            unset($model);
                            unset($controlerData);
                        }
                    }
                }
                $Product = $this->_addproduct($storeId);
                $Product->setCategoryIds($categoryid);
                $data['name'] = trim($product);
                $data['sku'] = trim($productcode);
                $data['manufacturer'] = $manufacturerid;
                $data['description'] = $descr;
                $data['short_description'] = $descr;
                $data['qty'] = $avail_qty;
                $data['attribute_set_id']=$attributeSet['attribute_set_id'];
                $data['price'] = $price;
                $data['cost'] = $cost;
                if (isset($tax_id)) {
                    $data['tax_class_id'] = $tax_id;
                } else {
                    $data['tax_class_id'] = 0;
                }
                $data['weight'] = $weight;
                $data['stock_data']['use_config_manage_stock'] = 1;
                $data['stock_data']['is_in_stock'] = 1;         
                if (isset($Set_ReorderPoint)) {
                    $data['stock_data']['min_qty'] = $ReorderPoint;
                }
                $data['status'] = $ItemStatus;
                $data['website_id'] = $website_id;
                $entityType = $this->_objectManager->get('Magento\Eav\Model\Config')->getEntityType('catalog_product');
                $entityTypeId = $entityType->getEntityTypeId();
                if ($entityTypeId != "" && isset($entityTypeId)) {
                    $data['entity_type_id'] = $entityTypeId;
                } else {
                    $data['entity_type_id'] =4;
                }
                if ($visibility) {
                    $data['visibility'] = $visibility;
                } else {
                    $data['visibility'] = '1';
                }
                $Product->addData($data);
                $Product->save();
                $productId = $Product->getId();
                $stockItem = $stockRegistryInterface->getStockItem($productId);
                $stockItem->setQty($avail_qty);
                $stockItem->setIsInStock(true);
                $stockItem->save();
                $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                if ($vItem['Image']) {
                    $this->addItemImage($productId, $vItem['Image'], $storeid = 1, $Thumbnail = 1);
                }
                if ($vItem['Image2']) {
                    $this->addItemImage($productId, $vItem['Image2'], $storeid=1, $Thumbnail = 0);
                }
                $Item->setStatus('Success');
                $Item->setProductID($productId);
                $Item->setSku($productcode);
                $Item->setProductName($product);
                $Items->setItems($Item->getItem());
            } else {
                $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                $Item->setStatus('Duplicate product code exists');
                $Item->setProductID('');
                $Item->setSku($productcode);
                $Item->setProductName($product);
                $Items->setItems($Item->getItem());
            }
        unset($attributes, $n_id, $attribute_option, $attribute_option1);
        }
        return $this->WgResponse($Items->getItems());
    }  
    function GetImage($username, $password, $data, $storeid = 1, $others)
    {
        $storeId = $this->getDefaultStore($storeid);        
        $status=$this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $Items = $this->_objectManager->get('Webgility\EccM2\Model\Items');
        $Items->setStatusCode('0');
        $Items->setStatusMessage('All Ok');
        $requestArray = $data;
        if (!is_array($requestArray)) {
            $Items->setStatusCode('9997');
            $Items->setStatusMessage('Unknown request or request not in proper format');            
            return $this->WgResponse($Items->getItems());               
        }

        if (count($requestArray) == 0) {
            $Items->setStatusCode('9996');
            $Items->setStatusMessage('REQUEST array(s) doesnt have correct input format');
            return $this->WgResponse($Items->getItems());           
        }
        $version = $this->getVersion();
        foreach ($requestArray as $k => $v4) {
            $status ="Success";
            $productID = $v4['ItemID'];
            $_images = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($productID)->getMediaGalleryImages();
            if ($_images) { 
                foreach ($_images as $_image) {
                    $responseArray = array();
                        $responseArray['ItemID'] = $productID;
                        if ($_image->path) {
                            $responseArray['Image'] = base64_encode(file_get_contents($_image->path));                            
                        }else{
                            $responseArray['Image'] = base64_encode(file_get_contents($_image['path']));
                        }
                        $Items->setItems($responseArray);
                        continue;           
                }
            }
        }       
        return $this->WgResponse($Items->getItems());
    }

    public function getProductById($storeId = 1, $start_item_no = 0, $items)
    {
        if ($start_item_no > 0) {
            if ($start_item_no > $limit) {
                $start_no = ($start_item_no/$limit) + 1;
                $start_no = (int)$start_no ;
            } else {
                $start_no = ($limit/$start_item_no) + 1;
                $start_no = (int)$start_no ;
            }
        } else {
            $start_no = 0;
        }
        $productFactory = $this->_objectManager->get('\Magento\Catalog\Model\Product');
        $productCollection = $productFactory->getResourceCollection();
        $productData = $productCollection->addAttributeToSelect('*')
                            ->addStoreFilter($storeId)
                            ->addAttributeToFilter('entity_id', $start_item_no)
                            ->addAttributeToSort('entity_id', 'asc')
                            ->setPageSize($limit)
                            ->setCurPage($start_no);
        return $productData;
    }  
    function addItemImage($itemid, $image, $storeid = 1, $Thumbnail)
    {
        $responseArray = array();
        $fileName = time().'.jpg';
        $ImageData = base64_decode($image);
        $imageUrl = $this->saveImage($itemid, $fileName, $ImageData, $Thumbnail);
    }
    function _createDestinationFolder($destinationFolder)
    {
        if (!$destinationFolder) {
            return $this;
        }

        if (substr($destinationFolder, -1) == DIRECTORY_SEPARATOR) {
            $destinationFolder = substr($destinationFolder, 0, -1);
        }

        if (!(is_dir($destinationFolder) || mkdir($destinationFolder, 0755, true))) {
            throw new \Exception("Unable to create directory '{$destinationFolder}'.");
        }
        return $this;
    }

    function _addDirSeparator($dir)
    {
        if (substr($dir, -1) != DIRECTORY_SEPARATOR) {
            $dir.= DIRECTORY_SEPARATOR;
        }
        return $dir;
    }

    function getDispretionPath($fileName)
    {
        $char = 0;
        $dispretionPath = '';
        while (($char < 2) && ($char < strlen($fileName))) {
            if (empty($dispretionPath)) {
                $dispretionPath = DIRECTORY_SEPARATOR.('.' == $fileName[$char] ? '_' : $fileName[$char]);
            } else {
                $dispretionPath = $this->_addDirSeparator($dispretionPath) . ('.' == $fileName[$char] ? '_' : $fileName[$char]);
            }
            $char ++;
        }
        return $dispretionPath;
    }

    function saveImage($productId, $fileName, $ImageData, $Thumbnail)
    {

        $config = $this->_objectManager->get('Magento\Catalog\Model\Product\Media\Config');
        $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem');
        $mediaDirectory = $mediaDirectory->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $imgpath = $config->getBaseMediaPath();
        $destFile = $mediaDirectory->getAbsolutePath($imgpath) . DIRECTORY_SEPARATOR . $fileName;
        $file = fopen($destFile, 'w+');
        fwrite($file, $ImageData);
        fclose($file);
        
        $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
      

        if ($Thumbnail == 1) {
            $mediaAttribute = [
                          'thumbnail' ];
        } else {
            $mediaAttribute = [
                    'small_image',
                    'image' ];
        }
        $product->addImageToMediaGallery($destFile, $mediaAttribute, false, false);
        $product->save();
        return $destFile;
    }
    #Add image functionality code area

    function synchronizeItems($username, $password, $item_json_array, $storeid = 1, $others)
    {
        global $set_Special_Price, $set_Short_Description;
        $storeId=$this->getDefaultStore($storeid);
        $status=$this->checkUser($username, $password);
        if ($status != "0") {
            return $status;
        }
        $Items = $this->_objectManager->get('Webgility\EccM2\Model\Items');
        $Items->setStatusCode('0');
        $Items->setStatusMessage('All Ok');
        $requestArray = $item_json_array;
        $pos = strpos($others, '/');
        if (isset($pos)) {
            $array_others = explode("/", $others);
        } else {
            $array_others= [];
            $array_others[0]=$others;     
        }
        if (!is_array($requestArray)) {
            $Items->setStatusCode('9997');
            $Items->setStatusMessage('Unknown request or request not in proper format');
            return $this->WgResponse($Items->getItems());
        }
        if (count($requestArray) == 0) {
            $Items->setStatusCode('9996');
            $Items->setStatusMessage('REQUEST array(s) doesnt have correct input format');
            return $this->WgResponse($Items->getItems());
        }
        $itemsProcessed = 0;
        $i = 0;
        $version = $this->getVersion();
        $stockRegistryInterface = $this->_objectManager->get('\Magento\CatalogInventory\Api\StockRegistryInterface');
        foreach ($requestArray as $k => $v4) {
            $status = "Success";
            $productID = $v4['ProductID'];
            $Price = $v4['Price'];
            $ProductName = $v4['ProductName'];
            $CostPrice = $v4['Cost'];   
            $productsCollection = $this->_objectManager->get('Magento\Catalog\Model\Product')->getCollection()
                    ->addAttributeToSelect('entity_id')
                    ->addAttributeToFilter('sku', $v4['Sku'])
                    ->load();

            $productsCollection = $productsCollection->toArray();
            if (empty($productsCollection) || !isset($productsCollection)) {
                $Item->setStatus('Failed');
                $Item->setProductID($v4['ProductID']);
                $Item->setSku($v4['Sku']);
                $Item->setProductName($ProductName);  
                $Item->setQuantity($v4['Qty']);
                $Item->setPrice($Price);                 
                $Item->setItemUpdateStatus('Failed');                          
                $Items->setItems($Item->getItem());
            } else {
                foreach ($array_others as $ot) {
                    if ($others == "QTY" || $others == "BOTH" || $ot == "QTY") {

                        $informationObj = $this->_objectManager->get('Magento\CatalogInventory\Model\Configuration');
                        $configItemMinQty = $informationObj->getMinQty($storeId);
            
                        $stockItem = $stockRegistryInterface->getStockItem($productID);
                        $product_stack_detail = $stockItem->toArray();
                        
                        $ConfigBackordersValue = $this->_objectManager->get('Magento\CatalogInventory\Api\StockConfigurationInterface')
                            ->getBackorders($storeId);
                        
                        $stockItem->setQty($v4['Qty']);

                        if ($product_stack_detail['use_config_min_qty'] == 1) {
                            $config_qty = $configItemMinQty;
                        } else {
                            $config_qty = $product_stack_detail['min_qty'];
                        }

                        if ($product_stack_detail['use_config_backorders'] == 1) {
                            // In this if when product geting values from config as config check box  checked
                            if ($ConfigBackordersValue == 0) {
                                // In this if when config has  NoBackorders option
                                if ($v4['Qty'] <= $config_qty) {
                                    $stockItem->setIs_in_stock(0);
                                } elseif ($v4['Qty'] == 0 && $config_qty != 0) {
                                    $stockItem->setIs_in_stock(1);
                                } else {
                                    $stockItem->setIs_in_stock(1);
                                }
                            } else {                        
                                $stockItem->setIs_in_stock(1);
                            }
                        } else {
                            if ($product_stack_detail['backorders'] == 0) {

                                if ($v4['Qty'] <= $config_qty) {
                                    $stockItem->setIs_in_stock(0);
                                } elseif ($v4['Qty'] == 0 && $config_qty != 0) {
                                    $stockItem->setIs_in_stock(1);
                                } else { 
                                    $stockItem->setIs_in_stock(1);
                                }
                            } else {
                                $stockItem->setIs_in_stock(1);
                            }   
                        }
                        $stockItem->save();
                    }
                    if ($others == "PRICE" || $others == "BOTH" || $ot == "PRICE") {
                        $p = $this->_objectManager->get('Magento\Catalog\Model\Product');
                        $p->load($productID);

                        if ($set_Special_Price) {
                            $p->special_price = $v4['Price'];
                            $p->save();
                            if ($p->getSpecialPrice() != $v4['Price']) {
                                $Product = $this->_editproduct($storeId, $productID);
                                $Product->setSpecialPrice($v4['Price']);
                                $Product->save();
                            }
                        } else {
                            $p->price = $v4['Price'];
                            $p->save();
                            if ($p->getPrice() != $v4['Price']) {
                                $Product = $this->_editproduct($storeId, $productID);
                                $Product->setPrice($v4['Price']);
                                $Product->save();
                            }
                        }
                    }

                    if ($others == "COST" || $ot == "COST") {
                        $p = $this->_objectManager->get('Magento\Catalog\Model\Product');
                        $p->load($productID);
                        $p->cost =$CostPrice;
                        $p->save();
                        if ($p->getCost() != $CostPrice) {
                            $Product = $this->_editproduct($storeId, $productID);
                            $Product->setCost($CostPrice);
                            $Product->save();
                        } else {
                            $status ="Cost Price for this product not found";
                        }
                    }

                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setStatus('Success');
                    $Item->setProductID($v4['ProductID']);
                    $Item->setSku($v4['Sku']);
                    $Item->setProductName($ProductName);    
                    $Item->setQuantity($v4['Qty']);
                    $Item->setPrice($Price);                    
                    $Item->setItemUpdateStatus('Success');                      
                    $Items->setItems($Item->getItem());
                }   
            }
        }
        return $this->WgResponse($Items->getItems());
    }

    function getOrders($username, $password, $datefrom, $start_order_no, $ecc_excl_list, $order_per_response = "25", $storeid = 1, $others,$ccdetails, $do_not_download_configurable_product_as_line_item, $do_not_download_bundle_product_as_line_item, $discount_as_line_item, $download_option_as_item, $donwload_state_code, $LastModifiedDate = null, $ProductType = null) {

        global $display_discount_desc, $get_Active_Carriers, $RewardsPoints_Name, $set_Short_Description, $set_field_Q_CIM_and_Q_Authorization; 
        
        $Orders = $this->_objectManager->get('Webgility\EccM2\Model\Orders');
        $serialize = $this->_objectManager->get(\Magento\Framework\Serialize\Serializer\Json::class);

        $orderlist=array();
        if (is_array($others)) {
            foreach ($others as $k=>$v) {
                $orderlist[] =  $v['OrderId'];
            }
        }
        if ($do_not_download_configurable_product_as_line_item && $do_not_download_bundle_product_as_line_item && $download_option_as_item) {
            $do_not_download_configurable_product_as_line_item = false;
            $do_not_download_bundle_product_as_line_item=false;
        }

        if ($do_not_download_configurable_product_as_line_item && $do_not_download_bundle_product_as_line_item) {
            $download_option_as_item=true;
            $do_not_download_configurable_product_as_line_item=false;
            $do_not_download_bundle_product_as_line_item=false;
        }
        if ($do_not_download_configurable_product_as_line_item || $do_not_download_bundle_product_as_line_item) {
            $download_option_as_item = true;
        }
        if (!$start_order_no == 0) {
            $my_orders = $this->_objectManager->get('Magento\Sales\Model\Order')->loadByIncrementId($start_order_no);
            $my_orders1 = $my_orders->toArray();
            $start_order_no = isset($my_orders1['entity_id'])?$my_orders1['entity_id'] : "";
            if (!isset($start_order_no) || $start_order_no=='') {
                $start_order_no=0;
            }
        }
        #$start_order_no=3;
        $storeId=$this->getDefaultStore($storeid);
        if (!isset($datefrom) or empty($datefrom)) {
            $datefrom=date('m-d-Y');
        }
        if (!isset($dateto) or empty($dateto)) {
            $dateto = date('m-d-Y');
        }
        $status=$this->checkUser($username, $password);

        if($status != "0") {
            return $status;
        }
        $by_updated_date='updated_at';
        if ($others == 'by_updated_at') {
            $by_updated_date ='updated_at';
        }
        $by_updated_date = '';
        $_orders = '';
        $_orders = $this->_getOrders($datefrom, $start_order_no, $ecc_excl_list, $storeId, $order_per_response, $by_updated_date, $orderlist, $LastModifiedDate);
        $orders_array = $countorders_array = $_orders->toArray();
        $country = [];
        $countryObj = $this->_objectManager->get('\Magento\Directory\Model\Country');
        $country_data = $countryObj->getResourceCollection()->load()->toOptionArray();
        //Commant for debug
        foreach($country_data as $ck => $cv) {
            if ($cv['value'] != '') {
                $country[$cv['value']] = trim($cv['label']);
            }
        }
        unset($country_data);
        if (array_key_exists('items', $countorders_array)) {
            $countorders_array = $countorders_array['items'];
        }
        if (count($countorders_array)>0) {
            $orders_remained = count($countorders_array);
        } else {
            $orders_remained = 0;
        }
        $no_orders = false;
        if ($orders_remained < 1) {
            $no_orders = true;
        }
        $Orders->setStatusCode($no_orders?"9999":"0");
        $Orders->setStatusMessage($no_orders?"No Orders returned":"Total Orders:".$_orders->getSize());
        $Orders->setTotalRecordFound($_orders->getSize()?$_orders->getSize():"0");
        $Orders->setTotalRecordSent(count($countorders_array)?count($countorders_array):"0");

        if ($no_orders) {
            return $this->WgResponse($Orders->getOrders()); 
        }
        
        foreach ($_orders as $_order) {
                $Order = $this->_objectManager->get('Magento\Sales\Model\Order');
                $customer_comment = "";
            if ($_order->getBiebersdorfCustomerordercomment()) {
                $customer_comment = $_order->getBiebersdorfCustomerordercomment();
            }
            foreach ($_order->getStatusHistoryCollection(true) as $_comment) {
                if ($_comment->getComment()) {
                    $customer_comment = $customer_comment." \r\n ".$_comment->getComment();
                }
            }
            $shipments = $_order->getShipmentsCollection();
            $shippedOn='';
            foreach ($shipments as $shipment) {
                $increment_id = $shipment->getIncrementId();
                $shippedOn = $shipment->getCreated_at();
                $shippedOn =$this->convertdateformate($shippedOn);
            }
            $orders='';
            $orders=$_order->toArray();
            if (!$_order->getGiftMessage()) {
                
                $giftMsgObj = $this->_objectManager->get('\Magento\GiftMessage\Helper\Message');
                $_order->setGiftMessage($giftMsgObj->getGiftMessage($_order->getGiftMessageId()));
            }
            $giftMessage = $_order->getGiftMessage()->toArray();
            $_payment = $_order->getPayment();
            $payment = $_payment->toArray();
            $localDate = $this->_objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
            if (strtotime($orders["created_at"])) {
                # Latest code modififed date for all country
                $orders["created_at"] = $localDate->formatDate($orders["created_at"],\IntlDateFormatter::MEDIUM, true);
                //$ndate = $localDate->scopeDate(null,$fdate,false);
                $fdate = date("m-d-Y | h:i:s A",strtotime($orders["created_at"]));
                $fdate = explode("|",$fdate);
                $dateCreateOrder= trim($fdate[0]);
                $timeCreateOrder= trim($fdate[1]);
            } else {
                $dateObj = $localDate->date($orders["created_at"]);
                $dateStrToTime = $dateObj->getTimestamp();
                $fdate = date("m-d-Y | h:i:s A", $dateStrToTime);
                $fdate = explode("|", $fdate);
                $dateCreateOrder = trim($fdate[0]);
                $timeCreateOrder = trim($fdate[1]);
            }
            if (!array_key_exists('billing_firstname', $orders) && !array_key_exists('billing_lastname', $orders)) {
                $billingAddressArray = $_order->getBillingAddress()->toArray();
                $orders["billing_firstname"] = $billingAddressArray["firstname"];
                $orders["billing_lastname"] = $billingAddressArray["lastname"];
                $orders["billing_company"] = $billingAddressArray["company"];
                $orders["billing_street"] = $billingAddressArray["street"];
                $orders["billing_city"] = $billingAddressArray["city"];
                $orders["billing_region"] = $billingAddressArray["region"];
                $orders["billing_region_id"] = $billingAddressArray["region_id"];
                $orders["billing_postcode"] = $billingAddressArray["postcode"];
                $orders["billing_country"] = $billingAddressArray["country_id"];
                $orders["customer_email"] = isset($billingAddressArray["customer_email"])?$billingAddressArray["customer_email"]:$orders["customer_email"];
                $orders["billing_telephone"] = $billingAddressArray["telephone"];
            }
            $Order = $this->_objectManager->get('Webgility\EccM2\Model\Order');
            $Order->setOrderId($orders['increment_id']);
            $Order->setTitle('');
            $Order->setFirstName($orders["billing_firstname"]);
            $Order->setLastName($orders["billing_lastname"]);
            $Order->setDate($dateCreateOrder);
            $Order->setTime($timeCreateOrder);
            $Order->setLastModifiedDate($this->_dateFormatwg($orders["updated_at"]));   
            $Order->setStoreID($orders['store_id']);
            $Order->setStoreName('');
            $Order->setCurrency($orders['order_currency_code']);
            $Order->setWeightSymbol('lbs');
            $Order->setWeightSymbolGrams('453.6');
            $Order->setCustomerId($orders['customer_id']);

            if ($shippedOn=='' || empty($shippedOn)) {
                $shippedOn = $dateCreateOrder;          
            }
            if ($ProductType == 'UNIFY') {
                $orderC = $this->_objectManager->get('\Magento\Sales\Model\Order\Creditmemo');
                $CreditMemoCollection = $orderC->getResourceCollection()
                        ->setOrderFilter($orders['entity_id']) ;
                $Order->setIsCreditMemoCreated("0");

                foreach ($CreditMemoCollection as $CreditMemo1) {
                    $Order->setIsCreditMemoCreated("1");
                    $CreditMemo = $this->_objectManager->get('Webgility\EccM2\Model\CreditMemo');
                    $CreditMemo->setCreditMemoID($CreditMemo1->getincrement_id());
                    $CreditMemo->setCreditMemoDate($CreditMemo1->getcreated_at());
                    $CreditMemo->setSubtotal($CreditMemo1->getgrand_total());
                    $CreditMemo->setRefundDiscount($CreditMemo1->getBaseDiscountAmount());
                    $CreditMemo->setRefundTax($CreditMemo1->getBaseTaxAmount());
                    $CreditMemo->setRefundFee($CreditMemo1->getAdjustmentNegative());
                    $CreditMemo->setRefundAdjustment($CreditMemo1->getAdjustmentPositive());
                    $CreditMemo->setRefundShipping($CreditMemo1->getBaseShippingAmount());
                    foreach ($CreditMemo1->getAllItems() as $CreditMemoItem) {
                        if (trim($CreditMemoItem->getsku())=='') {
                            continue;
                        }
                        $itemInOrder = $this->_objectManager->get('\Magento\Sales\Model\Order\Item')->load($CreditMemoItem->getorder_item_id());
                        $OrderQty = $itemInOrder->getQtyOrdered();
                        $ItemPrice = $itemInOrder->getPrice();
                        $CancelItemDetail = $this->_objectManager->get('Webgility\EccM2\Model\CancelItemDetail');
                        $CancelItemDetail->setItemID($CreditMemoItem->getproduct_id());
                        $CancelItemDetail->setItemSku($CreditMemoItem->getsku());
                        $CancelItemDetail->setItemName($CreditMemoItem->getname());
                        $CancelItemDetail->setQtyCancel($CreditMemoItem->getQty());
                        $CancelItemDetail->setQtyInOrder($OrderQty);
                        $CancelItemDetail->setPriceCancel($CreditMemoItem->getprice());
                        $CancelItemDetail->setItemPrice($ItemPrice);
                        $CreditMemo->setCancelItemDetail($CancelItemDetail->getCancelItemDetail());
                    }
                    $Order->setCreditMemos($CreditMemo->getCreditMemo());
                }
                unset($CreditMemoCollection);
            } else {
                $orderC = $this->_objectManager->get('\Magento\Sales\Model\Order\Creditmemo');
                $CreditMemoCollection = $orderC->getResourceCollection()
                        ->setOrderFilter($orders['entity_id']);
                $Order->setIsCreditMemoCreated("0");
                $totalccgrandtotal=0;
                $ccitemArray =  [];
                $totalccitemqty =0;
                foreach ($CreditMemoCollection as $CreditMemo1) {
                    $Order->setIsCreditMemoCreated("1");
                    $totalccgrandtotal = $totalccgrandtotal+$CreditMemo1->getgrand_total();
                    foreach ($CreditMemo1->getAllItems() as $CreditMemoItem) {  
                        if (trim($CreditMemoItem->getsku()) == '') {
                            continue;
                        }
                        $totalccitemqty = $totalccitemqty+$CreditMemoItem->getQty();
                        if (array_key_exists($CreditMemoItem->getsku(), $ccitemArray)) {
                            $ccitemArray[$CreditMemoItem->getsku()]["qty"] = $ccitemArray[$CreditMemoItem->getsku()]["qty"]+$CreditMemoItem->getQty();
                        } else {
                            $ccitemArray[$CreditMemoItem->getsku()]["qty"] =$CreditMemoItem->getQty();
                            $ccitemArray[$CreditMemoItem->getsku()]["name"] =$CreditMemoItem->getname();
                            $ccitemArray[$CreditMemoItem->getsku()]["ID"] =$CreditMemoItem->getproduct_id();
                            $ccitemArray[$CreditMemoItem->getsku()]["orderItemId"] =$CreditMemoItem->getorder_item_id();
                        }
                    }
                }
                if ($totalccgrandtotal!=0) {
                    $CreditMemo = $this->_objectManager->get('Webgility\EccM2\Model\CreditMemo');
                    $CreditMemo->setCreditMemoID($CreditMemo1->getincrement_id());
                    $CreditMemo->setSubtotal($totalccgrandtotal);
                    $CreditMemo->setCreditMemoDate($CreditMemo1->getcreated_at());
                    foreach ($ccitemArray as $cckey => $ccval) {
                        $itemInOrder = $this->_objectManager->get('\Magento\Sales\Model\Order\Item')->load($ccval["orderItemId"]);
                        $OrderQty = $itemInOrder->getQtyOrdered ();
                        $CancelItemDetail = $this->_objectManager->get('Webgility\EccM2\Model\CancelItemDetail');
                        $CancelItemDetail->setItemID($ccval["ID"]);
                        $CancelItemDetail->setItemSku($cckey);
                        $CancelItemDetail->setItemName($ccval["name"]);
                        $CancelItemDetail->setQtyCancel($ccval["qty"]);
                        $CancelItemDetail->setQtyInOrder($OrderQty);
                        $CancelItemDetail->setPriceCancel(round(($totalccgrandtotal/$totalccitemqty), 3));
                        $CancelItemDetail->setItemPrice(round(($totalccgrandtotal/$totalccitemqty), 3));    
                        $CreditMemo->setCancelItemDetail($CancelItemDetail->getCancelItemDetail()); 
                    }
                    $Order->setCreditMemos($CreditMemo->getCreditMemo());
                }
                unset($CreditMemoCollection);
            }


            $orderStatus = $this->_getorderstatuses($storeId);
            if (array_key_exists($orders['status'],$orderStatus)) {
                $Order->setStatus($orderStatus[$orders['status']]);
            } else {
                $Order->setStatus($orders['status']);
            }
            if ($payment['method'] == 'purchaseorder') {
                $orders['customer_note'] = $orders['customer_note'] ." Purchase Order Number: ".$payment['po_number'];
            }
            $order_comment='';
            foreach ($_order->getStatusHistoryCollection(true) as $_comment) {
                if ($_comment->getComment()) {
                    $cust_comment = $_comment->getComment();
                }
            }

            foreach ($_order->getStatusHistoryCollection(true) as $_comment) {
                if ($_comment->getComment()) {
                    $order_comment = $_comment->getComment();
                    break;
                }
            }
            $Order->setNotes(isset($order_comment) ? $order_comment : "");
            $giftMessage['message'] = isset($giftMessage['message']) ? $giftMessage['message'] : "";
            $Order->setComment(isset($cust_comment) ? $cust_comment : "");
            $Order->setFax('');

/***************************************************************************************************
Custamization for XPU-623-53661 Start: We create a config variable to manage this.
****************************************************************************************************/
            if ($set_field_Q_CIM_and_Q_Authorization) {
        
                $po_number_str = $payment['po_number'];
                $po_number = explode("-", $po_number_str);
                if (!empty($po_number['0'])) {
                    $q_cim = $po_number['0'];
                }   
                if ($q_cim != "" || $payment['last_trans_id'] != "") {
                    // code for custom fields  
                    $WG_OtherInfo = new WG_OtherInfo();
                    $WG_Other = new WG_Other();
                    $other_field = ['Q_CIM' => $q_cim];

                    foreach ($other_field as $key => $value) {
                        $WG_OtherInfo->setFieldName($key);
                        $WG_OtherInfo->setFieldValue(html_entity_decode($value));
                        $WG_Other->setCustomFeilds($WG_OtherInfo->getOtherinfo());
                    }                   
                    $Order->setOrderOtherInfo($WG_Other->getOther());

                }   
            }
/***************************************************************************************************
Custamization for XPU-623-53661 Ends.
****************************************************************************************************/

            $item_array = $this->getorderitems($orders["entity_id"], $orders["increment_id"], $download_option_as_item);

            $item_array = $item_array['items'];

            unset($onlineInfo);
            $onlineInfo = [];

            if ($do_not_download_configurable_product_as_line_item == true && $download_option_as_item == true) {
 
                unset($orderConfigItems);
                $orderConfigItems = [];
            }

            if ($do_not_download_bundle_product_as_line_item == true && $download_option_as_item == true) {
                unset($orderBundalItems);
                $orderBundalItems = [];
            }

            $itemI = 0;
            foreach ($item_array as $iInfo) {

                if (is_object($iInfo['product'])) {
                    $onlineInfo = $iInfo['product']->toArray();
                }
                if (intval($iInfo["qty_ordered"]) > 0 && is_numeric($iInfo["price"])) {

                    unset($productoptions);
                    $productoptions = [];

                    if(isset($iInfo['product_options'])){
                        if(is_array($iInfo['product_options'])){
                            $productoptions = $iInfo['product_options'];
                        }else{
                            $data = $serialize->unserialize($iInfo['product_options']);
                            if($data !== false) {
                                $productoptions = $serialize->unserialize($iInfo['product_options']);
                            }else {
                                $productoptions = $iInfo['product_options'];
                            }
                        }
                    }

                    if (isset($productoptions['options']) && is_array($productoptions['options'])) {

                        if ($productoptions['options']) {

                            if (is_array($productoptions['options']) && !empty($productoptions['options'])) {

                                if (isset($productoptions['attributes_info']) && is_array($productoptions['attributes_info'])) {
                                        
                                    $productoptions['attributes_info'] = array_merge($productoptions['attributes_info'], $productoptions['options']);
                                } else {
                                    $productoptions['attributes_info'] = $productoptions['options'];
                                }
                            }
                            unset($productoptions['options']);
                        }
                    }
                    if (!empty($productoptions['bundle_options']) && is_array($productoptions['bundle_options'])) {

                        if (array_key_exists('attributes_info', $productoptions)) {
                                
                            $productoptions['attributes_info'] = array_merge($productoptions['attributes_info'], $productoptions['bundle_options']);
                                
                        } else {

                            $productoptions['attributes_info'] = $productoptions['bundle_options'];
                        }                   
                        unset($productoptions['bundle_options']);
                    }

                    $product = $iInfo;
                    $product['type_id'] = $iInfo['product_type'];

                    if (isset($iInfo['product'])) {

                        $product_base = $iInfo['product']->toArray();
                        if (isset($product_base['tax_class_id'])) {
                            $product['tax_class_id'] = $product_base['tax_class_id'];
                        } else {
                            $product['tax_class_id'] = '';
                        }

                        if($product['type_id']!='configurable') {
                            $product['sku'] = $iInfo['sku'];
                        }else{
                            if($configurableItemSku==true)
                                $productoptions['simple_sku'] = $product_base['sku'];
                            else
                                $productoptions['simple_sku'] = $iInfo['sku'];
                        }

                    }else{

                        if($product['type_id']!='configurable')
                        {
                            $currentProduct = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($iInfo['product_id']);
                            $product['sku'] = $iInfo['sku'];
                        }else{
                            $currentProduct = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($iInfo['product_id']);
                            $productoptions['simple_sku'] = $product_base['sku'];
                        }

                        $product_base = $currentProduct->toArray();
                        if (isset($product_base['tax_class_id'])) {
                            $product['tax_class_id'] = $product_base['tax_class_id'];
                        } else {
                            $product['tax_class_id'] = '';
                        }

                    }

                    if ($do_not_download_configurable_product_as_line_item == true && $download_option_as_item == true) {

                        if (in_array($iInfo['parent_item_id'], $orderConfigItems)) {
                            continue;
                        }
                    }

                    if ($do_not_download_bundle_product_as_line_item == true && $download_option_as_item == true) {
    
                        if (in_array($iInfo['parent_item_id'], $orderBundalItems)) {
                            continue;
                        }
                    }   
                    if ($product['type_id'] == 'bundle') {
                        #Fixed:- order downlaod issue (The remote server returned an error: (500) Internal in Order download)
                        if ($iInfo['product'] != NULL && $iInfo['product'] != '') {
                            if($download_option_as_item  == true && $iInfo['product']->getPriceType()==0)
                            {
                                $iInfo["qty_ordered"] =0;
                                continue;
                            }
                        }
                        #Fixed:- order downlaod issue (The remote server returned an error: (500) Internal in Order download)
                    }

                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    if ($product['type_id'] != 'configurable') {

                        if ($do_not_download_bundle_product_as_line_item == true && $download_option_as_item == true) {
                            $orderBundalItems[] = $iInfo['item_id'];
                        }
                        $Item->setItemCode($product['sku']);        
                    } else {        
                        if ($do_not_download_configurable_product_as_line_item == true && $download_option_as_item == true) {
                            $orderConfigItems[] = $iInfo['item_id'];
                        }
                        $Item->setItemCode($productoptions['simple_sku']);
                    }   
                    $Item->setItemDescription($product['name']);        
                    if ($set_Short_Description) {
    
                        $Item->setItemShortDescr(empty($onlineInfo['short_description']) ? substr($product['short_description'], 0, 2000) : substr($onlineInfo['short_description'], 0, 2000));
                    } else {

                        $Item->setItemShortDescr(empty($onlineInfo['description']) ? substr($product['description'], 0, 2000) : substr($onlineInfo['description'], 0, 2000));
                    }
                    $Item->setItemID($iInfo['item_id']);
                    $Item->setQuantity($iInfo["qty_ordered"]);
                    $Item->setShippedQuantity($iInfo["qty_shipped"]);
                    $Item->setUnitPrice($iInfo["price"]);
                    $Item->setCostPrice(isset($onlineInfo["cost"]) ? $onlineInfo["cost"] : '0');
                    $Item->setWeight($iInfo["weight"]);
                    $Item->setFreeShipping("N");
                    $Item->setDiscounted("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");

                    if (isset($product['tax_class_id']) && $product['tax_class_id'] != 0) {
                        $Item->setTaxExempt("N");
                    } else {
                       $Item->setTaxExempt("Y");
                    }

                    $iInfo['onetime_charges'] = "0.00";
                    $Item->setOneTimeCharge(number_format($iInfo['onetime_charges'], 2, '.', ''));
                    $Item->setItemTaxAmount("");

                    if (array_key_exists("attributes_info", $productoptions)) {

                        $optionI = 0;
                        foreach ($productoptions['attributes_info'] as $item_option12) {

                            $Itemoption = $this->_objectManager->get('Webgility\EccM2\Model\Itemoption');

                            if (is_array($item_option12['value'])) {
                                $item_option1234 = '';
                                foreach ($item_option12['value'] as $item_option123) {
                                    $item_option1234 = " ".$item_option123['qty']." x ".$item_option123['title']." $".$item_option123['price'];
                                    #Fixed:- Html markups downloading in order item option.--Start
                                    $Itemoption->setOptionValue(htmlspecialchars_decode($item_option1234));
                                    $Itemoption->setOptionName(htmlspecialchars_decode($item_option12['label']));
                                    #Fixed:- Html markups downloading in order item option.--Start
                                    $Itemoption->setOptionPrice($item_option123['price']);
                                    $Item->setItemOptions($Itemoption->getItemoption());
                                }
                                unset($item_option1234);
                            } else {
                                #Fixed:- Html markups downloading in order item option.--Start
                                $Itemoption->setOptionValue(htmlspecialchars_decode($item_option12['value']));
                                $Itemoption->setOptionName(htmlspecialchars_decode($item_option12['label']));
                                #Fixed:- Html markups downloading in order item option.--Start
                                $Item->setItemOptions($Itemoption->getItemoption());    
                            }                               
                            $optionI++;
                        }
                    }

                    if (isset($iInfo['nonreturnable']) && $iInfo['nonreturnable'] == "Yes") {           
                        $Itemoption = $this->_objectManager->get('Webgility\EccM2\Model\Itemoption');

                        $Itemoption->setOptionValue("Non-returnable");
                        $Itemoption->setOptionName("Clearance");
                        $Item->setItemOptions($Itemoption->getItemoption());
                    }
                }

                $Order->setOrderItems($Item->getItem());
            }

            $discountadd = true;
#Discount Coupon as line item
            $orders["discount_amount"] = $orders["discount_amount"]?$orders["discount_amount"]:$orders["base_discount_amount"];

            if (($orders['coupon_code'] != '' || $orders['discount_description'] != '') && $discount_as_line_item == true) {

                    $discountadd =false;
                    $orders["discount_amount"] = $orders["discount_amount"]?$orders["discount_amount"]:$orders["base_discount_amount"];
                    if ($display_discount_desc) { 
                        $DESCR1 = $orders['discount_description'];
                    } else {
                        $DESCR1 = $orders['coupon_code'];
                    }

                    $itemI++;
                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setItemCode("Discount Coupon");
                    $Item->setItemDescription(substr($DESCR1,0,50));
                    $Item->setItemShortDescr("Coupon code ".htmlentities(substr($DESCR1, 0, 50), ENT_QUOTES));
                    $Item->setQuantity(1);
                    $discount_amount = $orders["discount_amount"];
                    if ($discount_amount< 0) {
                        $Item->setUnitPrice($orders["discount_amount"]);
                    } else {
                        $Item->setUnitPrice("-".$orders["discount_amount"]);
                    }
                    $Item->setWeight('');
                    $Item->setFreeShipping("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");
                    $Item->setDiscounted("Y");
                    $Order->setOrderItems($Item->getItem());
            }
#Reward Points as line item
            if (isset($orders["reward_points_balance"])) {
                    $itemI++;
                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setItemCode($RewardsPoints_Name);
                    $Item->setItemDescription($orders["reward_points_balance"].'reward points');
                    $Item->setItemShortDescr($orders["reward_points_balance"].'reward points');
                    $Item->setQuantity(1);
                    $Item->setUnitPrice("-".$orders["base_reward_currency_amount"]);
                    $Item->setWeight('');
                    $Item->setFreeShipping("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");
                    $Item->setDiscounted("Y");
                    $Order->setOrderItems($Item->getItem());

            }

            if (isset($orders["customer_credit_amount"]) && $orders["customer_credit_amount"]>0) {
                    $itemI++;
                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setItemCode("InternalCredit");
                    $Item->setItemDescription('Internal Credit');
                    $Item->setItemShortDescr('Internal Credit');
                    $Item->setQuantity(1);
                    $Item->setUnitPrice("-".$orders["customer_credit_amount"]);
                    $Item->setWeight('');
                    $Item->setFreeShipping("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");
                    $Item->setDiscounted("Y");
                    $Order->setOrderItems($Item->getItem());

            }

            if (isset($orders["gift_cards"])) {
                    $gift_cards = $serialize->unserialize($orders["gift_cards"]);
                    foreach ($gift_cards as $gift_card) {
                        $itemI++;
                        $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                        $Item->setItemCode("GiftCard");
                        $Item->setItemDescription(substr("GiftCard #.".$gift_card['c'], 0, 50));
                        $Item->setItemShortDescr(substr($gift_card['c'], 0, 50));
                        $Item->setQuantity(1);
                        $Item->setUnitPrice("-".$gift_card['a']);
                        $Item->setWeight('');
                        $Item->setFreeShipping("N");
                        $Item->setshippingFreight("0.00");
                        $Item->setWeightSymbol("lbs");
                        $Item->setWeightSymbolGrams("453.6");
                        $Item->setDiscounted("Y");
                        $Order->setOrderItems($Item->getItem());

                }
            }
            if (isset($orders["giftcert_code"])) {
                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setItemCode("Gift Certificate");
                    $Item->setItemDescription($orders["giftcert_code"]);
                    $Item->setItemShortDescr("Gift Certificate");
                    $Item->setQuantity(1);
                    $Item->setUnitPrice("-".$orders['giftcert_amount']);
                    $Item->setWeight('');
                    $Item->setFreeShipping("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");
                    $Item->setDiscounted("Y");
                    $Order->setOrderItems($Item->getItem());
            }

            if (isset($orders["gw_price"]) && $orders["gw_price"] != "0.0" && $orders["gw_price"] > "0.0") {

                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setItemCode("Gift Wrapping for Order");
                    $Item->setItemDescription("Gift Wrapping for Order");
                    $Item->setItemShortDescr("Gift Wrapping for Order");
                    $Item->setQuantity(1);
                    $Item->setUnitPrice($orders['gw_price']);
                    $Item->setWeight('');
                    $Item->setFreeShipping("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");
                    $Item->setDiscounted("Y");
                    $Order->setOrderItems($Item->getItem());

            }
            if (isset($orders["gw_items_price"]) && $orders["gw_items_price"] != "0.0" && $orders["gw_items_price"] > "0.0") {
                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $Item->setItemCode("Gift Wrapping for Items");
                    $Item->setItemDescription("Gift Wrapping for Items");
                    $Item->setItemShortDescr("Gift Wrapping for Items");
                    $Item->setQuantity(1);
                    $Item->setUnitPrice($orders['gw_items_price']);
                    $Item->setWeight('');
                    $Item->setFreeShipping("N");
                    $Item->setshippingFreight("0.00");
                    $Item->setWeightSymbol("lbs");
                    $Item->setWeightSymbolGrams("453.6");
                    $Item->setDiscounted("Y");
                    $Order->setOrderItems($Item->getItem());
                
            }
            /////////////////////////////////////
//   billing info
            /////////////////////////////////////
            $Bill = $this->_objectManager->get('Webgility\EccM2\Model\Bill');
            $CreditCard = $this->_objectManager->get('Webgility\EccM2\Model\CreditCard');
            $PayStatus = "Cleared";
            if (isset($payment['cc_type']) && $payment['cc_type'] != "") {
                if($ccdetails !== 'DONOTSEND') {            

                    $CreditCard->setCreditCardType($this->getCcTypeName($payment['cc_type']));
                    if (isset($payment['amount_paid'])) {
                        $CreditCard->setCreditCardCharge($payment['amount_paid']);
                    } else {
                        $CreditCard->setCreditCardCharge('0.00');
                    }
                    if (isset($payment['cc_exp_month']) && isset($payment['cc_exp_year'])) {
                        $CreditCard->setExpirationDate(sprintf('%02d', $payment['cc_exp_month']).substr($payment['cc_exp_year'], -2, 2));
                    } else {
                        $CreditCard->setExpirationDate("");
                    }

                    $CreditCardName = $payment['cc_owner'] ? ($payment['cc_owner']) : "";
                    $CreditCard->setCreditCardName($CreditCardName);
                    $encryptor = $this->_objectManager->get('\Magento\Framework\Encryption\EncryptorInterface');

                    $payment['cc_number_enc'] = $encryptor->decrypt($payment['cc_number_enc']); 
                    $CreditCardNumber = $payment['cc_number_enc']?$payment['cc_number_enc']:$payment['cc_last4'];
                    $CreditCard->setCreditCardNumber(utf8_encode($CreditCardNumber));
                    if (!empty($orders['quote_id'])) {

                        $getQuote = $this->_objectManager->get('\Magento\Quote\Model\Quote\Payment')->getCollection()->setQuoteFilter($orders['quote_id']);
                        $getQuote_val=$getQuote->toArray() ;
                        $CreditCard->setCVV2($encryptor->decrypt($getQuote_val['items']['0']['cc_cid_enc']));
                    } else {
                        $CreditCard->setCVV2('');
                    }
                    $CreditCard->setAdvanceInfo('');
                    $transcationId ="";
                    $transcationId = (isset($payment['cc_trans_id'])?($payment['cc_trans_id']):"");
                    $transcationId  = $transcationId ? $transcationId : $payment['last_trans_id'];
                }           
                $CreditCard->setTransactionId($transcationId);
                $CreditCard->getCreditCard();               
                $Bill->setCreditCardInfo($CreditCard->getCreditCard()); 
                } else {
                $transcationId = "";
                if (isset($payment['additional_information']['authorize_cards'])) {
                    $additional_information_authorize_cards = $payment['additional_information']['authorize_cards'];
                    if (is_array($additional_information_authorize_cards)) {
                        foreach ($additional_information_authorize_cards as $key => $value) {
                            $payment['last_trans_id'] = $value['last_trans_id'];
                            $payment['cc_type']= $value['cc_type'];
                            $payment['cc_exp_month'] = $value['cc_exp_month'];
                            $payment['cc_exp_year'] = $value['cc_exp_year'];
                            $payment['cc_last4'] = $value['cc_last4'];
                        }
                    }
                }
                if ($ccdetails !== 'DONOTSEND') {           
                    $CreditCard->setCreditCardType($this->getCcTypeName($payment['cc_type']));
                    $CreditCard->setCreditCardCharge($payment['amount_paid']);
                    $CreditCard->setExpirationDate(sprintf('%02d',$payment['cc_exp_month']).substr($payment['cc_exp_year'], -2, 2));
                    $CreditCardName = $payment['cc_owner'] ? ($payment['cc_owner']) : "";
                    $CreditCard->setCreditCardName($CreditCardName);
                    $CreditCardNumber = $payment['cc_number_enc']?$payment['cc_number_enc']:isset($payment['cc_last4']);
                    $CreditCard->setCreditCardNumber(utf8_encode($CreditCardNumber));
                    if (!empty($orders['quote_id'])) {

                       $getQuote= $this->_objectManager->get('\Magento\Quote\Model\Quote\Payment')->getCollection()->setQuoteFilter($orders['quote_id']);
                        $getQuote_val=$getQuote->toArray();

                        $encryptor = $this->_objectManager->get('\Magento\Framework\Encryption\EncryptorInterface');
                        $cc_cid = $encryptor->decrypt($getQuote_val['items']['0']['cc_cid_enc']);                      
                        $CreditCard->setCVV2($cc_cid);
                    } else {
                        $CreditCard->setCVV2('');
                    }
                    $CreditCard->setAdvanceInfo('');    
                }                   
                $transcationId = $transcationId ? $transcationId : $payment['last_trans_id'];
                $CreditCard->setTransactionId($transcationId);
                $CreditCard->getCreditCard();
                $Bill->setCreditCardInfo($CreditCard->getCreditCard());
            }

            if (isset($payment['amount_ordered']) && isset($payment['amount_paid'])) {
                if (($payment['amount_paid'] == $payment['amount_ordered'])) {
                        $PayStatus = "Pending";
                }
            }
    # for version 1.4.1.0

            $Bill->setPayMethod($payment['additional_information']['method_title']);
            $Bill->setTitle("");
            $Bill->setFirstName($orders["billing_firstname"]);
            $Bill->setLastName($orders["billing_lastname"]);

            if (!empty($orders["billing_company"])) {
                $Bill->setCompanyName($orders["billing_company"]);
            } else {
                $Bill->setCompanyName("");
            }

            $orders["billing_street"] = explode("\n", $orders["billing_street"]);
            $Bill->setAddress1($orders["billing_street"][0]);
            $Bill->setAddress2(isset($orders["billing_street"][1])?$orders["billing_street"][1]:"");
            $Bill->setCity($orders["billing_city"]);

            $region = $this->_objectManager->get('Magento\Directory\Model\Region')->load($orders['billing_region_id']);
            $state_code = $region->getCode();

            if ($donwload_state_code == 'True') {
                $Bill->setState($state_code);
            } else {
                $Bill->setState($orders["billing_region"]);
            }
    
            $Bill->setZip($orders["billing_postcode"]);
            $Bill->setCountry(trim($country[$orders["billing_country"]]));
            $Bill->setEmail($orders["customer_email"]);
            $Bill->setPhone($orders["billing_telephone"]);
            $Bill->setPONumber($payment['po_number']);

            $customer = $this->_objectManager->get('\Magento\Customer\Model\Customer')->load($orders["customer_id"]);
            $customerGroupId = $customer->getGroupId();
            $group = $this->_objectManager->get('\Magento\Customer\Model\Group')->load($customerGroupId);
            $group_nam = $group->getCode();
            $Bill->setGroupName($group_nam);
            $Order->setOrderBillInfo($Bill->getBill());
            $Ship = $this->_objectManager->get('Webgility\EccM2\Model\Ship');
        ##CASE 1 FOR SHIPPING INFO
            $shippingDesc = $_order->getShippingDescription();
            $ship_career = explode("-", $shippingDesc, 2);
            $ShipMethod = $ship_career[1];
            $Carrier = $carrier_code = $ship_career[0];
            $shipTrack1 = "";
          ##CASE 2 FOR SHIPPING INFO
            $shipmentCollection = $_order->getShipmentsCollection();
            foreach ($shipmentCollection as $shipment) {
                foreach ($shipment->getAllTracks() as $ship_data) {
                    $Req_ship_detail_arry=$ship_data->toArray();
                    $ShipMethod=$Req_ship_detail_arry['title'];
                    $carrier_code=$Req_ship_detail_arry['carrier_code'];
                    $shipTrack1=$Req_ship_detail_arry['track_number'];
                }
            }   
            if (isset($get_Active_Carriers)) {
                $carrierInstances = $this->_objectManager->get('\Magento\Shipping\Model\Config')->getActiveCarriers($storeid);
            } else {
                $carrierInstances = $this->_objectManager->get('\Magento\Shipping\Model\Config')->getAllCarriers($storeid);
            }

            $carriers['custom'] = (string)__("Custom Value");
            foreach ($carrierInstances as $code => $carrier) {
                if ($carrier->isTrackingAvailable()) {
                    $carriers[$code] = $carrier->getConfigData('title');
                }
            }
            $c_code=''; 
            foreach ($carriers as $c_key => $c_val) {
                if ($carrier_code == $c_key) {
                    $Carrier=$c_val;
                    break;
                }
            }
            unset($carrier_code);
            $Carrier=strtolower($Carrier);
            $ship_career = explode("-", $orders["shipping_description"], 2);

            #Fixed - Shipping Method Download issue in Order
            //$Ship->setShipMethod(empty($ShipMethod)?$ship_career[1]:$ShipMethod);
            if(!empty($ShipMethod)){
               $Ship->setShipMethod($ShipMethod);
            }else{
                $Ship->setShipMethod($ShipMethod2);
            }
            #Fixed - Shipping Method Download issue in Order

            $Ship->setCarrier(empty($Carrier)?$ship_career[0]:$Carrier);
            $Ship->setTrackingNumber(!empty($shipTrack1)?$shipTrack1:'');
            #End
            unset($shipTrack);
            $Ship->setTitle("");

            if (!array_key_exists('shipping_firstname', $orders) && !array_key_exists('shipping_lastname', $orders)) {
                $shippingAddressArray = $_order->getShippingAddress();
                if (is_array($shippingAddressArray)) {
                    $shippingAddressArray = $shippingAddressArray->toArray();
                }
                $orders["shipping_firstname"]=$shippingAddressArray["firstname"];
                $orders["shipping_lastname"]=$shippingAddressArray["lastname"];
                $orders["shipping_company"]=$shippingAddressArray["company"];
                $orders["shipping_street"]=$shippingAddressArray["street"];
                $orders["shipping_city"]=$shippingAddressArray["city"];
                $orders["shipping_region"]=$shippingAddressArray["region"];
                $orders["shipping_region_id"]=$shippingAddressArray["region_id"];
                $orders["shipping_postcode"]=$shippingAddressArray["postcode"];
                $orders["shipping_country"]=$shippingAddressArray["country_id"];
                $orders["customer_email"] = $shippingAddressArray["customer_email"]?$shippingAddressArray["customer_email"]:$orders["customer_email"];
                $orders["shipping_telephone"] = $shippingAddressArray["telephone"];
            }
            $Ship->setFirstName($orders["shipping_firstname"]);
            $Ship->setLastName($orders["shipping_lastname"]);
            if (!empty($orders["shipping_company"])) {
                $Ship->setCompanyName($orders["shipping_company"]);
            } else {
                $Ship->setCompanyName("");
            }

            $orders["shipping_street"] = explode("\n", $orders["shipping_street"]);

            $Ship->setAddress1($orders["shipping_street"][0]);
            $Ship->setAddress2(isset($orders["shipping_street"][1])?$orders["shipping_street"][1]:"");
            $Ship->setCity($orders["shipping_city"]);

            $region_shipping = $this->_objectManager->get('Magento\Directory\Model\Region')->load($orders['shipping_region_id']);
            $shipping_state_code = $region_shipping->getCode(); 
            if ($donwload_state_code == 'True') {

                $Ship->setState($shipping_state_code);
            } else {
                $Ship->setState($orders["shipping_region"]);
            }
            $Ship->setZip($orders["shipping_postcode"]);
            $Ship->setCountry(trim($country[$orders["shipping_country"]]));
            $Ship->setEmail($orders["customer_email"]);
            $Ship->setPhone($orders["shipping_telephone"]);

            $Order->setOrderShipInfo($Ship->getShip());
            $charges = $this->_objectManager->get('Webgility\EccM2\Model\Charges');

            $charges->setDiscount($discountadd ? abs($orders["discount_amount"]) : '');
            $charges->setStoreCredit(isset($orders["customer_balance_amount"]) ? $orders["customer_balance_amount"] : 0.00);
            $charges->setTax($orders["tax_amount"]);
            $charges->setShipping($orders["shipping_amount"]);
            $charges->setTotal($orders["grand_total"]);
            $charges->setSubTotal();
            $Order->setOrderChargeInfo($charges->getCharges());
            $Order->setShippedOn($shippedOn);
            $Order->setShippedVia(empty($Carrier) ? $ship_career[0] : $Carrier);
            unset($Carrier, $shipTrack1, $ShipMethod);
            $setSalesRep = '' ;
            $Order->setSalesRep($setSalesRep);
            $Orders->setOrders($Order->getOrder());     
        }
        return $this->WgResponse($Orders->getOrders());
    }  
    public function _dateFormatwg($date)
    {

        $localDate = $this->_objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        if (strtotime($date)) {
            # Latest code modififed date for all country
            $fdate = date("m-d-Y H:i:s",strtotime($date));
        } else {
            #Code is custamize for this customer
            $dateObj = $localDate->date($date);
            $dateStrToTime = $dateObj->getTimestamp();
            $fdate = date("m-d-Y H:i:s", $dateStrToTime);
        }
        return $fdate;      
    }

    public function _getOrders($datefrom, $start_order_no = 0, $order_status_list = '', $storeId = 1, $no_of_orders = 20, $by_updated_date= '', $orderlist, $LastModifiedDate)
    {    

        if (strtolower($order_status_list) == 'all' || strtolower($order_status_list) == "'all'") {
            $order_status = [];
            $orderStatusData = $this->_getorderstatuses($storeId);
            foreach ($orderStatusData as $sk => $sv) {
                $order_status[]= $sk;
            }
        } else {
            $order_status_list = str_replace("'", "", $order_status_list);
            $order_status_list = explode(",", $order_status_list);
            $order_status = $this->_orderStatustofetch($order_status_list, $storeId);
        }   
        if (isset($LastModifiedDate) && $LastModifiedDate != '') {
            $datefrom2 = explode(" ", $LastModifiedDate);
            $datetime1 = explode("-", $datefrom2[0]);       
            $LastModifiedDate = $datetime1[2]."-".$datetime1[0]."-".$datetime1[1];
            $LastModifiedDate .= " ".$datefrom2[1]; 
        } else {
            $datetime1 = explode("-", $datefrom);           
            $datefrom = $datetime1[2]."-".$datetime1[0]."-".$datetime1[1];  
            $datefrom .=" 00:00:00"; 
        }  
        $orderFactory = $this->_objectManager->get('\Magento\Sales\Model\Order');
        $orderCollection = $orderFactory->getResourceCollection();         
        $orderObject =  $orderCollection->addAttributeToSelect('*');
        if (!$orderlist && $LastModifiedDate) {
            $orderObject->addAttributeToFilter('updated_at', ['gt' =>  $LastModifiedDate, 'datetime' => true])
                    ->addAttributeToFilter('store_id', $storeId)
                    ->addAttributeToFilter('status', ['in' => $order_status])
                    ->addAttributeToSort('updated_at', 'asc')
                    ->setPageSize($no_of_orders);
        } elseif (!$orderlist && $datefrom) {

            $orderObject->addAttributeToFilter('created_at', ['from' => $datefrom, 'datetime' => true])
                            ->addAttributeToFilter('store_id', $storeId)
                            ->addAttributeToFilter('entity_id', ['gt' => $start_order_no])
                            ->addAttributeToFilter('status', ['in' => $order_status])
                            ->addAttributeToSort('entity_id', 'asc')
                            ->setPageSize($no_of_orders);
        } else {
            $orderObject->addAttributeToFilter('store_id', $storeId)
                            ->addAttributeToFilter('increment_id', ['in' => $orderlist])        
                            ->addAttributeToSort('entity_id', 'asc');
        }
        $this->_orders = $orderObject->load(); 
        return $this->_orders;
    }

    public function getPaymentlabel($paymethod = '')
    {

        $orderFactory = $this->_objectManager->get('\Magento\Sales\Model\Order');
        $method = "";
        foreach ($this->_getPaymentMethods() as $paymentCode => $paymentModel) {
            $paymentTitle = "paypal";
            if($paymentCode == $paymethod) {
                return $paymentTitle;
                break;
            }
        }
        return $method;
    }
    public function _orderStatustofetch($order_status_list, $storeId)
    {
        $orderStatus = $this->_getorderstatuses($storeId);
        $order_status = [];
        foreach ($orderStatus as $sk => $sv) {
            if (in_array($sv, $order_status_list)) {
                $order_status[] = $sk;
            }
        }
        return $order_status;
    }

    public function getDefaultStore($storeId) 
    {

        $storeManagerObj = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeModel = $this->_objectManager->get('\Magento\Store\Model\Store');
        if (isset($storeId) && $storeId != "") {
            $stores = $storeModel->getResourceCollection()
                            ->setLoadDefault(true)
                            ->addIdFilter($storeId)
                            ->load();
            $stores = $stores->toArray();
            $store_Id = $stores['items'][0]['store_id'];
            return $store_Id;
        }
        if (!defined("__STORE_ID")) {
            $name = $storeManagerObj->getDefaultStoreView();
            $name = $name->toArray();
            return $name['store_id'];
            define("__STORE_ID", $name['store_id']);
        } elseif (__STORE_ID != '') {
            return __STORE_ID;
        } else {
            return 1;
        }
    }
    public function getduplicaterecord($productname, $productcode)
    {

        $productname = '' ;
        $productsCollection = $this->_objectManager->get('Magento\Catalog\Model\Product')->getCollection()
                                    ->addAttributeToSelect('*')
                                    ->addAttributeToFilter('sku', $productcode)
                                    ->load();
        $productsCollection = $productsCollection->toArray();
        if (!empty($productsCollection)) {
            return "1";
        } else {
            return "0";
        }
    }
    public function getorderitems($Id, $incrementID, $download_option_as_item = false)
    {

        $incrementID = '' ;
        $orderItem = $this->_objectManager->get('\Magento\Sales\Model\Order\Item');
        $productObj = $this->_objectManager->get('\Magento\Catalog\Model\Product');
        unset($collection);
        if ($download_option_as_item == true) {
            $collection = $orderItem->getResourceCollection()
                    ->setOrderFilter($Id)
                    ->setOrder('item_id', 'asc');   
        } else {
            $collection = $orderItem->getResourceCollection()
                    ->setOrderFilter($Id)
                    ->addFieldToFilter('parent_item_id', ['null' => true])
                    ->setOrder('item_id', 'asc');   
        }
        $products = '';
        unset($products);
        $products = [];
        foreach ($collection as $item) {
            $products[] = $item->getProductId();    
        }
        $productsCollection = $productObj->getCollection()
                                ->addAttributeToSelect('*')
                                ->addIdFilter($products)
                                ->load();
        unset($item);              
        foreach ($collection as $item) {
            $item->setProduct($productsCollection->getItemById($item->getProductId()));
        }
        $collection = $collection->toArray();
        $productsCollection = $productsCollection->toArray();
        return $collection;
    }
    public function UpdateOrdersShippingStatus($username, $password, $Orders_json_array, $emailAlert = 'N', $statustype, $storeid = 1, $others)
    {

        global $set_capture_case;

        $storeId=$this->getDefaultStore($storeid);      
        $status = $this->checkUser($username,$password);
        if($status != "0") {
            return $status;
        }
        $Orders = $this->_objectManager->get('Webgility\EccM2\Model\Orders');
/////////////////////////////////////////////////
        $requestArray = $Orders_json_array;
        if (!is_array($requestArray)) {
            $Orders->setStatusCode("9997");
            $Orders->setStatusMessage("Unknown request or request not in proper format");
            return $this->WgResponse($Orders->getOrders());
        }
        if (count($requestArray) == 0) {
            $Orders->setStatusCode("9996");
            $Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");
            return $this->WgResponse($Orders->getOrders());
        }
        if (count($requestArray) == 0) {
            $no_orders = true;
        } else {
            $no_orders = false;
        }
        $Orders->setStatusCode($no_orders?"1000":"0");
        $Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");
        if ($no_orders) {
            return json_encode($responce_array);
        }
        $i=0;
        foreach ($requestArray as $k2 => $order) {
                $update_note = isset($order['UpdateOrderNote'])?$order['UpdateOrderNote']: "";
            if ($update_note == "Y") {
                $orders1 = $this->_UpdateOrdersShippingStatus($order['OrderID'], $storeId);
                $orders_array = $orders1->toArray();

                $orderIncrementID = $order['OrderID'];
                $order_1 = $this->_objectManager->get('Magento\Sales\Model\Order')->loadByIncrementId($orderIncrementID);
                $order_1->addStatusToHistory($order_1->getStatus(), $order['OrderNotes'], false);
                $order_1->save();
                $result = "Success";
                $Order = $this->_objectManager->get('Webgility\EccM2\Model\Order');
                $Order->setOrderID($order['OrderID']);
                $Order->setStatus($result);
                $Orders->setOrders($Order->getOrder());
            } else {
                $orderStatus = $this->_getorderstatuses($storeId);
                $status = $order['OrderStatus'];
                $emailAlert = $order['IsNotifyCustomer'];
                $order_status_list = [ 0 => $status ];
                $status_w = $this->_orderStatustofetch($order_status_list, $storeId);

                if(!empty($status_w)){
                    $order['OrderStatus'] = $status_w[0];
                }else{
                    $order['OrderStatus'] = '';
                }

                $info = "\nOrder shipped ";
                if ($order['ShippedOn'] != "") {
                    $info .= " on ". substr($order['ShippedOn'], 0, 10);
                }

                if ($order['ShippedVia'] != "" || $order['ServiceUsed'] != "") {
                    $info .= " via ".$order['ShippedVia']." ".$order['ServiceUsed'];
                }

                if ($order['TrackingNumber'] != "") {
                    $info .= " with Tracking Number ".$order['TrackingNumber'].".";
                }

                if ($order['OrderNotes'] != "") {
                    $info .=" \n".$order['OrderNotes'];
                }

                $orders1 = $this->_UpdateOrdersShippingStatus($order['OrderID'], $storeId);
                $orders_array=$orders1->toArray();

                if (array_key_exists('items', $orders_array)) {
                    $orders_array_w =$orders_array['items'];
                } else {
                    $orders_array_w =$orders_array;
                }
                foreach ($orders_array_w as $orders_el) {
    
                    /* $orderObj = $this->_objectManager->get('\Magento\Sales\Model\Order');
                    $current_order = $orderObj->load($orders_el['entity_id']); */
                    $current_order = $this->_objectManager->create('Magento\Sales\Model\Order')
        ->loadByAttribute('increment_id', $order['OrderID']);
                            
                    if (isset($order['IsCreateCreditMemo']) && $order['IsCreateCreditMemo'] == true && $current_order->canCreditMemo() && $current_order->hasInvoices()) {
                        $this->createCreditMemo($orders_el['entity_id'],$order['CancelItemDetail'],$storeId, true);
                        $result ="Error: Order cannot be cancelled. Please review manually. Credit memo successfully created.";
                                    
                    } elseif (strtolower($order['OrderStatus']) == 'canceled'  || strtolower($statustype) == strtolower('Cancel')) {
                        if ($current_order->getState() == strtolower($order['OrderStatus'])) {
                            $result ="Success: Order is already Canceled";
                            $emailAlert = "N";
                        } else {
                            $result = $this->cancelAction($orders_el['entity_id']);
                            $info ='';
                            if (trim($result) != "" && $result==1) {
                                $result ="";
                                $emailAlert = "N";
                            } else {
                                $emailAlert = "N";
                            }
                            $current_order->setStatus($order['OrderStatus']);
                        }

                    } elseif (strtolower($order['OrderStatus']) == 'holded') {
                        $result = $this->holdAction($orders_el['entity_id']);
                        if (trim($result) != "" && $result == 1) {
                            $result ="";
                        } else {
                            $emailAlert = "N";
                        }

                    } elseif (strtolower($order['OrderStatus']) == 'unholded') {
                        $result = $this->unholdAction($orders_el['entity_id']);
                        if (trim($result) != "" && $result==1) {
                            $result = "";
                        } else {
                            $emailAlert = "N";
                        }

                    } elseif (strtolower($order['OrderStatus']) == "complete") {
                        if ($current_order->getState() == strtolower($order['OrderStatus'])) {
                            $result = "Success: Order has already been completed";
                            $emailAlert = "N";
                        } elseif (strtolower($current_order->getState()) =="processing" || strtolower($current_order->getState())  == "pending" || strtolower($current_order->getState()) == "new") {

                            $current_order->setTotal_paid($orders_el['grand_total']);
                            $current_order->setBase_total_paid($orders_el['base_grand_total']);

                            $current_order->setTotal_invoiced($orders_el['grand_total']);
                            $current_order->setBase_total_invoiced($orders_el['base_grand_total']);

                            $current_order->setDiscount_invoiced($orders_el['discount_amount']);
                            $current_order->setBase_discount_invoiced($orders_el['base_discount_amount']);

                            $current_order->setSubtotal_invoiced($orders_el['subtotal']);
                            $current_order->setTax_invoiced($orders_el['tax_amount']);

                            $current_order->setShipping_invoiced($orders_el['shipping_amount']);
                            $current_order->setBase_subtotal_invoiced($orders_el['base_subtotal']);
                            $current_order->setBase_tax_invoiced($orders_el['base_tax_amount']);
                            $current_order->setBase_shipping_invoiced($orders_el['base_shipping_amount']);

                            foreach ($current_order->getAllItems() as $item_o) {
                                $item_o->setQtyInvoiced($item_o->getQtyToShip());
                                $data['items'][$item_o->getId()] = $item_o->getQtyToShip();
                            }
                            $data['comment_text'] = $order['OrderNotes'];

                            if(isset($set_capture_case)) {
                                $data['capture_case'] = 'offline';
                            }
                            $this->_saveInvoice($data,$orders_el['entity_id']);
                            $RequestOrders = [ "TRACKINGNUMBER" => $order['TrackingNumber'],
                            "SHIPPEDVIA" => $order['ShippedVia'],
                            "SERVICEUSED"=>$order['ServiceUsed']
                            ];
                            if ($current_order->canShip()) {
                                                        
                                if ($shipment = $this->_initShipment($current_order, $RequestOrders, $data)) {      
                                    $shipment->register();
                                    $shipment->addComment($info, true);
                                    $shipment_arr = $this->_saveShipment($shipment);
                                    $emailSender2 = $this->_objectManager->get('Magento\Sales\Model\Order\Email\Sender\ShipmentSender');
                                    $emailSender2->send($shipment, true);
                                }
                            }   
                            if($current_order->getState() != $order['OrderStatus']) {
                                $state = $order['OrderStatus'];
                                $current_order->setData('state', $state);
                                if ($status) {
                                    if ($status === true) {
                                        $status = $current_order->getConfig()->getStateDefaultStatus($state);
                                    }
                                    $current_order->setStatus($status);
                                }
                            }
                            $invoiceNotifies = false;
                            if ($emailAlert == 'Y') {
                                $invoiceNotified = True;
                            }
                            $current_order->addStatusToHistory($order['OrderStatus'], $info, $invoiceNotified);
                            $current_order->save();
                            $result = "Success: Order has been completed";

                        } else {
                            $result = "Error: Order cannot be completed. Please review manually";
                            $emailAlert = "N";
                        }
                    } else {
                        $result = 'Error : Order cannot be '.$current_order->getState()." . Please review manually";
                        $emailAlert = "N";
                    }
                    if($emailAlert == 'Y') {
                        $orderObj = $this->_objectManager->get('\Magento\Sales\Model\Order');
                        $current_order = $orderObj->loadByIncrementId($order['OrderID']);
                        $emailSender = $this->_objectManager->get('\Magento\Sales\Model\AdminOrder\EmailSender');
                        // $emailSender->send($current_order);
                        unset($info);
                    }
                }
                $result = $result?$result:'Success: Order has been '.ucfirst($order['OrderStatus']);
                $Order = $this->_objectManager->get('Webgility\EccM2\Model\Order');
                $current_order = $this->_objectManager->get('\Magento\Sales\Model\Order')->loadByIncrementId($order['OrderID']);
                $currentOrderArray = $current_order->toArray();
                $Order->setOrderID(isset($currentOrderArray->increment_id)?$currentOrderArray->increment_id: $order['OrderID']);
                $Order->setStatus($result);                     
                $updateDate = $this->_dateFormatwg($currentOrderArray['updated_at']);
                $Order->setLastModifiedDate($updateDate);
                $Order->setOrderNotes(isset($info) ? $info : "");
                $Order->setOrderStatus($current_order->getState());
                $Orders->setOrders($Order->getOrder());
            }
        }
        return $this->WgResponse($Orders->getOrders());
    }
    public function createCreditMemo($orderId, $ccitemdetails, $storeId = 1, $mailCreditMemo = false)
    {

        $creditmemoFactory = $this->_objectManager->get('\Magento\Sales\Model\Order\CreditmemoFactory');
            
        $orderFactory = $this->_objectManager->get('\Magento\Sales\Model\OrderFactory');
        $orderObj = $orderFactory->create()->load($orderId);
            
        if (!$orderObj->canCreditMemo()) {
                return false;
        }

        if ($orderObj->hasInvoices()) {
            $args['do_offline'] = 1;
            $args['comment_text'] = ""; 
            $args['shipping_amount'] = $orderObj->getBaseShippingAmount();
            $args['adjustment_positive'] = 0;
            $args['adjustment_negative'] = 0;
                
            if ($mailCreditMemo == true) {
                $args['send_email'] = 1;
            }
      
            $creditmemoLoader = $this->_objectManager->get('\Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader');
            $creditmemoLoader->setOrderId($orderId);
            $creditmemo = $creditmemoLoader->load();
            if (!$creditmemo->isValidGrandTotal()) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('The credit memo\'s total must be positive.'));
            }
            if (!empty($args['comment_text'])) {
                    $creditmemo->addComment(
                        $args['comment_text'],
                        isset($args['comment_customer_notify']),
                        isset($args['is_visible_on_front']));
                $creditmemo->setCustomerNote($args['comment_text']);
                $creditmemo->setCustomerNoteNotify(isset($args['comment_customer_notify']));
            }
            if (isset($args['do_offline'])) {
                    ##do not allow online refund for Refund to Store Credit
                if (!$args['do_offline'] && !empty($args['refund_customerbalance_return_enable'])) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                            __('Cannot create online refund for Refund to Store Credit.'));
                }
            }
            $items = $ccitemdetails;
            $ccqty1 = 0;
            $totalQty = 0;
            $item_array = "";
            foreach ($items as $item => $v) {
                $totalQty += $item['QtyCancel'];
                $item_array[$item['ItemID']] = $item['QtyCancel'];
                if ($item['QtyInOrder'] != $item['QtyCancel']) {
                    $ccqty1 = 1;
                }
            }
            $data = [
            'qtys' => $item_array,
            "do_offline" => "1"        
            ];
            if ($ccqty1 == 1) {
                $creditmemo = $creditmemoFactory->createByOrder($orderObj, $data);
            }       
            $creditmemoManagement = $this->_objectManager->create('Magento\Sales\Api\CreditmemoManagementInterface');
            $creditmemoManagement->refund($creditmemo, (bool)$args['do_offline'], !empty($args['send_email']));
                    
            if (!empty($args['send_email'])) {
                $creditmemoSender = $this->_objectManager->get('Webgility\EccM2\Model\WgSender');
            }
        }
        if ($ccqty1 == 1) {
            $orderCreditMemoStatusComment = "Credit Memo Created";
            $orderObj->setData('state', $orderObj->getState(), true);
            $orderObj->setStatus($orderObj->getState());
            $orderObj->addStatusToHistory ($orderObj->getState(), $orderCreditMemoStatusComment, false);
            $orderObj->save();
            
        } else {
            if (strtolower($orderObj->getState()) == "processing") {
                $orderCreditMemoStatusComment = "Credit Memo Created";
                $orderObj->setData('state', "closed", true);
                $orderObj->setStatus("closed");
                $orderObj->addStatusToHistory("closed", $orderCreditMemoStatusComment, false);
                $orderObj->save();
            } 
        }
    }
    public function _UpdateOrdersShippingStatus($orderId, $storeId = 1)
    {

        $orderCollectionObj = $this->_objectManager->get('\Magento\Sales\Model\Order')->getResourceCollection();
            
        $orders = $orderCollectionObj->addAttributeToSelect('*')
                        ->addFieldToFilter('increment_id', $orderId)
                        ->addAttributeToFilter('store_id', $storeId)
                        ->load();
        return $orders;
    }

    public function AutoSyncOrder($username, $password, $data, $statustype, $storeid, $others)
    {

        global $get_Active_Carriers;
        $status = $this->checkUser($username, $password);
        if ($status !='0') {
            return $status;
        }   
        $Orders = $this->_objectManager->get('Webgility\EccM2\Model\Orders');
        $response_array = $data; 
        if (!is_array($response_array)) {
            $Orders->setStatusCode("9997");
            $Orders->setStatusMessage("Unknown request or request not in proper format");   
            return $this->WgResponse($Orders->getOrders());         
        }
        if (count($response_array) == 0) {
            $Orders->setStatusCode("9996");
            $Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");             
            return $this->WgResponse($Orders->getOrders());
        }
        if (count($response_array) == 0) {
            $no_orders = true; 
        } else {
            $no_orders = false;
        }
        $Orders->setStatusCode($no_orders?"1000":"0");
        $Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");
        if ($no_orders) {
            return json_encode($response_array);
        }
        $storeId=$this->getDefaultStore($storeid);          
        foreach ($response_array as $k => $v) {     
            if (isset($order_wg)) {
                unset($order_wg);
            }
            foreach ($v as $k1 => $v1) {
                $order_wg[$k1] = $v1;
            }
            $order_id = $order_wg['OrderID'];
            $current_order = $this->_objectManager->get('Magento\Sales\Model\Order')->loadByIncrementId($order_id);
            
            if ($order_wg['IsNotifyCustomer']=='N') {
                $IsNotifyCustomer = false; 
            } else {
                $IsNotifyCustomer = true;
            }
            $isupdated = "error";
            switch ($statustype) {
                case 'paymentUpdate':
                case 'statusUpdate':
                case 'notesUpdate':
                    try {
                        $current_order->addStatusToHistory($current_order->getStatus(), $order_wg['OrderNotes'], $IsNotifyCustomer);
                        $current_order->save();
                        $isupdated = "success";
                    } catch(\Exception $e) {
                        $isupdated = "error";
                    }
                    break;

                case 'shipmentUpdate':
                    try {
                        $carrier_name=$order_wg['ServiceUsed'];
                        if ($get_Active_Carriers) {
                            $carrierInstances = $this->_objectManager->get('\Magento\Shipping\Model\Config')->getActiveCarriers($storeid);
                        } else {
                    
                            $carrierInstances = $this->_objectManager->get('\Magento\Shipping\Model\Config')->getAllCarriers($storeId);
                        }
                    
                        $carriers['custom'] = (string)__("Custom Value");  
                        foreach ($carrierInstances as $code => $carrier) {
                            if ($carrier->isTrackingAvailable()) {
                                $carriers[$code] = $carrier->getConfigData('title');
                            }
                        }
                        $c_code = '';   
                        if (in_array($carrier_name, $carriers, true)) {
                            $c_code= array_search($carrier_name, $carriers); 
                        }
                        if ($current_order->canShip() && !empty($c_code)) {
                            $CarrierCode =$this->_getShippingCode($order_wg['ShippedVia']);
                            $convertor = $this->_objectManager->get('\Magento\Sales\Model\Convert\Order');
                            $_shipment = $convertor->toShipment($current_order);        
                            $_track = $this->_objectManager->get('\Magento\Sales\Model\Order\Shipment\Track')
                                ->setNumber($order_wg['TrackingNumber'])
                                ->setCarrierCode($c_code)
                                ->setTitle($order_wg['ShippedVia']);
                            $_shipment->addTrack($_track);                       
                            $isupdated = "success";
                        } else {
                            $isupdated = "error";
                        }
                    } catch(\Exception $e) {
                        $isupdated = "error";
                    }               
            }
            $current_order = $this->_objectManager->get('Magento\Sales\Model\Order')->loadByIncrementId($order_id);
            $Order = $this->_objectManager->get('Webgility\EccM2\Model\Order');
            $Order->setOrderID($order_id);
            $Order->setStatus($isupdated);
            $Order->setLastModifiedDate($this->_dateFormatwg(isset($order_wg['updated_at'])));
            $Order->setOrderNotes($order_wg['OrderNotes']);
            $statuses = $this->_objectManager->get('Magento\Sales\Model\Order\Config')->getStateStatuses($current_order->getState(), true);
            foreach ($statuses as $statval) {
                $statuses=$statval;
            }
            $Order->setOrderStatus($statuses);  
            $Orders->setOrders($Order->getOrder()); 
        }   
        return $this->WgResponse($Orders->getOrders());
    }

    public function _editproduct($storeId = 1, $productId)
    {

        $Product = $this->_objectManager->get('Magento\Catalog\Model\Product')->setStoreId($storeId);;
        $Product->load($productId);
        return $Product;
    }
    public function _initShipment($current_order, $RequestOrders, $data)
    {

        try {
            $shipment = false;
            if (!$current_order->getId()) {
                $this->Msg[] = 'Error. Order not longer exist.';
                $this->result = 'Failed';
                return false;
            }
            if (!$current_order->canShip()) {
                return false;
            }
            $convertor  = $this->_objectManager->get('\Magento\Sales\Model\Convert\Order');
            $_shipment = $convertor->toShipment($current_order);
            $savedQtys = $this->_getItemQtys($data);

            foreach ($current_order->getAllItems() as $orderItem) {
                if (!$orderItem->getQtyToShip()) {
                    continue;
                }
                $_item = $convertor->itemToShipmentItem($orderItem);
                if (isset($savedQtys[$orderItem->getId()])) {
                    $qty = $savedQtys[$orderItem->getId()];
                }
                $_item->setQty($qty);
                $_shipment->addItem($_item);
                unset($qty);
                }
                if (is_array($RequestOrders['TRACKINGNUMBER'])) {
                    $t = 0;
                    foreach ($RequestOrders['TRACKINGNUMBER'] as $trackNumber) {
                        if (!empty($trackNumber)) {
                            if (!$CarrierCode =$this->_getShippingCode($RequestOrders['SHIPPEDVIA'][$t])) {
                                $CarrierCode="custom";
                                $Title = $RequestOrders['SHIPPEDVIA'][$t];
                            } elseif (isset($RequestOrders['SERVICEUSED'][$t])) {
                                $Title = $RequestOrders['SERVICEUSED'][$t];
                            } else {
                                $Title = $RequestOrders['SHIPPEDVIA'][$t];
                            }
                            $data = [
                            'carrier_code' => $CarrierCode,
                            'title' => $Title,
                            'number' => $trackNumber 
                            ];
                            $track = $this->_objectManager->get('Magento\Sales\Model\Order\Shipment\TrackFactory')->create()->addData($data);
                            $_shipment->addTrack($track); 
                        }
                        $t++;
                    }
                } else {
                    $trackNumber = $RequestOrders['TRACKINGNUMBER'];
                    if (!empty($trackNumber)) {
                        if (!$CarrierCode =$this->_getShippingCode($RequestOrders['SHIPPEDVIA'])) {
                            $CarrierCode="custom";
                            $Title = $RequestOrders['SHIPPEDVIA'];
                        } elseif (isset($RequestOrders['SERVICEUSED'])) {
                            $Title = $RequestOrders['SERVICEUSED'];
                        } else {
                            $Title = $RequestOrders['SHIPPEDVIA'];
                        }
                        $data = [
                        'carrier_code' => $CarrierCode,
                        'title' => $Title,
                        'number' => $trackNumber, 
                        ];
                        $track = $this->_objectManager->get('Magento\Sales\Model\Order\Shipment\TrackFactory')->create()->addData($data);
                        $_shipment->addTrack($track);
                    }
                }
                return $_shipment;
            } catch (\Exception $e) {
            $this->Msg[] = "Critical Error _initShipment (Exception e)" ;
        }
    }
    public function _saveShipment($shipment)
    {

        $shipment->getOrder()->setIsInProcess(true);
        $transactionSave = $this->_objectManager->get('Magento\Framework\DB\Transaction')
                            ->addObject($shipment)
                            ->addObject($shipment->getOrder())
                            ->save();
        return $this;
    }
    public function _saveInvoice($data, $orderId)
    {

        try {
            if ($invoice = $this->_initInvoice($orderId, $data, false)) {
                if (!empty($data['capture_case'])) {
                    $invoice->setRequestedCaptureCase($data['capture_case']);
                }
                if (!empty($data['comment_text'])) {
                    $invoice->addComment($data['comment_text'], isset($data['comment_customer_notify']));
                }
                $invoice->register();
                if (!empty($data['send_email'])) {
                    $invoice->setEmailSent(true);
                }

                $invoice->getOrder()->setIsInProcess(true);
                $transactionSave = $this->_objectManager->get('Magento\Framework\DB\Transaction')->addObject($invoice)->addObject($invoice->getOrder());
                $shipment = false;
                if (!empty($data['do_shipment'])) {
                    $shipment = $this->_prepareShipment($invoice);
                    if ($shipment) {
                        $shipment->setEmailSent($invoice->getEmailSent());
                        $transactionSave->addObject($shipment);
                    }
                }
                $transactionSave->save();
                /**
                 * Sending emails
                 */
                $comment = '';
                if (isset($data['comment_customer_notify'])) {
                    $comment = $data['comment_text'];
                }
                if ($shipment) {
                    $shipment->sendEmail(!empty($data['send_email']));
                }
            }
        } catch (\Exception $e) {
            $this->_getSession()->addError($this->__('Can not save invoice'));
        }
    }

    public function _getShippingCode($shipp)
    {
        $shipp = strtoupper($shipp);
        if (array_key_exists($shipp, $this->carriers_)) {
            return $this->carriers_[$shipp];
        }
       return false;
    }
    public function _getStoreDetails()
    {

        $storeManagerObj = $this->_objectManager->get('\Magento\Store\Model\Store');
        $informationObj = $this->_objectManager->get('\Magento\Store\Model\Information');
        $regionObj = $this->_objectManager->get('\Magento\Directory\Model\Region');
        $countryObj = $this->_objectManager->get('\Magento\Directory\Model\Country');
            
        $companyInfo['storeName'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_NAME);
        $companyInfo['storeAddress1'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_STREET_LINE1);
        $companyInfo['storeAddress2'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_STREET_LINE2);
        $companyInfo['storeCity'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_CITY);
        $country_code = $companyInfo['storeCountry'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_COUNTRY_CODE);
            
        if ($companyInfo['storeCountry'] == 'canada' || $companyInfo['storeCountry'] =='Canada') {
            $country_code = 'CA';
        }
     
        $companyInfo['storeRegion'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_REGION_CODE);
        $companyInfo['storeRegion'] = $regionObj->load($companyInfo['storeRegion'])->getCode();   
        $companyInfo['storePostCode'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_POSTCODE);
        $companyInfo['storePhone'] = $storeManagerObj->getConfig($informationObj::XML_PATH_STORE_INFO_PHONE);
        $companyInfo['websiteUrl'] = $storeManagerObj->getConfig($storeManagerObj::XML_PATH_SECURE_BASE_URL);

        return $companyInfo;
    }
    public function _getPaymentMethods($store = null)
    {   

        $paymentObj = $this->_objectManager->get('\Magento\Payment\Model\Config');
        $method = $paymentObj->getActiveMethods();
        if(is_array($method)) {
            return $method;
        }
    }
    public function _initInvoice($orderId, $data, $update = false)
    {

        $invoice = false;
        $order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderId);
        if (!$order->canInvoice()) {
            return false;
        }
        $convertor = $this->_objectManager->get('Magento\Sales\Model\Convert\Order');
        $invoice = $convertor->toInvoice($order);

        $savedQtys = $this->_getItemQtys($data);

        foreach ($order->getAllItems() as $orderItem) {
            $item = $convertor->itemToInvoiceItem($orderItem);
            if (isset($savedQtys[$orderItem->getId()])) {
                $qty = $savedQtys[$orderItem->getId()];
            } else {
                if ($orderItem->isDummy()) {
                    $qty = 1;
                } else {
                    $qty = $orderItem->getQtyToInvoice();
                }
            }
            $item->setQty($qty);
            $invoice->addItem($item);
        }
        $invoice->collectTotals();
        return $invoice;
    }

    public function _getItemQtys($data)
    {
        if (isset($data['items'])) {
            $qtys = $data['items'];
        } else {
            $qtys = [];
        }
        return $qtys;
    }
    public function getItemsByName($username, $password, $start_item_no = 0, $limit = 500, $itemname, $storeId = 1, $others)
    {

        $storeId=$this->getDefaultStore($storeId);
        $status = $this->checkUser($username, $password);
        if ($status!="0") {
            return $status;
        }
        $Items = $this->_objectManager->get('Webgility\EccM2\Model\Items');
        $items_query_product = $this->_getProductByName($storeId, $start_item_no, $limit, "");
        $count_query_product = $items_query_product->getSize();
        $Items->setStatusCode('0');
        $Items->setStatusMessage('All Ok');
        $Items->setTotalRecordFound($count_query_product?$count_query_product:'0');
        $Items->setTotalRecordSent(count($items_query_product->getItems())?count($items_query_product->getItems()):'0');

        if (!empty($items_query_product)) {
            #get the manufacturer
            $manufacturer = $this->_getmanufacturers($storeId);
            if ($manufacturer['totalRecords']>0) {
                foreach ($manufacturer['items'] as $manufacturer1) {
                    $manufacturer2[$manufacturer1['option_id']] = $manufacturer1['value'];
                }
            }
            unset($manufacturer, $manufacturer1);
            $itemI = 0;
            foreach ($items_query_product->getItems() as $iInfo11) {
                    $iInfo['category_ids'] = $iInfo11->getCategoryIds();
                    $options = $this->_getoptions($iInfo11);
                    $iInfo = $iInfo11->toArray();
                if ($iInfo['type_id'] == 'simple' || $iInfo['type_id'] == 'virtual' || $iInfo['type_id'] == 'downloadable') {
                    $Item = $this->_objectManager->get('Webgility\EccM2\Model\Item');
                    $desc = htmlspecialchars(substr($iInfo['description'], 0, 4000), ENT_QUOTES);
                    $stockRegistryInterface = $this->_objectManager->get('\Magento\CatalogInventory\Api\StockRegistryInterface');
                    $stockItem = $stockRegistryInterface->getStockItem($iInfo['entity_id'])->toArray();
                    $Item->setItemID($iInfo['entity_id']);
                    $Item->setItemCode($iInfo['sku']);
                    $Item->setItemDescription($iInfo['name']);
                    $Item->setItemShortDescr(substr($desc, 0, 300));

                    if (is_array($iInfo['category_ids'])) {
                            $categoriesI = 0;
                        foreach ($iInfo['category_ids'] as $category) {
                            unset($catArray);
                            $catArray['CategoryId'] = $category;
                            $Item->setCategories($catArray);
                            $categoriesI++;
                        }
                    }
                    if (!$categoriesI) {
                        $Item->setCategories('');
                    }
                    $iInfo['manufacturer'] = $iInfo['manufacturer'] ? $manufacturer2[$iInfo['manufacturer']] : $iInfo['manufacturer'];
                    $Item->setManufacturer($iInfo['manufacturer']);
                    $Item->setQuantity($stockItem['qty']);
                    $Item->setUnitPrice($iInfo11->getPrice());
                    $Item->setListPrice($iInfo['cost']);
                    $Item->setWeight($iInfo11->getWeight());
                    $Item->setLowQtyLimit($stockItem['min_qty']);
                    $Item->setFreeShipping('N');
                    $Item->setDiscounted('');
                    $Item->setShippingFreight('');
                    $Item->setWeightSymbol('lbs');
                    $Item->setWeightSymbolGrams('453.6');
                    $Item->setTaxExempt('N');
                    $Item->setUpdatedAt($iInfo["updated_at"]);
                    $responseArray['Items'][$itemI]['ItemVariants'] = '';
                    if (is_array($options) && (!empty($options))) {
                        $optionI = 0;
                        foreach ($options as $ioInfo) {
                            $ioInfo = parseSpecCharsA($ioInfo);
                            unset($responseArray['ItemOption']);
                            $responseArray['ItemOption']['ID'] = $ioInfo['option_type_id'];
                            $responseArray['ItemOption']['Value'] = htmlspecialchars($ioInfo['title'], ENT_QUOTES);
                            $responseArray['ItemOption']['Name'] = htmlspecialchars($ioInfo['option_title'], ENT_QUOTES);
                            $Item->setItemOption($responseArray['ItemOption']);
                            $optionI++;
                        }
                    }
                    $Item->setItemVariants('');
                    $Items->setItems($Item->getItem());
                }
                $itemI++;
            } // end items
        }
        return $this->WgResponse($Items->getItems());
    }
    public function _getoptions($product)
    {
        $collection = $product->getOptionInstance()->getProductOptionCollection($product);
        return ;
    }

    public function _getProductByName($storeId = 1, $start_item_no = 0, $limit = 20, $itemname)
    {
        return;
    }
    public function addCustomers($username, $password, $data, $storeid = 1)
    {
        
        $status = $this->checkUser($username, $password);
        if ($status != "0") {   
            return $status;
        }

        $storeId=$this->getDefaultStore($storeid);
        $Customers = $this->_objectManager->get('Webgility\EccM2\Model\Customers');
        $Customers->setStatusCode('0');
        $Customers->setStatusMessage('All Ok');         
        $requestArray = $data;
        if (!is_array($requestArray)) {
            $Items->setStatusCode('9997');
            $Items->setStatusMessage('Unknown request or request not in proper format');
            return $this->WgResponse($Items->getItems());
        }
            
        if (count($requestArray) == 0) {
            $Items->setStatusCode('9996');
            $Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
            return $this->WgResponse($Items->getItems());
        }           
        foreach ($requestArray as $k => $vCustomer) {
                            
            $customer = $this->_objectManager->get('Magento\Customer\Model\Customer');
            $Email = $vCustomer['Email'];
            $CustomerId = $vCustomer['CustomerId'];
            $firstname = $vCustomer['FirstName'];
            $middlename = $vCustomer['MiddleName'];
            $lastname = $vCustomer['LastName'];
            $company = $vCustomer['Company'];
            $street1 = $vCustomer['Address1'];
            $street2 = $vCustomer['Address2'];
            $city = $vCustomer['City'];
            $postcode = $vCustomer['Zip'];
            $country_code = $vCustomer['CountryCode'];
            $tel = $vCustomer['Phone'];
            $group = $vCustomer['CustomerGroup'];
            $password = hash('sha256', $Email.$firstname);
            $country_id = '';           
            $region_id = '';
            try {
                if ($country_code == 'canada' || $country_code == 'Canada') {
                    $country_code = 'CA';
                }
                $country_id = $this->_objectManager->get('Magento\Directory\Model\Country')
                   ->loadByCode($country_code,'iso3_code')->getId();
                $region = $vCustomer['State'];
                $regionModel = $this->_objectManager->get('Magento\Directory\Model\Region')->loadByName($region, $country_id);
                $region_id = $regionModel->getId();
            } catch (\Exception $ex) {
                $result = $ex->getMessage();
           }

            $customer->setWebsiteId($this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getWebsiteId());
            $customer->loadByEmail($Email);
            if (!$customer->getId()) {
                $customer->setEmail($Email);
                $customer->setFirstname($firstname);
                $customer->setLastname($lastname);
                $customer->setPassword($password);
                $customer->setData('group_id', $group);
                try {
                    $customer->save();
                    $customer->setConfirmation(null);
                    $customer->save();
                    $isNewCustomer = $customer->isObjectNew();
                    $storeId = $customer->getSendemailStoreId();
                    if ($isNewCustomer) {
                        if ($vCustomer['IsNotifyCustomer'] == 'Y') {
                            $customer->sendNewAccountEmail('registered', '', $storeId);
                        }   
                    } elseif ((!$customer->getConfirmation())) {
                        if ($vCustomer['IsNotifyCustomer'] == 'Y') {
                            $customer->sendNewAccountEmail('confirmed', '', $storeId);
                        }   
                    }           
                    $newPassword = 'auto';
                    if ($newPassword == 'auto') {
                        $newPassword = $customer->getPassword();
                    }
                    $customer->changePassword($newPassword);
                    if ($vCustomer['IsNotifyCustomer'] == 'Y') {
                        $customer->sendPasswordReminderEmail();
                    }
                } catch (\Exception $ex) {
                    $result = $ex->getMessage();
                }
                $_custom_address = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'company' => $company,
                'street' => [
                '0' => $street1,
                '1' => $street2,
                ],                   
                'city' => $city,
                'region_id' => $region_id,
                'region' => $region,
                'postcode' => $postcode,
                'country_id' => $country_id, /* Croatia */
                'telephone' => $tel,
                ];              
                $customAddress = $this->_objectManager->get('Magento\Customer\Model\Address');
                $customAddress->setData($_custom_address)
                          ->setCustomerId($customer->getId())
                          ->setIsDefaultBilling('1')
                          ->setIsDefaultShipping('1')
                          ->setSaveInAddressBook('1');                       
                try {
                    $customAddress->save();
                } catch (\Exception $ex) {
                    $result = $ex->getMessage();
                }
                $Customer = $this->_objectManager->get('Webgility\EccM2\Model\Customer');
                $Customer->setCustomerId($customer->getId());
                $Customer->setStatus('Success');
                $Customer->setFirstName($firstname);
                $Customer->setMiddleName($middlename);
                $Customer->setLastName($lastname);
                $Customer->setCustomerGroup($group);
                $Customer->setemail($Email);
                $Customer->setCompany($company);
                $Customer->setAddress1($vCustomer['Address1']);
                $Customer->setAddress2($vCustomer['Address2']);
                $Customer->setCity($city);
                $Customer->setState($region);
                $Customer->setZip($postcode);
                $Customer->setCountry($country_code);
                $Customer->setPhone($tel);
                $Customers->setCustomer($Customer->getCustomer());
            } else {
                $Customer = $this->_objectManager->get('Webgility\EccM2\Model\Customer');
                $Customer->setStatus('Customer email already exist');
                $Customer->setCustomerId($customer->getId());
                $Customer->setFirstName($firstname);
                $Customer->setLastName($lastname);
                $Customer->setemail($Email);
                $Customer->setCompany($company);
                $Customers->setCustomer($Customer->getCustomer());  
            }
        }
        return $this->WgResponse($Customers->getCustomers());                   
    }
    public function getCustomers($username, $password, $datefrom, $customerid, $limit, $storeid)
    {

        $datefrom =$datefrom ?$datefrom:0;  
        $status = $this->checkUser($username, $password);
        if ($status != "0") {   
            return $status;
        }
        $storeId=$this->getDefaultStore($storeid);
        $Customers = $this->_objectManager->get('Webgility\EccM2\Model\Customers');
        $customersArray = $this->_getCustomer($customerid, $datefrom, $storeId, $limit);
        $count_query_customers = $customersArray->getSize();
        $no_customer =false;
        if (count($customersArray) <= 0) {
            $no_customer = true;
        }
        $Customers->setStatusCode('0');
        $Customers->setStatusMessage('All Ok');
        $Customers->setTotalRecordFound($count_query_customers?$count_query_customers:'0');
        $Customers->setTotalRecordSent(count($customersArray)?count($customersArray):'0');
        foreach ($customersArray as $customer) {

            $address = $this->_objectManager->get('Magento\Customer\Model\Address')->load($customer->getDefaultBilling());
            $address = $address->toArray();
            $customer = $customer->toArray();
            $Customer = $this->_objectManager->get('Webgility\EccM2\Model\Customer');
/////////////////////////////////////////////////////////
            $Customer->setCustomerId($customer["entity_id"]);
            $Customer->setFirstName($customer["firstname"]);
            $Customer->setMiddleName($customer["middlename"]);
            $Customer->setLastName($customer["lastname"]);
            $Customer->setCustomerGroup($customer["group_id"]);
            $Customer->setCreatedAt($customer["created_at"]);
            $Customer->setUpdatedAt($customer["updated_at"]);
            $Customer->setemail($customer["email"]);
            $Customer->setcompany(!empty($customer['default_billing'])?$address["company"]:'');
            $Customer->setAddress1(!empty($customer['default_billing'])?$address["street"]:'');
            $Customer->setAddress2("");
            $Customer->setCity(!empty($customer['default_billing'])?$address["city"]:'');
            $Customer->setState(!empty($customer['default_billing'])?$address["region"]:'');
            $Customer->setZip(!empty($customer['default_billing'])?$address["postcode"]:'');
            $Customer->setCountry(!empty($customer['default_billing'])?$address["country_id"]:'');
            $Customer->setPhone(!empty($customer['default_billing'])?$address["telephone"]:'');
            $group = $this->_objectManager->get('Magento\Customer\Model\Group')->load($customer["group_id"]);
            $group_nam=$group->getCode();
            $Customer->setGroupName($group_nam);
            $subscriber = $this->_objectManager->get('Magento\Newsletter\Model\Subscriber')->loadByEmail($customer["email"]);              
            if ($subscriber->getId()) {
                $Customer->setsubscribedToEmail("true");
            } else {
                $Customer->setsubscribedToEmail("false");
            }                 
            $Customers->setCustomer($Customer->getCustomer());
        }
        return $this->WgResponse($Customers->getCustomers());
    }

    public function _getCustomer($start_item_no, $datefrom, $storeId, $limit)
    {

        $productsCollection = $this->_objectManager->get('Magento\Customer\Model\Customer')
                                    ->getCollection()
                                    ->addAttributeToSelect('*')
                                    ->addAttributeToSort('entity_id', 'asc')
                                    ->addFieldToFilter('entity_id', ['gt' =>(int)$start_item_no ])
                                    ->setPageSize($limit);         
        return $productsCollection;
    }

    public function getCustomerGroup($username, $password, $storeid = 1, $others)
    {

        $storeId=$this->getDefaultStore($storeid);
        $status = $this->checkUser($username, $password);
        if ($status != "0") {       
            return $status;
        }
        $Groupsets = $this->_objectManager->get('Webgility\EccM2\Model\Groupsets');
        $Groupsets->setStatusCode('0');
        $Groupsets->setStatusMessage('All Ok');
        $groupsData = $this->_getCustomerGroup($storeid);

        if (!empty($groupsData)) {
            $i =0;
            foreach ($groupsData as $key => $val) {
                $Groupset = $this->_objectManager->get('Webgility\EccM2\Model\Groupset');
                $Groupset->setGroupsetID($i);
                $Groupset->setGroupsetName($val);
                $Groupsets->setGroupsets($Groupset->getGroupset());
                $i++;
            }
        }
        return $this->WgResponse($Groupsets->getGroupsets());
    }

    public function _getCustomerGroup($storeid = 1)
    {

        $searchCriteriaBuilder = $this->_objectManager->get('Magento\Framework\Api\SearchCriteriaInterface');
        $groupRepository = $this->_objectManager->get('Magento\Customer\Model\ResourceModel\GroupRepository');
        $storeId=$this->getDefaultStore($storeid);   
        foreach ($groupRepository->getList($searchCriteriaBuilder)->getItems() as $item) {
                $customerGroup[$item->getId()] = $item->getCode();
        }   
        return($customerGroup); 
    }

    #below are

    public function addShipmentByOrder($current_order, $RequestOrders, $data)
    {
        try {
            if ($shipment = $this->_initShipment($current_order, $RequestOrders, $data)) {
                $shipment->register();
                $this->Msg[] = 'Create Shipment .';
                $comment = $data['comment_text'];
                $shipment->addComment($comment, true);
                if ($this->send_email) {
                    $shipment->setEmailSent(true);
                }
                $this->_saveShipment($shipment);
                if($data['copy_email'] == 1) {
                    if ($data['append_comment'] == 1) {
                        $shipment->sendUpdateEmail($this->send_email, $comment);
                        $this->send_email;
                    } else {
                        $shipment->sendUpdateEmail($this->send_email, '');
                        $this->send_email;
                    }
                }
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $this->Msg[] = "Critical Error AddShipment (Exception e)";
        }
    }

    public function getProduct($storeId = 1, $start_item_no = 0, $limit = 20, $date_time, $others)
    {

        if ($start_item_no > 0) {
            if ($start_item_no > $limit) {
               $start_no=($start_item_no/$limit)+1;
               $start_no = (int)$start_no;
            } else {
                $start_no=($limit/$start_item_no)+1;
                $start_no = (int)$start_no;
            }
        } else {
            $start_no = 0;
        }

        $date_time = isset($date_time) ? $date_time:'';
        ###########################################################################
        $storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');

        $store = $storeManager->getStore($storeId);
        $storeManager->setCurrentStore($store->getCode());                      
        #######################################################################
        $filter_array = [['attribute' => 'updated_at', 'gteq' => $date_time], ['attribute' => 'created_at', 'gteq' => $date_time]];

        if (isset($others[0]['ItemCode']) && trim($others[0]['ItemCode'])!='') {
            $others[0]['ItemCode'] = str_replace("'", "", $others[0]['ItemCode']);
            $date_time = 'NA';
            $skuArray = explode(",", $others[0]['ItemCode']);
            $filter_array = [['attribute' => 'sku', 'in' => [$skuArray]]];
        }
        $productFactory = $this->_objectManager->get('\Magento\Catalog\Model\Product');
        $productCollection = $productFactory->getResourceCollection();
        $productData = $productCollection->addAttributeToSelect('*')
                    ->addStoreFilter($storeId)
                    ->addAttributeToFilter($filter_array)
                    ->addAttributeToSort('entity_id', 'asc')
                    ->setPageSize($limit)
                    ->setCurPage($start_no);
        return $productData;
    }
    public function _getvisibilitystatus()
    {
        return;
    }

    public function isAuthorized($username, $password, $others)
    {
        $responseArray = [];
        $status =  $this->checkUser($username, $password);
        if ($status != "0") { //login name invalid
            if ($status == "1") {
                $responseArray['StatusCode'] = '1';
                $responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
            }
            if ($status == "2") { //password invalid
                $responseArray['StatusCode'] = '2';
                $responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
            }
            $response = json_encode($responseArray);
            return $this->WgResponse($response);
        }
    }

    public function saveShipment($username, $password, $xmlShipmentItem, $storeid = 1)
    {
        $storeId=$this->getDefaultStore($storeid);
        $data = [];
        $items = [];
        $xmlResponse = new xml_doc();
        $xmlResponse->version='1.0';
        $xmlResponse->encoding='UTF-8';
        $root = $xmlResponse->createTag("RESPONSE", []);
        $status =  $this->checkUser($username, $password);
        if ($status != "0") { //login name invalid
            if ($status == "1") {
                $xmlResponse->createTag("StatusCode", [], "1", $root, __ENCODE_RESPONSE);
                $xmlResponse->createTag("StatusMessage", [], "Invalid login. Authorization failed", $root, __ENCODE_RESPONSE);
                return $xmlResponse->generate();
            }
            if ($status == "2") { //password invalid
                $xmlResponse->createTag("StatusCode", [], "2", $root, __ENCODE_RESPONSE);
                $xmlResponse->createTag("StatusMessage", [], "Invalid password. Authorization failed", $root, __ENCODE_RESPONSE);
                return $xmlResponse->generate();
            }
        }

        $xmlRequest = new xml_doc($xmlShipmentItem);
        $xmlRequest->parse();
        $xmlRequest->getTag(0, $_tagName, $_tagAttributes, $_tagContents, $_tagTags, __ENCODE_RESPONSE);

        if (strtoupper(trim($_tagName)) != 'REQUEST') {
            $xmlResponse->createTag("StatusCode", [], "9997", $root, __ENCODE_RESPONSE);
            $xmlResponse->createTag("StatusMessage", [], "Unknown request or request not in proper format", $root, __ENCODE_RESPONSE);
            return $xmlResponse->generate();
        }

        if (count($_tagTags) == 0) {
            $xmlResponse->createTag("StatusCode", [], "9996", $root, __ENCODE_RESPONSE);
            $xmlResponse->createTag("StatusMessage", [], "REQUEST tag(s) doesnt have correct input format", $root, __ENCODE_RESPONSE);
            return $xmlResponse->generate();
       }

        $ShipmentTag = $xmlRequest->getChildByName(0, "SHIPMENT");
        $xmlRequest->getTag($ShipmentTag, $_tagName, $_tagAttributes, $_tagContents, $_tagTags, __ENCODE_RESPONSE);
        foreach ($_tagTags as $k => $v) {
            $xmlRequest->getTag($v, $_tagName, $_tagAttributes, $_tagContents, $_contentTags, __ENCODE_RESPONSE);
            if ($_tagContents !='') {
                $data[$_tagName] = $_tagContents;
            }
            $i =0;
            foreach ($_contentTags as $k1=>$v1) {
                $xmlRequest->getTag($v1, $_tagName, $_tagAttributes, $_tagContents, $_itemsTags, __ENCODE_RESPONSE);
                if ($_tagName == 'ITEM') {
                    foreach ($_itemsTags as $k2 => $v2) {
                        $xmlRequest->getTag($v2, $_tagName, $_tagAttributes, $_tagContents, $_itemsTags, __ENCODE_RESPONSE);
                        $items[$i][$_tagName] = $_tagContents;
                    }
                }
                if ($_tagName == 'SHIPPING') {
                    foreach ($_itemsTags as $k2 => $v2) {
                        $xmlRequest->getTag($v2, $_tagName, $_tagAttributes, $_tagContents, $_itemsTags, __ENCODE_RESPONSE);
                    $data['SHIPPING'][$i][$_tagName] = $_tagContents;
                    }
                }
                $i++;
            }
        }

        $orders = $this->UpdateOrdersShippingStatus($data['ORDERID'], $storeId);
        $orders_array = $orders->toArray();
        unset($orders);
        if (array_key_exists('items', $orders_array)) {
            $orders_array_w =$orders_array['items'];
        } else {
            $orders_array_w =$orders_array;
        }
        if ($qtyCount == $ItemCount) {
            $xmlResponse->createTag("StatusCode", [], "001", $root, __ENCODE_RESPONSE);
            $xmlResponse->createTag("StatusMessage", [], "Item not found for shipment", $root, __ENCODE_RESPONSE);
            return $xmlResponse->generate();
        }
        $k =0;
        foreach ($data['SHIPPING'] as $shippingcontent) {
            $shipingNo[$k] = $shippingcontent['NUMBER'];
            $trackingCarrier[$k] = $shippingcontent['TRACKINGCARRIER'];
            $k++;
        }
        $RequestOrders = [ 
         "TRACKINGNUMBER" => $shipingNo,
         "SHIPPEDVIA" => $trackingCarrier,
         "SERVICEUSED" => $trackingCarrier
        ];
        if ($shipment = $this->addShipmentByOrder($current_order, $RequestOrders, $itemData)) {
            $msg = 'The shipment has been created.Item quantity is'.$totalQty;
            $xmlResponse->createTag("StatusCode", [], "002", $root, __ENCODE_RESPONSE);
            $xmlResponse->createTag("StatusMessage", [], $msg, $root, __ENCODE_RESPONSE);
            return $xmlResponse->generate();
        } else {
            $xmlResponse->createTag("StatusCode", [], "003", $root, __ENCODE_RESPONSE);
            $xmlResponse->createTag("StatusMessage", [], "The shipment has been failed", $root, __ENCODE_RESPONSE);
            return $xmlResponse->generate();
        }
    }

    public function getVisibilityStatus($username, $password, $storeid, $others)
    {
        $responseArray = [];
        $storeId = $this->getDefaultStore($storeid);
        $status =  $this->checkUser($username, $password);
        if($status != "0") { //login name invalid
            if ($status == "1") {
                $responseArray['StatusCode'] = '1';
                $responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
            }
            if ($status == "2") { //password invalid
                $responseArray['StatusCode'] = '2';
                $responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
            }
        } else {
            $responseArray['StatusCode'] = '0';
            $responseArray['StatusMessage'] = 'All Ok';
            $visibilitystatus = $this->_getvisibilitystatus();
            if (is_array($visibilitystatus)) {
                $i =0;
                foreach ($visibilitystatus as $vstatusKey => $vstatusVal) {
                    $responseArray['VisibilityStatus'][$i]['StatusId'] = $vstatusKey;
                    $responseArray['VisibilityStatus'][$i]['StatusName'] = $vstatusVal;
                    $i++;
                }
            }
        }
        $response = json_encode($responseArray);
        return $this->WgResponse($response);
    }

    public function getOrderStatusForOrder($username, $password, $storeid, $others)
    {
        $storeId = $this->getDefaultStore($storeid);
        $responseArray = [];
        $status =  $this->checkUser($username, $password);
        if ($status != "0") { //login name invalid
            if ($status=="1") {
                $responseArray['StatusCode'] = '1';
                $responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
            }
            if ($status == "2") { //password invalid
                $responseArray['StatusCode'] = '2';
                $responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
            }
        } else {
            $responseArray['StatusCode'] = '0';
            $responseArray['StatusMessage'] = 'All Ok';
            $orderStatus = $this->_getorderstatuses($storeId);
            $invoiceflag = 0;
            foreach ($orderStatus as $id => $val) {
                $responseArray['OrderStatus'][$i]['StatusId'] = $id;
                $responseArray['OrderStatus'][$i]['StatusName'] = $val;
            }
        }
        $response = json_encode($responseArray);
        return $this->WgResponse($response);
    }
    public function parseSpecCharsA($arr)
    {
        foreach ($arr as $k => $v) {
            if (is_array($k)) {
                foreach ($k as $l => $m) {
                    $arr[$l] = addslashes(htmlentities($m, ENT_QUOTES));
                }
            } else {
                $arr[$k] = addslashes(htmlentities($v, ENT_QUOTES));
            }
        }
        return $arr;
    }

    public function _initOrder($id)
   {

        $orderObj = $this->_objectManager->get('\Magento\Sales\Model\Order');
        $order = $orderObj->load($id);
        if (!$order->getId()) {
            return "Error : Order not present";
        }

        return $order;
   }
    /**
     * Cancel order
     */
    public function cancelAction($id)
    {
        if ($order = $this->_initOrder($id)) {
            try {
                if ($order->canCancel()) {
                    $order->cancel()->save();
                    return true;
                } else {
                    return "Error: Order cannot be Cancelled. Please cancel it manually";
                }
            } catch (\Exception $e) {
                return "Error: Order cannot be Cancelled. Please cancel it manually";
            }
        }
   }
    /**
     * Hold order
     */
    public function holdAction($id)
    {
        if ($order = $this->_initOrder($id)) {
            try {
                if ($order->canHold()) {
                    $order->hold()->save();
                    return true;
                } else {
                    return "Error: Order cannot be Holded. Please review it manually";
                }
            } catch (\Exception $e) {
                return "Error: Order cannot be Holded. Please review it manually";
            }
        }
    }
    /**
     * Unhold order
     */
    public function unholdAction($id)
    {
        if ($order = $this->_initOrder($id)) {
            try {
                if ($order->canUnhold()) {
                    $order->unhold()->save();
                    return true;
                } else {
                    return "Error: Order cannot be Un Holded. Please review it manually";
                }
            } catch (\Exception $e) {
                return "Error: Order cannot be Un Holded. Please review it manually";
            }
        }
    }

    public function convertdateformate($shippedOn)
    {
        $shippedOnAry = explode(" ", $shippedOn);
        $shippedOnDate = explode("-", $shippedOnAry['0']);
        $shippedOn = $shippedOnDate['1']."-".$shippedOnDate['2']."-".$shippedOnDate['0'];
        return $shippedOn;
    }

    public function getCcTypeName($ccType)
    {
        return isset($this->types[$ccType]) ? $this->types[$ccType] : false;
    }
   
    public function addOrderShipment($username, $password, $data, $storeid, $download_option_as_item)
    {

        $storeId = $this->getDefaultStore($storeid);        
        $status = $this->checkUser($username, $password);
        $emailAlert = "N";
        if ($status!="0") {     
            return $status;
        }
        $Orders_obj = $this->_objectManager->get('Webgility\EccM2\Model\OrdersShipment');
        $requestArray=$data;

        if (!is_array($requestArray)) {
            $Orders_obj->setStatusCode('9997');
            $Orders_obj->setStatusMessage('Unknown request or request not in proper format');               
            return $this->WgResponse($Items->getItems());
        }

        if (count($requestArray) == 0) {
            $Orders_obj->setStatusCode('9996');
            $Orders_obj->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
            return $this->WgResponse($Items->getItems());
        }
            if (count($requestArray) == 0) {
                $no_orders = true;
            } else {
                $no_orders = false;
            }
            $Orders_obj->setStatusCode('0');
            $Orders_obj->setStatusMessage('All Ok');

            foreach ($requestArray as $orders) {
                foreach ($orders as $order) {

                    $order_id = $order['OrderId'];
                    $order_no=$order['OrderNo'];
                    $Order_obj = $this->_objectManager->get('Webgility\EccM2\Model\OrderShipment');
                    $Order_obj->setOrderId($order['OrderId']);
                    $Order_obj->setOrderNo($order['OrderNo']);

                    if ($order['IsNotifyCustomer'] || $order['IsNotifyCustomer'] == 'Y') {
                        $order['IsNotifyCustomer'] = "Y";
                    }
                    $emailAlert = $order['IsNotifyCustomer'];
                    $orders1 = $this->_UpdateOrdersShippingStatus($order_no, $storeId);
                    $orders_array = $orders1->toArray();

                    if (array_key_exists('items', $orders_array)) {
                        $orders_array_w = $orders_array['items'];
                    } else {
                        $orders_array_w = $orders_array;
                    }

                    foreach ($orders_array_w as $orders_el) {
                            
                        $orderObj = $this->_objectManager->get('\Magento\Sales\Model\Order');
                        $current_order = $orderObj->load($orders_el['entity_id']);
                        $item_array = $this->getorderitems($orders_el["entity_id"], "");
                        $item_array = $item_array['items'];
                        $product_type_bundle = false;
                        $product_type_configurable = false;
                        $attributeValue_yes = false;
                        foreach ($item_array as $item) {
                       
                            $product_type=$item['product_type'];
                            if ($product_type == "bundle") {
                                $product_type_bundle = true;
                            }
                            if ($product_type == "configurable") {
                                $product_type_configurable = true;
                            }   
                            $product_id = $item['product_id'];              
                        }
                        if (!$current_order->canShip() || $attributeValue_yes) {
                                $result = "Order cannot be shipped.Either its shipment is already created or there is other problem. Please review manually.";
                            foreach ($order['Shipments'] as $shipment) {
                                $ship_id=$shipment['ShipmentID'];
                            }
                            $emailAlert = "N";
                            $ShipmentObj = $this->_objectManager->get('Webgility\EccM2\Model\Shipment');
                            $ShipmentObj->setShipmentID($ship_id);
                            $ShipmentObj->setStatus($result);
                            $Order_obj->setShipments($ShipmentObj->getShipment());
                        } elseif ($download_option_as_item && ($product_type_bundle || $product_type_configurable)) {
                            $result = "Download option as item cannot be shipped.Please review manually.";
                            $emailAlert = "N";
                            foreach ($order['Shipments'] as $shipment) {
                                $ship_id = $shipment['ShipmentID'];
                            }
                            $ShipmentObj = $this->_objectManager->get('Webgility\EccM2\Model\Shipment');
                            $ShipmentObj->setShipmentID($ship_id);
                            $ShipmentObj->setStatus($result);
                            $Order_obj->setShipments($ShipmentObj->getShipment());
                        } elseif ($current_order->canShip()) {
                            $current_order->setTotal_paid($orders_el['grand_total']);
                            $current_order->setBase_total_paid($orders_el['base_grand_total']);
                            $current_order->setTotal_invoiced($orders_el['grand_total']);
                            $current_order->setBase_total_invoiced($orders_el['base_grand_total']);
                            $current_order->setDiscount_invoiced($orders_el['discount_amount']);
                            $current_order->setBase_discount_invoiced($orders_el['base_discount_amount']);
                            $current_order->setSubtotal_invoiced($orders_el['subtotal']);
                            $current_order->setTax_invoiced($orders_el['tax_amount']);
                            $current_order->setShipping_invoiced($orders_el['shipping_amount']);
                            $current_order->setBase_subtotal_invoiced($orders_el['base_subtotal']);
                            $current_order->setBase_tax_invoiced($orders_el['base_tax_amount']);
                            $current_order->setBase_shipping_invoiced($orders_el['base_shipping_amount']);
                            foreach ($order['Shipments'] as $shipment) {
                                $tracking_num=$shipment['TrackingNumber'];
                                $method = $shipment['Method'];
                                $carrier=$shipment['Carrier'];
                                $ship_id = $shipment['ShipmentID'];
                                $RequestOrders = [
                                "TRACKINGNUMBER" => $tracking_num,
                                "SHIPPEDVIA" => $carrier,
                                "SERVICEUSED"=> $method 
                                ];
                                foreach ($shipment['Items'] as $item) {
                                    $item_qty_shipped=$item['ItemQtyShipped'];
                                    $item_name = $item['ItemName'];
                                    $item_sku = $item['ItemSku'];
                                    $item_id = $item['ItemID'];
                                    $data['items'][$item_id] = $item_qty_shipped;
                                }//end foreach Items    
                                if ($shipment_array = $this->_initShipment($current_order, $RequestOrders, $data)) {                
                                    $shipment_array->register();
                                    #make second param true to notify customer.
                                    $shipment_array->addComment($info, true);
                                    if ($emailAlert == "Y") {
                                        $shipment_array->sendEmail(true);
                                        $shipment_array->setEmailSent(true);    
                                    } else {
                                        $shipment_array->sendEmail(false);
                                        $shipment_array->setEmailSent(false);
                                    }
                                    $shipment_arr = $this->_saveShipment($shipment_array);
                                }   
                                $result = "Success";        
                            }
                        }// end elseif
                    }// end foreach orders_array_w
                        $ShipmentObj = $this->_objectManager->get('Webgility\EccM2\Model\Shipment');
                        //
                        $ShipmentObj->setShipmentID($ship_id);
                        $ShipmentObj->setStatus($result);
                        $Order_obj->setShipments($ShipmentObj->getShipment());
                        $Orders_obj->setOrder($Order_obj->getShipments());
                }// end foreach orders
            }// end for each requestArray
            return $this->WgResponse($Orders_obj->getOrdersShipment());
   }
}
