<?php

namespace Zemez\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Zemez\Blog\Api\Data\PostInterface;

/**
 * Cms post grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    private $postFactory;

    /** @var PostDataProcessor */
    protected $dataProcessor;

    /** @var PostRepository  */
    protected $postRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    protected $cacheTypeList;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Zemez\Blog\Model\PostFactory $postFactory
    ) {
        $this->postFactory = $postFactory;
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->isAjax() && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach ($postItems as $postReceived) {
            /** @var \Zemez\Blog\Model\Post $post */
            $post = $this->postFactory->create()->load($postReceived['post_id']);
            try {
                $postData = $this->filterPost($postReceived);
                $extendedPostData = $post->getData();
                $this->setPostData($post, $extendedPostData, $postData);
                $post->save();

                $this->cacheTypeList->invalidate('full_page');

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPostId(
                    $post,
                    __('Something went wrong while saving the post.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Filtering posted data.
     *
     * @param array $postData
     * @return array
     */
    protected function filterPost($postData = [])
    {
        return $postData;
    }

    /**
     * Validate post data
     *
     * @param array $postData
     * @param \Zemez\Blog\Model\Post $post
     * @param bool $error
     * @param array $messages
     * @return void
     */
    protected function validatePost(array $postData, \Zemez\Blog\Model\Post $post, &$error, array &$messages)
    {
        if (!($this->dataProcessor->validate($postData) && $this->dataProcessor->validateRequireEntry($postData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithPostId($post, $error->getText());
            }
        }
    }

    /**
     * Add post title to error message
     *
     * @param PostInterface $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(PostInterface $post, $errorText)
    {
        return '[Post ID: ' . $post->getId() . '] ' . $errorText;
    }

    /**
     * Set blog post data
     *
     * @param \Zemez\Blog\Model\Post $post
     * @param array $extendedPostData
     * @param array $postData
     * @return $this
     */
    public function setPostData(\Zemez\Blog\Model\Post $post, array $extendedPostData, array $postData)
    {
        $post->setData(array_merge($post->getData(), $extendedPostData, $postData));
        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zemez_Blog::post_save');
    }
}
