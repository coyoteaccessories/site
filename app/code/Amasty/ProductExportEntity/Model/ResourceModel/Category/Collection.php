<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExportEntity
 */


declare(strict_types=1);

namespace Amasty\ProductExportEntity\Model\ResourceModel\Category;

use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\Data\Collection\AbstractDb;

class Collection extends CategoryCollection
{
    const NON_ATTRIBUTE_FIELDS = ['created_in', 'updated_in'];

    /**
     * Wrapper for compatibility with \Magento\Framework\Data\Collection\AbstractDb
     * Fixed filtering by the field "created_in" and "updated_in"
     *
     * @param mixed $attribute
     * @param mixed $condition
     * @return Collection|AbstractDb
     * @codeCoverageIgnore
     */
    public function addFieldToFilter($attribute, $condition = null)
    {
        if (in_array($attribute, self::NON_ATTRIBUTE_FIELDS)) {
            return AbstractDb::addFieldToFilter($attribute, $condition);
        } else {
            return parent::addFieldToFilter($attribute, $condition);
        }
    }
}
