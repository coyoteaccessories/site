<?php

namespace AJH\Fitment\Model\Fitment;

use AJH\Fitment\Model\Fitment\ResourceModel\Products as ResourceModel;
use AJH\Fitment\Model\Fitment\Cache as FitmentCache;
use AJH\Fitment\Helper\Data as FitmentHelper;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\RequestInterface;

class Products extends \AJH\Fitment\Model\Fitment\Api {

    const CACHE_TAG = 'ajh_fitment_products';

    protected $_cacheTag = 'ajh_fitment_products';
    protected $_eventPrefix = 'ajh_fitment_products';
    protected $_fitmentHelper, $_skus, $_request, $_params;
    protected $_year, $_make, $_makes, $_model, $_models, $_submodel, $_submodels, $_qualifiers, $_fitmentQualifiers, $_cat, $_criteria;
    protected $_resource;
    protected $_productCollectionFactory, $_productCollection;
    protected $_storeManager, $_eavConfig;
    protected $_fitmentCache;

    public function __construct(
    FitmentHelper $fitmentHelperData, RequestInterface $request,
            ResourceConnection $resource,
            ProductCollectionFactory $productCollectionFactory,
            StoreManagerInterface $storeManager, EavConfig $eavConfig,
            FitmentCache $fitmentCache
    ) {
        $this->_fitmentHelper = $fitmentHelperData;
        $this->_client = $this->_fitmentHelper->_client;
        $this->_params = $this->_fitmentHelper->_params;
        $this->_request = $request;
        $this->_resource = $resource;

        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_eavConfig = $eavConfig;
        $this->_fitmentCache = $fitmentCache;

        $this->_initFitmentParams();
    }

    protected function _construct() {
        $this->_init(ResourceModel::class);
    }

    public function getIdentities() {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues() {
        $values = [];

        return $values;
    }

    protected function _initFitmentParams() {
        $this->_year = $this->_request->getParam("year");
        $this->_make = $this->_request->getParam("make");
        $this->_model = $this->_request->getParam("model");
        $this->_submodel = $this->_request->getParam("submodel");
        $this->_qualifiers = $this->_request->getParam("qualifiers");
        $this->_qualifiers2 = $this->_request->getParam("_qualifiers");
        $this->_cat = $this->_request->getParam("cat");
        $this->_criteria = $this->_request->getParam("criteria");
        $this->_fitmentQualifiers = $this->getFitmentQualifiers();
    }

    public function loadFitmentSkus() {
        $collection = $this->retrieveVehicleParts();
        $this->_skus = $collection;

        return $this->_skus;
    }

    public function getProductCollection() {
        $productSkus = $this->loadFitmentSkus();

        $collection = $this->_productCollectionFactory->create();

        $collection->addAttributeToSelect('*')
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addWebsiteFilter()
                ->addAttributeToFilter('sku', array('in' => $productSkus))
                ->addAttributeToSort('sku')
                ->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
                ->addAttributeToFilter('status', array('eq' => 1));
        return $collection;
    }

    public function retrieveVehicleParts() {
        $fitment = array(
            'year' => $this->_year,
            'makeID' => $this->_make,
            'make' => json_encode($this->getFitmentMakeNameByID($this->_year, $this->_make)),
            'modelID' => $this->_model,
            'model' => json_encode($this->getFitmentModelNameByID($this->_year, $this->_make, $this->_model)),
            'submodelID' => $this->_submodel,
            'submodel' => json_encode($this->getFitmentSubModelNameByID($this->_year, $this->_make, $this->_model, $this->_submodel)),
            'qualifiers' => $this->_fitmentQualifiers
        );

        $_skus = $this->_fitmentCache->getCachedFitment($fitment);

        if (is_null($_skus) || count($_skus) < 1) {
            $_skus = $this->_retrieveVehicleParts($fitment);
        }

        return $_skus;
    }

    private function _retrieveVehicleParts($fitment = []) {
        $_skus = [];
        $parts = [];

        $this->_params['FitmentYear'] = $this->_year;
        $this->_params['FitmentMakeID'] = $this->_make;
        $this->_params['FitmentModelID'] = $this->_model;
        $this->_params['FitmentSubModelID'] = $this->_submodel;
        $this->_params['FitmentQualifiers'] = $this->_fitmentQualifiers;

        if ($this->_year) {
            $parts = $this->_client->RetrieveVehicleParts($this->_params)->RetrieveVehiclePartsResult->Parts->FitmentPart;
        }

        foreach ($parts as $part) {
            if (!in_array($part->PartNumber, $_skus)) {
                array_push($_skus, $part->PartNumber);
            }
        }

        $fitment['skus'] = serialize($_skus);
        $this->_fitmentCache->cacheFitment($fitment);

        return $_skus;
    }

    public function fitmentYears() {

        $fitmentYears = $this->getCachedFitmentYears();

        if (!count($fitmentYears)) {
            $fitmentYears = $this->_client->FitmentYears($this->_params)->FitmentYearsResult->FitmentYears->int;
            $this->cacheFitmentYears($fitmentYears);
        }

        return $fitmentYears;
    }

    public function fitmentMakes($year) {
        $this->_makes = $this->_fitmentCache->getCachedFitmentMakes($year);

        if (!count($this->_makes) && $year) {
            $this->_params['FitmentYear'] = $year;
            $this->_makes = $this->_client->FitmentMakes($this->_params)->FitmentMakesResult->FitmentMakes->FitmentMake;

            $this->cacheFitmentMakes($year, $this->_makes);
        }

        return $this->_makes;
    }

    public function fitmentModels($year, $make) {
        $this->_models = $this->_fitmentCache->getCachedFitmentModels($year, $make);
        if (!count($this->_models) && $year && $make) {
            $this->_params['FitmentYear'] = $year;
            $this->_params['FitmentMakeID'] = $make;
            $this->_makeID = $make;

            $this->_models = $this->_client->FitmentModels($this->_params)->FitmentModelsResult->FitmentModels->FitmentModel;
            $this->cacheFitmentModels($year, $make, $this->_models);
        }

        return $this->_models;
    }

    public function fitmentSubModels($year, $make, $model) {
        $this->_submodels = $this->_fitmentCache->getCachedFitmentSubModels($year, $make, $model);
        if (!count($this->_submodels) && $year && $make && $model) {
            $this->_params['FitmentYear'] = $year;
            $this->_params['FitmentMakeID'] = $make;
            $this->_params['FitmentModelID'] = $model;

            $this->_modelID = $model;

            $this->_submodels = $this->_client->FitmentSubModels($this->_params)->FitmentSubModelsResult->FitmentSubModels->FitmentSubModel;
            $this->cacheFitmentSubModels($year, $make, $model, $this->_submodels);
        }

        return $this->_submodels;
    }

    public function fitmentQualifiers($year, $make, $model, $submodel) {
        $qualifiers = $this->_fitmentCache->getCachedFitmentQualifiers($year, $make, $model, $submodel);

        if (!count($qualifiers)) {
            $this->_params['FitmentYear'] = $year;
            $this->_params['FitmentMakeID'] = $make;
            $this->_params['FitmentModelID'] = $model;
            $this->_params['FitmentSubModelID'] = $submodel;

            $this->_submodelID = $submodel;

            $this->_skus_no_qualifiers = $this->retrieveVehicleParts($year, $make, $model, $submodel, null, null);
            $qualifiers = $this->_client->FitmentQualifiers($this->_params)->FitmentQualifiersResult->Qualifiers;

            $this->_fitmentCache->cacheFitmentQualifiers($year, $make, $model, $submodel, $qualifiers);
        }

        return $qualifiers;
    }

    public function getFitmentMakeNameByID($year, $makeID) {
        $make = array();

        $makes = $this->fitmentMakes($year);

        foreach ($makes as $_make) {
            if (intval($_make->MakeID) === intval($makeID)) {
                $make = $_make;
                break;
            }
        }

        return $make;
    }

    public function getFitmentModelNameByID($year, $makeID, $modelID) {
        $model = array();

        $models = $this->fitmentModels($year, $makeID);

        foreach ($models as $_model) {
            if (intval($_model->ModelID) === intval($modelID)) {
                $model = $_model;
                break;
            }
        }

        return $model;
    }

    public function getFitmentSubModelNameByID($year, $makeID, $modelID,
            $submodelID) {
        $submodel = array();
        $submodels = $this->fitmentSubModels($year, $makeID, $modelID);

        if (is_array($submodels)) {
            foreach ($submodels as $_submodel) {
                if (intval($_submodel->SubModelID) === intval($submodelID)) {
                    $submodel = $_submodel;

                    break;
                }
            }
        } else {
            $submodel = $submodels;
        }

        return $submodel;
    }

    private function getFitmentQualifiers() {
        $qualifiers_str = '';
        $qualifiers_arr = [];
        $_qualifiers_arr = [];

        if (isset($this->_qualifiers) && is_array($this->_qualifiers)) {
            foreach ($this->_qualifiers as $key => $qualifier) {
                if ($key == 0) {
                    $qualifiers_arr = explode(",", $qualifier);
                }
            }
        }

        if (isset($this->_qualifiers2) && is_array($this->_qualifiers2)) {
            foreach ($this->_qualifiers2 as $key => $_qualifier) {
                if ($key == 0) {
                    $_qualifiers_arr = explode(",", $_qualifier);
                }
            }
        }

        if (count($qualifiers_arr)) {
            $_qualifiers_str = array();
            foreach ($qualifiers_arr as $key => $qualifier) {
                if ($qualifier !== '' && count($_qualifiers_arr)) {
                    if(is_array($_qualifiers_str) && isset($_qualifiers_arr[$key])){
                        array_push($_qualifiers_str, $_qualifiers_arr[$key] . '=' . $qualifier);
                    }
                }
            }

            $qualifiers_str = implode("|", $_qualifiers_str);
        }

        return $qualifiers_str;
    }

    public function getPdqProductCollection() {
        $dbConnection = $this->_resource->getConnection('revo');

        if ($this->_criteria) {
            $result = $dbConnection->fetchAll(sprintf('SELECT p1.partnumber, p2.partnumber as LinkedPart'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.AdditionalCriteria_PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.AdditionalCriteria_PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and v.SubModelID=%d and AdditionalCriteria_PartMasterID=%d', $this->_year, $this->_make, $this->_model, $this->_submodel, $this->_criteria
            ));
        } else {
            $result = $dbConnection->fetchAll(sprintf('SELECT p1.partnumber, p2.partnumber as LinkedPart'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and v.SubModelID=%d', $this->_year, $this->_make, $this->_model, $this->_submodel
            ));
        }

        $_productSkus = array_unique(array_merge(array_column($result, 'partnumber'), array_column($result, 'LinkedPart')));

        $productSkus = array_filter($_productSkus);
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*')
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addAttributeToFilter('sku', array('in' => $productSkus))
                ->addAttributeToSort('sku')
                ->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
                ->addAttributeToFilter('status', array('eq' => 1));
        return $collection;
    }

}
