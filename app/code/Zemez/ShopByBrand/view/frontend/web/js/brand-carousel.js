/**
 * Copyright © Zemez. All rights reserved.
 */

define([
    'jquery',
    'brandOwlCarousel'
], function($) {
    "use strict";
    $.widget('Zemez.brandCarousel', {
        options: {
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                768: {
                    items: 4
                },
                1200: {
                    items: 5
                }
            },
            autoPlay: false,
            nav: true,
            navText: [],
            dots: false
        },
        _create: function() {
            this.element.owlCarousel(this.options);
        },
    });
    return $.Zemez.brandCarousel;
});
