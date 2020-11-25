<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Block\Adminhtml\Slider\Edit\Tab\Slides\Column;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Status extends AbstractRenderer
{
    public function render(DataObject $row)
    {
        return $row->getStatus() ? __('enable') : __('disable');
    }
}
