<?php

namespace AJH\Fitment\Block\Catalog\Product;

//use Magento\Catalog\Model\Product;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Catalog\Model\Product\Attribute\Repository as AttributeRepository;

class ProductList extends \Magento\Catalog\Block\Product\ListProduct {

    protected $_customerSession;
    protected $categoryFactory;
    protected $productCollectionFactory;
    protected $_filterCollection;
    protected $_productAttributeRepository;
    protected $_filterable_attributes;
    protected $_collection;

    /**
     * ListProduct constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param Helper $helper
     * @param array $data
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(
    \Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Data\Helper\PostHelper $postDataHelper, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Magento\Framework\Url\Helper\Data $urlHelper, \Magento\Customer\Model\Session $customerSession, \Magento\Catalog\Model\CategoryFactory $categoryFactory, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \AJH\Fitment\Plugin\CatalogSearch\Model\Search\IndexBuilder $filteredCollection, \Magento\Store\Model\StoreManagerInterface $storeManager, HttpRequest $request, AttributeRepository $productAttributeRepository, \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, \Magento\Framework\App\ResourceConnection $resource, array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->categoryFactory = $categoryFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_filterCollection = $filteredCollection;

        $this->storeManager = $storeManager;
        $this->_request = $request;

        $this->_productAttributeRepository = $productAttributeRepository;
        $this->_filterable_attributes = [];

        $this->categoryRepository = $categoryRepository;
        $this->_collection = $collection;
        $this->_resource = $resource;

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

//    protected $productVisibility;
//    protected $productStatus;
//
//    public function __construct(
//    \Magento\Framework\View\Element\Template\Context $context, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus, \Magento\Catalog\Model\Product\Visibility $productVisibility, array $data = []
//    ) {
//        $this->productCollectionFactory = $productCollectionFactory;
//        $this->productStatus = $productStatus;
//        $this->productVisibility = $productVisibility;
//        parent::__construct($context, $data);
//    }
//
//    public function getProductCollection() {
//        $collection = $this->productCollectionFactory->create();
//        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
//        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
//        return $collection;
//    }
//
    public function getLoadedProductCollection101() {
        return $this->getProducts();

//        $params = $this->_request->getParams();
//
//        $this->_filterable_attributes = [];
//
//        foreach ($params as $key => $param) {
//            $this->loadFilterableAttribute($key);
//        }
//
//        $websiteId = $this->storeManager->getStore()->getWebsiteId();
//
//        $collection = $this->productCollectionFactory->create();
//        $collection->addAttributeToSelect('*');
//        foreach ($this->_filterable_attributes as $_attr) {
//            $collection->addAttributeToFilter($_attr, array('eq' => $this->_request->getParam($_attr)));
//        }
////        $collection->addWebsiteFilter($websiteId);
//        $product_ids = $this->_filterCollection->getCustomCollectionQuery();
//        $collection->addIdFilter($product_ids);
//
//        var_dump($product_ids);
//
////        if($this->_request->getParam('cat')){
////            $collection->addCategoriesFilter(['in' => array($this->_request->getParam('cat'))]);
////        }
//
//        return $collection;
    }

    private function loadFilterableAttribute($attr_code) {
        try {
            $attribute = $this->_productAttributeRepository->get($attr_code);
            if ($attribute['attribute_id'] && $attribute['is_filterable']) {
                array_push($this->_filterable_attributes, $attr_code);
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            //  attribute does not exists
        }
    }

    public function getProducts() {

        $count = $this->getProductCount();
        $category_id = $this->getData("category_id");
        $collection = clone $this->_collection;
        $collection->clear()->getSelect()->reset(\Magento\Framework\DB\Select::WHERE)->reset(\Magento\Framework\DB\Select::ORDER)->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)->reset(\Magento\Framework\DB\Select::GROUP);

        $product_ids = $this->_filterCollection->getCustomCollectionQuery();

        if (!$category_id) {
            $category_id = $this->_storeManager->getStore()->getRootCategoryId();
        }
        $category = $this->categoryRepository->get($category_id);
        if (isset($category) && $category) {
            $collection->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents()
                    ->addAttributeToSelect('name')
                    ->addAttributeToSelect('image')
                    ->addAttributeToSelect('small_image')
                    ->addAttributeToSelect('thumbnail')
                    ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                    ->addUrlRewrite()
                    ->addCategoryFilter($category)
                    ->addAttributeToSort('created_at', 'desc');
            $collection->addIdFilter($product_ids);
        } else {
            $collection->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents()
                    ->addAttributeToSelect('name')
                    ->addAttributeToSelect('image')
                    ->addAttributeToSelect('small_image')
                    ->addAttributeToSelect('thumbnail')
                    ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                    ->addUrlRewrite()
                    ->addAttributeToFilter('is_saleable', 1, 'left')
                    ->addAttributeToSort('created_at', 'desc');
        }

        $collection->getSelect()
                ->order('created_at', 'desc')
                ->limit($count);

        return $collection;
    }

//    public function getLoadedProductCollection() {
//        return $this->getProducts();
//    }

    public function getProductCount() {
        $limit = $this->getData("product_count");
        if (!$limit)
            $limit = 10;
        return $limit;
    }

}
