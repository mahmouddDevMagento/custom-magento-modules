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
        window.dataLayer.push({ecommerce: null});
        let currencyCode = data.currencyCode;
        let productData = [];
        var dataLayer = {
            'id' : data.parent_product_sku, // parent_product_sku
            'price' : data.product_price_value,
            'name' : data.product_name,
            'category' : data.category,
            'quantity' : 1,
            'variant' : data.product_sku,
        };
        productData.push(dataLayer);
        window.dataLayer.push({
            'event': 'add_to_cart',
            'ecommerce': {
                'currencyCode': currencyCode,
                'add': {
                    'products': productData
                }
            }
        });
    }

    /**
     * Retrieves product data by Id.
     *
     * @param {Number} productId - product Id
     * @returns {Object|undefined}
     * @private
     */
    function getProductById (productId) {
        return _.find(customerData.get('cart')().items, function (item) {
            return productId === item['product_id'];
        });
    }

    return function (productData) {
        $(document).on('ajax:addToCart', function (e, data) {
            setTimeout(function(){
                var pdata = getProductById(productData.product_id);
                window.dataLayer ?
                    notify(pdata) :
                    $(document).on('ga:inited', notify.bind(this, pdata));
            }, 4000);
        });
    };
});
