define([
    'uiCollection',
    'jquery',
    'ko',
    'underscore',
    'uiLayout',
    'mageUtils'
], function (Collection, $, ko, _, layout, utils) {
    'use strict';

    return Collection.extend({
        defaults: {
            checkedFields: [],
            staticFields: [],
            selected: [],
            fieldsContainerSelect: '[data-amexportcore-js="fields"]',
            fieldsSelect: '[data-amexportcore-js="field"]',
            positions: [],
            selectFieldsPath: null,
            isShowStatic: false,
            isShowFields: false,
            elemIndex: 0,
            staticIndex: 0,
            isShowDeleteBtn: false,
            listens: {
                newCheckedField: 'addCheckedFields',
                fieldsToRemove: 'removeFields',
                elems: 'toggleBtnDelete'
            },
            exports: {
                checkedFields: '${ $.selectFieldsPath }:checkedFields',
                newCheckedField: '${ $.selectFieldsPath }:newCheckedField',
                isShowDeleteBtn: '${ $.deleteBtnPath }:visible'
            },
            imports: {
                fields: '${ $.selectFieldsPath }:fields',
                selected: '${ $.selectFieldsPath }:selected',
                newCheckedField: '${ $.selectFieldsPath }:newCheckedField',
                fieldsToRemove: '${ $.selectFieldsPath }:fieldsToRemove',
                checkedFields: '${ $.provider }:${ $.dataScope }',
                staticFields: '${ $.provider }:${ $.parentScope }.static_fields'
            },
            modules: {
                selectFields: '${ $.selectFieldsPath }',
                deleteBtn: '${ $.deleteBtnPath }'
            }
        },

        initObservable: function () {
            this._super().observe([
                'checkedFields',
                'selected',
                'staticFields',
                'newCheckedField',
                'fieldsToRemove',
                'isShowStatic',
                'isShowFields',
                'isShowDeleteBtn'
            ]);

            return this;
        },

        toggleBtnDelete: function () {
            this.isShowDeleteBtn(!!this.elems().length);
        },

        removeFields: function () {
            if (this.fieldsToRemove().length) {
                this.elems.each(function (elem) {
                    if (_.contains(this.fieldsToRemove(), elem.code())) {
                        elem.remove();
                    }
                }.bind(this));

                this.fieldsToRemove([]);
            }
        },

        removeAllItems: function () {
            this.elems.each(function (elem) {
                elem.source.remove(elem.dataScope);
                elem.destroy();
            });

            this.isShowFields(false);
            this.isShowStatic(false);
        },

        renderDefaultFields: function () {
            if (this.isDefaultRendered) {
                return;
            }

            _.each(this.checkedFields(), function (item) {
                this.initFields(item);
            }.bind(this));

            _.each(this.staticFields(), function (item) {
                this.initStaticField(item);
            }.bind(this));

            this.isDefaultRendered = true;
        },

        getNameField: function () {
            return this.name + '.field-' + this.elemIndex;
        },

        getStaticFieldName: function () {
            return this.name + '.static_field-' + this.staticIndex;
        },

        initFields: function (item) {
            item = this.createField(item, this.elemIndex, this.dataScope, this.getNameField());
            layout([ item ]);
            this.insertChild(item.name);
            this.elemIndex += 1;
        },

        createField: function (data, index, dataScope, name) {
            return utils.extend(data, {
                'name': name,
                'component': 'Amasty_ExportCore/js/fields/field',
                'provider': this.provider,
                'dataScope': dataScope + '.' + index
            });
        },

        initStaticField: function (item) {
            item = this.createField(
                { isStatic: true }, this.staticIndex, this.getStaticDataScope(), this.getStaticFieldName()
            );
            layout([ item ]);
            this.insertChild(item.name);
            this.staticIndex += 1;
            this.isShowStatic(true);
        },

        getStaticDataScope: function () {
            var path = this.dataScope.split('.');

            return path.slice(0, path.length - 1).join('.') + '.static_fields';
        },

        addCheckedFields: function () {
            if (this.newCheckedField().length) {
                this.newCheckedField().forEach(function (item) {
                    this.initFields(item);
                }.bind(this));

                this.isShowFields(true);
                this.newCheckedField([]);
            }
        },

        getCheckedLength: function () {
            return Object.keys(this.checkedFields()).length;
        },

        checkFieldsState: function () {
            if (!this.getCheckedLength()) {
                this.isShowFields(false);
            }

            if (this.elems().length === this.getCheckedLength()) {
                this.isShowStatic(false);
            }
        }
    });
});
