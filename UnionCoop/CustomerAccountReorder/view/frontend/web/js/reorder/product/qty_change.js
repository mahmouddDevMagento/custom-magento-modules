define([
    'ko',
    'uiComponent',
    'jquery',
    'underscore',
    'mage/translate'
], function(ko, Component, $, _, $t) {
    'use strict';

    return Component.extend({


        initialize: function(config) {
            this._super();
            this.qty = ko.observable(config.defaultQty.toFixed(config.decimalPoint));
            this.productId = config.productId;
            this.qtyIncrement = parseFloat(config.incrementQty);
            this.minCartQty = config.minQty;
            this.decimalPoint = config.decimalPoint;
            this.qtyDisplay = ko.observable(config.defaultQty);
            this.baseUrl = config.base_url;
            this.checkQtyUrl = BASE_URL + 'check_qty/cart/checkqtyaddtocart';
        },

        initQtyDisplay: function() {
            var self = this;
            $('.qty-display-' + this.productId).text(this.qty());

            $('.qty-display-' + this.productId).on('change', function() {
                var newQty = parseFloat($(this).text());
                self.qtyDisplay(newQty);
            });
        },

        decreaseQty: function() {
            var newQty = parseFloat(this.qty()) - this.qtyIncrement;
            newQty = parseFloat(newQty.toFixed(this.decimalPoint));
            newQty = Math.max(newQty, this.minCartQty);


            this.updateQty(newQty);
        },

        increaseQty: function() {
            var newQty = parseFloat(this.qty()) + this.qtyIncrement;
            newQty = parseFloat(newQty.toFixed(this.decimalPoint));
            var self = this;

            this.checkStock(this.productId, newQty, function(qtyStatus) {
                if (!qtyStatus) {
                    self.updateQty(newQty);
                } else {
                    alert('The requested quantity is not available.');
                }
            });
        },

        updateQty: function(newQty) {
            newQty = Math.max(newQty, this.minCartQty);
            this.qty(newQty);
            this.qtyDisplay(newQty);

            $('.qty-display-' + this.productId).text(newQty);
        },

        checkStock: function(product_id, newQty, callback) {
            var self = this;
            $.ajax({
                url: this.checkQtyUrl,
                data: JSON.stringify({'product_id': product_id, 'newQty': newQty}),
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                async: true,
                success: function(res) {
                    if (res.success === false || res.error === 1) {
                        callback(true);
                    } else {
                        callback(false);
                    }
                },
                error: function(xhr, status, error) {
                    callback(true);
                }
            });
        },

    });
});
