define(
    [
        'jquery',
        'uiComponent',
        'knockout'
    ],
    function ($, Component, ko) {
        'use strict';

        ko.extenders.maxOrderCommentLength = function (target, maxLength) {
            var timer;
            var result = ko.computed({
                read: target,
                write: function (val) {
                    if (maxLength > 0) {
                        clearTimeout(timer);
                        if (val.length > maxLength) {
                            var limitedVal = val.substring(0, maxLength);
                            if (target() === limitedVal) {
                                target.notifySubscribers();
                            } else {
                                target(limitedVal);
                            }
                            result.css("_error");
                            timer = setTimeout(function () { result.css(""); }, 800);
                        } else {
                            target(val);
                            result.css("");
                        }
                    } else {
                        target(val);
                    }
                }
            }).extend({ notify: 'always' });
            result.css = ko.observable();
            result(target());
            return result;
        };

        return Component.extend({
            defaults: {
                template: 'Bold_OrderComment/checkout/order-comment-block'
            },
            initialize: function() {
                this._super();
                var self = this;
                this.comment = ko.observable("").extend({maxOrderCommentLength: this.getMaxLength()});

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
