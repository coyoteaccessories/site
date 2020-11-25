<?php

namespace Inchoo\Search\Controller\Results;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;

class For_ extends \Magento\Framework\App\Action\Action {

    protected $_logger;

    public function __construct(Context $context, LoggerInterface $logger) {
        parent::__construct($context);

        $this->_logger = $logger;
    }

    public function execute() {
        $this->_view->loadLayout();
        try {

//            $this->_initLayoutMessages('catalog/session');
            $this->_view->renderLayout();
        } catch (\Exception $e) {
//            Mage::getSingleton('catalogsearch/session')->addError($e->getMessage());
//            $this->_redirectError(
//                    Mage::getModel('core/url')
//                            ->setQueryParams($this->getRequest()->getQuery())
//                            ->getUrl('*')//redirect on exception (e.g. search term is empty); URL: example.com/module_frontname/
//            );

            $this->_logger->critical('Error message', ['exception' => $e->getMessage()]);
        }
    }

}
