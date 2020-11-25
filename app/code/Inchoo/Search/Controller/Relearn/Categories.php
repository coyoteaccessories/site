<?php

namespace Inchoo\Search\Controller\Relearn;

use Magento\Framework\App\Action\Context;
use Inchoo\Search\Model\Source\System\Category as SearchCategory;

class Categories extends \Magento\Framework\App\Action\Action {

    protected $_searchCategory;

    public function __construct(Context $context, SearchCategory $searchCategory) {
        parent::__construct($context);

        $this->_searchCategory = $searchCategory;
    }

    public function execute() {
        $c = $this->_searchCategory->toShortOptionArray();
        foreach ($c as $id => $name) {
            $v = str_replace(\Inchoo\Search\Model\Source\System\Category::TAB, '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', htmlspecialchars($name));
            echo sprintf('<span style="display:inline-block;width:50px;">%d</span> %s<br/>', $id, $v);
        }
    }

}
