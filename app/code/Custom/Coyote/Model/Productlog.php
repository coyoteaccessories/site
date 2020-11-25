<?php

namespace Custom\Coyote\Model;

class Productlog extends \Magento\Framework\Model\AbstractModel {

    protected function _construct() {
        $this->_init('Custom\Coyote\Model\ResourceModel\Productlog');
    }

}
