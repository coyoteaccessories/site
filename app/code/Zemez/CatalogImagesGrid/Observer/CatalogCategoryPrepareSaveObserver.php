<?php
namespace Zemez\CatalogImagesGrid\Observer;

/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

use Magento\Framework\Event\ObserverInterface;

class CatalogCategoryPrepareSaveObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $category = $observer->getCategory();

        $thumbnail = $category->getThumbnail();
        if(!$thumbnail) {
            $category->setThumbnail(['delete' => true]);
        } else if(isset($thumbnail[0]['name'])) {
            $category->setThumbnail($thumbnail);
        }
    }
}