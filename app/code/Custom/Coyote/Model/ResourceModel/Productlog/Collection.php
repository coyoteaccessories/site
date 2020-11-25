<?php

namespace Custom\Coyote\Model\ResourceModel\Productlog;

use Custom\Coyote\Model\ResourceModel\Productlog as ResourceModel;
use Custom\Coyote\Model\Productlog as Model;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'custom_coyote_productlog_collection';
    protected $_eventObject = 'productlog_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init(Model::class, ResourceModel::class);
    }

}
