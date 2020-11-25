<?php

namespace Zemez\NewsletterPopup\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Abstract settings controller.
 *
 * @package Zemez\NewsletterPopup\Controller\Adminhtml
 */
abstract class Settings extends Action
{
    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zemez_NewsletterPopup::newsletter_popup_config');
    }
}