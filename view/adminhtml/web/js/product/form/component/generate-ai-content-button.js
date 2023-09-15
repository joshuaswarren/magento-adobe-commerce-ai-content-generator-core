define([
    'jquery',
    'Magento_Ui/js/form/components/button',
    'uiRegistry',
    'Magento_Ui/js/modal/alert',
    'wysiwygAdapter',
    'ko',
    'loader'
], function ($, Button, uiRegistry, alert, wysiwygAdapter, ko) {
    'use strict';

    const loaderStart = function () {
        $(document.body).trigger('processStart')
    };

    const loaderStop = function () {
        $(document.body).trigger('processStop')
    };

    return Button.extend({

        initObservable: function () {
            this._super()
                .observe([
                    'productId',
                    'storeId',
                    'settings'
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

        getProductAttributes: function () {
            try {
                const productAttributesIndex = this.containers[0].containers[0].name + '.product_attributes_container.product_attributes';
                const input = uiRegistry.get(productAttributesIndex);
                if (input && input.value()) {
                    return input.value();
                }
            } catch (e) {
                console.error(e);
            }

            return [];
        },

        fillProxyField: function (data) {
            const destinations = typeof this.destination === 'object' ? this.destination : [this.destination];
            let choices = data.choices;

            $.each(destinations, function (key, dest) {
                if (!choices || !choices.length) {
                    return;
                }
                const destination = uiRegistry.get(dest);
                destination.value.subscribe(function (val) {
                    this.setApplyBtnDisableSate(!(val && val.length), destination.applyBtn)
                }.bind(this))
                const content = choices.pop();
                if (destination.formElement === 'wysiwyg') {
                    const editor = wysiwygAdapter.get(destination.wysiwygId);
                    editor.setContent(content);
                    wysiwygAdapter.triggerSave();
                }
                destination.value(content);
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
            if (!this.minLength) {
                return null;
            }

            const input = uiRegistry.get(this.minLength);
            if (input && input.error()) {
                throw input.error();
            }

            return parseInt(input.value());
        },

        getMaxLength: function () {
            if (!this.maxLength) {
                return null;
            }

            const input = uiRegistry.get(this.maxLength);
            if (input && input.error()) {
                throw input.error();
            }

            return parseInt(input.value());
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
            const minLength = this.getMinLength();
            const maxLength = this.getMaxLength();

            loaderStart();
            $.ajax({
                method: 'POST',
                data : {
                    "specification": {
                        "content_type": type,
                        "product_id": this.productId(),
                        "product_attributes": this.getProductAttributes(),
                        "min_length": minLength,
                        "max_length": maxLength,
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