define(
    ['underscore', 'mage/utils/wrapper'],
    function (_, wrapper) {
        'use strict';

        return function (target) {
            /**
             * If custom attributes array, convert to object
             */
            target.quoteAddressToFormAddressData =
            wrapper.wrapSuper(target.quoteAddressToFormAddressData, function (modelAddress) {
                var shippingAddress,
                    customAttributes;

                if (modelAddress.customAttributes) {
                    customAttributes = {};
                    _.each(modelAddress.customAttributes, function (attribute, key) {
                        if (_.isObject(attribute) && attribute.hasOwnProperty('attribute_code')) {
                            customAttributes[attribute.attribute_code] = attribute.value;
                        } else if (!_.isNumber(key)) {
                            customAttributes[key] = attribute;
                        }

                        delete modelAddress.customAttributes[key];
                    });

                    delete modelAddress.customAttributes;
                }

                shippingAddress = this._super(modelAddress);

                if (customAttributes) {
                    shippingAddress['custom_attributes'] = customAttributes;
                }

                return shippingAddress;
            });

            return target;
        };
    }
);
