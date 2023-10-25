define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedImages = config.selectedImages, // Change this variable name
            carouselImages = $H(selectedImages),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        /**
         * Show selected images when editing the form in the associated image grid
         */
        $('carousel_images').value = Object.toJSON(carouselImages);

        /**
         * Register Carousel Image
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerCarouselImage(grid, element, checked) {
            if (checked) {
                if (element.positionElement) {
                    element.positionElement.disabled = false;
                    carouselImages.set(element.value, element.positionElement.value);
                }
            } else {
                if (element.positionElement) {
                    element.positionElement.disabled = true;
                }
                carouselImages.unset(element.value);
            }
            $('carousel_images').value = Object.toJSON(carouselImages);
            grid.reloadParams = {
                'selected_images[]': carouselImages.keys()
            };
        }

        /**
         * Click on image row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function carouselImageRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Change image position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                carouselImages.set(element.checkboxElement.value, element.value);
                $('carousel_images').value = Object.toJSON(carouselImages);
            }
        }

        /**
         * Initialize carousel image row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function carouselImageRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0],
                position = $(row).getElementsByClassName('input-text')[0];

            if (checkbox && position) {
                checkbox.positionElement = position;
                position.checkboxElement = checkbox;
                position.disabled = !checkbox.checked;
                position.tabIndex = tabIndex++;
                Event.observe(position, 'keyup', positionChange);
            }
        }

        gridJsObject.rowClickCallback = carouselImageRowClick;
        gridJsObject.initRowCallback = carouselImageRowInit;
        gridJsObject.checkboxCheckCallback = registerCarouselImage;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                carouselImageRowInit(gridJsObject, row);
            });
        }
    };
});
