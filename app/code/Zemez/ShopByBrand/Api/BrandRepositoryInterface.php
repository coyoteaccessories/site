<?php

namespace Zemez\ShopByBrand\Api;

use Zemez\ShopByBrand\Api\Data\BrandInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface BrandRepositoryInterface
 *
 * @package Zemez\ShopByBrand\Api
 */
interface BrandRepositoryInterface
{
    /**
     * Save brand.
     *
     * @param \Zemez\ShopByBrand\Api\Data\BrandInterface $brand
     * @return \Zemez\ShopByBrand\Api\Data\BrandInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(BrandInterface $brand);

    /**
     * Retrieve brand.
     *
     * @param int $brandId
     * @return \Zemez\ShopByBrand\Api\Data\BrandInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($brandId);

    /**
     * Retrieve brands matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Zemez\ShopByBrand\Api\Data\BrandSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete brand.
     *
     * @param \Zemez\ShopByBrand\Api\Data\BrandInterface $brand
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(BrandInterface $brand);

    /**
     * Delete brand by ID.
     *
     * @param int $brandId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($brandId);
}