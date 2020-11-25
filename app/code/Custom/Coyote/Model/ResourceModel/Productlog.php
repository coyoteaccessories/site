<?php

namespace Custom\Coyote\Model\ResourceModel;

class Productlog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context) {
        parent::__construct($context);
    }

    protected function _construct() {
        $this->_init('external_product_log', 'entity_id');
    }
}
