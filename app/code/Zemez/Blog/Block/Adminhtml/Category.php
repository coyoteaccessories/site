<?php
namespace Zemez\Blog\Block\Adminhtml;

/**
 * Adminhtml tm_blog blocks content block
 */
class Category extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Zemez_Blog';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Categories');
        $this->_addButtonLabel = __('Add New Category');
        parent::_construct();
    }
}
