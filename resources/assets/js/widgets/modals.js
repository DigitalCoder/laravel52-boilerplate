/*jslint browser: true*/
/*jslint node: true */
/*global $, bootbox, window, clShowLoading, tinyMCE */

$(document).ready(function () {
    "use strict";
    /**
    * If two modals (including Bootbox) are open at once, closing the top modal will cause issues for the
    * remaining modal. This restores the class needed by the remaining modal
    */
    $("body").on("hidden.bs.modal", function () {
        if ($('.modal.in').css('display') === 'block') {
            $('body').addClass('modal-open');
        }
    });
});