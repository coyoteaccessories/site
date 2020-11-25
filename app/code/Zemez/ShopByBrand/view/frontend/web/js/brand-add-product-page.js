define([
    'jquery'
], function($) {
    'use strict';
    $.widget('tm.brandAddProductPage', {
        _create: function() {
            if (!$(this.options.selector).next().hasClass('product-brand')) {
                $(this.options.selector).after($('.product-brand'));
            }
        }
    });
    return $.tm.brandAddProductPage;
});
