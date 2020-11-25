<?php

namespace Inchoo\Search\Model\Resource\Vehiclestpms;

use AJH\ProductVehicle\Model\Vehiclestpms as Model;
use AJH\ProductVehicle\Model\ResourceModel\Vehiclestpms as ResourceModel;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    public function _construct() {
        $this->_init(Model::class, ResourceModel::class);
    }

}
