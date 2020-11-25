<?php
/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ShopByBrand\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface BrandSearchResultsInterface
 *
 * @package Zemez\ShopByBrand\Api\Data
 */
interface BrandSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return \Zemez\ShopByBrand\Api\Data\BrandInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \Zemez\ShopByBrand\Api\Data\BrandInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}