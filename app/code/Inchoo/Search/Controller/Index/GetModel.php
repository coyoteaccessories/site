<?php

namespace Inchoo\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;
use Inchoo\Search\Helper\Vehicle;

class GetModel extends \Magento\Framework\App\Action\Action {

    protected $_logger;

    public function __construct(Context $context, LoggerInterface $logger, Vehicle $vehicle) {
        parent::__construct($context);

        $this->_logger = $logger;
        $this->_vehicle = $vehicle;
    }

    public function execute() {
        try {
            $yearId = $this->getRequest()->getParam('year');
            $makeId = $this->getRequest()->getParam('make');
            if (!$yearId || !$makeId) {
                die;
            }
            $data = $this->_vehicle->getModels($yearId, $makeId);

            $options = ['' => 'Model'];
            foreach ($data as $key => $value) {
                $options[$key] = $value;
            }

            echo \Inchoo\Search\Helper\Data::getOptionsHtml($options);
            die;
        } catch (\Exception $e) {
            $this->_logger->critical('Error message', ['exception' => $e]);
        }
    }

}
