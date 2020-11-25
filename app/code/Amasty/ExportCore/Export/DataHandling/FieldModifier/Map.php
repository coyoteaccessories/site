<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;

class Map implements FieldModifierInterface
{
    const MAP = 'map';

    const DEFAULT_SETTINGS = [
        self::MAP => []
    ];

    /** @var array */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = array_merge(self::DEFAULT_SETTINGS, $config);
    }

    public function transform($value): string
    {
        return $this->config[self::MAP][$value] ?? $value;
    }
}
