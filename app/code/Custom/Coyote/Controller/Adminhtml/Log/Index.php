<?php

namespace Custom\Coyote\Controller\Adminhtml\Log;

class Index extends \Magento\Backend\App\Action {

    public function execute() {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
