define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'uiComponent'
], function ($, modal, urlBuilder, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/shipping',
            shippingFormTemplate: 'Magento_Checkout/shipping-address/form',
            shippingMethodListTemplate: 'Magento_Checkout/shipping-address/shipping-method-list',
            shippingMethodItemTemplate: 'Magento_Checkout/shipping-address/shipping-method-item',
            imports: {
                countryOptions: '${ $.parentName }.shippingAddress.shipping-address-fieldset.country_id:indexedOptions'
            }
        },

        initialize: function () {
            this._super();
            this.initModal();
        },

        initModal: function () {
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                buttons: [{
                    text: $.mage.__('Continue'),
                    class: 'action primary action-accept',
                    click: function () {
                        // Handle continue action here.
                        this.closeModal();
                    }
                }]
            };

            var popup = modal(options, $('#add-new-address-modal'));

            $('#add-new-address-button').on('click', function () {
                $.ajax({
                    // url: '/multishippingcustomization/index/addressform',
                    type: 'GET',
                    showLoader: true,
                    success: function (response) {
                        $('#add-new-address-modal').html(response).modal('openModal');
                    }
                });
            });
        }
    });
});
