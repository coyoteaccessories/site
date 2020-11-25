<?php

namespace AJH\Fitment\Controller\Index;

class Categories extends \Magento\Framework\App\Action\Action {

    protected $_pageFactory;

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute() {        
        $this->_view->loadLayout();
//        if ((bool) $this->getRequest()->getParam('isajax')) { // ?ajax=true
//            $this->getLayout()->getBlock('root')->setTemplate('page/content.phtml');  //changes the root template
//        }
        $this->_view->renderLayout();
        
//        return $this->_pageFactory->create();
    }

}
