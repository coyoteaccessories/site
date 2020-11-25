<?php

namespace Inchoo\Search\Controller\Relearn;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;
use Inchoo\Search\Helper\Vehicle;

class Makes extends \Magento\Framework\App\Action\Action {

    protected $_logger, $_vehicle;

    public function __construct(Context $context, LoggerInterface $logger, Vehicle $vehicle) {
        parent::__construct($context);

        $this->_logger = $logger;
        $this->_vehicle = $vehicle;
    }

    public function execute() {
        try {
            $yearId = $this->getRequest()->getParam('year');
            if (!$yearId) {
                die;
            }
            $makes = $this->_vehicle->getMakes($yearId);
            $options = ['' => 'Make'];
            foreach ($makes as $key => $value) {
                $options[$key] = $value;
            }
            echo \Inchoo\Search\Helper\Data::getOptionsHtml($options);
            die;
        } catch (\Exception $e) {
            $this->_logger->critical('Error message', ['exception' => $e]);
        }
    }

}
