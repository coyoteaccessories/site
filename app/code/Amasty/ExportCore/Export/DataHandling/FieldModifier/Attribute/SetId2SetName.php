<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier\Attribute;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;

class SetId2SetName implements FieldModifierInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var ProductResource
     */
    private $productResource;

    /**
     * @var array|null
     */
    private $map;

    public function __construct(
        CollectionFactory $collectionFactory,
        ProductResource $productResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->productResource = $productResource;
    }

    public function transform($value)
    {
        $map = $this->getMap();
        return $map[$value] ?? $value;
    }

    /**
     * Get attribute set Id to attribute set code map
     *
     * @return array
     */
    private function getMap()
    {
        if (!$this->map) {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $this->map = $collection->setEntityTypeFilter($this->productResource->getTypeId())
                ->toOptionHash();
        }
        return $this->map;
    }
}
