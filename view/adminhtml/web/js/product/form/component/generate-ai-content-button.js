define([
    'jquery',
    'Magento_Ui/js/form/components/button',
    'uiRegistry',
    'Magento_Ui/js/modal/alert',
    'loader'
], function ($, Button, uiRegistry, alert) {
    'use strict';

    const loaderStart = function () {
        $(document.body).trigger('processStart')
    };

    const loaderStop = function () {
        $(document.body).trigger('processStop')
    };

    return Button.extend({
        defaults: {
            imports: {
                product_id: "${ $.provider }:data.product_id",
                settings: "${ $.provider }:data.settings"
            }
        },

        initObservable: function () {
            this._super()
                .observe([
                    'product_id'
                ]);

            return this;
        },

        action: function () {
            try {
                this.call();
            } catch (e) {
                console.error(e);
            }
        },

        fillProxyField: function (data) {
            const destination = uiRegistry.get(this.destination);
            destination.value.subscribe(function (val) {
                this.setApplyBtnDisableSate(!(val && val.length))
            }.bind(this))
            destination.value(data.text);
        },

        setApplyBtnDisableSate: function (state) {
            const applyBtn = uiRegistry.get(this.applyBtn);
            if (applyBtn) {
                applyBtn.disabled(state)
            }
        },

        getMinLength: function () {
            let length = this.minLength ? uiRegistry.get(this.minLength).value() : null;

            if (length && length <= 0) {
                return null;
            }

            return length;
        },

        getMaxLength: function () {
            let length = this.maxLength ? uiRegistry.get(this.maxLength).value() : null;

            if (length && length <= 0) {
                return null;
            }

            return length;
        },

        processCommon: function (data) {
            this.hideError();
            this.fillProxyField(data);
        },

        displayError: function (msg) {
            this.hideError();
            $('body').notification('clear')
                .notification('add', {
                    error: true,
                    message: msg,
                    insertMethod: function (message) {
                        const $wrapper = $('<div class="ai-error"></div>').html(message);

                        $('.page-main-actions').after($wrapper);
                    }
                });
        },

        hideError: function () {
            $('.ai-error').remove();
        },

        call: function () {
            const type = this.aiContentType;
            const url = this.actionUrl;
            const successHandler = this.processCommon.bind(this);
            if (!successHandler) {
                throw 'Unsupported form element';
            }
            if (!type) {
                throw 'No aiContent provided';
            }
            loaderStart();
            $.ajax({
                method: 'POST',
                data : {
                    "specification": {
                        "content_type": type,
                        "product_id": this.product_id(),
                        "product_attributes": [],
                        "min_length": this.getMinLength(),
                        "max_length": this.getMaxLength()
                    }
                },
                url: url
            }).done(function (data) {
                successHandler(data)
            }).fail(function ( jqXHR) {
                this.displayError(jqXHR.responseJSON.message);
            }.bind(this)).always(function () {
                loaderStop();
            });
        }
    })
})