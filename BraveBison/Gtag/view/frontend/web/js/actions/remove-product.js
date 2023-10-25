/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'Magento_GoogleTagManager/js/google-tag-manager'
], function ($,customerData) {
    'use strict';

    /**
     * Dispatch product detail event to GA
     *
     * @param {Object} data - product data
     *
     * @private
     */
    function notify(data) {
        window.dataLayer.push({ ecommerce: null });
        let currencyCode = data.currencyCode;
        let productData = [];
        productData.id = data.id;
        productData.price = data.price;
        productData.name = data.name;
        productData.category = data.category;
        productData.quantity  = 1;
        window.dataLayer.push({
            'event': 'add_to_cart',
            'ecommerce': {
                'currencyCode':currencyCode,
                'detail': {
                    'products': [productData]
                }
            }
        });
    }

    return function (productData) {
        $(document).on('ajax:removeFromCart', function (e, data) {
            let product = _.find(customerData.get('cart')().items, function (item) {
            });
        });

    };
});
