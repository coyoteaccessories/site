<?php

namespace Inchoo\Search\Controller\Protocol;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\Action\Context;

class GetDate extends \Magento\Framework\App\Action\Action {

    protected $_logger;

    public function __construct(Context $context, LoggerInterface $logger) {
        parent::__construct($context);

        $this->_logger = $logger;
    }

    public function execute() {
        $protocolDates = \Inchoo\Search\Helper\Vehicle::getProtocolDates();
        $this->_logger->log(print_r($protocolDates, true), null, 'protocolDates.log');
    }

}
