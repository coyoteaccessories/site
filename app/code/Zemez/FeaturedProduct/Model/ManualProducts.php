<?php
/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FeaturedProduct\Model;


class ManualProducts extends \Zemez\FeaturedProduct\Model\FeaturedProductAbstract
{

    public function getFeature(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection,$collectionSize)
    {
        $collection->addFieldToFilter('entity_id',0)
            ->setPageSize($collectionSize)
            ->setCurPage(1);
    }

}