<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Model\Animation\Source;

use Zemez\FilmSlider\Model\Animation\AnimationAbstract;

class Position extends AnimationAbstract
{

    const ANIMATION_TYPE = 'data-position';

    public function getValues()
    {
        $arr = [];
        $config = $this->getConfig();
        $result = $config->get(Position::ANIMATION_TYPE);
        if ($result) {
            $arr = $result;
        }
        return $arr;
    }
}
