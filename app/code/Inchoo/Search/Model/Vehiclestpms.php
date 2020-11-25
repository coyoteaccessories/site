<?php

namespace Inchoo\Search\Model;

use Magento\Framework\Model\AbstractModel;
use Inchoo\Search\Model\ResourceModel\Vehicletpms as ResourceModel;

class Vehiclestpms extends AbstractModel {

    public function _construct() {
        $this->_init(ResourceModel::class);
    }

}
