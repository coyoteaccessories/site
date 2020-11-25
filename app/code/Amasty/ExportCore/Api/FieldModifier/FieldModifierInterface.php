<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


namespace Amasty\ExportCore\Api\FieldModifier;

interface FieldModifierInterface
{
    /**
     * @param $value
     * @return mixed
     */
    public function transform($value);
}
