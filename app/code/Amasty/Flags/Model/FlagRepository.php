<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */


namespace Amasty\Flags\Model;

use Amasty\Flags\Api\Data\FlagInterface;
use Amasty\Flags\Api\FlagRepositoryInterface;
use Amasty\Flags\Model\ResourceModel\Flag as FlagResource;
use Amasty\Flags\Model\FlagFactory;

class FlagRepository implements FlagRepositoryInterface
{
    /**
     * @var ResourceModel\Flag
     */
    private $flagResource;
    /**
     * @var FlagFactory
     */
    private $flagFactory;

    public function __construct(
        FlagResource $flagResource,
        FlagFactory $flagFactory
    ) {
        $this->flagResource = $flagResource;
        $this->flagFactory = $flagFactory;
    }

    /**
     * @param int $id Flag ID.
     *
     * @return FlagInterface
     */
    public function get($id)
    {
        $model = $this->flagFactory->create();

        $this->flagResource->load($model, $id);

        return $model;
    }

    public function delete(FlagInterface $entity)
    {
        return $this->flagResource->delete($entity);
    }

    public function save(FlagInterface $entity)
    {
        return $this->flagResource->save($entity);
    }
}
