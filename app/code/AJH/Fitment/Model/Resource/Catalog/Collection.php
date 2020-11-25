<?php

namespace AJH\Fitment\Model\Resource\Catalog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

    public function _construct() {
        $this->_init('fitment/catalog');
    }

    protected function getVehicles() {
        return $this;
    }

    public function getVehicleInfoByPartNumber($partNumber) {

        $vehicles = $this->getTable('fitment/vehicles');
        $this->getVehicles();


        $this->getSelect()->join(
                array(
            'v' => $vehicles
                ), 'v.PartMasterID=`main_table`.ID', array(
            'YearID',
            'MakeID',
            'ModelID',
            'SubModelID',
            'MakeName',
            'ModelName',
            'SubModelName'
                )
        );

        $this->addFieldToFilter(
                array('PartNumber'), array(
            array('eq' => $partNumber)
                )
        );

        return $this;
    }

    public function getSelectCountSql() {
        $countSelect = parent::getSelectCountSql();
        if (count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            $countSelect->reset(Zend_Db_Select::GROUP);
            $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
            $countSelect->columns("COUNT(DISTINCT " . implode(", ", $group) . ")");
        }

        return $countSelect;
    }

}
