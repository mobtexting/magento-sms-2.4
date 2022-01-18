
define(['ko'], function (ko) {
    let isSubscribed = ko.observable(false),
        isSubscribing = ko.observable(false),
        selectedSmsTypes = ko.observableArray();

    isSubscribed.extend({ notify: 'always' });

    return {
        isSubscribed: isSubscribed,
        isSubscribing: isSubscribing,
        selectedSmsTypes: selectedSmsTypes
    };
});
