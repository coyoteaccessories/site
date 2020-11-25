<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ExportCore
 */


declare(strict_types=1);

namespace Amasty\ExportCore\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;

class StoreId2StoreCode implements FieldModifierInterface
{
    private $stores;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $stores = $storeManager->getStores(true);
        foreach ($stores as $store) {
            $this->stores[$store->getId()] = $store->getCode();
        }
    }

    public function transform($value)
    {
        if (is_array($value)) {
            foreach ($value as &$storeId) {
                $storeId = $this->stores[$storeId] ?? 'all';
            }

            return $value;
        }

        return $this->stores[$value] ?? 'all';
    }
}
