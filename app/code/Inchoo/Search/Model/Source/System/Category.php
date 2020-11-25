<?php

namespace Inchoo\Search\Model\Source\System;

use Inchoo\Search\Model\Source\AbstractSource as InchooAbstractSource;

use Magento\Catalog\Model\CategoryFactory;

class Category extends InchooAbstractSource {

    const TAB = '.   ';
    
    protected $_categoryFactory;

    public function __construct(CategoryFactory $categoryFactory){
        $this->_categoryFactory = $categoryFactory;
    }
    
    protected function _toShortOptionArray() {
        $res = [];

        $collection = $this->_categoryFactory->create()->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSort('path');

        foreach ($collection as $category) {
            $res[$category->getId()] = sprintf('%s%s', str_repeat(self::TAB, $category->getLevel()), $category->getName()
            );
        }

        return $res;
    }

    public function getOptionText($value) {
        $category = $this->_categoryFactory->create()->load($value);
        return str_repeat(self::TAB, $category->getLevel()) . $category->getName();
    }

}
