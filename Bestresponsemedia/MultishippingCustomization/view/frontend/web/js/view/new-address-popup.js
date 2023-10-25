define([
    'jquery',
    'Magento_Ui/js/form/form',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    'domReady!'
], function ($, Component, url, modal) {
    'use strict';

    return Component.extend({

        initialize: function (config) {
            this._super();
            return this;
        },

        addNewAddPopup: function () {
            var addressForm = url.build('multishippingcustomization/index/addressform');
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'custom-popup-modal',
                buttons: []
            };
            var popup = modal(options, $('#address-form-modal'));
            $.ajax({
                showLoader: true,
                url: addressForm,
                data: {},
                type: 'POST',
                dataType: 'json'
            }).done(function (data) {
                $('#result').html(data.output);
                $('#address-form-modal').modal('openModal');
                $('body').trigger('contentUpdated');
                return true;
            });
        },
    });
});
