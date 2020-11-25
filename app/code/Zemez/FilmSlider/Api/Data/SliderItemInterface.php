<?php

/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace Zemez\FilmSlider\Api\Data;

interface SliderItemInterface
{

    const REGISTRY_NAME = 'film_slider_item';

    const SLIDE_ID = 'slideritem_id';

    const PARENT_ID = 'parent_id';

    const TITLE = 'title';

    const STATUS = 'status';

    const SORT_ITEM = 'sort_item';

    const IMAGE = 'image';

    const IMAGE_PARAMS = 'image_params';

    const IMAGE_PARAMS_ARRAY = 'image_params_array';

    const LAYER_GENERAL_PARAMS = 'layer_general_params';

    const LAYER_GENERAL_PARAMS_ARRAY = 'layer_general_params_array';

    const LAYER_ANIMATION_PARAMS = 'layer_animation_params';

    const CONTENT = 'content';

    const IMAGE_PREVIEW_WIDTH = 'preview_width';

    const IMAGE_PREVIEW_HEIGHT = 'preview_height';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getSlideritemId();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setSlideritemId($slideritemId);

    /**
     * @return mixed
     */
    public function getParentId();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setParentId($parentId);

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setTitle($title);

    /**
     * @return mixed
     */
    public function getSortItem();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setSortItem($sortItem);

    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getImage();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setImage($image);

    /**
     * @return mixed
     */
    public function getImageParams();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setImageParams($params);

    /**
     * @return mixed
     */
    public function getLayerGeneralParams();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setLayerGeneralParams($layerGeneralParams);

    /**
     * @return mixed
     */
    public function getLayerAnimationParams();

    /**
     * @return \Zemez\FilmSlider\Api\Data\SliderItemInterface
     */
    public function setLayerAnimationParams($layerAnimationParams);

    /**
     * @return mixed
     */
    public function getLayerGeneralParamsArray();

    /**
     * @return mixed
     */
    public function getImageParamsArray();
}
