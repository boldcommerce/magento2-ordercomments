define(
    [
        'jquery',
        'uiComponent',
        'knockout'
    ],
    function ($, Component, ko) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Bold_OrderComment/checkout/order-comment-block'
            },
            initialize: function() {
                this._super();
                var self = this;
                this.comment = ko.observable("");
                this.remainingCharacters = ko.computed(function(){
                    return self.getMaxLength() - self.comment().length;
                });
            },
            hasMaxLength: function() {
                return window.checkoutConfig.max_length > 0;
            },
            getMaxLength: function () {
                return window.checkoutConfig.max_length;
            },
            getInitialCollapseState: function() {
                return window.checkoutConfig.comment_initial_collapse_state;
            },
            isInitialStateOpened: function() {
                return this.getInitialCollapseState() === 1
            }
        });
    }
);
