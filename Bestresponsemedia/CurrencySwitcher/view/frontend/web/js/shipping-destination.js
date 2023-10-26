define([
    'jquery',
    'jquery/jquery.cookie'
], function ($) {
    'use strict';
    return function(){
        var ShippingCountry = $.cookie('shipping_county'); // Get Cookie Value

        if(ShippingCountry){
            $('.brm_shipping_country').empty();
            $('.brm_shipping_country').text(ShippingCountry);
        }
    };
});