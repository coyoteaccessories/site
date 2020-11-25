<?php
/**
 * Copyright © Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zemez\Blog\Block\Adminhtml;

/**
 * Adminhtml tm_blog blocks content block
 */
class Comment extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Zemez_Blog';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Comments');
        $this->_addButtonLabel = __('Add New Comment');
        parent::_construct();
    }
}
