define([
    'Magento_Ui/js/dynamic-rows/dynamic-rows'
], function (DynamicRows) {
    'use strict';

    return DynamicRows.extend({
        defaults: {
            parentComponent: null,
            listens: {
                relatedData: 'checkRelatedData'
            }
        },

        checkRelatedData: function () {
            if (this.parentComponent && this.relatedData.length) {
                this.parentComponent.opened(true);
            }
        },

        initContainer: function (parent) {
            this._super();

            this.parentComponent = parent;

            if (this.relatedData.length) {
                this.parentComponent.opened(true);
            }

            return this;
        }
    });
});
