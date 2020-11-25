<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExportEntity
 */


declare(strict_types=1);

namespace Amasty\ProductExportEntity\Export\DataHandling\FieldModifier;

use Amasty\ExportCore\Api\FieldModifier\FieldModifierInterface;
use Magento\Store\Model\StoreManagerInterface;

class WebsiteId2WebsiteCode implements FieldModifierInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var array|null
     */
    private $map;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
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
    private function getMap()
    {
        if (!$this->map) {
            $this->map = [0 => __('All Websites')];
            foreach ($this->storeManager->getWebsites() as $website) {
                $this->map[$website->getId()] = $website->getCode();
            }
        }
        return $this->map;
    }
}
