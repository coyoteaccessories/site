<?php

namespace AJH\ProductVehicle\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Partclass extends AbstractDb {

    public function _construct() {
        $this->_init('AJH\ProductVehicle\Model\Resource\Partclass', 'ID');
    }

}
