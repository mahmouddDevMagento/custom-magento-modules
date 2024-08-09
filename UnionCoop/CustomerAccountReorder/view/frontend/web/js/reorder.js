define([
    "jquery",
    "Magento_Ui/js/modal/modal"
], function($, modal) {
    "use strict";

    return function (config) {
        $(document).ready(function() {

            var options = {
                type: 'popup',
                responsive: true,
                modalClass: 'custom-popup-class',
                title: '<div class="title-container"> <span class="title-text">' + config.popupTitle + '</span> ' +
                    '<img class="title-image" src="' + config.imgPath + '"> ' +
                    '</div>',
                buttons: []
            };

            $('.buy-again-modal-content-'+config.orderId+' #select-all-items').click(function() {
                $('.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]').prop('checked', $(this).prop('checked'));
                toggleAddToCartButton();
                toggleQuantityControls();
            });

            $(document).on('click', '.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]', function() {
                var allChecked = $('.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]').length === $('.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]:checked').length;
                $('.buy-again-modal-content-'+config.orderId+' #select-all-items').prop('checked', allChecked);
                toggleAddToCartButton();
                toggleQuantityControls();
            });

            var popup = modal(options, $('.buy-again-modal-content-'+config.orderId));
            function openModal() {
                $('.buy-again-modal-content-'+config.orderId).modal('openModal');
            }

            $('.account.reorder-history-'+config.orderId).click(function(e) {
                e.preventDefault();
                openModal();
            });

            //
            // $(document).on('click', '.decrease-btn', function() {
            //     var input = $(this).siblings('.qty-display');
            //     var value = parseInt(input.text()) || 0;
            //     if (value > 1) {
            //         input.text(value - 1);
            //     }
            // });
            //
            // $(document).on('click', '.increase-btn', function() {
            //     var input = $(this).siblings('.qty-display');
            //     var value = parseInt(input.text()) || 0;
            //     input.text(value + 1);
            // });

            $(document).on('click', '.buy-again-modal-content-'+config.orderId+' #add-to-cart', function() {
                $('#loader-container').show();

                var selectedItems = [];
                $('.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]:checked').each(function() {
                    var itemId = $(this).val();
                    var qty = $(this).closest('.order-item').find('.qty-display').text();
                    selectedItems.push({ id: itemId, qty: qty });
                });

                $.ajax({
                    url: config.reorderAddToCartUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        items: selectedItems,
                    },
                    success: function(response) {
                        if (response) {
                            popup.closeModal();
                            // showMessage('Items added to cart successfully', 'success');
                            window.location.href = config.cartRedirectUrl;
                        }
                    },

                    complete: function() {
                        $('.buy-again-modal-content-'+config.orderId+' #loader-container').hide();
                    }
                });
            });

            function showMessage(message, type) {
                var messageContainer = $('.buy-again-modal-content-'+config.orderId+' #message-container');
                messageContainer.html('<div class="message ' + type + '">' + message + '</div>');
            }

            function toggleAddToCartButton() {
                var isChecked = $('.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]:checked').length > 0;
                $('.buy-again-modal-content-'+config.orderId+' #add-to-cart').prop('disabled', !isChecked);
            }

            toggleAddToCartButton();

            function toggleQuantityControls() {
                $('.buy-again-modal-content-'+config.orderId+' .order-item').each(function() {
                    var isChecked = $(this).find('input[type="checkbox"]').is(':checked');
                    var controls = $(this).find('.reorder-popup-control');
                    isChecked ? controls.show() : controls.hide();
                });
            }

            toggleQuantityControls();

            $(document).on('click', '.buy-again-modal-content-'+config.orderId+' .order-item input[type="checkbox"]', function() {
                toggleAddToCartButton();
                toggleQuantityControls();
            });

            $(document).on('click', '.buy-again-modal-content-'+config.orderId+' .notify-me-button', function() {
                var productId = $(this).closest('.buy-again-modal-content-'+config.orderId+' .order-item').find('.check-item').val();
                if (!productId) {
                    productId = $(this).closest('.buy-again-modal-content-'+config.orderId+' .item-details-container').data('product-id');
                }
                console.log('Product ID: ' + productId);

                $('.buy-again-modal-content-'+config.orderId+' #loader-container').show();

                var currentButton = $(this);

                $.ajax({
                    url: config.notifyUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        $('.buy-again-modal-content-'+config.orderId+' #loader-container').hide();

                        var messageContainer = currentButton.closest('.buy-again-modal-content-'+config.orderId+' .item-details-container').find('.notify-message-container');
                        if (response.success) {
                            messageContainer.html('<div class="notify-message success">' + response.message + '</div>');
                        } else {
                            messageContainer.html('<div class="notify-message error">' + response.message + '</div>');
                        }
                        setTimeout(function() {
                            messageContainer.find('.notify-message.success').remove();
                        }, 2500);
                    },
                    error: function(xhr, status, error) {
                        $('.buy-again-modal-content-'+config.orderId+' #loader-container').hide();
                        showMessage('Error sending notification: ' + error, 'error');
                    }
                });
            });

        });
    };
});
