<?php
/*© Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content.*/
 
namespace Webgility\EccM2\Model;

class Stores
{
    private $responseArray = [];
    private $stores = [];
    private $storescount = 0;

    public function setStatusCode($StatusCode)
    {
        $this->responseArray['StatusCode'] = $StatusCode?$StatusCode:0;
    }
    public function setStatusMessage($StatusMessage)
    {
        $this->responseArray['StatusMessage'] = $StatusMessage?$StatusMessage:'';
    }
    public function setstores($Stores)
    {
        $this->stores[] = $Stores?$Stores:'';
    }
    public function getStoresInfo()
    {
        $this->responseArray['Stores'] = $this->stores?$this->stores:'';
        return $this->responseArray;
    }
}
