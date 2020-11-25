<?php

namespace Inchoo\Search\Model\Source;

class AbstractSource extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource {

    protected $_options = null;

    public function getAllOptions() {
        if (is_null($this->_options)) {
            $this->_options = $this->toOptionArray();
        }
        return $this->_options;
    }

    public function getOptionArray() {
        $res = array();

        foreach ($this->getAllOptions() as $option) {
            $res[$option['value']] = $option['label'];
        }

        return $res;
    }

    public function getOptionText($value) {
        $options = $this->getAllOptions();

        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    protected function _toShortOptionArray() {
        return [];
    }

    public function toShortOptionArray() {
        if (null === $this->_options) {
            $this->_options = static::_toShortOptionArray();
        }
        return $this->_options;
    }

    public function toOptionArray() {
        $res = array();

        foreach ($this->toShortOptionArray() as $key => $value)
            $res[] = array(
                'value' => $key,
                'label' => $value
            );

        return $res;
    }

    public function getLabel($value) {
        return $this->getOptionText($value);
    }

}
