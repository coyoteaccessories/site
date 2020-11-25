<?php

namespace AJH\Fitment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use AJH\Fitment\Model\Fitment;

class Makes extends Template {

    private $fitment;    
    public $fitment_year;        

    public function __construct(Context $context, Fitment $fitment, RequestInterface $request) {        
        
        $this->fitment_year = $request->getParam('year', false);               

        $this->fitment = $fitment;        

        parent::__construct($context);
    }

    public function getMakes() {

        $yearId = $this->fitment_year;

        if (!$yearId) {
            die('Year is required.');
        }

        return $this->fitment->getMakes();
    }

//    private function getMake($makeId) {
//        $makes = $this->getMakes();
//        $make = array();
//
//        foreach ($makes as $_make) {
//            if ($_make->MakeID == $makeId) {
//                $make['ID'] = $_make->MakeID;
//                $make['Name'] = trim($_make->MakeName);
//            }
//        }
//
//        return $make;
//    }

}
