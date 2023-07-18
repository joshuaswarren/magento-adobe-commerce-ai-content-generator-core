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
                productId: "${ $.provider }:data.product_id",
                settings: "${ $.provider }:data.settings",
                storeId: "${ $.provider }:data.store_id"
            }
        },

        initObservable: function () {
            this._super()
                .observe([
                    'productId',
                    'storeId'
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
            const destinations = typeof this.destination === 'object' ? this.destination : [this.destination];
            let choices = data.choices;

            $.each(destinations, function (key, dest) {
                if (!choices.length) {
                    return;
                }
                const destination = uiRegistry.get(dest);
                destination.value.subscribe(function (val) {
                    this.setApplyBtnDisableSate(!(val && val.length), destination.applyBtn)
                }.bind(this))
                destination.value(choices.pop());
                destination.visible(true);
                $.each(destination.containers || [], function (key, container) {
                    container.visible(true);
                });
            }.bind(this));
        },

        setApplyBtnDisableSate: function (state, applyBtn) {
            const destBtn = uiRegistry.get(applyBtn ? applyBtn : this.applyBtn);
            if (destBtn) {
                destBtn.disabled(state);
                destBtn.visible(!state);
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
                        "product_id": this.productId(),
                        "product_attributes": [],
                        "min_length": this.getMinLength(),
                        "max_length": this.getMaxLength(),
                        "store_id": this.storeId(),
                        "number": this.number
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