<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */

namespace Amasty\Flags\Api\Data;

interface FlagInterface
{
    const ID = 'id';
    const NAME = 'name';
    const IMAGE_NAME = 'image_name';
    const PRIORITY = 'priority';
    const NOTE = 'note';
    const APPLY_COLUMN = 'apply_column';
    const APPLY_STATUS = 'apply_status';
    const APPLY_SHIPPING = 'apply_shipping';
    const APPLY_PAYMENT = 'apply_payment';

    /**
     * @return int
     */
    public function getId();
    
    /**
     * @param int $id
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();
    
    /**
     * @param string $name
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getImageName();
    
    /**
     * @param string $imageName
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setImageName($imageName);

    /**
     * @return int
     */
    public function getPriority();
    
    /**
     * @param int $priority
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setPriority($priority);

    /**
     * @return string
     */
    public function getNote();
    
    /**
     * @param string $note
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setNote($note);

    /**
     * @return int|null
     */
    public function getApplyColumn();
    
    /**
     * @param int|null $applyColumn
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setApplyColumn($applyColumn);

    /**
     * @return string
     */
    public function getApplyStatus();
    
    /**
     * @param string $applyStatus
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setApplyStatus($applyStatus);

    /**
     * @return string
     */
    public function getApplyShipping();
    
    /**
     * @param string $applyShipping
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setApplyShipping($applyShipping);

    /**
     * @return string
     */
    public function getApplyPayment();
    
    /**
     * @param string $applyPayment
     * @return \Amasty\Flags\Api\Data\FlagInterface
     */
    public function setApplyPayment($applyPayment);
}
