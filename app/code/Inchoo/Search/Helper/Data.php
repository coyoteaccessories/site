<?php

namespace Inchoo\Search\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class Data extends AbstractHelper {

    CONST VWVEHICLESTPMS = 'vwvehiclestpms';

    protected $_dbConnection, $_logger;

    public function __construct(ResourceConnection $dbConnection, LoggerInterface $logger) {
        $this->_dbConnection = $dbConnection;
        $this->_logger = $logger;
    }

    public function dbConnection($dbname = "revo") {
        return $this->_dbConnection->getConnection($dbname);
    }

    public static function getOptionsHtml($options) {
        $res = '';
        foreach ($options as $key => $value) {
            $res .= sprintf('<option value="%s">%s</option>', htmlspecialchars($key), htmlspecialchars($value));
        }
        return $res;
    }

    public function log($message) {
        $this->_logger->log($message, null, 'partsearch.log');
    }

    public function getVehicleTable() {
        return $this->VWVEHICLESTPMS;
    }

}
