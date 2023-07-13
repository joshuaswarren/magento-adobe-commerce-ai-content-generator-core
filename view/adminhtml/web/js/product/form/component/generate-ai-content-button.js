define([
    'jquery',
    'Magento_Ui/js/form/components/button',
    'uiRegistry',
    'loader'
], function ($, Button, uiRegistry) {
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
                product_id: "${ $.provider }:data.product_id"
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

        processCommon: function (data) {
            this.fillProxyField(data);
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
                        "min_length": null,
                        "max_length": null
                    }
                },
                url: url
            }).done(function (data) {
                successHandler(data)
            }).fail(function ( jqXHR, textStatus, errorThrown) {
                console.log( jqXHR, textStatus, errorThrown);
            }).always(function () {
                loaderStop();
            });
        }
    })
})