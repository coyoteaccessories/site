<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */

namespace Amasty\Flags\Api\Data;

interface OrderFlagInterface
{
    const ID = 'id';
    const ORDER_ID = 'order_id';
    const FLAG_ID = 'flag_id';
    const COLUMN_ID = 'column_id';
    const NOTE = 'note';

    /**
     * @return int
     */
    public function getId();
    
    /**
     * @param int $id
     * @return \Amasty\Flags\Api\Data\OrderFlagInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getOrderId();
    
    /**
     * @param int $orderId
     * @return \Amasty\Flags\Api\Data\OrderFlagInterface
     */
    public function setOrderId($orderId);

    /**
     * @return int
     */
    public function getFlagId();
    
    /**
     * @param int $flagId
     * @return \Amasty\Flags\Api\Data\OrderFlagInterface
     */
    public function setFlagId($flagId);

    /**
     * @return int
     */
    public function getColumnId();
    
    /**
     * @param int $columnId
     * @return \Amasty\Flags\Api\Data\OrderFlagInterface
     */
    public function setColumnId($columnId);

    /**
     * @return string
     */
    public function getNote();
    
    /**
     * @param string $note
     * @return \Amasty\Flags\Api\Data\OrderFlagInterface
     */
    public function setNote($note);
}
