<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class GetSubmodel extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        if (Mage::app()->getRequest()->isAjax()) {
            $dbConnection = \Inchoo\Search\Helper\Data::dbConnection();

            $year = $this->getRequest()->getPost("year");
            $make = $this->getRequest()->getPost("make");
            $model = $this->getRequest()->getPost("model");
            $submodels = $dbConnection->fetchAll('select SubModelID, SubModelName from ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' where YearID=' . $year . ' and MakeID=' . $make . ' and ModelID=' . $model . ' group by SubModelID order by SubModelName');

            $output = '<option value="">Sub Model</option>';
            foreach ($submodels as $submodel) {
                $output .= '<option value="' . $submodel['SubModelID'] . '">' . $submodel['SubModelName'] . '</option>';
            }
            echo $output;
            exit;
        }
    }

}
