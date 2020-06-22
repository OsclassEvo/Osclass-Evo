(function () {
    isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

    if (isWindows) {
        if(screen.width > 1200) {
            $('.main-panel').perfectScrollbar();
        }

        $('.sidebar .sidebar-wrapper').perfectScrollbar();
        $('.hummingbird-treeview').perfectScrollbar();

        $('html').addClass('perfect-scrollbar-on');
    } else {
        $('html').addClass('perfect-scrollbar-off');
    }
})();


var breakCards = true;

var searchVisible = 0;
var transparent = true;

var transparentDemo = true;
var fixedTop = false;

var mobile_menu_visible = 0,
    mobile_menu_initialized = false,
    toggle_initialized = false,
    bootstrap_nav_initialized = false;

var seq = 0,
    delays = 80,
    durations = 500;
var seq2 = 0,
    delays2 = 80,
    durations2 = 500;

$(document).ready(function () {
    setTimeout(function () {
        $('.main-panel').removeClass('loading');
        $('.wait').hide();
    }, 2000);

    $sidebar = $('.sidebar');
    window_width = $(window).width();

    $('body').bootstrapMaterialDesign({
        autofill: false
    });

    md.initSidebarsCheck();

    window_width = $(window).width();

    // check if there is an image set for the sidebar's background
    md.checkSidebarImage();

    md.initMinimizeSidebar();

    if($('.themes-list').length) {
        $('.themes-list').equalize({children: 'img'});
    }

    // Multilevel Dropdown menu

    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        var $el = $(this);
        var $parent = $(this).offsetParent(".dropdown-menu");
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');

        $(this).closest("a").toggleClass('open');

        $(this).parents('a.dropdown-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-menu .show').removeClass("show");
        });

        if (!$parent.parent().hasClass('navbar-nav')) {
            $el.next().css({
                "top": $el[0].offsetTop,
                "left": $parent.outerWidth() - 4
            });
        }

        return false;
    });

    $('a#help').click(function () {
        var helpText = $('#help-box p').text();

        Swal.fire({
            text: helpText,
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: "btn btn-info",
            showCancelButton: false
        });
    });

    $('#bulk-actions-btn').click(function () {
        var $bulk_type = $(this).attr('data-bulk-type'),
            $bulk_action = $('#bulk-actions option:selected'),
            $bulk = $bulk_action.val(),
            $bulk_alert = $bulk_action.attr('data-dialog-content'),
            $bulk_alert_title = $('#msg-confirm-action').val(),
            $bulk_alert_cancel = $('#msg-cancel-btn').val(),
            $bulk_alert_action = $bulk_action.text();

        $('#bulk_actions').val($bulk);

        if ($bulk) {
            if ($('#item-selected:checked').length) {
                Swal.fire({
                    title: $bulk_alert_title,
                    text: $bulk_alert,
                    type: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    confirmButtonText: $bulk_alert_action,
                    cancelButtonText: $bulk_alert_cancel,
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
            serviceUrl: $('#url-base-ajax').val() + 'location_countries',
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
            serviceUrl: $('#url-base-ajax').val() + 'location_regions&country=' + country,
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
            serviceUrl: $('#url-base-ajax').val() + 'location_cities&region=' + region,
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
            serviceUrl: $('#url-admin-base-ajax').val() + 'userajax',
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
            title: $('#msg-confirm-action').val(),
            text: $('#msg-confirm-delete-' + $itemType).val(),
            type: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            confirmButtonText: $('#msg-delete-btn').val(),
            cancelButtonText: $('#msg-cancel-btn').val(),
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
            title: $('#msg-confirm-action').val(),
            text: $('#msg-confirm-delete-theme').val(),
            type: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            confirmButtonText: $('#msg-delete-btn').val(),
            cancelButtonText: $('#msg-cancel-btn').val(),
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
            title: $('#msg-confirm-action').val(),
            text: $('#msg-confirm-delete-widget').val(),
            type: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            confirmButtonText: $('#msg-delete-btn').val(),
            cancelButtonText: $('#msg-cancel-btn').val(),
        }).then((result) => {
            if (result.value) {
                $('#widget-delete-form input[name="id"]').val($widgetId);
                $('#widget-delete-form').submit();
            }
        });
    });

    //   Activate bootstrap-select
    if ($(".selectpicker").length != 0) {
        $(".selectpicker").selectpicker();
    }

    //  Activate the tooltips
    $('table [rel="tooltip"]').tooltip({trigger: 'hover', container: '.main-panel'});
    $('.themes-list [rel="tooltip"]').tooltip({trigger: 'hover', container: 'body'});

    // Activate Popovers
    $('[data-toggle="popover"]').popover();

    //Activate tags
    // we style the badges with our colors
    var tagClass = $('.tagsinput').data('color');

    if ($(".tagsinput").length != 0) {
        $('.tagsinput').tagsinput();
    }

    $('.bootstrap-tagsinput').addClass('' + tagClass + '-badge');

    //    Activate bootstrap-select
    $(".select").dropdown({
        "dropdownClass": "dropdown-menu",
        "optionClass": ""
    });

    $('.form-control').on("focus", function () {
        $(this).parent('.input-group').addClass("input-group-focus");
    }).on("blur", function () {
        $(this).parent(".input-group").removeClass("input-group-focus");
    });


    if (breakCards == true) {
        // We break the cards headers if there is too much stress on them :-)
        $('[data-header-animation="true"]').each(function () {
            var $fix_button = $(this)
            var $card = $(this).parent('.card');

            $card.find('.fix-broken-card').click(function () {
                console.log(this);
                var $header = $(this).parent().parent().siblings('.card-header, .card-header-image');

                $header.removeClass('hinge').addClass('fadeInDown');

                $card.attr('data-count', 0);

                setTimeout(function () {
                    $header.removeClass('fadeInDown animate');
                }, 480);
            });

            $card.mouseenter(function () {
                var $this = $(this);
                hover_count = parseInt($this.attr('data-count'), 10) + 1 || 0;
                $this.attr("data-count", hover_count);

                if (hover_count >= 20) {
                    $(this).children('.card-header, .card-header-image').addClass('hinge animated');
                }
            });
        });
    }

    // remove class has-error for checkbox validation
    $('input[type="checkbox"][required="true"], input[type="radio"][required="true"]').on('click', function () {
        if ($(this).hasClass('error')) {
            $(this).closest('div').removeClass('has-error');
        }
    });

});

$(document).on('click', '.navbar-toggler', function () {
    $toggle = $(this);

    if (mobile_menu_visible == 1) {
        $('html').removeClass('nav-open');

        $('.close-layer').remove();
        setTimeout(function () {
            $toggle.removeClass('toggled');
        }, 400);

        mobile_menu_visible = 0;
    } else {
        setTimeout(function () {
            $toggle.addClass('toggled');
        }, 430);

        var $layer = $('<div class="close-layer"></div>');

        if ($('body').find('.main-panel').length != 0) {
            $layer.appendTo(".main-panel");

        } else if (($('body').hasClass('off-canvas-sidebar'))) {
            $layer.appendTo(".wrapper-full-page");
        }

        setTimeout(function () {
            $layer.addClass('visible');
        }, 100);

        $layer.click(function () {
            $('html').removeClass('nav-open');
            mobile_menu_visible = 0;

            $layer.removeClass('visible');

            setTimeout(function () {
                $layer.remove();
                $toggle.removeClass('toggled');

            }, 400);
        });

        $('html').addClass('nav-open');
        mobile_menu_visible = 1;
    }
});

// activate collapse right menu when the windows is resized
$(window).resize(function () {
    md.initSidebarsCheck();
});

md = {
    misc: {
        navbar_menu_visible: 0,
        active_collapse: true,
        disabled_collapse_init: 0,
    },

    checkSidebarImage: function () {
        $sidebar = $('.sidebar');
        image_src = $sidebar.data('image');

        if (image_src !== undefined) {
            sidebar_container = '<div class="sidebar-background" style="background-image: url(' + image_src + ') "/>';
            $sidebar.append(sidebar_container);
        }
    },
    initFormExtendedDatetimepickers: function () {
        $('.datetimepicker').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });

        $('.datepicker').datetimepicker({
            format: 'MM/DD/YYYY',
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });

        $('.timepicker').datetimepicker({
            //          format: 'H:mm',    // use this format if you want the 24hours timepicker
            format: 'h:mm A', //use this format if you want the 12hours timpiecker with AM/PM toggle
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'

            }
        });
    },

    initSidebarsCheck: function () {
        if ($(window).width() <= 991) {
            if ($sidebar.length != 0) {
                md.initRightMenu();
            }
        }
    },

    checkFullPageBackgroundImage: function () {
        $page = $('.full-page');
        image_src = $page.data('image');

        if (image_src !== undefined) {
            image_container = '<div class="full-page-background" style="background-image: url(' + image_src + ') "/>'
            $page.append(image_container);
        }
    },

    initMinimizeSidebar: function () {

        $('#minimizeSidebar').click(function () {
            var $btn = $(this);

            $.getJSON($(this).attr('data-url'));

            if ($('body').hasClass('sidebar-mini')) {
                $('body').removeClass('sidebar-mini');
            } else {
                $('body').addClass('sidebar-mini');
            }

            // we simulate the window Resize so the charts will get updated in realtime.
            var simulateWindowResize = setInterval(function () {
                window.dispatchEvent(new Event('resize'));
            }, 180);

            // we stop the simulation of Window Resize after the animations are completed
            setTimeout(function () {
                clearInterval(simulateWindowResize);
            }, 1000);
        });
    },

    checkScrollForTransparentNavbar: debounce(function () {
        if ($(document).scrollTop() > 260) {
            if (transparent) {
                transparent = false;
                $('.navbar-color-on-scroll').removeClass('navbar-transparent');
            }
        } else {
            if (!transparent) {
                transparent = true;
                $('.navbar-color-on-scroll').addClass('navbar-transparent');
            }
        }
    }, 17),


    initRightMenu: debounce(function () {
        $sidebar_wrapper = $('.sidebar-wrapper');

        if (!mobile_menu_initialized) {
            $navbar = $('nav').find('.navbar-collapse').children('.navbar-nav');

            mobile_menu_content = '';

            nav_content = $navbar.html();

            nav_content = '<ul class="nav navbar-nav nav-mobile-menu">' + nav_content + '</ul>';

            // navbar_form = $('nav').find('.navbar-form').get(0).outerHTML;

            $sidebar_nav = $sidebar_wrapper.find(' > .nav');

            // insert the navbar form before the sidebar list
            $nav_content = $(nav_content);
            // $navbar_form = $(navbar_form);
            $nav_content.insertBefore($sidebar_nav);
            // $navbar_form.insertBefore($nav_content);

            $(".sidebar-wrapper .dropdown .dropdown-menu > li > a").click(function (event) {
                event.stopPropagation();

            });

            // simulate resize so all the charts/maps will be redrawn
            window.dispatchEvent(new Event('resize'));

            mobile_menu_initialized = true;
        } else {
            if ($(window).width() > 991) {
                // reset all the additions that we made for the sidebar wrapper only if the screen is bigger than 991px
                $sidebar_wrapper.find('.navbar-form').remove();
                $sidebar_wrapper.find('.nav-mobile-menu').remove();

                mobile_menu_initialized = false;
            }
        }
    }, 200)
}

function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this,
            args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        }, wait);
        if (immediate && !timeout) func.apply(context, args);
    };
};

function getFileName(inputId) {
    var file = document.getElementById(inputId).value;

    file = file.replace(/\\/g, '/').split('/').pop();
    $('span[input-file-id="' + inputId + '"]').text(file);
}