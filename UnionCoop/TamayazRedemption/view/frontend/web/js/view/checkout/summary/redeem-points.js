/**
 * Copyright © UnionCoop.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals'
], function (Component, Totals) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Unioncoop_TamayazRedemption/checkout/summary/redeem-points'
        },

        getRedeemPoints: function () {
            var total = Totals.getSegment('redeem_points');
            return total.value ?? 0;

        },
        getFormattedRedeemPointsAmount: function () {
            return this.getFormattedPrice(this.getRedeemPoints());
        }
    });
});
