/*jslint browser: true*/
/*jslint node: true */
/*global $, bootbox, window, vcShowLoading, tinyMCE */

/**
* Contains fixes specific to Firefox
*/

var browserIsFirefox = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());

if (browserIsFirefox) {
    /**
    * Fixes a firefox bug that was not showing the modal or overlay in the correct position if
    * the page was scrolled.
    */
    $("body").on("show.bs.modal", function (e) {
        "use strict";
        $(e.target).removeClass('fade');
    });
    $("body").on("shown.bs.modal", function (e) {
        "use strict";
        $(e.target).css({
            bottom: 'auto',
            height: '100vh',
            top: $(document).scrollTop()
        });
        $(".modal-backdrop").css({
            bottom: '',
            height: '100vh',
            top: $(document).scrollTop()
        });
    });
}