<?php
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\Layer\ContextInterface;

namespace AJH\Fitment\Model\Catalog;

class Layer extends \Magento\Catalog\Model\Layer {

    public function prepareProductCollection($collection) {
        parent::prepareProductCollection($collection);

        return $this;
    }

    public function getProductCollection() {
                

        return Mage::getSingleton('AJH_Fitment_Model_Fitment_Api')->getVehicleParts();
    }

}
