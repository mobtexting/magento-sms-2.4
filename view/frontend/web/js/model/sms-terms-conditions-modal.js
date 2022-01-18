
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'smsNotifications'
], function ($, modal, $t, smsNotifications) {
    'use strict';

    return {
        modalWindow: null,
        /**
         * Create pop-up window for provided element.
         *
         * @param {HTMLElement} element
         * @param {string} title
         */
        createModal: function (element, title) {
            this.modalWindow = element;

            const options = {
                title: title,
                modalClass: 'modal-popup-sms-terms-conditions',
                responsive: true,
                buttons: [
                    {
                        text: $t('I Agree'),
                        class: 'action primary',
                        click: function () {
                            smsNotifications.isSubscribed(true);
                            smsNotifications.isSubscribing(false);

                            this.closeModal();
                        }
                    },
                    {
                        text: $t('Cancel'),
                        class: 'action secondary',
                        click: this.handleDisagree
                    }
                ],
                modalCloseBtnHandler: this.handleDisagree
            };

            modal(options, $(this.modalWindow));
        },
        showModal: function () {
            $(this.modalWindow).modal('openModal');
        },
        handleDisagree: function () {
            smsNotifications.isSubscribed(false);
            smsNotifications.isSubscribing(false);

            this.closeModal();
        }
    };
});
