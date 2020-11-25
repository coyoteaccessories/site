<?php

namespace Inchoo\Search\Block;

use Magento\CatalogSearch\Block\Advanced\Result as AdvancedResult;
use Magento\Framework\UrlInterface;

class Result extends AdvancedResult {

    protected $_urlInterface;

    public function __construct(UrlInterface $urlInterface) {
        $this->_urlInterface = $urlInterface;
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $this->_urlInterface->getBaseUrl()
            ))->addCrumb('search', array(
                'label' => __('Search'),
                'link' => $this->_urlInterface->getUrl('*')
            ))->addCrumb('search_result', array(
                'label' => __('Results')
            ));
        }
        $title = sprintf("Search results for: '%s'", $this->getRequest()->getQuery('sku'));
        $this->getLayout()->getBlock('head')->setTitle($title);
    }

    public function getFormUrl() {
        return $this->_urlInterface
                        ->setQueryParams($this->getRequest()->getQuery())
                        ->getUrl('*', array('_escape' => true)); //URL: example.com/module_frontname/
    }

}
