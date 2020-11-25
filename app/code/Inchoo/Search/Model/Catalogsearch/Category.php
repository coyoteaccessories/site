<?php

namespace Inchoo\Search\Model\Catalogsearch;

use Magento\Store\Model\StoreManagerInterface;

class Category {

    protected static $_catIds = null;
    protected static $_storeId = null;
    protected static $_storeManager;

    public function __construct(StoreManagerInterface $storeManager) {
        self::$_storeManager = $storeManager;

        self::_init();
    }

    public static function _init() {
        // store ID 3 is for PDQ website -> pdqmainwebsite -> English store view
        self::$_catIds = [
            18 => [3 => 120, 41 => 115], // PDQ Sensors, OROTek Sensors
            20 => [3 => 121, 41 => 136], // PDQ Service Kits, OROTek Service Kits
            21 => [3 => 122], // Accessories
            22 => [3 => 123], //Trigger & Reset Tools
        ];
    }

    public static function getStoreId() {
        if (null === self::$_storeId) {
            self::$_storeId = self::$_storeManager->getStore()->getId();
        }
        return self::$_storeId;
    }

    public static function id($id) {
        if (isset(self::$_catIds[$id])) {
            $storeId = self::getStoreId();
            if (isset(self::$_catIds[$id][$storeId])) {
                return self::$_catIds[$id][$storeId];
            } else {
                return $id;
            }
        }
        return $id;
    }

    public static function ids($ids) {
        $res = [];
        foreach ($ids as $id) {
            $res[] = self::id($id);
        }
        return $res;
    }

}

//\Inchoo\Search\Model\Catalogsearch\Category::_init();
