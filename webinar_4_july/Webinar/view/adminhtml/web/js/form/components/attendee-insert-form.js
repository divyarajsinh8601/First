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
                attendeeListing: '${ $.attendeeListingProvider }',
                attendeeModal: '${ $.attendeeModalProvider }'
            }
        },

        /**
         * Close modal, reload attendee listing
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
                this.attendeeModal().closeModal();
                this.attendeeListing().reload({
                    refresh: true
                });
            }
        }
    });
});
