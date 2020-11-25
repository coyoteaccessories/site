<?php

namespace Zemez\ShopByBrand\Controller\Adminhtml\Index;

use Zemez\ShopByBrand\Api\Data\BrandInterface;

/**
 * Mass enable action.
 *
 * @package Zemez\ShopByBrand\Controller\Adminhtml\Action
 */
class MassEnable extends MassStatus
{
    /**
     * Brand status.
     *
     * @var int
     */
    protected $_status = BrandInterface::STATUS_ENABLED;

    /**
     * Action success message.
     *
     * @var string
     */
    protected $_successMessage = 'A total of %1 record(s) have been enabled.';
}
