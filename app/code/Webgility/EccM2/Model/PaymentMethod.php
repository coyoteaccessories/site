<?php

/*© Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content.
*/

namespace Webgility\EccM2\Model;

class PaymentMethod
{
    private $paymentMethod = [];
    public function setMethodId($MethodId)
    {
        $this->paymentMethod['MethodId'] = $MethodId?$MethodId:'';
    }
    public function setMethod($Method)
    {
        $this->paymentMethod['Method'] = $Method?$Method:'';
    }
    public function setDetail($Detail)
    {
        $this->paymentMethod['Detail'] = $Detail?$Detail:'';
    }
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}
