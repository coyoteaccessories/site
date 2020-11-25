<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Amasty\ExportCore\Export\DataHandling\ActionConfigBuilder;

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
        if ($this->config[ActionConfigBuilder::IS_MULTISELECT] && !empty($value)) {
            $multiSelectOptions = explode(',', $value);
            $result = [];
            foreach ($multiSelectOptions as $option) {
                if (array_key_exists($option, $map)) {
                    $result[] = $map[$option];
                }
            }

            return implode(',', $result);
        } else {
            return $map[$value] ?? $value;
        }
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
