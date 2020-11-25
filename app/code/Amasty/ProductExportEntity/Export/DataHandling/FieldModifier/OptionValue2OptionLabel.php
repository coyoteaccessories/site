<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExportEntity
 */


declare(strict_types=1);

namespace Amasty\ProductExportEntity\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;

class OptionValue2OptionLabel implements FieldModifierInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array|null
     */
    private $map;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function transform($value)
    {
        $map = $this->getMap();
        return $map[$value] ?? $value;
    }

    /**
     * Get option value to option label map
     *
     * @return array
     */
    private function getMap()
    {
        if (!$this->map) {
            $this->map = [];
            if (isset($this->config['options'])
                && is_array($this->config['options'])
            ) {
                foreach ($this->config['options'] as $option) {
                    $this->map[$option['value']] = $option['label'];
                }
            }
        }
        return $this->map;
    }
}
