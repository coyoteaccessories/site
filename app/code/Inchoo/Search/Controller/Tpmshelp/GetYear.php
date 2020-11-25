<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class GetYear extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        if (Mage::app()->getRequest()->isAjax()) {
            $dbConnection = \Inchoo\Search\Helper\Data::dbConnection();

            $MakeID = $this->getRequest()->getPost("makeId");
            $years = $dbConnection->fetchAll('SELECT YearID FROM aaia_basevehicle where MakeID=' . $MakeID . ' group by YearID order by YearID');
            $output = '<option value="">Year</option>';
            foreach ($years as $year) {
                $output .= '<option value="' . $year['YearID'] . '">' . $year['YearID'] . '</option>';
            }
            echo $output;
            exit;
        }
    }

}
