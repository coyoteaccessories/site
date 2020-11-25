<?php

namespace Inchoo\Search\Controller\Tpmshelp;

use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action {

    public function __construct(Context $context) {
        parent::__construct($context);
    }

    public function execute() {
        $this->_view->loadLayout();
//        $this->_initLayoutMessages('catalogsearch/session');
        $this->_view->renderLayout();
    }

}
