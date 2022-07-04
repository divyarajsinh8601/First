define([
    'jquery',
    'Magento_Ui/js/form/components/insert-form'
], function ($, Insert) {
    'use strict';

    return Insert.extend({
        defaults: {
            listens: {
                responseData: 'onResponse'
            },
            modules: {
                speakerListing: '${ $.speakerListingProvider }',
                speakerModal: '${ $.speakerModalProvider }'
            }
        },

        /**
         * Close modal, reload speaker listing
         *
         * @param {Object} responseData
         */
        onResponse: function (responseData) {
            if (responseData.error) {
                $('body').notification('clear')
                .notification('add', {
                    error: true,
                    message: $.mage.__(responseData.messages),

                    /**
                     * @param {String} message
                     */
                    insertMethod: function (message) {
                        var $wrapper = $('<div></div>').html(message);

                        $('.page-main-actions').after($wrapper);
                    }
                });
            }
            if (!responseData.error) {
                this.speakerModal().closeModal();
                this.speakerListing().reload({
                    refresh: true
                });
            }
        }
    });
});
