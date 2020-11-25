<?php

namespace AJH\Fitment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use AJH\Fitment\Model\Fitment;
use AJH\Fitment\Model\Fitment\Categories as FitmentCategories;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface as CoreSession;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;

class Categories extends Template {

    protected $fitment, $_fitmentCategoriesModel;
    public $fitment_year;
    public $fitment_make;
    public $fitment_model;
    public $fitment_submodel;
    public $qualifiers;
    public $_qualifiers;
    protected $_coreSession;
    protected $_resourceConnection;
    protected $_storeManager;

    public function __construct(Context $context, Fitment $fitment,
            FitmentCategories $_categories, RequestInterface $request,
            CoreSession $coreSession, ResourceConnection $_resourceConnection,
            StoreManagerInterface $storeManager) {

        $this->fitment_year = $request->getParam('year', false);
        $this->fitment_make = $request->getParam('make', false);
        $this->fitment_model = $request->getParam('model', false);
        $this->fitment_submodel = $request->getParam('submodel', false);

        $this->qualifiers = $request->getParam('qualifiers');
        $this->_qualifiers = $request->getParam('_qualifiers');

        if ($request->getParam('qualifiers') && !is_array($request->getParam('qualifiers')) && $request->getParam('qualifiers') != '') {
            $this->qualifiers = explode(",", $request->getParam('qualifiers'));
        }

        if ($request->getParam('_qualifiers') && !is_array($request->getParam('qualifiers')) && $request->getParam('_qualifiers') != '') {
            $this->_qualifiers = explode(",", $request->getParam('_qualifiers'));
        }

        $this->_coreSession = $coreSession;
        $this->fitment = $fitment;
        $this->_resourceConnection = $_resourceConnection;

        $this->_fitmentCategoriesModel = $_categories;

        $this->_storeManager = $storeManager;

        parent::__construct($context);
    }

    protected function _prepareLayout() {
        $title = $this->pageConfig->getTitle()->getDefault();
        $title .= ' - ' . $this->getPageTitle();

        $this->pageConfig->getTitle()->set(__($title));
        return parent::_prepareLayout();
    }

    public function getPageTitle() {
        $fitment = $this->getFitmentFilters();

        $title = $fitment['year'] . ' ' . $fitment['make']['Name'] . ' ' . $fitment['model']['Name'] . ' ' . $fitment['submodel']['Name'];

        return $title;
    }

    public function getCategories() {
        return $this->_fitmentCategoriesModel->getProductCategories();
    }

    public function getFitmentFilters() {

        if ($this->fitment_year) {
            $isAjax = $this->getRequest()->getParam('isajax');
            $makes = $this->fitment->getMakes();
            $models = $this->fitment->getModels();
            $submodels = $this->fitment->getSubModels();
            
            $make = $makes;

            if (is_array($makes)) {
                foreach ($makes as $_make) {
                    if ($_make['ID'] == $this->fitment_make) {
                        $make = $_make;
                    }
                }
            }
//            else {
//                $make = $makes;
//            }

            if (is_array($models)) {
                foreach ($models as $_model) {
                    if ($_model['ID'] == $this->fitment_model) {
                        $model = $_model;
                    }
                }
            } else {
                $model = $models;
            }

            if (is_array($submodels)) {
                foreach ($submodels as $_submodel) {
                    if ($_submodel['ID'] == $this->fitment_submodel) {
                        $submodel = $_submodel;
                    }
                }
            } else {
                $submodel = $submodels;
            }

            $filters = array(
                'year' => $this->fitment_year,
                'make' => $make,
                'model' => $model,
                'submodel' => $submodel,
                'qualifiers' => $this->qualifiers,
                'isAjax' => $isAjax,
                'params' => array(
                    'year' => $this->fitment_year,
                    'make' => $this->fitment_make,
                    'model' => $this->fitment_model,
                    'submodel' => $this->fitment_submodel,
                    'qualifiers[]' => isset($this->qualifiers) && is_array($this->qualifiers)?implode(",", $this->qualifiers):null,
                    '_qualifiers[]' => isset($this->_qualifiers) && is_array($this->_qualifiers)?implode(",", $this->_qualifiers):null
                )
            );

//            $vehicles = array();
//            $vehicle_index = $filters['year'] . $filters['make']->MakeID . $filters['model']->ModelID . $filters['submodel']->SubModelID . str_replace(' ', '', strtolower($filters['qualifiers'][0]));
//            $garage_vehicles = Mage::getSingleton('core/session')->getFitmentGarage();
//
//
//            if (count($garage_vehicles['garage'])) {
//                foreach ($garage_vehicles['garage'] as $vehicle) {
//                    $_vehicle_index = $vehicle['year'] . $vehicle['make']->MakeID . $vehicle['model']->ModelID . $vehicle['submodel']->SubModelID . str_replace(' ', '', strtolower($vehicle['qualifiers'][0]));
//                    $vehicles['garage'][$_vehicle_index] = $vehicle;
//                }
//            }
//
//            $vehicles['garage'][$vehicle_index] = $filters;
//            Mage::getSingleton('core/session')->setFitmentGarage($vehicles);

            return $filters;
        } else {
            return;
        }
    }

}
