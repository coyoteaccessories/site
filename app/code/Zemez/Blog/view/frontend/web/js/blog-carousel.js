/**
 * Copyright © Zemez. All rights reserved.
 */

define([
    'jquery',
    'blogOwlCarousel'
], function($){
    "use strict";

    $.widget('Zemez.blogCarousel', {

        options: {
            responsive : {
                0 : {
                  items: 1
                },
                480 : {
                  items: 2
                },
                768 : {
                  items: 3
                },
                1200 : {
                  items: 4
                }
            },
            autoPlay: false,
            nav:true,
            navText: [],
            dots: false
        },

        _create: function() {
          var $owl = this.element.owlCarousel(this.options);

          $('body').on('rtlEnabled', function () {
            $owl.data('owl.carousel').options.rtl = true;
            $owl.trigger( 'refresh.owl.carousel' );
          });
        }
        
    });

    return $.Zemez.blogCarousel;

});
