<?php

namespace AJH\ProductVehicle\Model;

use Magento\Framework\Model\AbstractModel;
use AJH\ProductVehicle\Model\ResourceModel\Models as ResourceModel;

class Models extends AbstractModel {

    public function _construct() {
        $this->_init(ResourceModel::class);
    }

}
