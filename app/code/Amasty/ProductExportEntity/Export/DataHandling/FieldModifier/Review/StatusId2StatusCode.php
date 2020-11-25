<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExportEntity
 */


declare(strict_types=1);

namespace Amasty\ProductExportEntity\Export\DataHandling\FieldModifier\Review;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Magento\Review\Helper\Data as StatusSource;

class StatusId2StatusCode implements FieldModifierInterface
{
    /**
     * @var StatusSource
     */
    private $source;

    /**
     * @var array|null
     */
    private $map;

    public function __construct(StatusSource $source)
    {
        $this->source = $source;
    }

    public function transform($value)
    {
        $map = $this->getMap();
        return $map[$value] ?? $value;
    }

    /**
     * Get website Id to website code map
     *
     * @return array
     */
    private function getMap(): ?array
    {
        if (!$this->map) {
            $this->map = $this->source->getReviewStatuses();
        }
        return $this->map;
    }
}
