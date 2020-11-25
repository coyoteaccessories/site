<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */

namespace Amasty\Flags\Api\Data;

interface ColumnInterface
{
    const ID = 'id';
    const NAME = 'name';
    const POSITION = 'position';
    const COMMENT = 'comment';

    /**
     * @return int
     */
    public function getId();
    
    /**
     * @param int $id
     * @return \Amasty\Flags\Api\Data\ColumnInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();
    
    /**
     * @param string $name
     * @return \Amasty\Flags\Api\Data\ColumnInterface
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getPosition();
    
    /**
     * @param int $position
     * @return \Amasty\Flags\Api\Data\ColumnInterface
     */
    public function setPosition($position);

    /**
     * @return string
     */
    public function getComment();
    
    /**
     * @param string $comment
     * @return \Amasty\Flags\Api\Data\ColumnInterface
     */
    public function setComment($comment);
}
