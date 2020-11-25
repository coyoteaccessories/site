<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */

namespace Amasty\Flags\Api;

use Amasty\Flags\Api\Data\ColumnInterface;

interface ColumnRepositoryInterface
{
    /**
     * @param int $id Flag ID.
     * @return ColumnInterface
     */
    public function get($id);

    public function delete(ColumnInterface $entity);

    public function save(ColumnInterface $entity);
}
