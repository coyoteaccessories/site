<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */


namespace Amasty\Flags\Controller\Adminhtml\Flag;

use Amasty\Flags\Model\Flag;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Amasty\Flags\Api\ColumnRepositoryInterface;
use Amasty\Flags\Api\FlagRepositoryInterface;
use Amasty\Flags\Model\Column;
use Magento\Backend\App\Action\Context;
use Amasty\Flags\Controller\Adminhtml\Flag as FlagAction;
use Magento\Framework\File\Uploader;
use Magento\Framework\File\UploaderFactory;

class Save extends FlagAction
{
    /**
     * @var FlagRepositoryInterface
     */
    private $flagRepository;
    /**
     * @var ColumnRepositoryInterface
     */
    private $columnRepository;
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(
        Context $context,
        FlagRepositoryInterface $flagRepository,
        ColumnRepositoryInterface $columnRepository,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->flagRepository = $flagRepository;
        $this->columnRepository = $columnRepository;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = (int)$this->getRequest()->getParam('id');
            /** @var Flag $model */
            $model = $this->flagRepository->get($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This flag no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            foreach (['apply_status', 'apply_shipping', 'apply_payment'] as $field) {
                if (isset($data[$field])) {
                    $data[$field] = implode(',', $data[$field]);
                } else {
                    $data[$field] = '';
                }
            }

            $model->setData($data);
            if (!$data['apply_column']) {
                $model->unsetData('apply_column');
            }

            try {
                $this->flagRepository->save($model);

                if ($data['apply_column']) {
                    /** @var Column $column */
                    $column = $this->columnRepository->get($data['apply_column']);
                    $column->assignFlags([$model->getId()]);
                }

                try {
                    /** @var $uploader Uploader */
                    $uploader = $this->uploaderFactory->create(
                        ['fileId' => 'image_name']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg']);

                    $directoryWrite = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

                    if ($model->getImageName()) {
                        $directoryWrite->delete($model->getImageRelativePath());
                    }

                    $basePath = $directoryWrite->getAbsolutePath(Flag::FLAGS_FOLDER);
                    $imageName = "{$model->getId()}.{$uploader->getFileExtension()}";

                    $uploader->save($basePath, $imageName);

                    $model
                        ->setImageName($imageName)
                        ->save();

                } catch (\Exception $e) {
                    if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                        throw $e;
                    }
                }

                $this->messageManager->addSuccessMessage(__('The flag has been saved.'));

                $this->_session->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_session->setFormData($data);

                return $resultRedirect->setPath('*/*/edit', [
                    'id' => $this->getRequest()->getParam('id')
                ]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
