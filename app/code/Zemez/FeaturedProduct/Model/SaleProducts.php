<?php
/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FeaturedProduct\Model;


class SaleProducts extends \Zemez\FeaturedProduct\Model\FeaturedProductAbstract
{

    public function getFeature(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection,$collectionSize)
    {

        $collection->setPageSize(
            $collectionSize
        )->setCurPage(
            1
        )->getSelect()->where('price_index.final_price < price_index.price');

        return $collection;
    }

}