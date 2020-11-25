<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Model;

use Zemez\ProductLabels\Model\ResourceModel\ProductLabel as Label;
use Zemez\ProductLabels\Api\Data\ProductLabelInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductLabelRepository implements \Zemez\ProductLabels\Api\ProductLabelRepositoryInterface
{
    /**
     * @var ResourceProductLabel
     */
    protected $_resource;
    /**
     * @var ProductLabel
     */
    protected $_productLabelFactory;

    public function __construct(Label $resource, ProductLabelInterfaceFactory $productLabelFactory)
    {
        $this->_resource = $resource;
        $this->_productLabelFactory = $productLabelFactory;
    }

    public function save(\Zemez\ProductLabels\Api\Data\ProductLabelInterface $productLabel)
    {
        try {
            $this->_resource->save($productLabel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $productLabel;
    }

    public function getById($productLabelId)
    {
        $productLabel = $this->_productLabelFactory->create();
        try {
            $productLabel->load($productLabelId);
        } catch (\Exception $e) {
            throw new NoSuchEntityException(__('Product label with id "%1" does not exist.', $productLabelId));
        }
        if (!$productLabel->getId()) {
            throw new NoSuchEntityException(__('Product label with id "%1" does not exist.', $productLabelId));
        }

        return $productLabel;
    }

    public function delete(\Zemez\ProductLabels\Api\Data\ProductLabelInterface $productLabel)
    {
        try {
            $this->_resource->delete($productLabel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function deleteById($productLabelId)
    {
        $this->delete($this->getById($productLabelId));
    }

    public function getModelInstance()
    {
        return $this->_productLabelFactory->create();
    }
}
