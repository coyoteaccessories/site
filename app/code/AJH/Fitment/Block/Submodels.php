<?php

namespace AJH\Fitment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use AJH\Fitment\Model\Fitment;

class Submodels extends Template {

    protected $fitment;
    public $fitment_year;
    public $fitment_make;
    public $fitment_model;

    public function __construct(Context $context, Fitment $fitment,
            RequestInterface $request) {

        $this->fitment_year = $request->getParam('year', false);
        $this->fitment_make = $request->getParam('make', false);
        $this->fitment_model = $request->getParam('model', false);

        $this->fitment = $fitment;

        parent::__construct($context);
    }

    public function getSubmodels() {

        $yearId = $this->fitment_year;
        $makeId = $this->fitment_make;
        $modelId = $this->fitment_model;

        if (!(int) $yearId || !(int) $makeId || !(int) $modelId) {
            die('Year, Make and Model are required.');
        }

        $submodels = $this->fitment->getSubModels();

        return $submodels;
    }

//    private function getSubmodel($modelId) {
//        $models = $this->getSubmodels();
//        $model = array();
//
//        foreach ($models as $_model) {
//            if ($_model->SubModelID == $modelId) {
//                $model['ID'] = $_model->SubModelID;
//                $model['Name'] = trim($_model->SubModelName);
//            }
//        }
//
//        return $model;
//    }
}
