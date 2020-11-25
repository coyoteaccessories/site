<?php

namespace Inchoo\Search\Block;

use Magento\CatalogSearch\Block\Advanced\Form as AdvancedForm;
use Magento\Framework\UrlInterface;

class Inchoo_Search_Block_Form extends AdvancedForm {

    protected $_urlInterface;

    public function __construct(UrlInterface $urlInterface) {
        $this->_urlInterface = $urlInterface;
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $this->_urlInterface->getBaseUrl()
            ))->addCrumb('search', array(
                'label' => __('Search')
            ));
        }
    }

    /**
     * Retrieve search form action url
     *
     * @return string
     */
    public function getSearchPostUrl() {
        return $this->_urlInterface->getUrl('search/results/for'); //URL: example.com/module_frontname/results/for
    }

    public function getPdqPostUrl() {
        return $this->_urlInterface->getUrl('search/results/filter');
    }

}
