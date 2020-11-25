<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */


namespace Amasty\Flags\Block\Adminhtml\Flag\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('amasty_flags_flag_edit_tabs');
        $this->setDestElementId('edit_form');
    }
}
