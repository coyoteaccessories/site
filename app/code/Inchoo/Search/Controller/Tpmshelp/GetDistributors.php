<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class GetDistributors extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        if (Mage::app()->getRequest()->isAjax()) {
            $dbConnection = \Inchoo\Search\Helper\Data::dbConnection();

            $distributors = $dbConnection->fetchAll('SELECT ID, Name FROM distributors group by ID order by Name');
            $output = '<option value="">Distributors</option>';
            foreach ($distributors as $distributor) {
                $output .= '<option value="' . $distributor['ID'] . '">' . $distributor['Name'] . '</option>';
            }
            echo $output;
            exit;
        }
    }

}
