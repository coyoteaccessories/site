<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;

class Uppercase implements FieldModifierInterface
{
    public function transform($value): string
    {
        return mb_strtoupper($value);
    }
}
