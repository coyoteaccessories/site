<?php

namespace Webgility\EccM2\Model;

class Itemoption
{
    private $responseArray = array();
    # Nodes for item options
    public function setOptionID($OptionID)
    {
        $this->responseArray['OptionID'] = $OptionID ? $OptionID : '';
    }
    public function setOptionValue($OptionValue)
    {
        $this->responseArray['OptionValue'] = $OptionValue ? $OptionValue : '';
    }
    public function setOptionName($OptionName)
    {
        $this->responseArray['OptionName'] = $OptionName ? $OptionName : '';
    }
    public function setOptionPrice($OptionPrice)
    {
        $this->responseArray['OptionPrice'] = $OptionPrice ? $OptionPrice : '';
    }
    public function getItemoption()
    {
        return $this->responseArray;
    }
}
