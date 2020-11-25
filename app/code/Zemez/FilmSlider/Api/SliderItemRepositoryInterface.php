<?php

/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Api;

interface SliderItemRepositoryInterface
{

    /**
     * Save sliderItem.
     *
     * @param \Zemez\FilmSlider\Api\Data\SliderItemInterface $sliderItem
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Zemez\FilmSlider\Api\Data\SliderItemInterface $slider);

    /**
     * Retrieve sliderItem.
     *
     * @param int $sliderItemId
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($sliderItemId);

    /**
     * Retrieve sliderItems matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete sliderItem.
     *
     * @param \Zemez\FilmSlider\Api\Data\SliderItemInterface $sliderItem
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Zemez\FilmSlider\Api\Data\SliderItemInterface $sliderItem);

    /**
     * Delete sliderItem by ID.
     *
     * @param int $sliderItemId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($sliderItemId);

    /**
     * @return mixed
     */
    public function getModelInstance();
}
