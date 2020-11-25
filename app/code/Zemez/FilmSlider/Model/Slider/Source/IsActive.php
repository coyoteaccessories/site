<?php

/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Model\Slider\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Zemez\FilmSlider\Model\Slider;

class IsActive implements OptionSourceInterface
{


    protected $slider;


    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->slider->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
