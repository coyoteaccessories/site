<?php

namespace Webgility\EccM2\Model;

class CompanyInfo
{
    private $responseArray = [];

    public function setStatusCode($StatusCode)
    {
        $this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
    }
    public function setStatusMessage($StatusMessage)
    {
        $this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
    }
    public function setStoreID($StoreID)
    {
        $this->responseArray['StoreID'] = $StoreID ? $StoreID :'';
    }
    public function setStoreName($StoreName)
    {
        $this->responseArray['StoreName'] = $StoreName?$StoreName:'';
    }
    public function setAddress($Address)
    {
        $this->responseArray['Address'] =$Address ? $Address :'';
    }
    public function setAddress1($Address1)
    {
        $this->responseArray['Address1'] =$Address1 ? $Address1 :"";
    }
    public function setAddress2($Address2)
    {
        $this->responseArray['Address2'] =$Address2 ? $Address2 :"";
    }
    public function setcity($city)
    {
        $this->responseArray['city'] = $city ? $city :'';
    }
    public function setState($State)
    {
        $this->responseArray['State'] =$State ? $State : '';
    }
    public function setCountry($Country)
    {
        $this->responseArray['Country'] = $Country ? $Country : '';
    }
    public function setZipcode($Zipcode)
    {
        $this->responseArray['Zipcode'] = $Zipcode ? $Zipcode : '';
    }
    public function setPhone($Phone)
    {
        $this->responseArray['Phone'] =$Phone ? $Phone :'';
    }
    public function setFax($Fax)
    {
        $this->responseArray['Fax'] =$Fax ? $Fax : '';
    }
    public function setWebsite($Website)
    {
         $this->responseArray['Website'] =$Website ? $Website : '';
    }
    public function getCompanyInfo()
    {
         return $this->responseArray;
    }
}
