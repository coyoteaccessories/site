<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling;

use Amasty\ExportCore\Api\Config\Entity\Field\ActionInterface;
use Amasty\ExportCore\Api\Config\Entity\Field\ActionInterfaceFactory;
use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Amasty\ExportCore\Export\DataHandling\FieldModifier\OptionValue2OptionLabel;
use Amasty\ImportExportCore\Api\Config\ConfigClass\ArgumentInterface;
use Amasty\ImportExportCore\Api\Config\ConfigClass\ConfigClassInterface;
use Amasty\ImportExportCore\Api\Config\ConfigClass\ConfigClassInterfaceFactory;
use Amasty\ImportExportCore\Config\Xml\ArgumentsPrepare;

class ActionConfigBuilder
{
    const IS_MULTISELECT = 'isMultiselect';

    /**
     * @var ActionInterfaceFactory
     */
    private $actionFactory;

    /**
     * @var ConfigClassInterfaceFactory
     */
    private $configClassFactory;

    /**
     * @var ArgumentInterface[]
     */
    private $optionsArguments = [];

    /**
     * @var bool
     */
    private $isMultiselect;

    /**
     * @var ArgumentsPrepare
     */
    private $argumentsPrepare;

    public function __construct(
        ActionInterfaceFactory $actionFactory,
        ConfigClassInterfaceFactory $configClassFactory,
        ArgumentsPrepare $argumentsPrepare
    ) {
        $this->actionFactory = $actionFactory;
        $this->configClassFactory = $configClassFactory;
        $this->argumentsPrepare = $argumentsPrepare;
    }

    /**
     * Set options arguments
     *
     * @param ArgumentInterface[] $arguments
     *
     * @return $this
     */
    public function setOptionsArguments($arguments)
    {
        $this->optionsArguments = $arguments;

        return $this;
    }

    /**
     * Set multiselect attribute type
     *
     * @param bool $isMultiselect
     *
     * @return $this
     */
    public function setIsMultiselect($isMultiselect)
    {
        $this->isMultiselect = $this->argumentsPrepare->execute(
            [
                self::IS_MULTISELECT => [
                    'name'     => self::IS_MULTISELECT,
                    'xsi:type' => 'boolean',
                    'value'    => $isMultiselect
                ]
            ]
        );

        return $this;
    }

    /**
     * Build field action config instance
     *
     * @return ActionInterface|null
     */
    public function build()
    {
        $result = $this->isValidForBuild()
            ? $this->performBuild()
            : null;
        $this->resetState();

        return $result;
    }

    /**
     * Performs build
     *
     * @return ActionInterface
     */
    private function performBuild()
    {
        /** @var ConfigClassInterface $class */
        $class = $this->configClassFactory->create(
            [
                'baseType' => FieldModifierInterface::class,
                'name' => OptionValue2OptionLabel::class,
                'arguments' => array_merge($this->optionsArguments, $this->isMultiselect)
            ]
        );

        /** @var ActionInterface $action */
        $action = $this->actionFactory->create();
        $action->setConfigClass($class);

        return $action;
    }

    /**
     * Checks if the builder state valid for build
     *
     * @return bool
     */
    private function isValidForBuild()
    {
        return !empty($this->optionsArguments);
    }

    /**
     * Reset builder state
     *
     * @return void
     */
    private function resetState()
    {
        $this->optionsArguments = null;
    }
}
