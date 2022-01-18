

define([
    'jquery',
    'uiComponent',
    'Mobtexting_SMSNotifications/js/model/sms-terms-conditions-modal',
    'smsNotifications'
], function ($, Component, termsConditionsModal, smsNotifications) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mobtexting_SMSNotifications/sms-terms-conditions',
            modalTitle: null,
            modalContent: null
        },
        initObservable: function () {
            this._super();

            smsNotifications.isSubscribing.subscribe(this.showModal);

            return this;
        },
        initModal: function(element) {
            termsConditionsModal.createModal(element, this.modalTitle);
        },
        showModal: function () {
            if (!smsNotifications.isSubscribing()) {
                return;
            }

            termsConditionsModal.showModal();
        }
    });
});
