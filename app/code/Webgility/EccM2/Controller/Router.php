<?php

/*© Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content. */

namespace Webgility\EccM2\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    private $actionFactory;

    /**
     * Router constructor.
     *
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory
    ) {
        $this->actionFactory = $actionFactory;
    }

    /**
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        $d = explode('/', $identifier, 3);

        if (isset($d[0]) && ($d[0] != 'webgility')) {
            return false;
        }
        if (isset($d[1]) && ($d[1] != 'webgility-magento.php')) {
            return false;
        }

        $request->setModuleName('webgility')->setControllerName('index')->setActionName('index');
        
        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
        $request->setParam('isAjax', true);
        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }
}
