<?php
namespace Custom\Coyote\Block\Adminhtml;

class Log extends \Magento\Backend\Block\Widget\Grid\Container{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_log';
        $this->_blockGroup         = 'coyote';
        $this->_headerText         = 'Product Log';
        $this->_removeButton('add');
        parent::__construct();

    }
}
