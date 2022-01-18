
define([
    'jquery'
],
function ($) {
    $.widget('Mobtexting.checkboxToggler', {
        options: {
            checkboxSelector: '[data-role-toggleable-checkbox]'
        },
        checkboxElements: null,
        _create: function () {
            this.checkboxElements = $(this.options.checkboxSelector);

            this._registerEvents();
        },
        _registerEvents: function () {
            this.element.on('click', this.toggleCheckboxes.bind(this));
            this.checkboxElements.on('click', this.toggleSelectAll.bind(this));
        },
        toggleCheckboxes: function () {
            const self = this;

            this.checkboxElements.each(function () {
                $(this).prop('checked', self.element.prop('checked'));
            });
        },
        toggleSelectAll: function () {
            this.element.prop('checked', this.checkboxElements.filter(':checked').length === this.checkboxElements.length);
        }
    });

    return {
        'MobtextingCheckboxToggler': $.Mobtexting.checkboxToggler
    };
});
