$(document).ready(function () {
    if(typeof osc !== 'undefined') {
        if(osc.mouse_dragging) {
            $('.main-panel').perfectScrollbar('update');

            $('body').on('mouseover', '.sidebar-wrapper', function() {
                $('.sidebar .sidebar-wrapper').perfectScrollbar('update');
            });
        }

        $('input[type="file"]').fileinputAuto({
            btnClass: "btn btn-rose mt-3",
            btnSelect: osc.translations.msg_select_file,
            btnRemove: osc.translations.msg_remove_file,
            btnChange: osc.translations.msg_change_file
        });

        $('#bulk-actions-btn').click(function () {
            var $bulk_type = $(this).attr('data-bulk-type'),
                $bulk_action = $('#bulk-actions option:selected'),
                $bulk = $bulk_action.val(),
                $bulk_alert = $bulk_action.attr('data-dialog-content'),
                $bulk_alert_action = $bulk_action.text();

            $('#bulk_actions').val($bulk);

            if ($bulk) {
                if ($('#item-selected:checked').length) {
                    Swal.fire({
                        title: osc.translations.msg_confirm_action,
                        text: $bulk_alert,
                        type: 'warning',
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonClass: "btn btn-success",
                        cancelButtonClass: "btn btn-danger",
                        confirmButtonText: $bulk_alert_action,
                        cancelButtonText: osc.translations.msg_cancel,
                    }).then((result) => {
                        if (result.value) {
                            $('#bulk-actions-form').submit();
                        }
                    });
                } else {
                    Swal.fire({
                        text: $('#msg-' + $bulk_type + '-not-selected').val(),
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-info",
                        showCancelButton: false
                    });
                }
            } else {
                Swal.fire({
                    text: $('#msg-bulk-action-not-selected').val(),
                    type: 'error',
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-info",
                    showCancelButton: false
                });
            }
        });

        $('#countryName').on('keydown', function () {
            var $this = $(this);

            $('#countryId').val('');

            $(this).autocomplete({
                serviceUrl: osc.base_ajax_url + 'location_countries',
                minChars: 2,
                noCache: true,
                paramName: 'term',
                deferRequestBy: 500,
                showNoSuggestionNotice: true,
                noSuggestionNotice: $('#msg-autocomplete-nothing-found').val(),
                onSearchStart: function (query) {
                    $this.parents('.autocomplete-search').addClass('show');
                },
                onSearchComplete: function (query, suggestions) {
                    setTimeout(function () {
                        $this.parents('.autocomplete-search').removeClass('show');
                    }, $this.val().length * 1000);
                },
                onSelect: function (result) {
                    $('#countryId').val(result.id);
                    $('#regionId').val('');
                    $('#region').val('');
                    $('#cityId').val('');
                    $('#city').val('');
                }
            });
        });

        $('#region').on('keydown', function () {
            var $this = $(this);

            $('#regionId').val('');

            if ($('#countryId').val() != '' && $('#countryId').val() != undefined) {
                var country = $('#countryId').val();
            } else {
                var country = $('#country').val();
            }

            $(this).autocomplete({
                serviceUrl: osc.base_ajax_url + 'location_regions&country=' + country,
                minChars: 2,
                noCache: true,
                paramName: 'term',
                deferRequestBy: 500,
                showNoSuggestionNotice: true,
                noSuggestionNotice: $('#msg-autocomplete-nothing-found').val(),
                onSearchStart: function (query) {
                    $this.parents('.autocomplete-search').addClass('show');
                },
                onSearchComplete: function (query, suggestions) {
                    setTimeout(function () {
                        $this.parents('.autocomplete-search').removeClass('show');
                    }, $this.val().length * 1000);
                },
                onSelect: function (result) {
                    $('#regionId').val(result.id);
                    $('#cityId').val('');
                    $('#city').val('');
                }
            });
        });

        $('#city').on('keydown', function () {
            var $this = $(this);

            $('#cityId').val('');

            if ($('#regionId').val() != '' && $('#regionId').val() != undefined) {
                var region = $('#regionId').val();
            } else {
                var region = $('#region').val();
            }

            $(this).autocomplete({
                serviceUrl: osc.base_ajax_url + 'location_cities&region=' + region,
                minChars: 2,
                noCache: true,
                paramName: 'term',
                deferRequestBy: 500,
                showNoSuggestionNotice: true,
                noSuggestionNotice: $('#msg-autocomplete-nothing-found').val(),
                onSearchStart: function (query) {
                    $this.parents('.autocomplete-search').addClass('show');
                },
                onSearchComplete: function (query, suggestions) {
                    setTimeout(function () {
                        $this.parents('.autocomplete-search').removeClass('show');
                    }, $this.val().length * 1000);
                },
                onSelect: function (result) {
                    $('#cityId').val(result.id);
                }
            });
        });

        $('body').on('keydown', '#user, #fUser', function () {
            var $this = $(this);

            $(this).autocomplete({
                serviceUrl: osc.adm_base_ajax_url + 'userajax',
                minChars: 0,
                noCache: true,
                paramName: 'term',
                deferRequestBy: 500,
                showNoSuggestionNotice: true,
                noSuggestionNotice: $('#msg-autocomplete-nothing-found').val(),
                transformResult: function (response) {
                    var str = response.replace(/\[|\]/g, '');
                    var objResponse = $.parseJSON(str);
                    var arrResponse = $.makeArray(objResponse);

                    return $.map(arrResponse, function (result) {
                        return {value: result.label, id: result.id, label: result.value, data: null};
                    })
                },
                onSearchStart: function (query) {
                    $this.parents('.autocomplete-search').addClass('show');
                    $('#userId').val('');
                    $('#fUserId').val('');
                },
                onSearchComplete: function (query, suggestions) {
                    setTimeout(function () {
                        //console.log(suggestions);
                        $this.parents('.autocomplete-search').removeClass('show');
                    }, $this.val().length * 1000);
                },
                onSelect: function (result) {
                    $(this).val(result.label);
                    $('#userId').val(result.id);
                    $('#fUserId').val(result.id);
                }
            });
        });

        $('body').on('click', 'a#listing-delete', function (e) {
            var $this = $(this),
                $itemId = $this.attr('data-listing-id'),
                $itemType = $this.attr('data-delete-type');

            e.preventDefault();

            Swal.fire({
                title: osc.translations.msg_confirm_action,
                text: $('#msg-confirm-delete-' + $itemType).val(),
                type: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                confirmButtonText: osc.translations.msg_delete,
                cancelButtonText: osc.translations.msg_cancel,
            }).then((result) => {
                if (result.value) {
                    if ($itemType == 'field') {
                        $.get($this.attr('href'), function () {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: $('#msg-deleted-successfull').val(),
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $('tr[data-field-id="' + $itemId + '"]').slideUp(function () {
                                $(this).remove();

                                if ($('#custom-fields-table tr').length == 1) {
                                    $('#custom-fields-table').prepend('<tr><td colspan="2" class="text-center"><p>' + $('#msg-no-data').val() + '</p></td></tr>');
                                }
                            });
                        });
                    } else if ($itemType == 'category') {
                        $.get($this.attr('href'), function () {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: $('#msg-deleted-successfull').val(),
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $('li#' + $itemId + '').slideUp(function () {
                                $(this).remove();

                                if ($('#sortable-categories li').length == 1) {
                                    $('#sortable-categories').replaceWith('<p>' + $('#msg-no-data').val() + '</p>');
                                }
                            });
                        });
                    } else if ($itemType == 'delete_country' || $itemType == 'delete_region' || $itemType == 'delete_city') {
                        $('#item-delete-form input[name="type"]').val($itemType);
                        $('#item-delete-form input[name="id[]"]').val($itemId);
                        $('#item-delete-form').submit();
                    } else if ($itemType == 'comment') {
                        $('#item-delete-form input[name="id"]').val($itemId);
                        $('#item-delete-form').submit();
                    } else if ($itemType == 'currency') {
                        $('#item-delete-form input[name="code"]').val($itemId);
                        $('#item-delete-form').submit();
                    } else if ($itemType == 'plugin') {
                        $('#item-delete-form input[name="plugin"]').val($itemId);
                        $('#item-delete-form').submit();
                    } else {
                        $('#item-delete-form input[name="id[]"]').val($itemId);
                        $('#item-delete-form').submit();
                    }
                }
            });
        });

        $('button#theme-delete').click(function (e) {
            var $this = $(this),
                $themeId = $this.attr('data-theme-id');

            e.preventDefault();

            Swal.fire({
                title: osc.translations.msg_confirm_action,
                text: $('#msg-confirm-delete-theme').val(),
                type: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                confirmButtonText: osc.translations.msg_delete,
                cancelButtonText: osc.translations.msg_cancel,
            }).then((result) => {
                if (result.value) {
                    $('#theme-delete-form input[name="webtheme"]').val($themeId);
                    $('#theme-delete-form').submit();
                }
            });
        });

        $('a#widget-delete').click(function (e) {
            var $this = $(this),
                $widgetId = $this.attr('data-widget-id');

            e.preventDefault();

            Swal.fire({
                title: osc.translations.msg_confirm_action,
                text: $('#msg-confirm-delete-widget').val(),
                type: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                confirmButtonText: osc.translations.msg_delete,
                cancelButtonText: osc.translations.msg_cancel,
            }).then((result) => {
                if (result.value) {
                    $('#widget-delete-form input[name="id"]').val($widgetId);
                    $('#widget-delete-form').submit();
                }
            });
        });
    }
});