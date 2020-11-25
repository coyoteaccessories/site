<?php
/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Model\Animation\Source;

use Zemez\FilmSlider\Model\Animation\AnimationAbstract;

class HideTransition extends AnimationAbstract
{

    const ANIMATION_TYPE = 'data-hide-transition';

    public function getValues()
    {
        $arr = [];
        $config = $this->getConfig();
        $result = $config->get(HideTransition::ANIMATION_TYPE);
        if ($result) {
            $arr = $result;
        }
        return $arr;
    }
}
