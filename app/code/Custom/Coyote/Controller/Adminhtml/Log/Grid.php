<?php

namespace Custom\Coyote\Controller\Adminhtml\Log;

class Grid extends \Magento\Backend\App\Action {

    public function execute() {
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('Custom\Coyote\Block\Adminhtml\Log\Grid')->toHtml()
        );
    }

}
