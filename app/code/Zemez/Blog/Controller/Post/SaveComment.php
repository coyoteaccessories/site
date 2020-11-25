<?php
namespace Zemez\Blog\Controller\Post;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\TestFramework\Inspection\Exception;
use Zemez\Blog\Model\CommentFactory;
use Zemez\Blog\Model\PostFactory;
use Zemez\Blog\Model\Url;
use Zemez\Blog\Helper\Data;


class SaveComment extends \Magento\Framework\App\Action\Action
{

    protected $_commentFactory;

    protected $_postFactory;

    protected $_helper;

    protected $_urlModel;

    private $_googleVerifyURL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        Context $context,
        CommentFactory $commentFactory,
        PostFactory $postFactory,
        Url $url,
        Data $helper
    ) {
        $this->_commentFactory = $commentFactory;
        $this->_postFactory = $postFactory;
        $this->_helper = $helper;
        $this->_urlModel = $url;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        if ($data) {
            /** @var \Zemez\Blog\Model\Comment $model */
            $model = $this->_commentFactory->create();

            $model->setData($data);

            try {
                $validate = $model->validate();
                $validateCaptcha = $this->_helper->isReCaptchaActive()
                    ? $this->validateCaptcha($data['g-recaptcha-response'])
                    : true;
                if ($validate === true && $validateCaptcha === true) {
                    $model->save();
                    $this->messageManager->addSuccess(__('Comment will be displayed after approve.'));
                }
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $this->messageManager->addError($errorMessage);
                    }
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the comment.'));
            }
        }
        return $resultRedirect;
    }

    private function validateCaptcha($response)
    {
        if ($this->_helper->isReCaptchaActive()) {
            $secret = $this->_helper->getReCaptchaSecret();
            $data = array (
                'secret=' . stripslashes($secret),
                'response=' . stripslashes($response)
            );
            $call = file_get_contents($this->_googleVerifyURL . '?' . implode('&', $data));
            $answers = json_decode($call, true);
            if (!$answers['success']) {
                throw new \RuntimeException(__('Captcha is not valid'));
            }
        }
        return true;
    }
}
