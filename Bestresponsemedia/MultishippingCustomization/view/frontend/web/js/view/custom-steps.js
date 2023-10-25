define([
    'jquery',
    'Magento_Ui/js/form/form',
    'mage/url',
    'domReady!'
], function ($, Component, url) {
    'use strict';

    return Component.extend({

        initialize: function (config) {
            this._super();
            this.updateShippingSteps();
            return this;
        },

        updateShippingSteps: function () {

            $('#shipping_method_form').submit(function (e) {
                e.preventDefault();
                var customShipping = url.build('multishippingcustomization/index/customshipping');
                $.ajax({
                    showLoader: true,
                    url: customShipping,
                    data: $(this).serialize(),
                    type: 'POST',
                    dataType: 'json'
                }).complete(function (data) {
                    $('#step2').trigger('click');
                    $('#shipping_method_form').hide();
                    $('#multishipping-billing-form').show();
                    $('#step2').addClass('active');
                    $('#step1').removeClass('active');
                    $('#step2').hide();
                 //   history.pushState({}, null, '/multishipping/checkout/billing/');
                    return true;
                });
            });

            $('#step1').on('click', function () {
               // history.pushState({}, null, '/multishipping/checkout/shipping/');
                // window.history.pushState('step1', 'Title', '/multishipping/checkout/shipping/');
                $('#step1').addClass('active');
                $('#step2').removeClass('active');

                $('#shipping_method_form').show();
                $('#multishipping-billing-form').hide();

                $('#next-button').show();


            });

            $('#step2').on('click', function (e) {
                e.preventDefault();
                var customShipping = url.build('multishippingcustomization/index/customshipping');
                $.ajax({
                    showLoader: true,
                    url: customShipping,
                    data: $('#shipping_method_form').serialize(),
                    type: 'POST',
                    dataType: 'json'
                }).complete(function (data) {
                   // history.pushState({}, null, '/multishipping/checkout/billing/');
                    $('#step2').show().addClass('active');
                    $('#step1').removeClass('active');
                    $('#shipping_method_form').hide();
                    $('#multishipping-billing-form').show();
                    $('#next-button').hide();
                    return true;
                });

            });
        },
    });

});

