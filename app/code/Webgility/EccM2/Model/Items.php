<?php

namespace Webgility\EccM2\Model;

class Items
{
    private $responseArray = [];
    private $Items = [];

    public function setStatusCode($StatusCode)
    {
        $this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
    }
    public function setStatusMessage($StatusMessage)
    {
        $this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
    }
    public function setTotalRecordFound($TotalRecordFound)
    {
        $this->responseArray['TotalRecordFound'] = $TotalRecordFound?$TotalRecordFound:0;
    }
    public function setTotalRecordSent($TotalRecordSent)
    {
        $this->responseArray['TotalRecordSent'] = $TotalRecordSent?$TotalRecordSent:0;
    }
    public function setServertime($date)
    {
        $this->responseArray['Servertime'] = $date?$date:'';
    }
    public function setItems($Items1)
    {
        $this->Items[] = $Items1?$Items1:'';
    }
    public function getItems()
    {
        $this->responseArray['Items'] = $this->Items;
        return $this->responseArray;
    }
}
