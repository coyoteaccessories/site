<?php

namespace Webgility\EccM2\Model;

class CancelItemDetail
{
    private $responseArray = [];
    public function setItemID($ItemID)
    {
        $this->responseArray['ItemID'] = $ItemID ? $ItemID : '';
    }
    public function setItemSku($ItemSku)
    {
        $this->responseArray['SKU'] = $ItemSku ? $ItemSku : '';
    }
    public function setItemName($ItemName)
    {
        $this->responseArray['ProductName'] = $ItemName ? $ItemName : '';
    }
    public function setQtyCancel($QtyCancel)
    {
        $this->responseArray['QtyCancel'] = $QtyCancel?$QtyCancel : '';
    }
    public function setQtyInOrder($QtyInOrder)
    {
        $this->responseArray['QtyInOrder'] = $QtyInOrder?$QtyInOrder : '';
    }
    public function setItemPrice($ItemPrice)
    {
        $this->responseArray['ItemPrice'] = $ItemPrice?$ItemPrice : '';
    }
    public function setPriceCancel($PriceCancel)
    {
        $this->responseArray['PriceCancel'] = $PriceCancel?$PriceCancel : '';
    }
    public function getCancelItemDetail()
    {
        return $this->responseArray;
    }
}
