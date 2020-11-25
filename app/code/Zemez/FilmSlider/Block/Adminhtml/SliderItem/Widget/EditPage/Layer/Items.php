<?php

/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */
namespace Zemez\FilmSlider\Block\Adminhtml\SliderItem\Widget\EditPage\Layer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Items extends Template
{

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param array $data
     */
    public function __construct(Context $context,
                                \Magento\Framework\Registry $coreRegistry,
                                array $data = [])
    {
        $this->_storeManager = $context->getStoreManager();
        $this->_coreRegistry = $coreRegistry;
        $this->jsLayout = $this->getImageBasePath();

        parent::__construct($context, $data);
    }

    protected function getImageBasePath()
    {
        $model = $this->_coreRegistry->registry(\Zemez\FilmSlider\Model\SliderItem::REGISTRY_NAME);
        $params = $model->getLayerGeneralParams();

        return [
            'basePathWysiwygImage'=>$this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA),
            'layerItemDefault'=>json_decode($params, true)];
    }
}
