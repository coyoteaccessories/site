<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Api;

interface ProductLabelRepositoryInterface
{

    /**
     * @param \Zemez\ProductLabels\Api\Data\ProductLabelInterface $productLabel
     * @return \Zemez\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Zemez\ProductLabels\Api\Data\ProductLabelInterface $productLabel);

    /**
     * @param $productLabelId
     * @return \Zemez\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($productLabelId);

    /**
     * @param \Zemez\ProductLabels\Api\Data\ProductLabelInterface $productLabel
     * @return \Zemez\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Zemez\ProductLabels\Api\Data\ProductLabelInterface $productLabel);

    /**
     * @param $productLabelId
     * @return \Zemez\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($productLabelId);

    /**
     * @return \Zemez\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getModelInstance();
}
