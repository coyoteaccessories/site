<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\ProductLabels\Controller\Adminhtml\Productlabel;

use Zemez\ProductLabels\Api\ProductLabelRepositoryInterfaceFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var \Zemez\ProductLabels\Api\ProductLabelRepositoryInterface
     */
    protected $_productLabelRepository;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        ProductLabelRepositoryInterfaceFactory $productLabelRepository,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_productLabelRepository = $productLabelRepository;
        $this->_coreRegistry = $registry;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zemez_ProductLabels::productlabels_edit');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $productLabelId = $this->getRequest()->getParam('smart_label_id');
        $productLabelRepository = $this->_productLabelRepository->create();
        if ($productLabelId) {
            try {
                $model = $productLabelRepository->getById($productLabelId);
            } catch (\Exception $e) {
                $this->messageManager->addError(__('This product label no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/index/index');
            }
        } else {
            $model = $productLabelRepository->getModelInstance();
        }
        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');
        // Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register(\Zemez\ProductLabels\Api\Data\ProductLabelInterface::REGISTRY_NAME, $model);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Zemez_ProductLabels::productlabels_save');
        $resultPage->addBreadcrumb(__('Smart Product Labels'), __('Smart Label'));
        $resultPage->addBreadcrumb(__('Smart Product Labels'), __('Smart Label'));
        $resultPage->getConfig()->getTitle()->prepend(__('Smart Label'));

        return $resultPage;
    }
}
