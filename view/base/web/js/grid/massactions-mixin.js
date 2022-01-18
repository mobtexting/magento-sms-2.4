define([
    'underscore',
    'mageUtils'
], function (_, utils) {
    'use strict';

    return function (target) {
        return target.extend({
            /**
             * @inheritDoc
             * @todo: Figure out how to use callback in listing XML instead of overriding default callback.
             */
            defaultCallback: function (action, data) {
                if (data.params.namespace !== 'sms_subscriptions_listing') {
                    this._super(action, data);
                    return;
                }

                let itemsType = 'selected',
                    selections = {};

                data[itemsType] = _.difference(data.selected, data.excluded);
                selections[itemsType] = data[itemsType];

                if (!selections[itemsType].length) {
                    selections[itemsType] = false;
                }

                _.extend(selections, data.params || {});

                utils.submit({
                    url: action.url,
                    data: selections
                });
            }
        });
    };
});