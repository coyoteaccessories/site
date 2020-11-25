<?php

namespace Inchoo\Search\Controller\Relearn;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;
use Inchoo\Search\Helper\Vehicle;

class Url extends \Magento\Framework\App\Action\Action {

    protected $_logger, $_vehicle;

    public function __construct(Context $context, LoggerInterface $logger, Vehicle $vehicle) {
        parent::__construct($context);

        $this->_logger = $logger;
        $this->_vehicle = $vehicle;
    }

    public function execute() {
        try {
            $yearId = $this->getRequest()->getParam('year');
            $makeId = $this->getRequest()->getParam('make');
            $modelId = $this->getRequest()->getParam('model');
            $submodelId = $this->getRequest()->getParam('submodel');
            
            $url = $this->_vehicle->getRelearnUrl($yearId, $makeId, $modelId, $submodelId);
            if ($url) {
                $output = sprintf('<a href="%s" target="_blank">Click here to display reset procedure</a>', $url);
            } else {
                $output = 'Reset Procedure Is Not Available';
            }
            echo $output;
            die;
        } catch (Exception $e) {
            $this->_logger->critical('Error message', ['exception' => $e]);
        }
    }

}
