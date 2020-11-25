<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Model\Animation;

abstract class AnimationAbstract
{

    protected $_animationConfig;

    public function __construct(\Zemez\FilmSlider\Api\Animation\ConfigInterface $animationConfig)
    {
        $this->_animationConfig = $animationConfig;
    }

    protected function getConfig()
    {
        return $this->_animationConfig;
    }

    abstract public function getValues();
}
