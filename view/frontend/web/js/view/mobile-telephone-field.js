
define([
    'ko',
    'uiComponent',
    'mage/translate',
    'smsNotifications'
], function (ko, Component, $t, smsNotifications) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mobtexting_SMSNotifications/mobile-telephone-field',
            mobileTelephonePrefixOptions: [],
            defaultMobileTelephonePrefix: '',
            mobileTelephonePrefix: '',
            mobileTelephoneNumber: '',
            tracks: {
                mobileTelephonePrefix: true,
                mobileTelephoneNumber: true
            },
            statefull: {
                mobileTelephonePrefix: true,
                mobileTelephoneNumber: true
            }
        },
        showField: ko.observable(false),
        initialize: function () {
            this._super();

            if (this.mobileTelephonePrefix.length === 0 && this.defaultMobileTelephonePrefix.length > 0) {
                this.mobileTelephonePrefix = this.defaultMobileTelephonePrefix;
            }
        },
        initObservable: function () {
            this._super();

            smsNotifications.isSubscribed.subscribe(this.toggleFieldVisibility, this);

            return this;
        },
        toggleFieldVisibility: function (isSubscribed) {
            this.showField(isSubscribed);

            if (!isSubscribed) {
                this.mobileTelephonePrefix = this.defaultMobileTelephonePrefix;
                this.mobileTelephoneNumber = '';
            }
        }
    });
});
