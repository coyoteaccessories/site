<?php
/*© Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content. */

namespace Webgility\EccM2\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory ;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $request = $this->getRequest();
        $cartCompitableVersion = 'Community Edition  2.0.0 to 2.3.4';

        if (!$request->getParam('request')) {
            $str='Webgility Store Module Compatible with Magento version: '.$cartCompitableVersion;
            $this->getResponse()->setBody($str);
            return;
        } else {
            try {
                $compress = $request->getParam('iscompress');
                $xml = $this->_objectManager->get('Webgility\EccM2\Model\Desktop')
                ->parseRequest($request->getParam('request'), $compress);
                $this->getResponse()->setBody($xml);
                return;
            } catch (\Exception $e) {
                $this->getResponse()->setBody($e);
                return;
            }
        }
    }
}
