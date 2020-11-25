<?php

namespace AJH\Fitment\Model\Resource;

class Catalog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

    public function _construct() {
        $this->_init('fitment/catalog', 'PartMasterID');
    }

}
