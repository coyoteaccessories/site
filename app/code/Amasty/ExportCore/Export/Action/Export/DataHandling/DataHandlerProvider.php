<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\Action\Export\DataHandling;

use Amasty\ExportCore\Api\Config\EntityConfigInterface;
use Amasty\ExportCore\Api\Config\Profile\FieldInterface;
use Amasty\ExportCore\Api\Config\Profile\FieldsConfigInterface;
use Amasty\ExportCore\Api\ExportProcessInterface;
use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Amasty\ExportCore\Export\Config\EntityConfigProvider;
use Amasty\ExportCore\Export\Config\RelationConfigProvider;
use Amasty\ImportExportCore\Config\ConfigClass\Factory as ConfigClassFactory;

class DataHandlerProvider
{
    const SUBENTITIES_KEY = 'sub';

    /**
     * @var ConfigClassFactory
     */
    private $configClassFactory;

    /**
     * @var EntityConfigProvider
     */
    private $entityConfigProvider;

    /**
     * @var RelationConfigProvider
     */
    private $relationConfigProvider;

    public function __construct(
        ConfigClassFactory $configClassFactory,
        EntityConfigProvider $entityConfigProvider,
        RelationConfigProvider $relationConfigProvider
    ) {
        $this->configClassFactory = $configClassFactory;
        $this->entityConfigProvider = $entityConfigProvider;
        $this->relationConfigProvider = $relationConfigProvider;
    }

    /**
     * @param ExportProcessInterface $exportProcess
     *
     * @return FieldModifierInterface[][]
     */
    public function prepareModifiers(ExportProcessInterface $exportProcess): array
    {
        $relations = $this->relationConfigProvider->get($exportProcess->getProfileConfig()->getEntityCode());
        return $this->processFields(
            $exportProcess->getEntityConfig(),
            $exportProcess->getProfileConfig()->getFieldsConfig(),
            $relations
        );
    }

    protected function processFields(
        EntityConfigInterface $entityConfig,
        FieldsConfigInterface $fieldsConfig,
        ?array $relations
    ): array {
        $result = [];
        if (!empty($fieldsConfig->getFields())) {
            foreach ($fieldsConfig->getFields() as $field) {
                if ($field->getType() === FieldInterface::FIELD_TYPE
                    && ($actions = $this->getFieldActions($field, $entityConfig))
                ) {
                    $result[$field->getName()] = $actions;
                }
            }
        }

        if (!empty($fieldsConfig->getSubEntitiesFieldsConfig()) && !empty($relations)) {
            foreach ($relations as $relation) {
                foreach ($fieldsConfig->getSubEntitiesFieldsConfig() as $subEntityFieldsConfig) {
                    if ($relation->getSubEntityFieldName() == $subEntityFieldsConfig->getName()) {
                        $subEntityFieldsActions =  $this->processFields(
                            $this->entityConfigProvider->get($relation->getChildEntityCode()),
                            $subEntityFieldsConfig,
                            $relation->getRelations()
                        );
                        if (!empty($subEntityFieldsActions)) {
                            $result[self::SUBENTITIES_KEY][$subEntityFieldsConfig->getName()] = $subEntityFieldsActions;
                        }
                        break;
                    }
                }
            }
        }

        return $result;
    }

    protected function getFieldActions(FieldInterface $field, EntityConfigInterface $entityConfig): array
    {
        $actions = [];

        foreach ($entityConfig->getFieldsConfig()->getFields() as $fieldConfig) {
            if ($fieldConfig->getName() === $field->getName()) {
                if (!empty($fieldConfig->getActions())) {
                    foreach ($fieldConfig->getActions() as $action) {
                        if (!$action->getConfigClass()) {
                            continue;
                        }
                        $actions[] = $this->configClassFactory->createObject($action->getConfigClass());
                    }
                }
            }
        }

        return $actions;
    }
}
