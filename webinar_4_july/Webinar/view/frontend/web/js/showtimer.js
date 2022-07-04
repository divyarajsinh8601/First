define([
    'jquery',
    'mage/url',
    'uiComponent'
], function ($, url, Component) {
    'use strict';

    return Component.extend({
        initialize: function (config) {
            this._super();

            if ($(config.countdown).length) {

                //$(config.countdown).show();

                // Set the date we're counting down to
                var countDownDate = new Date(this.webinarDate).getTime();

                // Update the count down every 1 second
                var x = setInterval(function() {

                    // Get today's date and time
                    var now = new Date().getTime();

                    // Find the distance between now and the count down date
                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $(config.countdown).find(".days").html(days);
                    $(config.countdown).find(".hours").html(hours);
                    $(config.countdown).find(".minutes").html(minutes);
                    $(config.countdown).find(".seconds").html(seconds);

                    // If the count down is finished, clear the interval
                    if (distance < 0) {
                        clearInterval(x);
                    }
                }, 1000);
            }

            this.isCurrentCustomerEnrolled(config);
        },

        isCurrentCustomerEnrolled: function (config) {
            var linkUrl = url.build('webinars/index/checkattendee');
            $.ajax({
                showLoader: false,
                type: "POST",
                url: linkUrl,
                data: {
                    webinarId: config.webinarId
                },
                success: function(response) {
                    if (response.registered) {
                        $(config.countdown).show();
                    }
                }
            });
        }
    });
});