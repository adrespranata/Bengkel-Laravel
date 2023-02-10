/* ==============================================
 *
 *  @name     toast.js
 *  @author   Frend
 *  @github   https://github.com/FrendEr/toast.js
 *
 * ==============================================
 */

;(function(define) {
    define(['jquery'], function($) {

        'use strict';

        var timer = null;

        var DEFAULTS = {
            duration  : 2000,
            animateIn : 'fadeIn',
            animateOut: 'fadeOut'
        };

        // message status
        var status = {
            info    : 'toast-info',
            error   : 'toast-error',
            success : 'toast-success'
        };

        // animation type,
        // more info in animate.css(https://github.com/daneden/animate.css)
        var animations = {
            shake       : 'shake',
            fadeIn      : 'fadeIn',
            fadeInUp    : 'fadeInUp',
            flipInX     : 'flipInX',
            fadeOut     : 'fadeOut',
            flipOutX    : 'flipOutX',
            fadeOutDown : 'fadeOutDown',
            fadeOutUp   : 'fadeOutUp'
        };

        // toast object
        var toast = {
            info: info,
            error: error,
            success: success
        };

        return toast;

        /*
         * @function  info
         * @usage     display normal message
         */
        function info(options) {
            DEFAULTS.status = 'info';
            return notify(
                extend(DEFAULTS, options)
            );
        }

        /*
         * @function  error
         * @usage     display error message
         */
        function error(options) {
            DEFAULTS.status = 'error';
            return notify(
                extend(DEFAULTS, options)
            );
        }

        /*
         * @function  success
         * @usage     display success message
         */
        function success(options) {
            DEFAULTS.status = 'success';
            return notify(
                extend(DEFAULTS, options)
            );
        }

        /*
         * @function  notify
         * @usage     core function
         */
        function notify(options) {
            var target;

            if (timer !== null && $('.toast-message').length) {
                // clear timeout
                clearTimeout(timer);
                timer = null;

                // reset singleton
                target = $('.toast-message').attr('class', 'toast-message').show();
            } else if (!$('.toast-message').length) {
                target = $('<div class="toast-message"></div>');
            }

            target
                .html(options.message)
                .addClass(status[options.status] + ' animated ' + options.animateIn);

            timer = setTimeout(function() {
                target.addClass(options.animateOut);

                // custom callback trigger
                options.callback && typeof options.callback === 'function' && options.callback.call();
            }, options.duration);

            return target.appendTo(document.body);
        }

        /*
         * @function   extend
         * @usage      extend object, don't support deep-clone, please keep the options simple
         */
        function extend(destination, source) {
            for (var prop in source) {
                destination[prop] = source[prop];
            }

            return destination;
        }

    });
}(typeof define === 'function' && define.amd ? define : function(deps, factory) {
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        window.toast = factory(window.jQuery);
    }
}));
