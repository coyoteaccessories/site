<?php

namespace Inchoo\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;

class GetYear extends \Magento\Framework\App\Action\Action {

    protected $_logger;

    public function __construct(Context $context, LoggerInterface $logger) {
        parent::__construct($context);

        $this->_logger = $logger;
    }

    public function execute() {
        try {
            $years = \Inchoo\Search\Helper\Vehicle::getYears();
            $options = [0 => 'Year'] + array_combine($years, $years);
            echo \Inchoo\Search\Helper\Data::getOptionsHtml($options);
            die;
        } catch (\Exception $e) {
            $this->_logger->critical('Error message', ['exception' => $e]);
        }
    }

}
