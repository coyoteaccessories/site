define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'Amasty_ExportPro/js/form/tag-it'
], function (jQuery, Abstract) {
    'use strict';

    return Abstract.extend({
        initialize: function () {
            this._super();
            var input = "input[name='" + this.inputName + "']";
            jQuery.async(input, (function () {
                jQuery(input).tagit();
            }));

            return this;
        }
    });
});
