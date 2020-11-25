<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;

class DefaultValue implements FieldModifierInterface
{
    /**
     * @var bool
     */
    private $force = false;

    private $value;

    public function __construct($config)
    {
        if (isset($config['force'])) {
            //TODO 'true'
            $this->force = $config['force'] === 'true';
        }

        if (!isset($config['value'])) {
            throw new \LogicException('DefaultValue action value is not set');
        }

        $this->value = $config['value'];
    }

    public function transform($value)
    {
        if ($this->force) {
            return $this->value;
        }

        return ($value === null || $value === '') ? $this->value : $value;
    }
}
