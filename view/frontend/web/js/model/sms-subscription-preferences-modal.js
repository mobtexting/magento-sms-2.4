define([
    'jquery',
    'ko',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($, ko, modal, $t) {
    'use strict';

    return {
        open: ko.observable(false).extend({notify: 'always'}),
        modalWindow: null,
        /**
         * Create pop-up window for provided element.
         *
         * @param {HTMLElement} element
         * @param {string} title
         * @param {function(Object)} onClose
         */
        createModal: function (element, title, onClose) {
            this.modalWindow = element;

            const options = {
                title: title,
                modalClass: 'modal-popup-sms-subscription-preferences',
                responsive: true,
                buttons: [
                    {
                        text: $t('Save'),
                        class: 'action primary',
                        /** @inheritdoc */
                        click: function () {
                            if (onClose && typeof onClose === 'function') {
                                onClose(this);
                            }
                            
                            this.closeModal();
                        }
                    }
                ]
            };

            modal(options, $(this.modalWindow));
        },
        showModal: function () {
            $(this.modalWindow).modal('openModal');
        }
    };
});
