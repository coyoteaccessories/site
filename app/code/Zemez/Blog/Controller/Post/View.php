<?php
namespace Zemez\Blog\Controller\Post;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use \Magento\Framework\Registry;
use Zemez\Blog\Model\PostFactory;
use Zemez\Blog\Helper\Data;

class View extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_postFactory;
    protected $_resultForwardFactory;
    protected $_registry;
    protected $_helper;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        PostFactory $postFactory,
        ForwardFactory $resultForwardFactory,
        Registry $registry,
        Data $helper
    ) {
        $this->_helper = $helper;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_postFactory = $postFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_registry = $registry;

        parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->_registry->registry('tm_blog_post');
        if (!$post->getId() || !$post->getIsVisible()) {
            $resultForward = $this->_resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }

        $resultPage = $this->_resultPageFactory->create();

        $resultPage->getConfig()->setPageLayout($this->_helper->getPostLayout());

        return $resultPage;
    }
}
