<?php

namespace Zemez\ShopByBrand\Block\Brand\Product;

use Zemez\ShopByBrand\Helper\Data as ShopByBrandHelper;
use Zemez\ShopByBrand\Api\BrandRepositoryInterface;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

/**
 * Class Brand
 *
 * @package Zemez\ShopByBrand\Block\Brand\Product
 */
class Brand extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var \Zemez\ShopByBrand\Helper\Data
     */
    protected $_helper;

    /**
     * @var BrandRepositoryInterface
     */
    protected $_brandRepository;

    /**
     * @var JsonHelper
     */
    protected $_jsonHelper;

    /**
     * @var string
     */
    protected $_template = 'product/brand.phtml';

    /**
     * @var null|\Zemez\ShopByBrand\Model\Brand
     */
    private $_brand = null;

    /**
     * Brand constructor.
     *
     * @param Registry                 $registry
     * @param ShopByBrandHelper        $helper
     * @param BrandRepositoryInterface $brandRepository
     * @param JsonHelper               $jsonHelper
     * @param Context                  $context
     * @param array                    $data
     */
    public function __construct(
        Registry $registry,
        ShopByBrandHelper $helper,
        BrandRepositoryInterface $brandRepository,
        JsonHelper $jsonHelper,
        Context $context,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_helper = $helper;
        $this->_brandRepository = $brandRepository;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    /**
     * Check if has brand.
     *
     * @return bool
     */
    public function hasBrand()
    {
        return $this->getBrand() !== null;
    }

    /**
     * Get brand.
     *
     * @return null|\Zemez\ShopByBrand\Model\Brand
     */
    public function getBrand()
    {
        if (null === $this->_brand) {
            if ($id = $this->_getBrandId()) {
                $this->_brand = $this->_brandRepository->getById($id);
            }
        }

        return $this->_brand;
    }

    /**
     * Get brand id.
     *
     * @return int|null
     */
    protected function _getBrandId()
    {
        /** @var \Magento\Catalog\Model\Product $product */
        if($product = $this->_registry->registry('current_product')) {
            return $this->_helper->isAssignedToBrand($product);
        }

        return null;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->hasBrand()) {
            return '';
        }

        if ($this->getBrand()->isDisabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}