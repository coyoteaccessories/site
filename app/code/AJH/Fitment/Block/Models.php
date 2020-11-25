<?php

namespace AJH\Fitment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use AJH\Fitment\Model\Fitment;

class Models extends Template {

    private $fitment;
    public $fitment_year;
    public $fitment_make;

    public function __construct(Context $context, Fitment $fitment,
            RequestInterface $request) {

        $this->fitment_year = $request->getParam('year', false);
        $this->fitment_make = $request->getParam('make', false);

        $this->fitment = $fitment;

        parent::__construct($context);
    }

    public function getModels() {

        $yearId = $this->fitment_year;
        $makeId = $this->fitment_make;

        if (!(int) $yearId || !(int) $makeId) {
            die('Year and Make are required');
        }

        $models = $this->fitment->getModels();

        return $models;
    }

//    private function getModel($modelId) {
//        $models = $this->getModels();
//        $model = array();
//
//        foreach ($models as $_model) {
//            if ($_model->ModelID == $modelId) {
//                $model['ID'] = $_model->ModelID;
//                $model['Name'] = trim($_model->ModelName);
//            }
//        }
//
//        return $model;
//    }
}
