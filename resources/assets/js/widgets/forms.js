/*jslint browser: true*/
/*jslint node: true */
/*global $, bootbox, window */

(function () {
    "use strict";

    $(document).ready(function () {
        initChangeListeners();
        initConfirmLeave();
        initConfirmDeleteForms();
        initFormValidity();
        initPrependUrlField();
        initSelectOnFocus();
    });

    window.clInitFormListeners = function ($target) {
        var $form = $('input,textarea,select', $target)
            .map(function (index, input) {
                return input.form;
            });

        initChangeListeners($target, $form);
        if ($form) {
            checkFormIsValid($form);
        }
    };

    /**
     * Set up handling to confirm navigation when form fields have changed without saving
     * @returns {string}
     */
    function confirmLeave() {
        if ($(".cl-field-modified").length > 0) {
            return 'You have unsaved changes.';
        }
    }

    window.clResetFieldChangeStatus = function (field) {
        field
            .data('cl-original-field-value', $(field).val())
            .removeClass("cl-field-modified");
    };

    /**
     * If the user tries to navigate away from a page containing an unsaved form with changed values, confirm the navigation.
     */
    function initConfirmLeave() {

        if (!$('body').hasClass('cl-route-login')) {
            // This will catch navigating away for any reason other than clicking a link, such as Refreshing or entering a new URL.
            // Uses the browser's confirmation dialog as there is no way to override it
            $(window).on('beforeunload', confirmLeave);
        }

        // Allow submitting a form without showing the warning message
        $('form').on('submit', function () {
            $(".cl-field-modified").removeClass("cl-field-modified");
        });

        // When navigating away via a link, use the Bootbox confirmation modal instead of the browser's built-in modal, since we can catch this event.
        $("a").click(function () {
            var href = $(this).attr('href');
            if (($(".cl-field-modified").length === 0) || (href === "") || (href === "#")) {
                //no changes, or not going anywhere
                return true;
            }
            if ($(this).hasClass("ignore-changes")) {
                //ok to ignore changes
                $(".cl-field-modified").removeClass("cl-field-modified");
                return true;
            }
            bootbox.confirm("Are you sure you want to navigate away from this page? You have unsaved changes that will be lost.", function (result) {
                if (result === true) {
                    $(".cl-field-modified").removeClass("cl-field-modified");
                    location.href = href;
                }
            });
            return false;
        });
    }

    /**
     * This function is refactored so that it can be called from dynamic actions after page load
     * @param $target
     * @param
     */
    function initChangeListeners($target, $form) {
        $target = $target || $('form');
        $form = $form || $target;

        // Store the original value of each field. Since this could be called multiple times for a form
        // that has dynamic fields, add a class so that we don't re-initialize fields multiple times.
        $('textarea,input,select,text', $target).filter(":not(.cl-js-change-initialized):not(.cl-js-no-change)").each(function () {
            var $originalValue = null;
            if ($(this).is(":checkbox") || $(this).is(":radio")) {
                $originalValue = $(this).prop("checked");
            } else {
                $originalValue = $(this).val();
            }
            $(this)
                .data('cl-original-field-value', $originalValue)
                .addClass("cl-js-change-initialized")
                .on('change keyup click', function () {
                    var $originalValue = $(this).data("cl-original-field-value"),
                        $currentValue = null,
                        $fieldName = null;
                    if ($(this).is(":radio")) {
                        // For radio fields, you have to check each element in the group
                        $fieldName = $(this).attr('name');
                        $("input[type=radio][name=" + $fieldName + "]").each(function () {
                            $currentValue = $(this).prop("checked");
                            $originalValue = $(this).data("cl-original-field-value");
                            if ($currentValue == $originalValue) {
                                $(this).removeClass("cl-field-modified");
                            } else {
                                $(this).addClass("cl-field-modified");
                            }
                        });
                    } else {
                        if ($(this).is(":checkbox")) {
                            $currentValue = $(this).prop("checked");
                        } else {
                            $currentValue = $(this).val();
                        }
                        if ($currentValue == $originalValue) {
                            $(this).removeClass("cl-field-modified");
                        } else {
                            $(this).addClass("cl-field-modified");
                        }
                    }
                }).on('change keypress click blur', function () {
                    checkFormIsValid($form);
                });
        });

        $(':required', $target).one('blur keydown', function () {
            $(this).addClass('cl-touched');
        });
    }

    /**
     * Disable the submit button on a form if it is not valid.
     * @returns {null}
     */
    function initFormValidity() {
        var $forms = $('form');

        if ($forms.length < 1) {
            return null;
        }

        $forms.each(function () {
            if (!$(this).hasClass('cl-delete-button-form')) {
                checkFormIsValid($(this));
            }
        });
    }

    /**
     * Toggles submit button depending on form validity
     * @param $form
     */
    function checkFormIsValid($form) {
        var $button = $('button[type=submit]:not(.cl-delete-button):not(.cl-table-action-button):not(.cl-modal-header-button):not(.cl-create-button)', $form),
            disabled = true;
        if (!$form || ($form.length < 1) || !$button || ($button.length < 1)) {
            return null;
        }

        if ($button.hasClass('cl-js-default-button-enabled')) {
            // Button is enabled by default
            disabled = false;
        } else {
            if (($(".cl-js-change-initialized").length === 0) || ($(".cl-field-modified").length > 0)) {
                // Form either does not track changed fields, or has at least one changed field
                if ($form.get(0).checkValidity()) {
                    // Form is valid
                    disabled = false;
                }
            }
        }
        $button.prop('disabled', disabled);
    }

    /**
     * Intercept the click of any delete buttons in a form with class "cl-delete-button-form", and confirm deletion before proceeding.
     */
    function initConfirmDeleteForms() {
        $(".cl-delete-button-form").on('submit', function (e) {
            var form = $(this),
                description = $(this).data('description');
            if (form.data("submission-confirmed") === "1") {
                // form was submitted by this function
                return true;
            }
            if (!description || (description === '')) {
                description = 'record';
            }
            bootbox.confirm("Are you sure you want to permanently delete this " + description + "?", function (result) {
                if (result === true) {
                    form.data("submission-confirmed", "1");
                    form.submit();
                }
            });
            e.preventDefault();
            return false;
        });
    }

    /**
     * Prepend any URLs entered into a type=url field with "http://", if missing
     */
    function initPrependUrlField() {
        $("input[type=url]").on('blur', function () {
            var url = $(this).val();
            if (url === '') {
                //ignore blank
                return true;
            }
            if (!(/^http(s?):\/\//i).test(url)) {
                $(this).val('http://' + url);
            }
        });
    }

    /**
     * Any input with "cl-select-on-focus" class will automatically select the entire contents on focus or click
     */
    function initSelectOnFocus() {
        $(".cl-select-on-focus").on('focus click', function () {
            $(this).select();
        });
    }

})();
