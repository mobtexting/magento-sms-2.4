
define([
    'jquery',
    'ko',
    'uiComponent',
    'Mobtexting_SMSNotifications/js/model/sms-subscription-preferences-modal',
    'smsNotifications'
], function ($, ko, Component, subscriptionPreferencesModal, smsNotifications) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mobtexting_SMSNotifications/sms-subscription-preferences',
            groupedSmsTypes: {},
            selectedSmsTypes: [],
            modalTitle: null,
            tracks: {
                selectedSmsTypes: true
            }
        },
        modalTrigger: null,
        initObservable: function () {
            this._super();

            subscriptionPreferencesModal.open.subscribe(this.showModal);
            smsNotifications.selectedSmsTypes.subscribe(smsTypes => {
                if (smsTypes !== this.selectedSmsTypes) {
                    this.selectedSmsTypes = smsTypes;
                }
            }, this);

            const smsTypeCodes = this.groupedSmsTypes.map(groupedSmsType => groupedSmsType.smsTypes)
                .reduce(
                    (accumulator, currentValue) => [...accumulator, ...currentValue.map(smsType => smsType.code)],
                    []
                );

            this.selectAllSmsTypes = ko.pureComputed({
                read: () => this.selectedSmsTypes.length === smsTypeCodes.length,
                write: value => { this.selectedSmsTypes = value ? smsTypeCodes.slice(0) : []; },
                owner: this
            });

            if (this.selectedSmsTypes.length === 0) {
                this.selectAllSmsTypes(true);
            }

            return this;
        },
        initModal: function(element) {
            subscriptionPreferencesModal.createModal(element, this.modalTitle, this.hideModal.bind(this));
        },
        showModal: function () {
            subscriptionPreferencesModal.showModal();
        },
        hideModal: function () {
            smsNotifications.selectedSmsTypes(this.selectedSmsTypes);
        }
    });
});
