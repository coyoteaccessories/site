<?php

namespace Webgility\EccM2\Model;

class Charges
{
    private $responseArray = [];

    public function setDiscount($Discount)
    {
        $this->responseArray['Discount'] = $Discount?$Discount:0;
    }
    public function setStoreCredit($StoreCredit)
    {
        $this->responseArray['StoreCredit'] = $StoreCredit?$StoreCredit:0;
    }
    public function setTax($Tax)
    {
        $this->responseArray['Tax'] = $Tax?$Tax:0;
    }
    public function setShipping($Shipping)
    {
        $this->responseArray['Shipping'] = $Shipping?$Shipping:0;
    }
    public function setTotal($Total)
    {
        $this->responseArray['Total'] = $Total?$Total:0;
    }
    public function setSubTotal($SubTotal = 0.00)
    {
        $this->responseArray['SubTotal'] = $SubTotal?$SubTotal:0;
    }
    public function getCharges()
    {
        return $this->responseArray;
    }
}
