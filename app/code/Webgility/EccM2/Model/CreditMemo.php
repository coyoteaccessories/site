<?php
 
namespace Webgility\EccM2\Model;

class CreditMemo
{
    private $responseArray = [];
    
    public function setCreditMemoID($CreditMemoID)
    {
        $this->responseArray['CreditMemoID'] = $CreditMemoID ? $CreditMemoID : '';
        $this->responseArray['CancelItemDetail']  = [];
    }
    public function setCreditMemoDate($CreditMemoDate)
    {
        $this->responseArray['CreditMemoDate'] = $CreditMemoDate ? $CreditMemoDate : '';
    }
    public function setRefundDiscount($RefundDiscount)
    {
        $this->responseArray['RefundDiscount'] = $RefundDiscount ? $RefundDiscount : '';
    }
    public function setRefundTax($RefundTax)
    {
        $this->responseArray['RefundTax'] = $RefundTax ? $RefundTax : '';
    }
    public function setRefundFee($RefundFee)
    {
        $this->responseArray['RefundFee'] = $RefundFee ? $RefundFee : '';
    }
    public function setRefundAdjustment($RefundAdjustment)
    {
        $this->responseArray['RefundAdjustment'] = $RefundAdjustment ? $RefundAdjustment : '';
    }
    public function setRefundShipping($RefundShipping)
    {
        $this->responseArray['RefundShipping'] = $RefundShipping ? $RefundShipping : '';
    }
    public function setSubtotal($Subtotal)
    {
        $this->responseArray['Subtotal'] = $Subtotal ? $Subtotal : '';
    }
    public function setShippingAndHandling($ShippingAndHandling)
    {
        $this->responseArray['ShippingAndHandling'] = $ShippingAndHandling ? $ShippingAndHandling : '';
    }
    public function setAdjustmentRefund($AdjustmentRefund)
    {
        $this->responseArray['AdjustmentRefund'] = $AdjustmentRefund ? $AdjustmentRefund : '';
    }
    public function setAdjustmentFee($AdjustmentFee)
    {
        $this->responseArray['AdjustmentFee'] = $AdjustmentFee ? $AdjustmentFee : '';
    }
    public function setCancelItemDetail($CancelItemDetail)
    {
        $this->responseArray['CancelItemDetail'][] = $CancelItemDetail?$CancelItemDetail:[];
    }
    public function getCreditMemo()
    {
        return $this->responseArray;
    }
}
