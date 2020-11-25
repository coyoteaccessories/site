<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_InventoryExportEntity
 */


declare(strict_types=1);

namespace Amasty\InventoryExportEntity\Export\FieldsClass;

use Amasty\ExportCore\Api\Config\Entity\FieldsConfigInterface;
use Amasty\ExportCore\Export\Config\EntityConfig;

class DescribeAndSkipNonExistent extends \Amasty\ExportCore\Export\FieldsClass\Describe
{
    public function execute(FieldsConfigInterface $existingConfig, EntityConfig $entityConfig): FieldsConfigInterface
    {
        $descriptionFieldNames = array_keys($this->describe($entityConfig));
        $existingFields = $existingConfig->getFields();

        foreach ($existingFields as $fieldKey => $fieldConfig) {
            if (!in_array($fieldConfig->getName(), $descriptionFieldNames)) {
                unset($existingFields[$fieldKey]);
            }
        }

        $existingConfig->setFields($existingFields);

        return parent::execute($existingConfig, $entityConfig);
    }
}
