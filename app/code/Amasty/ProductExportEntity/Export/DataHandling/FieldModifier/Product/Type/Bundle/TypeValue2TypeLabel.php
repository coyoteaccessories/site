<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExportEntity
 */

declare(strict_types=1);

namespace Amasty\ProductExportEntity\Export\DataHandling\FieldModifier\Product\Type\Bundle;

use Amasty\ProductExportEntity\Export\DataHandling\FieldModifier\SourceOption\Value2Label;
use Amasty\ProductExportEntity\Export\Product\Type\Bundle\SourceOption\TypeOptions;

class TypeValue2TypeLabel extends Value2Label
{
    /**
     * @var TypeOptions
     */
    private $sourceModel;

    public function __construct(TypeOptions $sourceModel)
    {
        $this->sourceModel = $sourceModel;
    }

    protected function getSourceModel()
    {
        return $this->sourceModel;
    }
}
