<?php

namespace Inchoo\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;

class GetSubmodel extends \Magento\Framework\App\Action\Action {

    protected $_logger;
    protected $_resourceConnection;

    public function __construct(Context $context, LoggerInterface $logger, ResourceConnection $resourceConnection) {
        parent::__construct($context);

        $this->_logger = $logger;
        $this->_resourceConnection = $resourceConnection;
    }

    public function execute() {
        if ($this->getRequest()->isAjax()) {

            $connection = $this->getDbConnection();

            $year = $this->getRequest()->getPost("year");

            $make = $this->getRequest()->getPost("make");
            $model = $this->getRequest()->getPost("model");
            $submodel = $this->getRequest()->getPost("submodel");

            $sessiondata = array(
                'year' => $year,
                'make' => $make,
                'model' => $model,
                'submodel' => $submodel
            );

            $session = Mage::getSingleton('core/session');
            $session->setData('sessiondata', $sessiondata);

            $finalResults = $connection->fetchAll('select PartMasterID, YearID, MakeName, ModelName, SubModelName from vwvehiclestpms '
                    . ' where YearID=' . $year . ' and MakeID=' . $make . ' and ModelID=' . $model . ' and SubModelID=' . $submodel);            

            $output = '<tabel><tr>
			<th>PART ID </th>
			<th>YEAR </th>
			<th>MAKE </th>
			<th>MODEL </th>
			<th>SUBMODEL</th>
                    </tr>';

            foreach ($finalResults as $finalResult) {
                $output .= '<tr><td>' . $finalResult['PartMasterID'] . '</td>';
                $output .= '<td>' . $finalResult['YearID'] . '</td>';
                $output .= '<td>' . $finalResult['MakeName'] . '</td>';
                $output .= '<td>' . $finalResult['ModelName'] . '</td>';
                $output .= '<td>' . $finalResult['SubModelName'] . '</td></tr></tabel>';
                echo $output;
                exit;
            }
        }
    }

    public static function getDbConnection() {
        return $this->_resourceConnection->getConnection('revo');
    }

}
