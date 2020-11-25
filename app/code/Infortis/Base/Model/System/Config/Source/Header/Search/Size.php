<?php

namespace Infortis\Base\Model\System\Config\Source\Header\Search;

class Size
{
    public function toOptionArray()
    {
        return [
            ['value' => 'xl',   'label' => __('XL')],
            ['value' => 'l',    'label' => __('L')],
            ['value' => '',     'label' => __('M (default)')],
            ['value' => 's',    'label' => __('S')],
        ];
    }
}
