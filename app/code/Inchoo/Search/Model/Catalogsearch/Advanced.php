<?php

namespace Inchoo\Search\Model\Catalogsearch;

use Magento\CatalogSearch\Model\Advanced as CatalogSearchAdvanced;
use Magento\Framework\Registry;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\CatalogSearch\Model\AdvancedFactory as AdvancedCollectionFactory;
use Magento\Catalog\Model\Config as CatalogConfig;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
//use Magento\Catalog\Model\Layer as CatalogLayer;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as ProductAttributeCollectionFactory;
use Magento\Eav\Model\Entity\AttributeFactory as AttributeCollectionFactory;
use Magento\Framework\App\Request\Http as RequestHttp;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Inchoo\Search\Helper\Data as SearchDataHelper;
use Inchoo\Search\Helper\Search as SearchSearchHelper;

class Advanced extends CatalogSearchAdvanced {

    protected $_searchVehicle, $_registry, $_scopeConfig, $_dateTime, $_categoryFactory, $_eavConfig, $_productFactory, $_request, $_logger;
    protected $_productStatus, $_advancedCollectionFactory, $_catalogConfig, $_productVisibility, $_catalogLayer, $_attributeCollectionFactory;
    protected $_customerSession, $_searchDataHelper, $_searchSearchHelper;

    public function __construct(CategoryFactory $categoryFactory, ProductFactory $productFactory, EavConfig $eavConfig, Registry $registry, ScopeConfigInterface $scopeConfig, DateTime $dateTime, ProductStatus $productStatus, AdvancedCollectionFactory $advancedCollectionFactory, CatalogConfig $catalogConfig, ProductVisibility $productVisibility, AttributeCollectionFactory $attributeCollectionFactory, RequestHttp $request, LoggerInterface $logger, CustomerSession $customerSession, ProductAttributeCollectionFactory $productAttributeCollectionFactory, SearchDataHelper $searchDataHelper, SearchSearchHelper $searchSearchHelper) {

        $this->_registry = $registry;
        $this->_scopeConfig = $scopeConfig;
        $this->_dateTime = $dateTime;

        $this->_categoryFactory = $categoryFactory;

        $this->_eavConfig = $eavConfig;

        $this->_productFactory = $productFactory;
        $this->_productStatus = $productStatus;
        $this->_advancedCollectionFactory = $advancedCollectionFactory;

        $this->_catalogConfig = $catalogConfig;
        $this->_productVisibility = $productVisibility;

        $this->_catalogLayer = $productAttributeCollectionFactory;
        $this->_attributeCollectionFactory = $attributeCollectionFactory;

        $this->_request = $request;

        $this->_logger = $logger;

        $this->_customerSession = $customerSession;

        $this->_searchDataHelper = $searchDataHelper;
        $this->_searchSearchHelper = $searchSearchHelper;
    }

    public function getDbConnection() {
        $dbConnection = $this->_searchDataHelper->dbConnection();

        return $dbConnection;
    }

    /**
     * Returns prepared search criterias in text
     *
     * @return array
     */
    public function getSearchCriterias() {
        $searchType = $this->_registry->registry('catalog_part_search_type') ?: $this->_request->getControllerName();
        if ($searchType == 'results') {
            $search = $this->_searchCriterias;

            $year = (int) $_GET['year'];
            $make = (int) $_GET['make'];
            $model = (int) $_GET['model'];
            $submodel = (int) $_GET['submodel'];
            $categoryId = (int) @$_GET['cat'];

            $read = $this->getDbConnection(); //Zend_Db::factory('Pdo_Mysql', $dbConfig);
            $makename = $read->fetchCol(sprintf('SELECT MakeName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                            . ' WHERE YearID=%d and MakeID=%d'
                            . ' GROUP BY MakeID', $year, $make
            ));
            $modelname = $read->fetchCol(sprintf('SELECT ModelName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                            . ' WHERE YearID=%d and MakeID=%d and ModelID=%d'
                            . ' GROUP BY ModelID', $year, $make, $model
            ));
            $submodelname = $read->fetchCol(sprintf('SELECT SubModelName  FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                            . ' WHERE YearID=%d and MakeID=%d and ModelID=%d and SubModelID=%d'
                            . ' GROUP BY ModelID', $year, $make, $model, $submodel
            ));
            $search[] = array('name' => 'Year', 'value' => $year);
            if (isset($makename[0]))
                $search[] = array('name' => 'Make', 'value' => $makename[0]);
            if (isset($modelname[0]))
                $search[] = array('name' => 'Model', 'value' => $modelname[0]);
            if (isset($submodelname[0]))
                $search[] = array('name' => 'Sub Model', 'value' => $submodelname[0]);
            if ($categoryId) {
                $_category = $this->_categoryFactory->create()->load($categoryId);
                $search[] = array('name' => 'Category', 'value' => $_category->getName());
            }
            return $search;
        }
        return $this->_searchCriterias;
    }

    public function getAttributeName() {
        $params = $this->getRequest()->getParams();
        $matchcase = array();
        if (!empty($params)) {
            foreach ($params as $key => $getatt) {
                $attr = $this->_eavConfig->getAttribute('catalog_product', $key);
                if ($attr->usesSource()) {
                    $label = $attr->getSource()->getOptionText($getatt);
                }
                if ($attr->getId()) {
                    $options = $this->_eavConfig->getAttribute('catalog_product', $key)->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        $optioninfo = array(
                            $option['label'] => $option['value']
                        );

                        $searchAttribute = $this->key_value_pair_exists($optioninfo, $label, $getatt);
                        if ($searchAttribute) {
                            $matchcase[] = array(
                                'attribute' => $key,
                                array('in' => array($getatt))
                            );
                        }
                    }
                }
            }
        }
        return $matchcase;
    }

    public function getFilteredAttributes() {
        $params = $this->getRequest()->getParams();
        $attributes = array();
        if (!empty($params)) {
            foreach ($params as $key => $getatt) {
                if ($key == 'criteria') {
                    continue;
                }
                $attr = $this->_eavConfig->getAttribute('catalog_product', $key);
                if ($attr->usesSource()) {
                    $label = $attr->getSource()->getOptionText($getatt);
                }
                if ($attr->getId()) {
                    $options = $this->_eavConfig->getAttribute('catalog_product', $key)->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        $optioninfo = array(
                            $option['label'] => $option['value']
                        );

                        $searchAttribute = $this->key_value_pair_exists($optioninfo, $label, $getatt);
                        if ($searchAttribute) {
                            $attributes[] = array($key => $getatt);
                        }
                    }
                }
            }
            return $attributes;
        }
    }

    public function getFilterableAttributes() {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $productAttributes */
        $productAttributes = $this->_catalogLayer->create();
        $productAttributes->addFieldToFilter(
                ['is_filterable', 'is_filterable_in_search'], [[1, 2], 1]
        );

        return $productAttributes;
    }

    public function key_value_pair_exists(array $haystack, $key, $value) {
        return array_key_exists($key, $haystack) && $haystack[$key] == $value;
    }

    public function getProductCollection() {
        $year = (int) $_GET['year'];
        $make = (int) $_GET['make'];
        $model = (int) $_GET['model'];
        $submodel = (int) $_GET['submodel'];
        $categoryId = (int) @$_GET['cat'];
        $criteria = isset($_GET['criteria']) ? $_GET['criteria'] : '';
        $searchType = $this->_registry->registry('catalog_part_search_type') ?: $this->_request->getControllerName();

        $read = $this->getDbConnection();
        if ($searchType == 'results' && is_null($this->_productCollection)) {
            if ($criteria) {
                $attributes = $read->fetchAll(sprintf('SELECT AttributeType, AttributeValue'
                                . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                                . ' JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid'
                                . ' WHERE YearID=%d and MakeID=%d and ModelID=%d and SubModelID=%d and AdditionalCriteria_PartMasterID=%d', $year, $make, $model, $submodel, $criteria
                ));
            } else {
                $attributes = $read->fetchAll(sprintf('SELECT AttributeType, AttributeValue'
                                . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                                . ' JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid'
                                . ' WHERE YearID=%d and MakeID=%d and ModelID=%d and SubModelID=%d', $year, $make, $model, $submodel
                ));
            }
            $this->_logger->log(sprintf('SELECT AttributeType, AttributeValue'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid'
                            . ' WHERE YearID=%d and MakeID=%d and ModelID=%d and SubModelID=%d', $year, $make, $model, $submodel
                    ), null, 'query.log');

            if (!empty($filteredAttribute)) {
                $filteredAttributeProduct = $this->getFilteredProduct($filteredAttribute, $attributes);
            } else {
                $attribute_arr = array();
                $categoryIds = array(0);

                if (count($attributes)) {
                    foreach ($attributes as $attribute) {
                        $attribute_arr[$attribute['AttributeType']][] = $attribute['AttributeValue'];
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(54)) {
                    $categoryIds = array(54);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_nut_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Thread'])) {
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_thread')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Thread']))) {
                            $lug_thread_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(56)) {
                    $categoryIds = array(56);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_bolt_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(59)) {
                    $categoryIds = array(59);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {

                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_studs_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(57)) {
                    $categoryIds = array(57);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_wheel_locks_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Hub Bore']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(61)) {
                    $categoryIds = array(61);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'center_bore')->getSource()->getAllOptions();
                    foreach ($options as $option) {

                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Hub Bore']))) {
                            $center_bore[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(5)) {
                    $categoryIds = array(5);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                            $adapter_vehicle_bolt_pattern_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(72)) {
                    $categoryIds = array(72);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                            $hub_centric_spacers_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(76)) {
                    $categoryIds = array(76);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                            $wheel_spacers_optionIds[] = (int) $option['value'];
                            break;
                        }
                    }
                }

                $this->_productCollection = $this->_advancedCollectionFactory->create()
                        ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                        ->addMinimalPrice()
                        ->addStoreFilter();

                $this->_productStatus->addVisibleFilterToCollection($this->_productCollection);
                $this->_productVisibility->addVisibleInSearchFilterToCollection($this->_productCollection);
                if ($year && $make && $model && $submodel) {
                    if (in_array($categoryId, \Inchoo\Search\Model\Catalogsearch\Category::ids([18, 20, 21, 22, 33]))) {
                        $categoryIds = [$categoryId];
                        $filters = \Inchoo\Search\Model\Search::getSkus();
                    } else {
                        $filters[] = array('attribute' => 'sku');
                    }
                    if (isset($attribute_arr['Lug']) && isset($lug_nut_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(54)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_nut_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Lug']) && isset($lug_bolt_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(56)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_bolt_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Lug']) && isset($lug_studs_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(59)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_studs_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Lug']) && isset($lug_wheel_locks_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(57)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_wheel_locks_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Hub Bore']) && isset($center_bore) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(61)
                    ) {
                        $filters[] = array('attribute' => 'center_bore', array('in' => $center_bore));
                    }

                    if (isset($attribute_arr['Bolt Pattern']) && isset($adapter_vehicle_bolt_pattern_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(5)
                    ) {
                        $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $adapter_vehicle_bolt_pattern_optionIds));
                    }

                    if (isset($attribute_arr['Bolt Pattern']) && isset($hub_centric_spacers_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(72)
                    ) {
                        $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $hub_centric_spacers_optionIds));
                    }

                    if (isset($attribute_arr['Bolt Pattern']) && isset($wheel_spacers_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(76)
                    ) {
                        $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', 'finset' => $wheel_spacers_optionIds);
                    }

                    if ($categoryId) {
                        $this->_productCollection
                                ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                                ->addAttributeToFilter('category_id', array('in' => array($categoryIds)));
                    }

                    if (count($filters)) {
                        $this->_productCollection->addAttributeToFilter($filters, '', 'left');
                    } else {
                        $this->_productCollection->getSelect()->where('e.entity_id IS NULL');
                    }


                    if (!is_null($this->_productCollection)) {
                        $mygarage = $this->_customerSession->getMyGarage();
                        $makename = $read->fetchCol(sprintf('SELECT MakeName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                                        . ' WHERE YearID=%d AND MakeID=%d'
                                        . ' GROUP BY MakeID', $year, $make
                        ));
                        $modelname = $read->fetchCol(sprintf('SELECT ModelName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                                        . ' WHERE YearID=%d AND MakeID=%d AND ModelID=%d'
                                        . ' GROUP BY ModelID', $year, $make, $model
                        ));
                        $submodelname = $read->fetchCol(sprintf('SELECT SubModelName'
                                        . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                                        . ' WHERE YearID=%d AND MakeID=%d AND ModelID=%d AND SubModelID=%d'
                                        . ' GROUP BY SubModelID', $year, $make, $model, $submodel
                        ));


                        if (isset($makename[0]) && isset($modelname[0]) && isset($submodelname[0])) {
                            if ($criteria) {
                                $mygarage["year=$year&make=$make&model=$model&submodel=$submodel&criteria=$criteria"] = "$year $makename[0] $modelname[0] $submodelname[0] (w/ Additional Criteria)";
                            } else {
                                $mygarage["year=$year&make=$make&model=$model&submodel=$submodel"] = "$year $makename[0] $modelname[0] $submodelname[0]";
                            }
                            $mygarage = array_unique($mygarage);
                            $this->_customerSession->setMyGarage($mygarage);
                        }
                    }
                }
            }
        } else if (is_null($this->_productCollection)) {
            $collection = $this->_engine->getAdvancedResultCollection();
            $this->prepareProductCollection($collection);
            if (!$collection) {
                return $collection;
            }
            $this->_productCollection = $collection;
        }

        $params = $this->getRequest()->getParams();
        $params_not_in_params = array('year', 'make', 'model', 'submodel', 'cat', 'criteria', 'debug');


        foreach ($params as $key => $param) {
            if (!in_array($key, $params_not_in_params)) {
                $this->_productCollection->addAttributeToFilter(array(
                    array('attribute' => $key, 'eq' => $param)
                ));
            }
        }

        return $this->_productCollection;
    }

    public function getPdqProductCollection() {
        $year = (int) $_GET['year'];
        $make = (int) $_GET['make'];
        $model = (int) $_GET['model'];
        $submodel = (int) $_GET['submodel'];
        $categoryId = (int) @$_GET['cat'];
        $criteria = isset($_GET['criteria']) ? $_GET['criteria'] : '';
        $searchType = $this->_registry->registry('catalog_part_search_type') ?: $this->_request->getControllerName();

        $read = $this->getDbConnection(); // Zend_Db::factory('Pdo_Mysql', $dbConfig);
        if ($searchType == 'results' && is_null($this->_productCollection)) {
            if ($criteria) {
                $attributes = $read->fetchAll('SELECT AttributeType, AttributeValue FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid WHERE YearID =' . $year . ' and MakeID = ' . $make . ' and ModelID = ' . $model . ' and SubModelID = ' . $submodel);
            } else {
                $attributes = $read->fetchAll('SELECT AttributeType, AttributeValue FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid WHERE YearID =' . $year . ' and MakeID = ' . $make . ' and ModelID = ' . $model . ' and SubModelID = ' . $submodel . ' and AdditionalCriteria_PartMasterID = ' . $criteria);
            }

            $filteredAttribute = $this->getAttributeName();

            if (!empty($filteredAttribute)) {
                $filteredAttributeProduct = $this->getFilteredProduct($filteredAttribute, $attributes);
            } else {
                $attribute_arr = array();
                $categoryIds = array(0);

                foreach ($attributes as $attribute) {
                    $attribute_arr[$attribute['AttributeType']][] = $attribute['AttributeValue'];
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(54)) {
                    $categoryIds = array(54);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_nut_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Thread'])) {
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_thread')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Thread']))) {
                            $lug_thread_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(56)) {
                    $categoryIds = array(56);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_bolt_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(59)) {
                    $categoryIds = array(59);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {

                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_studs_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(57)) {
                    $categoryIds = array(57);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                            $lug_wheel_locks_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Hub Bore']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(61)) {
                    $categoryIds = array(61);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'center_bore')->getSource()->getAllOptions();
                    foreach ($options as $option) {

                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Hub Bore']))) {
                            $center_bore[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(5)) {
                    $categoryIds = array(5);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                            $adapter_vehicle_bolt_pattern_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(72)) {
                    $categoryIds = array(72);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                            $hub_centric_spacers_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(76)) {
                    $categoryIds = array(76);
                    $collection = $this->_productFactory->create()->getCollection();
                    $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                            $wheel_spacers_optionIds[] = $option['value'];
                            break;
                        }
                    }
                }

                $this->_productCollection = $this->_advancedCollectionFactory->create()
                        ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                        ->addMinimalPrice()
                        ->addStoreFilter();

                $this->_productStatus->addVisibleFilterToCollection($this->_productCollection);
                $this->_productVisibility->addVisibleInSearchFilterToCollection($this->_productCollection);

                if (!empty($_GET['year']) && !empty($_GET['make']) && !empty($_GET['model']) && !empty($_GET['submodel'])) {
                    $filters = \Inchoo\Search\Model\Search::getSkus();
                    if (isset($attribute_arr['Lug']) && isset($lug_nut_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(54)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_nut_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Lug']) && isset($lug_bolt_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(56)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_bolt_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Lug']) && isset($lug_studs_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(59)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_studs_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Lug']) && isset($lug_wheel_locks_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(57)
                    ) {
                        $filters[] = array('attribute' => 'lug_', array('in' => $lug_wheel_locks_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                    }

                    if (isset($attribute_arr['Hub Bore']) && isset($center_bore) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(61)
                    ) {
                        $filters[] = array('attribute' => 'center_bore', array('in' => $center_bore));
                    }

                    if (isset($attribute_arr['Bolt Pattern']) && isset($adapter_vehicle_bolt_pattern_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(5)
                    ) {
                        $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $adapter_vehicle_bolt_pattern_optionIds));
                    }

                    if (isset($attribute_arr['Bolt Pattern']) && isset($hub_centric_spacers_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(72)
                    ) {
                        $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $hub_centric_spacers_optionIds));
                    }

                    if (isset($attribute_arr['Bolt Pattern']) && isset($wheel_spacers_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(76)
                    ) {
                        $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', 'finset' => $wheel_spacers_optionIds);
                    }

                    $this->_productCollection
                            ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                            ->addAttributeToFilter('category_id', array('in' => \Inchoo\Search\Model\Catalogsearch\Category::ids([18, 20, 21, 22, 33])));

                    if (count($filters)) {
                        $this->_productCollection->addAttributeToFilter($filters, '', 'left');
                    } else {
                        $this->_productCollection->getSelect()->where('e.entity_id IS NULL');
                    }

                    if (!is_null($this->_productCollection)) {
                        $mygarage = $this->_customerSession->getMyGarage();
                        $makename = $read->fetchCol('SELECT MakeName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                                . ' WHERE YearID=' . (int) $_GET['year'] . ' and MakeID=' . (int) $_GET['make']
                                . ' GROUP BY MakeID');
                        $modelname = $read->fetchCol('SELECT ModelName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                                . ' WHERE YearID=' . (int) $_GET['year'] . ' and MakeID=' . (int) $_GET['make'] . ' and ModelID=' . (int) $_GET['model']
                                . ' GROUP BY ModelID');
                        $submodelname = $read->fetchCol('SELECT SubModelName'
                                . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                                . ' WHERE YearID=' . (int) $_GET['year'] . ' and MakeID=' . (int) $_GET['make']
                                . ' and ModelID=' . (int) $_GET['model'] . ' and SubModelID=' . (int) $_GET['submodel']
                                . ' GROUP BY ModelID');
                        if (isset($makename[0]) && isset($modelname[0]) && isset($submodelname[0])) {
                            if ($criteria) {
                                $mygarage["year=$year&make=$make&model=$model&submodel=$submodel&criteria=$criteria"] = "$year $makename[0] $modelname[0] $submodelname[0] (w/ Additional Criteria)";
                            } else {
                                $mygarage["year=$year&make=$make&model=$model&submodel=$submodel"] = "$year $makename[0] $modelname[0] $submodelname[0]";
                            }
                            $mygarage = array_unique($mygarage);
                            $this->_customerSession->setMyGarage($mygarage);
                        }
                    }
                }
            }
        } else if (is_null($this->_productCollection)) {
            $collection = $this->_engine->getAdvancedResultCollection();
            $this->prepareProductCollection($collection);
            if (!$collection) {
                return $collection;
            }
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    public function getFilteredProduct($filteredAttribute, $attributes) {
        $categoryId = (int) @$_GET['cat'];
        $url = $_SERVER["REQUEST_URI"];
        $inchooSearchHelper = \Inchoo\Search\Helper\Search;
        $query_params = $inchooSearchHelper->getQueryParamsByUrl($url);

        $attribute_arr = array();
        $categoryIds = array(0);

        foreach ($attributes as $attribute) {
            $attribute_arr[$attribute['AttributeType']][] = $attribute['AttributeValue'];
            if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(54)
            ) {
                $categoryIds = [$categoryId];
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                        $lug_nut_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Thread'])) {
                $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_thread')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Thread']))) {
                        $lug_thread_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(56)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([56]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                        $lug_bolt_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(59)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([59]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                foreach ($options as $option) {

                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                        $lug_studs_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Lug']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(57)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([57]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                        $lug_wheel_locks_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Hub Bore']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(61)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([61]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'center_bore')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Hub Bore']))) {
                        $center_bore[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(5)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([5]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                        $adapter_vehicle_bolt_pattern_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(72)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([72]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                        $hub_centric_spacers_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }

            if (isset($attribute_arr['Bolt Pattern']) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(76)
            ) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([76]);
                $collection = $this->_productFactory->create()->getCollection();
                $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                foreach ($options as $option) {
                    if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                        $wheel_spacers_optionIds[] = (int) $option['value'];
                        break;
                    }
                }
            }
        }

        $this->_productCollection = $_advancedCollectionFactory
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->addMinimalPrice()
                ->addStoreFilter();
        $this->_productStatus->addVisibleFilterToCollection($this->_productCollection);
        $this->_productVisibility->addVisibleInSearchFilterToCollection($this->_productCollection);
        if ($categoryId && in_array($categoryId, \Inchoo\Search\Model\Catalogsearch\Category::ids([18, 20, 21, 22, 33]))) {
            $categoryIds = array($categoryId);
            $filters = $inchooSearchHelper->getSkus();
        } else {
            $filters[] = array('attribute' => 'sku');
        }
        if (isset($attribute_arr['Lug']) && isset($lug_nut_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(54)
        ) {
            $filters[] = array('attribute' => 'lug_', array('in' => $lug_nut_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
        }
        if (isset($attribute_arr['Lug']) && isset($lug_bolt_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(56)
        ) {
            $filters[] = array('attribute' => 'lug_', array('in' => $lug_bolt_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
        }
        if (isset($attribute_arr['Lug']) && isset($lug_studs_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(59)
        ) {
            $filters[] = array('attribute' => 'lug_', array('in' => $lug_studs_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
        }
        if (isset($attribute_arr['Lug']) && isset($lug_wheel_locks_optionIds) && isset($lug_thread_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(57)
        ) {
            $filters[] = array('attribute' => 'lug_', array('in' => $lug_wheel_locks_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
        }
        if (isset($attribute_arr['Hub Bore']) && isset($center_bore) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(61)
        ) {
            $filters[] = array('attribute' => 'center_bore', array('in' => $center_bore));
        }
        if (isset($attribute_arr['Bolt Pattern']) && isset($adapter_vehicle_bolt_pattern_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(5)
        ) {
            $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $adapter_vehicle_bolt_pattern_optionIds));
        }
        if (isset($attribute_arr['Bolt Pattern']) && isset($hub_centric_spacers_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(72)
        ) {
            $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $hub_centric_spacers_optionIds));
        }
        if (isset($attribute_arr['Bolt Pattern']) && isset($wheel_spacers_optionIds) && $categoryId == \Inchoo\Search\Model\Catalogsearch\Category::id(76)
        ) {
            $filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $wheel_spacers_optionIds));
        }
        if ($categoryId) {
            $this->_productCollection
                    ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                    ->addAttributeToFilter('category_id', array('in' => array($categoryIds)));
        }

        foreach ($query_params as $query_key => $query_val) {
            $this->_productCollection->addAttributeToFilter($query_key, array('eq' => (int) $query_val));
        }

        if (count($filters)) {
            $this->_productCollection->addAttributeToFilter($filters, '', 'left');
        } else {
            $this->_productCollection->getSelect()->where('e.entity_id IS NULL');
        }

        return $this->_productCollection;
    }

    public function getCatalogLayerAttributes() {
        $filterableAttributes = ObjectManager::getInstance()->get(\Magento\Catalog\Model\Layer\Category\FilterableAttributeList::class);

        $appState = ObjectManager::getInstance()->get(\Magento\Framework\App\State::class);
        $layerResolver = ObjectManager::getInstance()->get(\Magento\Catalog\Model\Layer\Resolver::class);
        $filterList = ObjectManager::getInstance()->create(
                \Magento\Catalog\Model\Layer\FilterList::class, [
            'filterableAttributes' => $filterableAttributes
                ]
        );

        $category = $this->_categoryFactory->create()->load($_GET['cat']);

        $appState->setAreaCode('frontend');
        $layer = $layerResolver->get();
        $layer->setCurrentCategory($category);
        $attributes = $filterList->getFilters($layer);

        return $attributes;
    }

    public function getAllAttributes() {
        $selectedFilteredAttribute = $this->getFilteredAttributes();
//        $layer = $this->_catalogLayer;
//        $category = $this->_categoryFactory->create()->load($_GET['cat']);
//        $layer->setCurrentCategory($category);
        $attributes = $this->getFilterableAttributes(); //$layer->getFilterableAttributes();
        $attributeData = array();
        foreach ($attributes as $attribute) {
            if (!$this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode())) {
                $attributeData[] = array('label' => $attribute->getFrontendLabel(), 'code' => $attribute->getAttributecode());
            }
        }
        return $attributeData;
    }

    public function getAllSelectedAtt() {
        $selectedFilteredAttribute = $this->getFilteredAttributes();
        $layer = $this->_catalogLayer;
        $category = $this->_categoryFactory->create()->load($_GET['cat']);
        $layer->setCurrentCategory($category);
        $attributes = $layer->getFilterableAttributes();

        $attributeData = array();
        foreach ($attributes as $attribute) {
            if ($this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode())) {
                $key = $this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode());
                $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute->getAttributecode());
                if ($attribute->usesSource()) {
                    $options = $attribute->getSource()->getAllOptions(false);
                    foreach ($options as $option) {
                        $value = array_keys($key);
                        $attrbuteId = $key[$value[0]];
                        if ($option['value'] == $attrbuteId) {
                            $attributeValue = $option['label'];
                        }
                    }
                }
                $attributeData[] = array(
                    'label' => $attribute->getFrontendLabel(),
                    'code' => $attribute->getAttributecode(),
                    'code_value' => $option['value'],
                    'attributeValue' => $attributeValue
                );
            }
        }
        return $attributeData;
    }

    public function getPdqSelectedAtt() {
        $selectedFilteredAttribute = $this->getFilteredAttributes();

        $layer = $this->_catalogLayer;
        $attributes = $layer->getFilterableAttributes();
        $attributeData = array();
        foreach ($attributes as $attribute) {
            if ($this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode())) {
                $key = $this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode());
                $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute->getAttributecode());
                if ($attribute->usesSource()) {
                    $options = $attribute->getSource()->getAllOptions(false);
                    foreach ($options as $option) {
                        $value = array_keys($key);
                        $attrbuteId = $key[$value[0]];
                        if ($option['value'] == $attrbuteId) {
                            $attributeValue = $option['label'];
                        }
                    }
                }
                $attributeData[] = array(
                    'label' => $attribute->getFrontendLabel(),
                    'code' => $attribute->getAttributecode(),
                    'code_value' => $option['value'],
                    'attributeValue' => $attributeValue
                );
            }
        }
        return $attributeData;
    }

    public function getSelectedAttribute() {
        $selectedFilteredAttribute = $this->getFilteredAttributes();
        $layer = $this->_catalogLayer;
        $attributes = $layer->getFilterableAttributes();
        $attributeData = array();
        foreach ($attributes as $attribute) {
            if ($this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode())) {
                $key = $this->multiKeyExists($selectedFilteredAttribute, $attribute->getAttributecode());
                $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute->getAttributecode());
                if ($attribute->usesSource()) {
                    $options = $attribute->getSource()->getAllOptions(false);
                    foreach ($options as $option) {
                        $value = array_keys($key);
                        $attrbuteId = $key[$value[0]];
                        if ($option['value'] == $attrbuteId) {
                            $attributeValue = $option['label'];
                        }
                    }
                }
                $attributeData[] = array(
                    'label' => $attribute->getFrontendLabel(),
                    'code' => $attribute->getAttributecode(),
                    'code_value' => $option['value'],
                    'attributeValue' => $attributeValue
                );
            }
        }
        return $attributeData;
    }

    public function multiKeyExists(array $arr, $key) {
        if (array_key_exists($key, $arr)) {
            return $arr;
        }

        foreach ($arr as $element) {
            if (is_array($element)) {
                if ($this->multiKeyExists($element, $key)) {
                    return $element;
                }
            }
        }
        return false;
    }

    public function sideBarupdate() {
        $params = $this->getRequest()->getParams();

        if (isset($params['lug_style'])) {
            $lug_style_optionId = $params['lug_style'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_style')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_style_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['make_style'])) {
            $make_style_optionId = $params['make_style'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('make_style')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($make_style_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_wrench'])) {
            $lug_wrench_optionId = $params['lug_wrench'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_wrench')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_wrench_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_length'])) {
            $lug_length_optionId = $params['lug_length'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_length')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_length_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_package'])) {
            $lug_package_optionId = $params['lug_package'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_package')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_package_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_thread'])) {
            $lug_thread_optionId = $params['lug_thread'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_thread')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_thread_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_finish'])) {
            $lug_finish_optionId = $params['lug_finish'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_finish')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_finish_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_bolt_length'])) {
            $lug_bolt_length_optionId = $params['lug_bolt_length'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_bolt_length')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_bolt_length_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['lug_shank'])) {
            $lug_shank_optionId = $params['lug_shank'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('lug_shank')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($lug_shank_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_drill_type'])) {
            $adapter_drill_type_optionId = $params['adapter_drill_type'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_drill_type')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($adapter_drill_type_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_vehicle_bolt_pattern'])) {
            $adapter_vehicle_bolt_pattern_optionId = $params['adapter_vehicle_bolt_pattern'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_vehicle_bolt_pattern')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => '4 x 100mm' //$attributeInfo->getSource()->getOptionText($adapter_vehicle_bolt_pattern_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_wheel_bolt_pattern'])) {
            $adapter_wheel_bolt_pattern_optionId = $params['adapter_wheel_bolt_pattern'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_wheel_bolt_pattern')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($adapter_wheel_bolt_pattern_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_thickness'])) {
            $adapter_thickness_optionId = $params['adapter_thickness'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_thickness')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($adapter_thickness_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_id_vehicle'])) {
            $adapter_id_vehicle_optionId = $params['adapter_id_vehicle'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_id_vehicle')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($adapter_id_vehicle_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_od_wheel'])) {
            $adapter_od_wheel_optionId = $params['adapter_od_wheel'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_od_wheel')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($adapter_od_wheel_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['adapter_stud_thread'])) {
            $adapter_stud_thread_optionId = $params['adapter_stud_thread'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('adapter_stud_thread')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($adapter_stud_thread_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['mnf_process'])) {
            $mnf_process_optionId = $params['mnf_process'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('mnf_process')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($mnf_process_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['spacer_thickness'])) {
            $spacer_thickness_optionId = $params['spacer_thickness'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('spacer_thickness')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($spacer_thickness_optionId)
            );
            return $vehicalinfo;
        }

        if (isset($params['spacer_od'])) {
            $spacer_od_optionId = $params['spacer_od'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('spacer_od')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($spacer_od_optionId)
            );
            return $vehicalinfo;
        }


        if (isset($params['spacer_id'])) {
            $spacer_id_optionId = $params['spacer_id'];
            $attributeInfo = $this->_attributeCollectionFactory->create()
                    ->setCodeFilter('spacer_id')
                    ->getFirstItem();

            $vehicalinfo = array(
                'vehicaltype' => $attributeInfo->getFrontendLabel(),
                'vehicalname' => $attributeInfo->getSource()->getOptionText($spacer_id_optionId)
            );
            return $vehicalinfo;
        }
    }

    public function getCategories() {


        $params = $this->_request->getParams();
        if (!isset($params['year']) || !isset($params['make']) || !isset($params['model']) || !isset($params['submodel'])) {
            return false;
        }
        $searchType = $this->_registry->registry('catalog_part_search_type') ?: $this->_request->getControllerName();

        $year = (int) $params['year'];
        $make = (int) $params['make'];
        $model = (int) $params['model'];
        $submodel = (int) $params['submodel'];
        $criteria = isset($params['criteria']) ? $params['criteria'] : '';

        $read = $this->getDbConnection();

        $makename = $read->fetchCol(sprintf('SELECT MakeName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' WHERE YearID=%d and MakeID=%d GROUP BY MakeID', $year, $make
        ));
        $modelname = $read->fetchCol(sprintf('SELECT ModelName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' WHERE YearID=%d and MakeID=%d and ModelID=%d GROUP BY ModelID', $year, $make, $model
        ));

        $submodelname = $read->fetchCol(sprintf('SELECT SubModelName FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                        . ' WHERE YearID=%d and MakeID=%d and ModelID=%d and SubModelID=%d'
                        . ' GROUP BY ModelID', $year, $make, $model, $submodel
        ));

        if (is_null($this->_productCollection)) {
            if ($criteria) {
                $attributes = $read->fetchAll(sprintf('SELECT AttributeType, AttributeValue'
                                . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                                . ' JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid'
                                . ' WHERE YearID=%d and MakeID = %d and ModelID = %d and SubModelID = %d and AdditionalCriteria_PartMasterID=%d', $year, $make, $model, $submodel, $criteria
                ));
            } else {
                $attributes = $read->fetchAll(sprintf('SELECT AttributeType, AttributeValue'
                                . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                                . ' JOIN tpms_applicationguide_attributes a on a.VehicleID = v.Vehicleid'
                                . ' WHERE YearID=%d and MakeID = %d and ModelID = %d and SubModelID = %d', $year, $make, $model, $submodel
                ));
            }
            $attribute_arr = array();
            $aboutVehical = array();
            $categoryIds = array(0);

            $lugNutCount = $lugBoltCount = $lugStudCount = $lugWLCount = $hubBoreCount = $boltAddopterCount = $hubCentricCount = $wheelSpacerCount = 0;
            $getLugNut = $getLugBolt = $getStud = $getLugWheelLock = $getHubBore = $getBoltAdapter = $getHubCentric = $getWheelSpacer = 0;
            if (!empty($attributes)) {
                foreach ($attributes as $attribute) {
                    $attribute_arr[$attribute['AttributeType']][] = $attribute['AttributeValue'];
                    $aboutVehical[] = array('AttributeType' => $attribute['AttributeType'], 'AttributeValue' => $attribute['AttributeValue']);

                    if (isset($attribute_arr['Lug']) && $attribute['AttributeValue'] == 'nut') {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                                $lug_nut_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Thread'])) {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_thread')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Thread']))) {
                                $lug_thread_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Lug']) && $attribute['AttributeValue'] == 'bolt') {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                                $lug_bolt_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Lug']) && $attribute['AttributeValue'] == 'bolt') {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                                $lug_studs_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Lug'])) {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'lug_')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Lug']))) {
                                $lug_wheel_locks_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Hub Bore'])) {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'center_bore')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Hub Bore']))) {
                                $center_bore[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Bolt Pattern'])) {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                                $adapter_vehicle_bolt_pattern_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Bolt Pattern'])) {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                                $hub_centric_spacers_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }

                    if (isset($attribute_arr['Bolt Pattern'])) {
                        $options = $this->_eavConfig->getAttribute('catalog_product', 'adapter_vehicle_bolt_pattern')->getSource()->getAllOptions();
                        foreach ($options as $option) {
                            if (in_array(strtolower($option['label']), array_map('strtolower', $attribute_arr['Bolt Pattern']))) {
                                // echo 'test'; exit;
                                $wheel_spacers_optionIds[] = (int) $option['value'];
                                break;
                            }
                        }
                    }
                }
            }

            $filters = $this->_searchSearchHelper->getSkus();
            $filterIsNull = false;

            if (!count($filters)) {
                $filters = null;
                $filterIsNull = true;
            }

            $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([18]);

            $collection = $this->_productFactory->create()->getCollection();
            $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
            $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
//            $this->_productStatus->addVisibleFilterToCollection($collection);
//            $this->_productVisibility->addVisibleInSearchFilterToCollection($collection);

            $orotekSenorsCount = $collection
                    ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                    ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                    ->addAttributeToFilter($filters, '', 'left')
                    ->getSize();

            $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([20]);
            $collection = $this->_productFactory->create()->getCollection();
            $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
            $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
            $orotekServiceKitsCount = $collection
                    ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                    ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                    ->addAttributeToFilter($filters, '', 'left')
                    ->getSize();


            $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([21]);
            $collection = $this->_productFactory->create()->getCollection();
            $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
            $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
            $accessoriesCount = $collection
                    ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                    ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                    ->addAttributeToFilter($filters, '', 'left')
                    ->getSize();

            $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([22]);
            $collection = $this->_productFactory->create()->getCollection();
            $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
            $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
            $triggerToolsCount = $collection
                            ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                            ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                            ->addAttributeToFilter($filters, '', 'left')->getSize();

            $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([33]);
            $collection = $this->_productFactory->create()->getCollection();
            $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
            $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
            $TPMSStemsCount = $collection
                    ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                    ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                    ->addAttributeToFilter($filters, '', 'left')
                    ->getSize();
            /* TPMS    END */


            if (isset($attribute_arr['Lug']) && isset($lug_nut_optionIds) && isset($lug_thread_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([54]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $lugNutCount_filters[] = $filters[0];
                $lugNutCount_filters[] = array(
                    'attribute' => 'lug_', array('in' => $lug_nut_optionIds),
                    'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds)
                );
                $lugNutCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($lugNutCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Lug']) && isset($lug_bolt_optionIds) && isset($lug_thread_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([56]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $lugBoltCount_filters[] = $filters[0];
                $lugBoltCount_filters[] = array(
                    'attribute' => 'lug_', array('in' => $lug_bolt_optionIds),
                    'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds)
                );
                $lugBoltCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($lugBoltCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Lug']) && isset($lug_studs_optionIds) && isset($lug_thread_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([59]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $lugStudCount_filters[] = $filters[0];
                $lugStudCount_filters[] = array('attribute' => 'lug_', array('in' => $lug_studs_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                $lugStudCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($lugStudCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Lug']) && isset($lug_wheel_locks_optionIds) && isset($lug_thread_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([57]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $lugWLCount_filters[] = $filters[0];
                $lugWLCount_filters[] = array('attribute' => 'lug_', array('in' => $lug_wheel_locks_optionIds), 'attribute' => 'lug_thread', array('in' => $lug_thread_optionIds));
                $lugWLCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($lugWLCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Hub Bore']) && isset($center_bore)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([61]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $hubBoreCount_filters[] = $filters[0];
                $hubBoreCount_filters[] = array('attribute' => 'center_bore', array('in' => $center_bore));
                $hubBoreCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($hubBoreCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Bolt Pattern']) && isset($adapter_vehicle_bolt_pattern_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([5]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $boltAddopterCount_filters[] = $filters[0];
                $boltAddopterCount_filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $adapter_vehicle_bolt_pattern_optionIds));
                $boltAddopterCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($boltAddopterCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Bolt Pattern']) && isset($hub_centric_spacers_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([72]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $hubCentricCount_filters[] = $filters[0];
                $hubCentricCount_filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', array('in' => $hub_centric_spacers_optionIds));
                $hubCentricCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($hubCentricCount_filters, '', 'left')
                        ->getSize();
            }

            if (isset($attribute_arr['Bolt Pattern']) && isset($wheel_spacers_optionIds)) {
                $categoryIds = \Inchoo\Search\Model\Catalogsearch\Category::ids([76]);
                $collection = $this->_productFactory->create()->getCollection();
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $wheelSpacerCount_filters[] = $filters[0];
                $wheelSpacerCount_filters[] = array('attribute' => 'adapter_vehicle_bolt_pattern', 'finset' => array_unique($wheel_spacers_optionIds));
                $wheelSpacerCount = $collection
                        ->joinField('category_id', 'catalog_category_product', 'category_id', 'product_id=entity_id', null, 'left')
                        ->addAttributeToFilter('category_id', array('in' => array($categoryIds)))
                        ->addAttributeToFilter($wheelSpacerCount_filters, '', 'left')
                        ->getSize();
            }

            $aboutVehical = array_map('unserialize', array_unique(array_map('serialize', $aboutVehical)));

            $totalProduct = ($lugNutCount + $lugBoltCount + $lugStudCount + $lugWLCount + $hubBoreCount + $boltAddopterCount + $hubCentricCount + $wheelSpacerCount + $orotekSenorsCount + $orotekServiceKitsCount + $accessoriesCount + $triggerToolsCount + $TPMSStemsCount);
            $data = array('getYear' => $year, 'getMake' => $make, 'getModel' => $model, 'getSubmodel' => $submodel,
                'year' => $year, 'make' => $makename[0], 'model' => $modelname[0], 'submodel' => $submodelname[0],
                'Lug' => $lugNutCount, 'Bolt' => $lugBoltCount, 'Stud' => $lugStudCount, 'WheelLock' => $lugWLCount,
                'hubBore' => $hubBoreCount, 'Adapter' => $boltAddopterCount, 'hubcentric' => $hubCentricCount,
                'wheelspacers' => $wheelSpacerCount, 'aboutVehical' => $aboutVehical, 'totalProduct' => $totalProduct,
                'orotekSenorsCount' => $orotekSenorsCount, 'orotekServiceKitsCount' => $orotekServiceKitsCount,
                'accessoriesCount' => $accessoriesCount, 'triggerToolsCount' => $triggerToolsCount,
                'TPMSStemsCount' => $TPMSStemsCount, 'filterIsNull' => $filterIsNull);
            if ($criteria) {
                $data['criteria'] = $criteria;
            }
            if ($totalProduct < 1) {
                $data = array('getYear' => $year, 'getMake' => $make, 'getModel' => $model, 'getSubmodel' => $submodel,
                    'year' => $year, 'make' => $makename[0], 'model' => $modelname[0], 'submodel' => $submodelname[0],
                    'Lug' => (int) 0, 'Bolt' => (int) 0, 'Stud' => (int) 0, 'WheelLock' => (int) 0,
                    'hubBore' => (int) 0, 'Adapter' => (int) 0, 'hubcentric' => (int) 0,
                    'wheelspacers' => (int) 0, 'aboutVehical' => $aboutVehical, 'totalProduct' => (int) 0);
            }

            return $data;
        }
    }

    public function getShoppingby() {
        $searchType = $this->_registry->registry('catalog_part_search_type') ?: $this->_request->getControllerName();

        if ($searchType == 'results') {
            $filter = $this->_filterAttirbute;
            $params = $this->getRequest()->getParams();

            foreach ($params as $key => $value) {
                if ($key == 'year' || $key == 'make' || $key == 'model' || $key == 'submodel' || $key == 'cat') {
                    // WTF ??
                } else {
                    $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $key);
                    $label = $attribute->getFrontend()->getLabel();
                    $text = $attribute->getSource()->getOptionText($value);
                    $filter[] = array(
                        'attribute_slug' => $key,
                        'attribute_label' => $label,
                        'attribute_value' => $value,
                        'attribute_text' => $text
                    );
                }
            }
            return $filter;
        }
        return $this->_filterAttirbute;
    }

    public function clearAll() {
        $searchType = $this->_registry->registry('catalog_part_search_type') ?: $this->_request->getControllerName();
        if ($searchType == 'results') {
            $filter = $this->_filterAttirbute;
            $params = $this->getRequest()->getParams();
            $filter = '/search/results/for/?';

            foreach ($params as $key => $value) {
                if ($key == 'year' || $key == 'make' || $key == 'model' || $key == 'submodel' || $key == 'cat') {
                    $filter .= $key . '=' . $value . '&';
                }
            }
            return $filter;
        }
        return $this->_filterAttirbute;
    }

}
