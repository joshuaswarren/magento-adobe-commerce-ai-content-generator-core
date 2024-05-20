define([
    'jquery',
    'Magento_Ui/js/form/components/button',
    'uiRegistry',
    'wysiwygAdapter',
    'Magento_Ui/js/modal/alert',
    'loader',
    'mage/translate'
], function ($, Button, uiRegistry, wysiwygAdapter, alert) {
    'use strict';

    const loaderStart = function () {
        $(document.body).trigger('processStart')
    };

    const loaderStop = function () {
        $(document.body).trigger('processStop')
    };

    const displayAlert = function (title, msg, buttons) {
        alert({
            title: title,
            content: msg,
            actions: {
                always: function(){}
            },
            buttons: buttons
        })
    };

    return Button.extend({

        initialize: function () {
            this._super();

            if (this.delete()) {
                this.remove();
            }
        },

        initObservable: function () {
            this._super()
                .observe([
                    'delete'
                ]);

            return this;
        },

        action: function () {
            try {
                this.run();
            } catch (e) {
                console.error(e);

                displayAlert(
                    $.mage.__('Error'),
                    $.mage.__('Cannot apply generated content into <strong>%1</strong> field. You may need to copy-paste it manually.').replace('%1', this.destinationFieldTitle || $.mage.__('proper'))
                )
            }
        },

        getFormElementType: function (id) {
            const input = uiRegistry.get(id);
            if (!input) {
                return null;
            }

            if (input.pageBuilder) {
                return 'page-builder'
            }

            return input.formElement;
        },


        fillProxyField: function (data) {
            const destination = uiRegistry.get(this.destination);
            destination.value.subscribe(function (val) {
                this.setApplyBtnDisableSate(!(val && val.length))
            }.bind(this))
            destination.value(data.text);
        },

        getGeneratedContent: function () {
            const source = uiRegistry.get(this.sourceField);
            if (source.formElement === 'wysiwyg') {
                const editor = wysiwygAdapter.get(source.wysiwygId);

                return editor.getContent();
            }

            return source.value();
        },

        handleWysiwyg: function () {
            const el = uiRegistry.get(this.destination);
            const editor = wysiwygAdapter.get(el.wysiwygId);
            editor.setContent(this.getGeneratedContent());
            el.value(this.getGeneratedContent());
            wysiwygAdapter.triggerSave();

            this.displaySuccess();
        },

        handleTextInput: function () {
            uiRegistry.get(this.destination).value(this.getGeneratedContent());
            this.displaySuccess();
        },

        displaySuccess: function () {
            displayAlert(
                $.mage.__('Success'),
                $.mage.__('Generated content has been copied into <strong>%1</strong> field.').replace('%1', this.destinationFieldTitle || $.mage.__('proper'))
            )
        },

        handlePageBuilder: function () {
            let msg =     $.mage.__('Cannot copy generated content into <strong>%1</strong> field').replace('%1', this.destinationFieldTitle || $.mage.__('proper'));
            msg += '<br /><br />';
            msg += $.mage.__('The <strong>%1</strong> field uses Page Builder to manage its content and you must edit it manually. Please copy generated content and paste it in desired place.').replace('%1', this.destinationFieldTitle || $.mage.__('proper'));

            displayAlert(
                $.mage.__('Manual Action Required'),
                msg,
                [
                    {
                        text: $.mage.__('Copy'),
                        'class': 'action-primary action-basic',

                        /**
                         * Click action.
                         */
                        click: function () {
                            navigator.clipboard.writeText(this.getGeneratedContent())
                            window.alert('Copied to clipboard');
                        }.bind(this)
                    }
                ]
            );
        },

        setApplyBtnDisableSate: function (state) {
            const applyBtn = uiRegistry.get(this.applyBtn);
            if (applyBtn) {
                applyBtn.disabled(state)
            }
        },


        getHandler: function () {
            const formElementType = this.getFormElementType(this.destination);
            switch (formElementType) {
                case 'page-builder':
                    return this.handlePageBuilder.bind(this);
                case 'wysiwyg':
                    return this.handleWysiwyg.bind(this);
                case 'textarea':
                case 'input':
                    return this.handleTextInput.bind(this);
                default:
                    return null;
            }
        },

        run: function () {
            const type = this.aiContentType;
            const handler = this.getHandler();
            if (handler) {
                handler();
            }
        }
    })
})