<?php

namespace AJH\Fitment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use AJH\Fitment\Model\Fitment;

class Qualifiers extends Template {

    protected $fitment;
    public $fitment_year;
    public $fitment_make;
    public $fitment_model;
    public $fitment_submodel;
    
    private $_include_qualifiers = ['Region'];

    public function __construct(Context $context, Fitment $fitment,
            RequestInterface $request) {

        $this->fitment_year = $request->getParam('year', false);
        $this->fitment_make = $request->getParam('make', false);
        $this->fitment_model = $request->getParam('model', false);
        $this->fitment_submodel = $request->getParam('submodel', false);

        $this->fitment = $fitment;

        parent::__construct($context);
    }

    public function getQualifiers() {

        $yearId = $this->fitment_year;
        $makeId = $this->fitment_make;
        $modelId = $this->fitment_model;
        $submodelId = $this->fitment_submodel;
        $_qualifiers = [];

        if (!(int) $yearId || !(int) $makeId || !(int) $modelId || !(int) $submodelId) {
            die('Year, Make, Model and SubModel are required.');
        }

        $qualifiers = $this->fitment->getQualifiers();

        if (is_array($qualifiers)) {
            foreach ($qualifiers as $key => $qualifier) {
                if(in_array($qualifier['Description'], $this->_include_qualifiers)){
                    $_qualifiers[] = $qualifier;                                
                }
            }
        }        
        return $_qualifiers;
    }

}
