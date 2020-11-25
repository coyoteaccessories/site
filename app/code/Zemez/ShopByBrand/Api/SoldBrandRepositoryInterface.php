<?php

namespace Zemez\ShopByBrand\Api;

use Zemez\ShopByBrand\Api\Data\SoldBrandInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface SoldBrandRepositoryInterface
 *
 * @package Zemez\ShopByBrand\Api
 */
interface SoldBrandRepositoryInterface
{
    /**
     * Save sold brand.
     *
     * @param \Zemez\ShopByBrand\Api\Data\SoldBrandInterface $soldBrand
     * @return \Zemez\ShopByBrand\Api\Data\SoldBrandInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(SoldBrandInterface $soldBrand);

    /**
     * Retrieve sold brand.
     *
     * @param int $soldBrandId
     * @return \Zemez\ShopByBrand\Api\Data\SoldBrandInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($soldBrandId);

    /**
     * Retrieve sold brands matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Zemez\ShopByBrand\Api\Data\BrandSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete sold brand.
     *
     * @param \Zemez\ShopByBrand\Api\Data\SoldBrandInterface $soldBrand
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(SoldBrandInterface $soldBrand);

    /**
     * Delete sold brand by ID.
     *
     * @param int $soldBrandId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($soldBrandId);
}