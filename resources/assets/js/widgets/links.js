/*jslint browser: true*/
/*jslint node: true */
/*global $, bootbox, window */

/*
 * Any buttons with the class "cl-link-button" will function as a link. The URL should be contained in data-href.
 * Using the class "cl-link-new" will open the link in a new tab.
 */
function initLinkButtons() {
    "use strict";
    //opens in the same window/tab
    $(".cl-link-button").on('click', function (e) {
        if ($(this).attr('data-href')) {
            location.href = $(this).data('href');
        }
        $(this).blur();
        e.preventDefault();
    });

    //prevent a click on an actual button within the link button from triggering navigation
    $("button[data-toggle=modal]").click(function (e) {
        var targetModal = $($(this).data('target'));
        if (!targetModal) {
            return true;
        }
        e.stopPropagation();
        targetModal.modal('show');
    });

    //opens in a new window/tab
    $(".cl-link-button-new").on('click', function (e) {
        var win;
        if ($(this).attr('data-href')) {
            win = window.open($(this).data('href'), '_blank');
            win.focus();
        }
        $(this).blur();
        e.preventDefault();
    });

    //opens the print dialog
    $(".cl-print-button").on('click', function () {
        window.print();
        return false;
    });
}

$(document).ready(function () {
    "use strict";
    initLinkButtons();
});