
define(['Magento_Ui/js/lib/core/storage/local'], function (storage) {
    "use strict";

    return function () {
        if (storage.get('sms-notification-subscription.isSubscribeChecked') !== undefined) {
            storage.remove('sms-notification-subscription.isSubscribeChecked');
        }

        if (storage.get('sms-notification-subscription.selectedSmsTypes') !== undefined) {
            storage.remove('sms-notification-subscription.selectedSmsTypes');
        }

        if (storage.get('sms-mobile-telephone') !== undefined) {
            storage.remove('sms-mobile-telephone');
        }
    };
});
