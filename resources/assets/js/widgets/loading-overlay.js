/*jslint browser: true*/
/*jslint node: true */
/*global $, window, browserIsFirefox*/

//browserIsFirefox is defined in firefox.js

window.clHideLoading = function () {
    "use strict";
    $("body").removeClass("cl-loading-overlay-active");
    $("#cl-loading-overlay").removeClass("active");
};

window.clShowLoading = function () {
    "use strict";
    if (browserIsFirefox) {
        $("#loading-overlay").css({
            bottom: '',
            height: '100vh',
            top: $(document).scrollTop()
        });
        $("body").addClass("cl-loading-overlay-active");
    }
    $("#cl-loading-overlay").addClass("active");
};

$(document).ready(function () {
    "use strict";
    var loadingOverlay = $('<div id="cl-loading-overlay"><span class="glyphicon glyphicon-refresh glyphicon-spin"></span></div>');
    $(document.body).append(loadingOverlay);
});