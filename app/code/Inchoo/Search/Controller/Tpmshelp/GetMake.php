<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class GetMake extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        if (Mage::app()->getRequest()->isAjax()) {
            $dbConnection = \Inchoo\Search\Helper\Data::dbConnection();

            $makes = $$dbConnection->fetchAll('SELECT MakeID, MakeName FROM aaia_make group by MakeID order by MakeName');
            $output = '<option value="">Make</option>';
            foreach ($makes as $make) {
                $output .= '<option value="' . $make['MakeID'] . '">' . $make['MakeName'] . '</option>';
            }
            echo $output;
            exit;
        }
    }

}
