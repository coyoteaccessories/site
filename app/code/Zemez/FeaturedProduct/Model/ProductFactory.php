<?php
/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FeaturedProduct\Model;


use Symfony\Component\Config\Definition\Exception\Exception;

class ProductFactory
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    protected $_dataProvider;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Zemez\FeaturedProduct\Model\Data\Provider\ProductModel $dataProvider
    )
    {
        $this->_objectManager = $objectManager;
        $this->_dataProvider = $dataProvider;
    }


    public function create($productType,$data=[])
    {
        $productModelInterface = 'Zemez\FeaturedProduct\Api\FeaturedProductInterface';
        $productModel = $this->_dataProvider->getModelByType($productType);

        if(!is_a($productModel,$productModelInterface,true)) {
            throw new Exception(sprintf('Model must be an instance of %s,%s given',
                $productModelInterface,
                get_class($productModel))
            );
        }
       return $this->_objectManager->create($productModel,$data);
    }


}