/**
 *
 * Copyright © 2019 Zemez. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

define([
    'jquery',
    'Zemez_FilmSlider/js/slider/item/sliderpagedit',
    'Zemez_FilmSlider/js/slider/item/canvas',
    'Zemez_FilmSlider/js/slider/item/image',
],function($){
    'use strict';

    /**
     * Widget trigger item width
     *
     */
    $.widget('tm.sliderItemLayerWidth', $.tm.sliderPageEdit,{

        _create: function() {

            //Observ input if insert file name set to background.
            this._on({
                change: function(event)
                {
                    var canvasSortableItems = this._getCanvasSortableItemsSelector();
                    $(canvasSortableItems).find('div[class*="selected"]').width($(this.element).val());
                }
            });
        },

    });

    return $.tm.sliderItemLayerWidth;
});