define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';
    return function (target) {
        return $.widget('mage.sidebar', $.mage.sidebar, {

            /**
             * Update content after item remove
             *
             * @param {Object} elem
             * @private
             */
            _removeItemAfter: function (elem) {
                var productData = this._getProductById(Number(elem.data('cart-item')))
                if (window.location.href.indexOf(this.shoppingCartUrl) !== 0) {
                    this.notify(productData);
                }
                if (!_.isUndefined(productData)) {
                    $(document).trigger('ajax:removeFromCart', {
                        productIds: [productData['product_id']],
                        productInfo: [
                            {
                                'id': productData['product_id']
                            }
                        ]
                    });

                    if (window.location.href.indexOf(this.shoppingCartUrl) === 0) {
                        window.location.reload();
                    }
                }
            },

            /**
             * Dispatch product detail event to GA
             *
             * @param {Object} data - product data
             *
             * @private
             */
            notify: function (data) {
                window.dataLayer.push({ecommerce: null});
                let currencyCode = data.currencyCode;
                let productData = [];
                var dataLayer = {
                    'id' : data.parent_product_sku, // parent_product_sku
                    'price' : data.product_price_value,
                    'name' : data.product_name,
                    'category' : data.category,
                    'quantity' : data.qty,
                    'variant' : data.product_sku,
                };
                productData.push(dataLayer);

                window.dataLayer.push({
                    'event': 'remove_from_cart',
                    'ecommerce': {
                        'currencyCode': currencyCode,
                        'remove': {
                            'products': productData
                        }
                    }
                });
            }

        });
    }
});