<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Block\Adminhtml\Slider\Edit\Tab;

use Magento\Backend\Block\Widget\Tab;

class Slides  extends Tab
{
    public function getLabel()
    {
        return 'Slider Items';
    }

    public function getTitle()
    {
        return 'Slider Items';
    }

    public function canShowTab()
    {
        return true;
    }

    protected function _toHtml()
    {
        return $this->getLayout()->createBlock(
            'Zemez\FilmSlider\Block\Adminhtml\Slider\Edit\Tab\Slides\SliderItemGrid',
            'category.product.grid'
        )->toHtml();
    }
}
