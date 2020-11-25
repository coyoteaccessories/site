<?php

namespace Inchoo\Search\Controller\Index;

use Magento\Framework\App\Action\Context;

class ClearGarage extends \Magento\Framework\App\Action\Action {

    protected $_customerSession, $_responseFactory, $_url;

    public function __construct(Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\UrlInterface $url) {
        parent::__construct($context);

        $this->_customerSession = $customerSession;
        $this->_url = $url;
    }

    public function execute() {
        $this->_customerSession->unsMyGarage();

        $url = $this->_url->getBaseUrl();
        $this->getResponse()->setRedirect($url);
    }

}
