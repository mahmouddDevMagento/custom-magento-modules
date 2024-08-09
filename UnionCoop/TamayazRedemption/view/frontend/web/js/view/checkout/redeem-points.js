define([
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'Magento_Checkout/js/action/select-payment-method',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/cart/totals-processor/default',
    'Magento_Checkout/js/model/cart/cache',
    'ko'
], function ($, Component, quote, urlBuilder, selectPaymentMethodAction, customerData, defaultTotalsProcessor, cartCache, ko) {
    'use strict';

    var tamayazConfig = window.checkoutConfig.TamayazRedemption || {};

    return Component.extend({
        defaults: {
            template: 'Unioncoop_TamayazRedemption/checkout/redeem-points',
            isSwitchChecked: ko.observable(window.checkoutConfig.TamayazRedemption.use_redeem_points_amount),
        },
        blockTitle: tamayazConfig.block_title ?? 'Use Your Tamayaz points',
        redeemPoints: tamayazConfig.redeem_points ?? 0,
        redeemPointsAmount: tamayazConfig.redeem_points_amount ?? 0,

        initialize: function () {
            this._super();
            var self = this;

            // Subscribe to the switch state changes
            this.isSwitchChecked.subscribe(function (newValue) {
                if (newValue) {
                    self.validateAndSaveRedeemPoints(self.redeemPoints, self.redeemPointsAmount, true);
                    return;
                }
                self.saveRedeemPoints(0, 0, false);
            });

            // If the block is not displayed, ensure the switch is off and save the state
            if (!this.displayBlock()) {
                this.isSwitchChecked(false);
                this.saveRedeemPoints(0, 0, false);
            }
        },

        validateAndSaveRedeemPoints: function (points, pointsAmount, isSwitchOn) {
            var baseGrandTotal = quote.getTotals()().base_grand_total;

            // Validate if redemption amount is greater than the grand total
            if (pointsAmount > baseGrandTotal) {
                pointsAmount = baseGrandTotal;
            }

            this.saveRedeemPoints(points, pointsAmount, isSwitchOn);
        },

        // Save the redeem points and amount
        saveRedeemPoints: function (points, pointsAmount, isEnabled) {
            var updateUrl = urlBuilder.build('tamayazredemption/quote/saveRedeemPoints');
            var data = {
                redeem_points: points,
                redeem_points_amount: pointsAmount,
                redemptionSwitchEnabled : isEnabled
            };

            $.ajax({
                showLoader: true,
                url: updateUrl,
                data: data,
                type: "POST",
                dataType: 'json'
            }).done(function (response) {
                if (response.success) {
                    var totals = quote.getTotals()();
                    totals.base_grand_total -= pointsAmount;
                    quote.setTotals(totals);
                    customerData.set('checkout-data', quote);
                    cartCache.set('totals', null);
                    defaultTotalsProcessor.estimateTotals();
                } else {
                    console.error('Error saving redeem points and amount:', response.message);
                }
            }).fail(function (error) {
                console.error('Error saving redeem points and amount:', error);
            });
        },

        /**
         * Check if redemption is allowed
         * @returns {boolean}
         */
        canRedeem: ko.computed(function () {
            return tamayazConfig.canRedeem;
        }),

        /**
         * Determine if the Tamayaz redemption block should be displayed
         * @returns {boolean}
         */
        displayBlock: ko.computed(function () {
            return tamayazConfig.showPointsDetails;
        }),

    });
});
