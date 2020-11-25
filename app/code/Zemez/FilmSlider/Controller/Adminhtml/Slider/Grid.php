<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Controller\Adminhtml\Slider;

class Grid  extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->_coreRegistry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zemez_FilmSlider::filmslider_grid');
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('slider_id');
        $sliderRepository = $this->_objectManager->create('Zemez\FilmSlider\Api\SliderRepositoryInterface');

        try {
            $model = $sliderRepository->getById($id);
            $this->_coreRegistry->register(\Zemez\FilmSlider\Model\Slider::REGISTRY_NAME, $model);
        } catch (\Exception $e) {
            $this->messageManager->addError(__('This slider no longer exists.'));
            /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'Zemez\FilmSlider\Block\Adminhtml\Slider\Edit\Tab\Slides\SliderItemGrid',
                'category.product.grid'
            )->toHtml()
        );
    }
}
