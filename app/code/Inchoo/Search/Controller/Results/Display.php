<?php

namespace Inchoo\Search\Controller\Results;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;

class Display extends \Magento\Framework\App\Action\Action {

    protected $_logger;

    public function __construct(Context $context, LoggerInterface $logger) {
        parent::__construct($context);

        $this->_logger = $logger;
    }

    public function execute() {
        $this->_view->loadLayout();

        try {
            
        } catch (\Exception $e) {
            $this->_logger->critical('Error message', ['exception' => $e->getMessage()]);
        }

        $this->_view->renderLayout();
    }

}
