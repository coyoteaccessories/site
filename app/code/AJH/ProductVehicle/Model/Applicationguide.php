<?php

namespace AJH\ProductVehicle\Model;

use Magento\Framework\Model\AbstractModel;
use AJH\ProductVehicle\Model\ResourceModel\Applicationguide as ResourceModel;

class Applicationguide extends AbstractModel {

    public function _construct() {
        $this->_init(ResourceModel::class);
    }

}
