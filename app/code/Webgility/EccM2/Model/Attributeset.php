<?php

/*� Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content. */

namespace Webgility\EccM2\Model;

class Attributeset
{
    private $Attributeset = [];

    public function setAttributesetID($AttributesetID)
    {
        $this->Attributeset['AttributesetID'] = $AttributesetID ? $AttributesetID :"";
    }

    public function setAttributesetName($AttributesetName)
    {
        $this->Attributeset['AttributesetName'] = $AttributesetName ? $AttributesetName :"";
    }

    public function getAttributeset()
    {
        return $this->Attributeset;
    }
}
