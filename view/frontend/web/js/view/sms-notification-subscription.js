
define([
    'jquery',
    'ko',
    'uiComponent',
    'mage/translate',
    'smsNotifications',
    'Mobtexting_SMSNotifications/js/model/sms-subscription-preferences-modal',
    'Mobtexting_SMSNotifications/js/model/sms-terms-conditions-modal'
], function ($, ko, Component, $t, smsNotifications, subscriptionPreferencesModal, termsConditionsModal) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mobtexting_SMSNotifications/sms-notification-subscription',
            isOptinRequired: true,
            isTermsAndConditionsShownAfterOptin: true,
            selectedSmsTypes: '',
            isSubscribeChecked: false,
            tracks: {
                isSubscribeChecked: true,
                selectedSmsTypes: true
            },
            statefull: {
                isSubscribeChecked: true,
                selectedSmsTypes: true
            }
        },
        showTermsAndConditions: null,
        initialize: function () {
            this._super();

            if (!this.isOptinRequired && !this.isSubscribeChecked) {
                this.isSubscribeChecked = true;
            }

            smsNotifications.isSubscribed(this.isSubscribeChecked);

            if (this.selectedSmsTypes.length > 0) {
                smsNotifications.selectedSmsTypes(this.selectedSmsTypes.split(','));
            }
        },
        initObservable: function () {
            const self = this;

            this._super();

            this.showTermsAndConditions = ko.computed(function () {
                return self.isOptinRequired && self.isTermsAndConditionsShownAfterOptin;
            });

            smsNotifications.isSubscribed.subscribe(this.handleSubscribe, this);
            smsNotifications.selectedSmsTypes.subscribe(this.setSmsSelectedTypes, this);

            return this;
        },
        handleCheckboxClick: function (data, event) {
            if (!$(event.target).is(':checked')) {
                smsNotifications.isSubscribing(false);
                smsNotifications.isSubscribed(false);

                return true;
            }

            if (data.showTermsAndConditions()) {
                smsNotifications.isSubscribing(true);

                event.stopImmediatePropagation();

                return false;
            }

            smsNotifications.isSubscribing(false);
            smsNotifications.isSubscribed(true);

            return true;
        },
        handlePreferencesClick: function () {
            subscriptionPreferencesModal.open(true);
        },
        handleTermsConditionsClick: function () {
            termsConditionsModal.showModal();
        },
        handleSubscribe: function (isSubscribed) {
            this.isSubscribeChecked = isSubscribed;
        },
        setSmsSelectedTypes: function (selectedSmsTypes) {
            selectedSmsTypes = selectedSmsTypes.join(',');

            if (selectedSmsTypes !== this.selectedSmsTypes) {
                this.selectedSmsTypes = selectedSmsTypes;
            }
        }
    });
});
