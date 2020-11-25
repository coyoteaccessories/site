<?php

namespace AJH\Fitment\Model;

use AJH\Fitment\Model\YearsFactory as YearsCollection;
use AJH\Fitment\Model\MakesFactory as MakesCollection;
use AJH\Fitment\Model\ModelsFactory as ModelsCollection;
use AJH\Fitment\Model\SubModelsFactory as SubModelsCollection;
use AJH\Fitment\Model\QualifiersFactory as QualifiersCollection;
use AJH\Fitment\Model\Fitment\Api as FitmentApi;
use AJH\Fitment\Model\Fitment\Cache as FitmentCache;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Fitment extends \Magento\Framework\Model\AbstractModel {

    protected $_fitmentYears, $_fitmentMakes, $_fitmentModels, $_fitmentSubModels, $_fitmentQualifiers, $_request;
    private $_year, $_make, $_model, $_submodel;
    private $fitmentApi, $fitmentCache;

    public function __construct(Context $context, Registry $registry,
            YearsCollection $fitmentYears, MakesCollection $fitmentMakes,
            ModelsCollection $fitmentModels,
            SubModelsCollection $fitmentSubModels,
            QualifiersCollection $fitmentQualifiers, RequestInterface $request,
            FitmentApi $fitmentApi, FitmentCache $fitmentCache) {
        $this->_fitmentYears = $fitmentYears;
        $this->_fitmentMakes = $fitmentMakes;
        $this->_fitmentModels = $fitmentModels;
        $this->_fitmentSubModels = $fitmentSubModels;
        $this->_fitmentQualifiers = $fitmentQualifiers;

        $this->_request = $request;

        $this->fitmentApi = $fitmentApi;
        $this->fitmentCache = $fitmentCache;

        $this->_year = $this->_request->getParam('year');
        $this->_make = $this->_request->getParam('make');
        $this->_model = $this->_request->getParam('model');
        $this->_submodel = $this->_request->getParam('submodel');

        parent::__construct($context, $registry);
    }

    public function getYears() {
        $_years = $this->_fitmentYears->create();
        $collection = $_years->getCollection();
        return $collection;
    }

    public function getAllMakes() {
        return $this->fitmentCache->_getCachedFitmentMakes();
    }

    public function getMakeYears($makeID) {
        $years = array();

        if (!intval($makeID)) {
            $makeID = $this->_request->getParam('make');
        }                

        $makes = $this->getAllMakes();

        foreach ($makes as $key => $make) {
            if ((int) $key === (int) $makeID) {                
                $years = $make['years'];
            }
        }                

        return $years;
    }

    public function getMakes() {
        $makes = [];
        
        $year = $this->_year?$this->_year:$this->_request->getPostValue('year'); //for garage "makes" quesry

        if ((int) $year) {
            $_makes = $this->_fitmentMakes->create();
            $collection = $_makes->getCollection()->addFieldToFilter('year', ['eq' => $year]);

            if ($collection->count() > 0) {
                $makes = $this->_getMakes($collection);
            } else {
                $makes = $this->fitmentApi->getMakes();
            }
        }

        return $makes;
    }

    private function _getMakes($collection) {
        $makes = [];

        foreach ($collection as $_make) {
            $make = [];
            $data = $_make->getData();
            if (isset($data['makeID'])) {
                $make['ID'] = $data['makeID'];
                $make['Name'] = $data['makeName'];
                $make['Logo'] = $data['makeLogo'];

                $makes[] = $make;
            }
        }

        return $makes;
    }

    public function getMake() {
        $make = [];
        $year = $this->_year;
        $makeID = $this->_make;

        if ((int) $year && (int) $makeID) {
            $makes = $this->_fitmentMakes->create();
            $collection = $makes->getCollection()->addFieldToFilter('year', ['eq' => $year])->addFieldToFilter('makeID', ['eq' => $makeID]);

            if ($collection->count() > 0) {
                $make = $this->_getMake($collection->getFirstItem());
            } else {
                $make = $this->fitmentApi->getMake($makeID);
            }
        }

        return $make;
    }

    private function _getMake($collection) {
        $make = [];
        $data = $collection->getData();

        if (count($data) && isset($data['makeID'])) {
            $make['ID'] = $data['makeID'];
            $make['Name'] = $data['makeName'];
            $make['Logo'] = $data['makeLogo'];
        }

        return $make;
    }

    public function getModels() {
        $models = [];

        $year = $this->_year;
        $makeID = $this->_make;                

        if ((int) $year && (int) $makeID) {
            $models = $this->_fitmentModels->create();
            $collection = $models->getCollection()
                    ->addFieldToFilter('year', ['eq' => $year])
                    ->addFieldToFilter('makeID', ['eq' => $makeID]);

            if ($collection->count() > 0) {
                $models = $this->_getModels($collection);
            } else {
                $models = $this->fitmentApi->getModels();
            }
        }

        return $models;
    }

    private function _getModels($collection) {
        $models = [];
        foreach ($collection as $_model) {
            $model = [];
            $data = $_model->getData();
            if (isset($data['modelID'])) {
                $model['ID'] = $data['modelID'];
                $model['Name'] = $data['modelName'];

                $models[] = $model;
            }
        }

        return $models;
    }

    public function getModel() {
        $model = [];

        $year = $this->_year;
        $makeID = $this->_make;
        $modelID = $this->_model;

        if ((int) $year && (int) $makeID && (int) $modelID) {
            $model = $this->_fitmentModels->create();
            $collection = $model->getCollection()
                    ->addFieldToFilter('year', ['eq' => $year])
                    ->addFieldToFilter('makeID', ['eq' => $makeID])
                    ->addFieldToFilter('modelID', ['eq' => $modelID]);

            if ($collection->count() > 0) {
                $model = $this->_getModel($collection->getFirstItem());
            } else {
                $model = $this->fitmentApi->getModel($modelID);
            }
        }

        return $model;
    }

    private function _getModel($collection) {
        $model = [];
        $data = $collection->getData();

        if (count($data) && isset($data['modelID'])) {
            $model['ID'] = $data['modelID'];
            $model['Name'] = $data['modelName'];
        }

        return $model;
    }

    public function getSubModels() {
        $submodels = [];

        $year = $this->_year;
        $makeID = $this->_make;
        $modelID = $this->_model;

        if ((int) $year && (int) $makeID && (int) $modelID) {
            $submodels = $this->_fitmentSubModels->create();
            $collection = $submodels->getCollection()
                    ->addFieldToFilter('year', ['eq' => $year])
                    ->addFieldToFilter('makeID', ['eq' => $makeID])
                    ->addFieldToFilter('modelID', ['eq' => $modelID]);

            if ($collection->count() > 0) {
                $submodels = $this->_getSubModels($collection);
            } else {
                $submodels = $this->fitmentApi->getSubModels();
            }
        }

        return $submodels;
    }

    private function _getSubModels($collection) {
        $submodels = [];
        foreach ($collection as $_submodel) {
            $submodel = [];
            $data = $_submodel->getData();
            if (isset($data['modelID'])) {
                $submodel['ID'] = $data['submodelID'];
                $submodel['Name'] = $data['submodelName'];

                $submodels[] = $submodel;
            }
        }

        return $submodels;
    }

    public function getSubModel() {
        $submodel = [];

        $year = $this->_year;
        $makeID = $this->_make;
        $modelID = $this->_model;
        $submodelID = $this->_submodel;

        if ((int) $year && (int) $makeID && (int) $modelID) {
            $submodel = $this->_fitmentSubModels->create();
            $collection = $submodel->getCollection()
                    ->addFieldToFilter('year', ['eq' => $year])
                    ->addFieldToFilter('makeID', ['eq' => $makeID])
                    ->addFieldToFilter('modelID', ['eq' => $modelID])
                    ->addFieldToFilter('submodelID', ['eq' => $submodelID]);

            if ($collection->count() > 0) {
                $submodel = $this->_getSubModel($collection->getFirstItem());
            } else {
                $submodel = $this->fitmentApi->getSubModel($submodelID);
            }
        }

        return $submodel;
    }

    private function _getSubModel($collection) {
        $submodel = [];
        $data = $collection->getData();

        if (count($data) && isset($data['submodelID'])) {
            $submodel['ID'] = $data['submodelID'];
            $submodel['Name'] = $data['submodelName'];
        }

        return $submodel;
    }

    public function getQualifiers() {
        $qualifiers = [];

        $year = $this->_year;
        $makeID = $this->_make;
        $modelID = $this->_model;
        $submodelID = $this->_submodel;

        if ((int) $year && (int) $makeID && (int) $modelID && (int) $submodelID) {
            $qualifiers = $this->_fitmentQualifiers->create();
            $collection = $qualifiers->getCollection()
                    ->addFieldToFilter('year', ['eq' => $year])
                    ->addFieldToFilter('makeID', ['eq' => $makeID])
                    ->addFieldToFilter('modelID', ['eq' => $modelID])
                    ->addFieldToFilter('submodelID', ['eq' => $modelID]);

            if ($collection->count() > 0) {
                $qualifiers = $this->_getQualifiers($collection);
            } else {
                $qualifiers = $this->fitmentApi->getQualifiers();
            }
        }

        return $qualifiers;
    }        

}
