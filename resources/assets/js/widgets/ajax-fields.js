/*jslint browser: true*/
/*jslint node: true */
/*global $, bootbox, window, csrfToken, clShowLoading, clHideLoading */
$(document).ready(function () {
    "use strict";
    // Set the CSRF token for all AJAX calls
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        jqXHR.setRequestHeader("X-CSRF-Token", csrfToken);
        options.cache = false;
    });

    // Always hide the loading overlay when an AJAX request completes
    $(document)
        .ajaxComplete(function () {
            clHideLoading();
        })
        .ajaxError(function () {
            bootbox.alert("An error occurred while saving your data.");
        });

    $(".cl-ajax-field").each(function () {
        var field = $(this),
            fieldName = $(this).attr('name');

        //field must have a "data-url" attribute
        if (!field.attr('data-url')) {
            return null;
        }

        if (field.is(":radio")) {
            field.data('cl-original-value', $("input[type=radio][name=" + fieldName + "]:checked").val());
            $("input[type=radio][name=" + fieldName + "]")
                .on('click keyup', function () {
                    var element = $("input.cl-ajax-field[name=" + fieldName + "]"), //get the first one, since that holds the rest of the data
                        value = $("input[type=radio][name=" + fieldName + "]:checked").val();
                    window.clSendAjaxField(element, value);
                });
        } else {
            field.data('cl-original-value', field.val());
            field.on('change blur', function () {
                var value,
                    element = $(this),
                    dateTimePicker;

                //if this is a datetimepicker field, get the ISO date
                dateTimePicker = $(this).data('DateTimePicker');
                if (dateTimePicker) {
                    value = dateTimePicker.date().format();
                } else {
                    value = $(this).val();
                }

                window.clSendAjaxField(element, value);
            });
        }
    });

    $("body").on('click', ".cl-delete-button-ajax", function () {
        var id = $(this).data('id'),
            description = $(this).data('description'),
            targetUrl = $(this).data('url'),
            payload;
        if (!id || !description || !targetUrl) {
            return null;
        }
        bootbox.confirm("Are you sure you want to permanently delete this " + description + "?", function (result) {
            if (result === true) {
                payload = {
                    "_method": 'DELETE',
                    "id": id
                };
                clShowLoading();
                $.ajax({
                    url: targetUrl,
                    data: payload,
                    method: 'POST',
                    success: function (data) {
                        if (data.status && (data.status === 'success')) {
                            if (window.clDeleteCallback && (typeof window.clDeleteCallback === 'function')) {
                                window.clDeleteCallback(id);
                            }
                        } else {
                            bootbox.alert("An error occurred while deleting this " + description + (data.message
                                ? ': ' + data.message + '.'
                                : '.'));
                        }
                    }
                });
            }
        });
        return false;
    });
});

window.clSendAjaxField = function (element, value) {
    "use strict";
    var oldValue = element.data('cl-original-value'),
        name = element.attr('name'),
        dataFieldName = element.data('field-name') || element.attr('name'),
        url = element.data('url'),
        payload = {},
        relatedElements = $("input[name=" + name + "],textarea[name=" + name + "]");
    if (value === oldValue) {
        //no change, so ignore
        return null;
    }

    if (element.hasClass('cl-ajax-pending')) {
        //update in progress, so ignore duplicate request
        return null;
    }

    //set the field to read-only while saving
    relatedElements
        .prop('readonly', true)
        .addClass('cl-ajax-pending');

    payload[dataFieldName] = value;
    $.ajax({
        url: url,
        data: payload,
        method: 'POST',
        success: function (data) {
            if (data.status && (data.status === 'success')) {
                element.data('cl-original-value', value);
            } else {
                bootbox.alert("An error occurred while saving this change." + (data.message
                    ? ': ' + data.message + '.'
                    : '.'));
                element.val(element.data('cl-original-value'));
            }
            relatedElements
                .prop('readonly', false)
                .removeClass('cl-ajax-pending');
        },
        error: function () {
            relatedElements
                .val(element.data('cl-original-value'))
                .prop('readonly', false)
                .removeClass('cl-ajax-pending');
        }
    });
};