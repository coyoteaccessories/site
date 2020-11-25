<?php
/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FeaturedProduct\Model;


class RatedProducts extends \Zemez\FeaturedProduct\Model\FeaturedProductAbstract
{

    public function getFeature(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection,$collectionSize)
    {
        $collection->setPageSize(
            $collectionSize
        )->setCurPage(
            1
        )->getSelect()
            ->from(
                ['review_entity_summary' => $collection->getTable('review_entity_summary')],
                ['summary' => 'SUM(review_entity_summary.rating_summary)']

            )->where('e.entity_id = review_entity_summary.entity_pk_value'
            )->group(
                'review_entity_summary.entity_pk_value'
            )->order(
                'summary ' . \Magento\Framework\DB\Select::SQL_DESC
            )->having(
                'SUM(review_entity_summary.rating_summary) > ?',
                0
            );
        return $collection;
    }

}