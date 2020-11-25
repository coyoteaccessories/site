<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class GetModel extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        if (Mage::app()->getRequest()->isAjax()) {
            $dbConnection = \Inchoo\Search\Helper\Data::dbConnection();

            $year = $this->getRequest()->getPost("year");
            $make = $this->getRequest()->getPost("make");
            $models = $dbConnection->fetchAll('SELECT aaia_model.ModelID, aaia_model.ModelName FROM aaia_model where ModelID IN(SELECT aaia_basevehicle.ModelID FROM aaia_basevehicle where aaia_basevehicle.MakeID=' . $make . ' And aaia_basevehicle.YearID=' . $year . ') order by aaia_model.ModelName');
            $output = '<option value="">Model</option>';
            foreach ($models as $model) {
                $output .= '<option value="' . $model['ModelID'] . '">' . $model['ModelName'] . '</option>';
            }
            echo $output;
            exit;
        }
    }

}
