<?php

namespace Inchoo\Search\Controller\Relearn;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;

class Years extends \Magento\Framework\App\Action\Action {

    protected $_logger;

    public function __construct(Context $context, LoggerInterface $logger) {

        parent::__construct($context);

        $this->_logger = $logger;
    }

    public function execute() {
        if ($this->getRequest()->isAjax()) {
            try {
                $years = \Inchoo\Search\Helper\Data::getYears();
                $options = [0 => 'Year'] + array_combine($years, $years);
                echo \Inchoo\Search\Helper\Data::getOptionsHtml($options);
                die;
            } catch (\Exception $e) {
                $this->_logger->critical('Error message', ['exception' => $e]);
            }
        }
    }

}
