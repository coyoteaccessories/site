<?php

/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FeaturedProduct\Model\Data\Provider;


class ProductModel implements \Zemez\FeaturedProduct\Api\Data\ProductModelInterface
{

    protected $_types;

    public function __construct()
    {
        $this->_types = $this->_getProductsModel();
    }

    /**
     *
     * Get model by key
     *
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function getModelByType($type)
    {
        if(!$this->_types || !isset($this->_types[$type]))
        {
            throw new \Exception('Model does not exist.');
        }
        return $this->_types[$type];
    }

    /**
     * Get models data
     * @return array
     */
    protected function _getProductsModel()
    {
        return [
          'new_product' => 'Zemez\FeaturedProduct\Model\NewProducts',
          'sale_product' => 'Zemez\FeaturedProduct\Model\SaleProducts',
          'viewed_product' => 'Zemez\FeaturedProduct\Model\ViewedProducts',
          'bestseller_product' => 'Zemez\FeaturedProduct\Model\BestsellersProducts',
          'rated_product' => 'Zemez\FeaturedProduct\Model\RatedProducts',
          'manual_product' => 'Zemez\FeaturedProduct\Model\ManualProducts',
          'all_product' => 'Zemez\FeaturedProduct\Model\AllProducts',
        ];
    }


}