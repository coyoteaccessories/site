<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Model\Animation\Source;

use Zemez\FilmSlider\Model\Animation\AnimationAbstract;

class ShowTransition extends AnimationAbstract
{

    const ANIMATION_TYPE = 'data-show-transition';

    public function getValues()
    {
        $arr = [];
        $config = $this->getConfig();
        $result = $config->get(ShowTransition::ANIMATION_TYPE);
        if ($result) {
            $arr = $result;
        }
        return $arr;
    }
}
