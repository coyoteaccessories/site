define([
    'uiElement'
], function (Element) {
    'use strict';

    return Element.extend({
        defaults: {
            template: 'Amasty_ExportCore/fields/field',
            links: {
                output_value: '${ $.provider }:${ $.dataScope }.output_value',
                code: '${ $.provider }:${ $.dataScope }.code'
            },
            modules: {
                parent: '${ $.parentName }'
            }
        },

        initObservable: function () {
            this._super().observe([
                'output_value',
                'code'
            ]);

            return this;
        },

        remove: function () {
            this.source.remove(this.dataScope);
            this.destroy();
            this.parent().checkFieldsState();
        }
    });
});
