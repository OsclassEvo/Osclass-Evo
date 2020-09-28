<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function customPageTitle($string) {
    return sprintf(__('Locations &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Add, edit or delete the countries, regions and cities installed on your Osclass. <strong>Be careful</strong>: modifying locations can cause your statistics to be incorrect until they're recalculated. Modify only if you're sure what you're doing!") . '</p>';
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {

    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#d_add_country_form').validate({
                rules: {
                    country: {
                        required: true
                    },
                    c_country: {
                        required: true
                    }
                },
                messages: {
                    country: "<?php echo osc_esc_js(__('Field "Country" is required')); ?>.",
                    c_country: "<?php echo osc_esc_js(__('Field "Country code" is required')); ?>."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            $('#d_edit_country_form').validate({
                rules: {
                    e_country: {
                        required: true
                    },
                    e_country_slug: {
                        required: true
                    }
                },
                messages: {
                    e_country: "<?php echo osc_esc_js(__('Field "Country" is required')); ?>.",
                    e_country_slug: "<?php echo osc_esc_js(__('Field "Slug" is required')); ?>."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            $('#d_add_region_form').validate({
                rules: {
                    region: {
                        required: true
                    }
                },
                messages: {
                    region: "<?php echo osc_esc_js(__('Field "Region" is required')); ?>."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            $('#d_edit_region_form').validate({
                rules: {
                    e_region: {
                        required: true
                    },
                    e_region_slug: {
                        required: true
                    }
                },
                messages: {
                    e_region: "<?php echo osc_esc_js(__('Field "Region" is required')); ?>.",
                    e_region_slug: "<?php echo osc_esc_js(__('Field "Slug" is required')); ?>."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            $('#d_add_city_form').validate({
                rules: {
                    city: {
                        required: true
                    }
                },
                messages: {
                    city: "<?php echo osc_esc_js(__('Field "City" is required')); ?>."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            $('#d_edit_city_form').validate({
                rules: {
                    e_city: {
                        required: true
                    },
                    e_city_slug: {
                        required: true
                    }
                },
                messages: {
                    e_city: "<?php echo osc_esc_js(__('Field "City" is required')); ?>.",
                    e_city_slug: "<?php echo osc_esc_js(__('Field "Slug" is required')); ?>."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                }
            });

            $('body').on('change', 'input.bulk-action-country', function() {
                if($('input.bulk-action-country:checked').length > 0) {
                    $('#b_remove_country').show();
                } else {
                    $('#b_remove_country').hide();
                }
            });

            $('body').on('change', 'input.bulk-action-region', function() {
                if($('input.bulk-action-region:checked').length > 0) {
                    $('#b_remove_region').show();
                } else {
                    $('#b_remove_region').hide();
                }
            });

            $('body').on('change', 'input.bulk-action-city', function() {
                if($('input.bulk-action-city:checked').length > 0) {
                    $('#b_remove_city').show();
                } else {
                    $('#b_remove_city').hide();
                }
            });

            $("#b_remove_country").on('click', function() {
                Swal.fire({
                    title: '<?php _e('Confirm action'); ?>',
                    text: '<?php _e('Are you sure you want to delete the selected countries?'); ?>',
                    type: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    confirmButtonText: '<?php _e('Delete'); ?>',
                    cancelButtonText: '<?php _e('Cancel'); ?>',
                }).then((result) => {
                    if (result.value) {
                        $('#item-delete-form input[name="id[]"]').remove();

                        $('input.bulk-action-country:checked').each(function() {
                            $('#item-delete-form').append('<input type="hidden" name="id[]" value="' + $(this).val() + '" />');
                        });

                        $("#item-delete-form input[name='type']").val('delete_country');

                        $('#item-delete-form').submit();
                    }
                });
            });

            $("#b_remove_region").on('click', function() {
                Swal.fire({
                    title: '<?php _e('Confirm action'); ?>',
                    text: '<?php _e('Are you sure you want to delete the selected regions?'); ?>',
                    type: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    confirmButtonText: '<?php _e('Delete'); ?>',
                    cancelButtonText: '<?php _e('Cancel'); ?>',
                }).then((result) => {
                    if (result.value) {
                        $('#item-delete-form input[name="id[]"]').remove();

                        $('input.bulk-action-region:checked').each(function() {
                            $('#item-delete-form').append('<input type="hidden" name="id[]" value="' + $(this).val() + '" />');
                        });

                        $("#item-delete-form input[name='type']").val('delete_region');

                        $('#item-delete-form').submit();
                    }
                });
            });

            $("#b_remove_city").on('click', function() {
                Swal.fire({
                    title: '<?php _e('Confirm action'); ?>',
                    text: '<?php _e('Are you sure you want to delete the selected cities?'); ?>',
                    type: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    confirmButtonText: '<?php _e('Delete'); ?>',
                    cancelButtonText: '<?php _e('Cancel'); ?>',
                }).then((result) => {
                    if (result.value) {
                        $('#item-delete-form input[name="id[]"]').remove();

                        $('input.bulk-action-city:checked').each(function() {
                            $('#item-delete-form').append('<input type="hidden" name="id[]" value="' + $(this).val() + '" />');
                        });

                        $("#item-delete-form input[name='type']").val('delete_city');

                        $('#item-delete-form').submit();
                    }
                });
            });

            $('body').on('click', 'a.edit_country', function() {
                var element = $(this);

                $("input[name='country_code']").val(element.attr('data-code'));
                $("input[name='e_country']").val(element.attr('data-name'));
                $("input[name='e_country_slug']").val(element.attr('data-slug'));
            });

            $('body').on('click', 'button#country-add', function() {
                $('#d_add_country_form').submit();
            });

            $('body').on('click', 'button#country-edit', function() {
                $('#d_edit_country_form').submit();
            });

            $('body').on('click', 'a.edit_region', function() {
                var element = $(this);

                $("input[name='region_id']").val(element.attr('data-id'));
                $("input[name='e_region']").val(element.attr('data-name'));
                $("input[name='e_region_slug']").val(element.attr('data-slug'));
            });

            $('body').on('click', 'button#region-add', function() {
                $('#d_add_region_form').submit();
            });

            $('body').on('click', 'button#region-edit', function() {
                $('#d_edit_region_form').submit();
            });

            $('body').on('click', 'a.edit_city', function() {
                var element = $(this);

                $("input[name='city_id']").val(element.attr('data-id'));
                $("input[name='e_city']").val(element.attr('data-name'));
                $("input[name='e_city_slug']").val(element.attr('data-slug'));
            });

            $('body').on('click', 'button#city-add', function() {
                $('#d_add_city_form').submit();
            });

            $('body').on('click', 'button#city-edit', function() {
                $('#d_edit_city_form').submit();
            });
        });

        function show_region(code, name, is_clicked = false) {
            if(is_clicked) {
                $('a.btn-view-country[data-country="' + code + '"]').removeClass('text-dark').addClass('text-info');
                $('a.btn-view-country[data-country!="' + code + '"]').removeClass('text-info').addClass('text-dark');
            }

            $.ajax({
                "url": "<?php echo osc_admin_base_url(); ?>index.php?page=ajax&action=regions&countryId=" + code,
                "dataType": 'json',
                success: function( json ) {
                    var html, view_class;

                    var selected_region = '<?php echo Params::getParam('region'); ?>';

                    $('#i_regions tr').remove();
                    $('#i_cities tr').remove();

                    $.each(json, function(i, val){
                        if(selected_region && selected_region == val.pk_i_id) {
                            view_class = 'info';
                        } else {
                            view_class = 'dark'
                        }

                        html += '<tr>';
                        html += '<td class="col-bulkactions location w-5 d-none d-xl-table-cell">';
                        html += '<div class="form-check"><label class="form-check-label">';
                        html += '<input class="form-check-input bulk-action-region" type="checkbox" name="region[]" value="' + val.pk_i_id + '" />';
                        html += '<span class="form-check-sign"><span class="check"></span></span></label></div>';
                        html += '</td>';
                        html += '<td>';
                        html +=  val.s_name;
                        html += '</td>';
                        html += '<td class="col-actions w-25 text-right">';
                        html += '<a href="javascript:void(0);" rel="tooltip" class="btn-icon text-warning edit_region" title="<?php  _e('Edit'); ?>" data-id="' + val.pk_i_id + '" data-name="' + val.s_name + '" data-slug="' + val.s_slug + '" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editRegionModal"><i class="material-icons">edit</i></a>';
                        html += '<a id="listing-delete" data-delete-type="delete_region" data-listing-id="' + val.pk_i_id + '" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=locations&type=delete_region&id[]=' + val.pk_i_id + '" rel="tooltip" class="btn-icon text-danger" title="<?php  _e('Delete'); ?>"><i class="material-icons">delete</i></a>';
                        html += '<a href="javascript:void(0);" rel="tooltip" class="btn-icon text-' + view_class + ' btn-view-region" data-region="' + val.pk_i_id + '" onclick="show_city(' + val.pk_i_id + ', true);"  title="<?php  _e('View more'); ?>"><i class="material-icons">arrow_right_alt</i></a>';
                        html += '</td>';
                        html += '</tr>';
                    });

                    $('#i_regions').append(html);
                }
            });

            $('input[name="country_c_parent"]').val(code);
            $('input[name="country_parent"]').val(name);

            $('#b_new_region').show();
            $('#b_new_city').hide();
        }

        function show_city(region_id, is_clicked = false) {
            if(is_clicked) {
                $('.btn-view-region[data-region="' + region_id + '"]').removeClass('text-dark').addClass('text-info');
                $('.btn-view-region[data-region!="' + region_id + '"]').removeClass('text-info').addClass('text-dark');
            }

            $.ajax({
                "url": "<?php echo osc_admin_base_url(); ?>index.php?page=ajax&action=cities&regionId=" + region_id,
                "dataType": 'json',
                success: function( json ) {
                    var html;

                    $('#i_cities tr').remove();

                    $.each(json, function(i, val){
                        html += '<tr>';
                        html += '<td class="col-bulkactions location w-5 d-none d-xl-table-cell">';
                        html += '<div class="form-check"><label class="form-check-label">';
                        html += '<input class="form-check-input bulk-action-city" type="checkbox" name="city[]" value="' + val.pk_i_id + '" />';
                        html += '<span class="form-check-sign"><span class="check"></span></span></label></div>';
                        html += '</td>';
                        html += '<td>';
                        html +=  val.s_name;
                        html += '</td>';
                        html += '<td class="col-actions w-25 text-right">';
                        html += '<a href="javascript:void(0);" rel="tooltip" class="btn-icon text-warning edit_city" title="<?php  _e('Edit'); ?>" data-id="' + val.pk_i_id + '" data-name="' + val.s_name + '" data-slug="' + val.s_slug + '" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editCityModal"><i class="material-icons">edit</i></a>';
                        html += '<a id="listing-delete" data-delete-type="delete_city" data-listing-id="' + val.pk_i_id + '" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=locations&type=delete_city&id[]=' + val.pk_i_id + '" rel="tooltip" class="btn-icon text-danger" title="<?php  _e('Delete'); ?>"><i class="material-icons">delete</i></a>';
                        html += '</td>';
                        html += '</tr>';
                    });

                    $('#i_cities').append(html);
                }
            });

            $('input[name="region_parent"]').val(region_id);

            $('#b_new_city').show();
        }
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';

$aCountries = __get('aCountries');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Add new Country'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="d_add_country_form" class="has-form-actions">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="add_country" />
                    <input type="hidden" name="c_manual" value="1" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="country"><?php _e('Country'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="country" class="form-control" type="text" name="country" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="c_country"><?php _e('Country code'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="c_country" class="form-control" type="text" name="c_country" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="country-add" type="button" class="btn btn-info btn-link" ><?php echo osc_esc_html( __('Add country') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCountryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Edit country'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="d_edit_country_form" class="has-form-actions">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="edit_country" />
                    <input type="hidden" name="country_code" value="" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="e_country"><?php _e('Country'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="e_country" class="form-control" type="text" name="e_country" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="e_country_slug"><?php _e('Slug'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="e_country_slug" class="form-control" type="text" name="e_country_slug" value="" />
                                        <span class="form-text text-muted"><?php _e('The slug has to be a unique string, could be left blank'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="country-edit" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Edit country') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addRegionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Add new Region'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="d_add_region_form" class="has-form-actions">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="add_region" />
                    <input type="hidden" name="country_c_parent" value="" />
                    <input type="hidden" name="country_parent" value="" />
                    <input type="hidden" name="r_manual" value="1" />
                    <input type="hidden" name="region_id" id="region_id" value="" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="region"><?php _e('Region'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="region" class="form-control" type="text" name="region" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="region-add" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Add region') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRegionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Edit region'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="d_edit_region_form" class="has-form-actions">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="edit_region" />
                    <input type="hidden" name="region_id" value="" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="e_region"><?php _e('Region'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="e_region" class="form-control" type="text" name="e_region" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="e_region_slug"><?php _e('Slug'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="e_region_slug" class="form-control" type="text" name="e_region_slug" value="" />
                                        <span class="form-text text-muted"><?php _e('The slug has to be a unique string, could be left blank'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="region-edit" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Edit region') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCityModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Add new City'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="d_add_city_form" class="has-form-actions">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="add_city" />
                    <input type="hidden" name="country_c_parent" value="" />
                    <input type="hidden" name="country_parent" value="" />
                    <input type="hidden" name="region_parent" value="" />
                    <input type="hidden" name="ci_manual" value="1" />
                    <input type="hidden" name="city_id" id="city_id" value="" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="city"><?php _e('City'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="city" class="form-control" type="text" name="city" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="city-add" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Add city') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCityModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Edit city'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="d_edit_city_form" class="has-form-actions">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="locations" />
                    <input type="hidden" name="type" value="edit_city" />
                    <input type="hidden" name="city_id" value="" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="e_city"><?php _e('City'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="e_city" class="form-control" type="text" name="e_city" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-12 col-xl-3 col-form-label form-label text-left text-xl-right" for="e_city_slug"><?php _e('Slug'); ?></label>

                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <input id="e_city_slug" class="form-control" type="text" name="e_city_slug" value="" />
                                        <span class="form-text text-muted"><?php _e('The slug has to be a unique string, could be left blank'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="city-edit" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Edit city') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="row">
    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="d-inline-block"><?php _e('Countries'); ?></h4>
                <a id="b_new_country" class="btn btn-sm btn-success float-right" href="javascript:void(0);" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#addCountryModal"><?php _e('Add new Country'); ?></a>
                <a id="b_remove_country" class="btn btn-sm btn-danger float-right fc-limited" href="javascript:void(0);"><?php _e('Remove selected'); ?></a>
            </div>

            <div class="card-body">
                <?php if($aCountries): ?>
                    <table class="table table-striped table-shopping">
                        <tbody>
                        <?php foreach($aCountries as $country): ?>
                            <?php
                            if(Params::getParam('country_code') && $country['pk_c_code'] == Params::getParam('country_code')) {
                                $view_class = 'info';
                            } else {
                                $view_class = 'dark';
                            }
                            ?>
                            <tr>
                                <td class="col-bulkactions location w-5 d-none d-xl-table-cell">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input bulk-action-country" type="checkbox" name="country[]" value="<?php echo $country['pk_c_code']; ?>" />
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <?php echo $country['s_name']; ?>
                                </td>
                                <td class="col-actions w-25 text-right">
                                    <a href="javascript:void(0);" rel="tooltip" class="btn-icon text-warning edit_country" title="<?php  _e('Edit'); ?>" data-name="<?php echo osc_esc_html($country['s_name']);?>" data-code="<?php echo $country['pk_c_code'];?>" data-slug="<?php echo $country['s_slug'];?>" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editCountryModal"><i class="material-icons">edit</i></a>
                                    <a id="listing-delete" data-delete-type="delete_country" data-listing-id="<?php echo $country['pk_c_code']; ?>" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=locations&type=delete_country&id[]=<?php echo $country['pk_c_code']; ?>" rel="tooltip" class="btn-icon text-danger" title="<?php  _e('Delete'); ?>"><i class="material-icons">delete</i></a>
                                    <a href="javascript:void(0);" rel="tooltip" class="btn-icon text-<?php echo $view_class; ?> btn-view-country" data-country="<?php echo $country['pk_c_code'];?>" onclick="show_region('<?php echo osc_esc_js($country['pk_c_code']); ?>', '<?php echo osc_esc_js($country['s_name']); ?>', true);" title="<?php  _e('View more'); ?>"><i class="material-icons">arrow_right_alt</i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="d-inline-block"><?php _e('Regions'); ?></h4>
                <a id="b_new_region" class="btn btn-sm btn-success float-right fc-limited" href="javascript:void(0);" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#addRegionModal"><?php _e('Add new Region'); ?></a>
                <a id="b_remove_region" class="btn btn-sm btn-danger float-right fc-limited" href="javascript:void(0);"><?php _e('Remove selected'); ?></a>
            </div>

            <div class="card-body">
                <table id="i_regions" class="table table-striped table-shopping"></table>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="d-inline-block"><?php _e('Cities'); ?></h4>
                <a id="b_new_city" class="btn btn-sm btn-success float-right fc-limited" href="javascript:void(0);" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#addCityModal"><?php _e('Add new City'); ?></a>
                <a id="b_remove_city" class="btn btn-sm btn-danger float-right fc-limited" href="javascript:void(0);"><?php _e('Remove selected'); ?></a>
            </div>

            <div class="card-body">
                <table id="i_cities" class="table table-striped table-shopping"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php if(Params::getParam('country') && Params::getParam('country_code')): ?>
        show_region('<?php echo osc_esc_js(Params::getParam('country_code')); ?>', '<?php echo osc_esc_js(Params::getParam('country')); ?>');
    <?php endif; ?>

    <?php if(Params::getParam('region')): ?>
        show_city(<?php echo osc_esc_js(Params::getParam('region')); ?>);;
    <?php endif; ?>
</script>

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="settings" />
    <input type="hidden" name="action" value="locations" />
    <input type="hidden" name="type" value="" />
    <input type="hidden" name="id[]" value="" />
</form>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>