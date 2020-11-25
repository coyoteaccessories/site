<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */

namespace Amasty\Flags\Api;

use Amasty\Flags\Api\Data\FlagInterface;

interface FlagRepositoryInterface
{
    /**
     * @param int $id Flag ID.
     * @return FlagInterface
     */
    public function get($id);

    public function delete(FlagInterface $entity);

    public function save(FlagInterface $entity);
}
