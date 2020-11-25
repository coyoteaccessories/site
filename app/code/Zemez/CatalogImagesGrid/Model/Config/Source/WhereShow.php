<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */
namespace Zemez\CatalogImagesGrid\Model\Config\Source;


class WhereShow implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'before_products_grid', 'label' => __('Before Products Grid')],
                ['value' => 'after_products_grid', 'label' => __('After Products Grid')]];
    }
}