<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Model\Filter;

class IsOnSale
{
    /**
     * @param $productCollection
     */
    public function onSale($productCollection)
    {
        $productCollection->getSelect()->where('price_index.final_price < price_index.price');
    }

    /**
     * @param $productCollection
     */
    public function notOnSale($productCollection)
    {
        $productCollection->getSelect()->where(
            'price_index.price IS NULL OR price_index.final_price >= price_index.price'
        );
    }
}
