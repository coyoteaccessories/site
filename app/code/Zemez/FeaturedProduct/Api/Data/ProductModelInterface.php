<?php

/**
 *
 * Copyright © 2015 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FeaturedProduct\Api\Data;

interface ProductModelInterface
{
    public function getModelByType($type);
}