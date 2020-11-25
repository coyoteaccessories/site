<?php

namespace Zemez\ShopByBrand\Controller\Adminhtml\Index;

use Zemez\ShopByBrand\Api\Data\BrandInterface;

/**
 * Mass disable action.
 *
 * @package Zemez\ShopByBrand\Controller\Adminhtml\Action
 */
class MassDisable extends MassStatus
{
    /**
     * Brand status.
     *
     * @var int
     */
    protected $_status = BrandInterface::STATUS_DISABLED;

    /**
     * Action success message.
     *
     * @var string
     */
    protected $_successMessage = 'A total of %1 record(s) have been disabled.';
}
