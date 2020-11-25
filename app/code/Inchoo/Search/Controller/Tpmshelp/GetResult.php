<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class GetResult extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        if (Mage::app()->getRequest()->isAjax()) {
            $dbConnection = \Inchoo\Search\Helper\Data::dbConnection();

            $year = $this->getRequest()->getPost("year");
            $make = $this->getRequest()->getPost("make");
            $model = $this->getRequest()->getPost("model");
            $submodel = $this->getRequest()->getPost("submodel");

            $finalResults = $dbConnection->fetchAll('select RecommendedSensors from tpmschallengeworksheet where YearID=' . $year . ' and MakeID=' . $make . ' and ModelID=' . $model . ' and SubModelID=' . $submodel);

            if (!empty($finalResults)) {
                $output = $finalResults[0]['RecommendedSensors'];
            } else {
                $output = 'No record found';
            }
            echo $output;
            exit;
        }
    }

}
