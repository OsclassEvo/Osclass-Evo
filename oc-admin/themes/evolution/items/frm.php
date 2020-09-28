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

osc_enqueue_script('tiny_mce5');


function customFrmText() {
    $new_item = __get('new_item');
    $return = array();

    if($new_item) {
        $return['edit']       = false;
        $return['title']      = __('Add listing');
        $return['icon']       = __('add');
        $return['action_frm'] = 'post_item';
        $return['btn_text']   = __('Add listing');
    } else {
        $return['edit']       = true;
        $return['title']      = __('Edit listing');
        $return['icon']       = __('create');
        $return['action_frm'] = 'item_edit_post';
        $return['btn_text']   = __('Update listing');
    }
    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Listing');
}

function customHead() {
    $aux = customFrmText();
    ?>
    <script type="text/javascript">
        $(document).ready(function(){

            <?php if(osc_editor_enabled_at_items()): ?>
                tinyMCE.init({
                    mode : "textareas",
                    skin: 'custom',
                    mobile: {
                        // theme: 'mobile',
                        menubar: 'edit view insert format table'
                    },
                    menu: {
                        edit: {title: 'Edit', items: 'undo redo | selectall'}
                    },
                    menubar: 'edit view insert format table',
                    width: "100%",
                    height: "440px",
                    language: 'en',
                    branding: false,
                    plugins : 'advlist autolink lists link image imagetools media charmap preview anchor searchreplace visualblocks code codesample fullscreen insertdatetime media table contextmenu',
                    toolbar: 'undo redo | styleselect bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | codesample code',
                    entity_encoding : "raw",
                    relative_urls: false,
                    remove_script_host: false,
                    convert_urls: false,
                    media_live_embeds: true,
                    image_advtab: true,
                    paste_data_images: true,
                    link_assume_external_targets: true,
                    link_quicklink: true,
                    file_picker_types: 'image media',
                    file_picker_callback: function(callback, value, meta) {
                        if (meta.filetype == 'image') {
                            $('#upload').trigger('click');

                            $('#upload').on('change', function() {
                                var file = this.files[0];
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    callback(e.target.result, {
                                        alt: ''
                                    });
                                };

                                reader.readAsDataURL(file);
                            });
                        }
                    }
                });
            <?php endif; ?>

            // Code for form validation
            $("form[name='item']").validate({
                rules: {
                    catId: {
                        required: true,
                        digits: true
                    },
                    <?php if(osc_price_enabled_at_items()) { ?>
                    price: {
                        maxlength: 50
                    },
                    currency: "required",
                    <?php } ?>
                    <?php if(osc_images_enabled_at_items()) { ?>
                    "photos[]": {
                        accept: "<?php echo osc_esc_js(osc_allowed_extension()); ?>"
                    },
                    <?php } ?>
                    contactName: {
                        required: true,
                        minlength: 3,
                        maxlength: 35
                    },
                    contactEmail: {
                        required: true,
                        email: true
                    },
                    address: {
                        minlength: 3,
                        maxlength: 100
                    }
                    <?php osc_run_hook('item_form_new_validation_rules'); ?>
                },
                messages: {
                    catId: "<?php echo osc_esc_js(__('Choose one category')); ?>.",
                    <?php if(osc_price_enabled_at_items()) { ?>
                    price: {
                        maxlength: "<?php echo osc_esc_js(__("Price: no more than 50 characters")); ?>."
                    },
                    currency: "<?php echo osc_esc_js(__("Currency: make your selection")); ?>.",
                    <?php } ?>
                    <?php if(osc_images_enabled_at_items()) { ?>
                    "photos[]": {
                        accept: "<?php echo osc_esc_js(sprintf(__("Photo: must be %s"), osc_allowed_extension())); ?>."
                    },
                    <?php } ?>
                    contactName: {
                        required: "<?php echo osc_esc_js(__("Name: this field is required")); ?>.",
                        minlength: "<?php echo osc_esc_js(__("Name: enter at least 3 characters")); ?>.",
                        maxlength: "<?php echo osc_esc_js(__("Name: no more than 35 characters")); ?>."
                    },
                    contactEmail: {
                        required: "<?php echo osc_esc_js(__("Email: this field is required")); ?>.",
                        email: "<?php echo osc_esc_js(__("Invalid email address")); ?>."
                    },
                    address: {
                        minlength: "<?php echo osc_esc_js(__("Address: enter at least 3 characters")); ?>.",
                        maxlength: "<?php echo osc_esc_js(__("Address: no more than 100 characters")); ?>."
                    }
                    <?php osc_run_hook('item_form_new_validation_messages'); ?>
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

            $('#language-tab li a').click(function(){
                var currentLocale = $(this).attr('href').replace('#','');

                $('div[id!="' + currentLocale + '#s_title"][class*="multilang"], div[id!="' + currentLocale + '#s_text"][class*="multilang"]').addClass('fc-limited');
                $('[name*="' + currentLocale + '"]').parents('.row').removeClass('fc-limited');
            });

            $('#countryId').change(function() {
                var pk_c_code = $(this).val();
                var url = '<?php echo osc_admin_base_url(true) . "?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
                var result = '';

                if(pk_c_code != '') {
                    $("#regionId").attr('disabled', false);
                    $("#cityId").attr('disabled', true);

                    $('.selectpicker').selectpicker('refresh');

                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        success: function(data){
                            var length = data.length;

                            if(length > 0) {
                                result += '<option value=""><?php _e("Select a region..."); ?></option>';

                                for(key in data) {
                                    result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                                }

                                $("#regionId option").remove();
                                $("#regionId").html(result);

                                $("#cityId option[value != '']").remove();
                            } else {
                                result += '<option value=""><?php _e('No results') ?></option>';

                                $("#regionId option").remove();
                                $("#regionId").html(result);

                                $("#cityId option[value != '']").remove();
                            }

                            $('.selectpicker').selectpicker('refresh');
                        }
                    });
                } else {
                    $("#regionId option[value != '']").remove();
                    $("#cityId option[value != '']").remove();

                    $("#regionId").attr('disabled', true);
                    $("#cityId").attr('disabled', true);

                    $('.selectpicker').selectpicker('refresh');
                }
            });

            $('#regionId').change(function() {
                var pk_c_code = $(this).val();
                var url = '<?php echo osc_admin_base_url(true) . "?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
                var result = '';

                if(pk_c_code != '') {
                    $("#cityId").attr('disabled', false);

                    $('.selectpicker').selectpicker('refresh');

                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'json',
                        success: function(data) {
                            var length = data.length;

                            if(length > 0) {
                                result += '<option selected value=""><?php _e("Select a city..."); ?></option>';
                                for(key in data) {
                                    result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                                }

                                $("#cityId option").remove();
                                $("#cityId").html(result);
                            } else {
                                result += '<option value=""><?php _e('No results') ?></option>';

                                $("#cityId option").remove();
                                $("#cityId").html(result);
                            }

                            $("#cityId").html(result);

                            $('.selectpicker').selectpicker('refresh');
                        }
                    });
                } else {
                    $("#cityId option[value != '']").remove();
                    $("#cityId").attr('disabled', true);

                    $('.selectpicker').selectpicker('refresh');
                }
            });

            if( $("#regionId").val() == "") {
                $("#cityId option[value != '']").remove();
                $("#cityId").attr('disabled', true);

                $('.selectpicker').selectpicker('refresh');
            }

            if($("#countryId").length) {
                if( $("#countryId").prop('type').match(/select/)) {
                    if( $("#countryId").val() == "") {
                        $("#regionId option[value != '']").remove();
                        $("#regionId").attr('disabled', true);

                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            }

            <?php
                $extensions = explode(',', osc_esc_js(osc_allowed_extension()));
                $allowed_extensions = '';

                foreach($extensions as $ext) {
                    $allowed_extensions .= '.' . $ext . ',';
                }

                $allowed_extensions = rtrim($allowed_extensions, ',');
            ?>

            $("#upload-photo").dropzone({
                url: '<?php echo osc_admin_base_url(true); ?>',
                uploadMultiple: true,
                maxFilesize: <?php echo round(osc_get_preference('maxSizeKb') / 1024, 2, PHP_ROUND_HALF_UP); ?>,
                filesizeBase: 1024,
                maxFiles: <?php echo osc_max_images_per_item() == 0 ? 10000 : osc_max_images_per_item(); ?>,
                parallelUploads: <?php echo osc_max_images_per_item() == 0 ? 10000 : osc_max_images_per_item(); ?>,
                acceptedFiles: '<?php echo $allowed_extensions; ?>',
                autoProcessQueue: false,
                addRemoveLinks: true,
                previewsContainer: '#attached-files',
                paramName: 'photos',
                hiddenInputContainer: '#item',
                dictRemoveFile: '<?php  echo osc_esc_js(__('Remove')); ?>',
                dictMaxFilesExceeded: '<?php  echo osc_esc_js(__('You can not upload any more images')); ?>',
                dictFileTooBig: '<?php  echo osc_esc_js(__('File is too big ({{filesize}}Mb). Max filesize: {{maxFilesize}}Mb')); ?>',
                dictInvalidFileType: '<?php  echo osc_esc_js(__('You can not upload files of this type')); ?>',
                init: function() {
                    var myDropzone = this;

                    myDropzone.on('addedfile', function(file) {
                        var imgCount = $('#attached-files .dz-preview').length;

                        if (imgCount > <?php echo osc_max_images_per_item() == 0 ? 10000 : osc_max_images_per_item(); ?>) {
                            Swal.fire({
                                type: 'error',
                                title: '<?php _e('Limit is exceeded'); ?>',
                                text: '<?php printf(__('Allowed to add no more than %s images'),osc_max_images_per_item() == 0 ? 10000 : osc_max_images_per_item()); ?>'
                            });

                            myDropzone.removeFile(file);
                        }
                    });

                    myDropzone.on('forcedremove', function(file) {
                        myDropzone.addFile(file);
                    });

                    $('#item-post').click(function(e) {
                        if (myDropzone.files != "") {
                            myDropzone.processQueue();
                        } else {
                            $('#item').submit();
                        }
                    });

                    myDropzone.on('sendingmultiple', function(data, xhr, formData) {
                        formData.append('page', 'items');
                        formData.append('action', '<?php echo $aux['action_frm']; ?>');
                        formData.append('CSRFName', $('input[name="CSRFName"]').val());
                        formData.append('CSRFToken', $('input[name="CSRFToken"]').val());

                        <?php if($aux['edit']): ?>
                            formData.append('id', '<?php echo osc_item_id(); ?>');
                            formData.append('secret', '<?php echo osc_item_secret(); ?>');
                        <?php endif; ?>

                        formData.append('data', $('#item').serialize());
                    });

                    myDropzone.on('successmultiple', function(files, response) {
                        if(response == 'success') {
                            location.href = '<?php echo osc_admin_base_url(true) . "?page=items"; ?>';
                        } else {
                            $('#flash-message').html('<div id="flashmessage" class="alert flashmessage flashmessage-error" data-dismiss="alert"><a class="btn ico btn-mini ico-close close">x</a><pre>' + response + '</pre></div>');
                            myDropzone.removeAllFiles(true);
                            $(".main-panel").scrollTop(0).perfectScrollbar('update');
                        }
                    });
                }
            });

            var cInterval;

            $("#s_username").keyup(function(event) {
                var userName = $(this).val();
                if(userName.length) {
                    $("#available").show();

                    clearInterval(cInterval);
                    cInterval = setInterval(function(){
                        $.getJSON(
                            "<?php echo osc_base_url(true); ?>?page=ajax&action=check_username_availability",
                            {"s_username": userName},
                            function(data){
                                clearInterval(cInterval);
                                if(data.exists == 0) {
                                    $("#available").text('<?php echo osc_esc_js(__("The username is available")); ?>').removeClass('text-danger').addClass('text-success');
                                } else {
                                    $("#available").text('<?php echo osc_esc_js(__("The username is NOT available")); ?>').removeClass('text-success').addClass('text-danger');
                                }
                            }
                        );
                    }, 1000);
                } else {
                    $("#available").hide();
                }
            });

            $("#s_username").focus(function() {
                var userName = $(this).val();

                if(userName.length) {
                    $("#available").show();
                } else {
                    $("#available").hide();
                }
            });

            $('#update_expiration').change(function() {
                if($(this).is(':checked')) {
                    $('#dt_expiration').prop('value', '');
                    $('div.update_expiration').show();
                } else {
                    $('#dt_expiration').prop('value', '-1');
                    $('div.update_expiration').hide();
                }
            });

            $('a#img-delete').click(function() {
                var $this = $(this),
                    photoId = $.trim(parseInt($this.attr('data-id'))),
                    item_id = <?php echo osc_item_id(); ?>,
                    name = $.trim($this.attr('data-name')),
                    token = '<?php echo osc_item_secret(); ?>';

                Swal.fire({
                    title: '<?php _e('Confirm action'); ?>',
                    text: '<?php _e('Are you sure you want to delete this image?'); ?>',
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    showCancelButton: true,
                    confirmButtonText: "<?php _e("Delete"); ?>",
                    cancelButtonText: "<?php _e("Cancel"); ?>"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '<?php echo osc_base_url(true); ?>?page=ajax&action=delete_image&id=' + photoId + '&item=' + item_id + '&code=' + name + '&secret=' + token,
                            type: 'POST',
                            dataType: 'json',
                            error: function(){},
                            success: function(data){
                                if(data.success) {
                                    $('.dz-preview[data-id="' + photoId + '"]').slideUp(500, function() {
                                        $(this).remove();
                                    });
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: '<?php _e('Error...'); ?>',
                                        text: data.msg
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$aux = customFrmText();

$categories = Category::newInstance()->toTree();
$new_item = __get('new_item');
$actions    = __get('actions');

if($new_item) {
    $options = array(0,1,3,5,7,10,15,30);
} else {
    $options = array(-1,0,1,3,5,7,10,15,30);
}
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons"><?php echo $aux['icon']; ?></i>
        </div>
        <h4 class="card-title"><?php echo $aux['title']; ?></h4>
    </div>

    <div class="card-body">
        <?php printLocaleTabs(); ?>

        <form id="item" action="<?php echo osc_admin_base_url(true); ?>" method="post" name="item" class="has-form-actions form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="page" value="items" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />
            <input id="upload" class="fc-limited" type="file" name="image" >

            <?php if($aux['edit']): ?>
                <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
                <input type="hidden" name="secret" value="<?php echo osc_item_secret(); ?>" />

                <div class="row no-gutters">
                    <div class="col-xl-8 text-center text-xl-right mt-3 mt-xl-0">
                        <a class="btn btn-warning btn-sm" href="<?php echo osc_item_url(); ?>"><?php _e('View listing on front'); ?></a>
                        <?php foreach($actions as $action): ?>
                            <?php echo $action; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row no-gutters mt-5 ml-3">
                <div class="col-12 col-xl-8">
                    <div class="row no-gutters">
                        <div class="col-xl-7 pr-xl-4">
                            <?php printLocaleTitle(osc_get_locales()); ?>

                            <div class="row no-gutters mt-2 mb-2">
                                <div class="col-12">
                                    <label for="catId" class="bmd-label-floating w-100"><?php _e('Category'); ?></label>
                                    <?php ItemForm::evolution_category_multiple_selects(); ?>
                                </div>
                            </div>

                            <?php printLocaleDescription(osc_get_locales()); ?>

                            <?php if(osc_price_enabled_at_items()): ?>
                                <div class="row no-gutters price mt-3 mr-xl-5">
                                    <div class="col-5 col-sm-8">
                                        <div class="form-group">
                                            <label for="price" class="bmd-label-floating"><?php _e('Price'); ?></label>
                                            <?php ItemForm::price_input_text(null, 'form-control'); ?>
                                        </div>
                                    </div>

                                    <div class="col-7 col-sm-4">
                                        <?php ItemForm::currency_select(null, null, 'selectpicker show-tick', 'data-size="7" data-width="auto" data-style="btn btn-round btn-info btn-sm"'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(osc_images_enabled_at_items()): ?>
                                <div class="row no-gutters mt-2 mb-2">
                                    <div class="col-lg-12">
                                        <label class="bmd-label-floating w-100"><?php _e('Photos'); ?></label>

                                        <button id="upload-photo" class="btn btn-round btn-success btn-file mt-3" style="float:none!important;" type="button"><?php _e('Add new photo'); ?></button>

                                        <div id="attached-files" class="attached-files" style="margin-left: 0;">
                                            <?php $photos = osc_get_item_resources(); ?>
                                            <?php if(count($photos)): ?>
                                                <?php foreach($photos as $photo): ?>
                                                    <div class="dz-preview dz-image-preview" data-id="<?php echo $photo['pk_i_id'] ?>">
                                                        <div class="dz-image edit-image"><img src="<?php echo osc_apply_filter('resource_path', osc_base_url() . $photo['s_path']) . $photo['pk_i_id'] . '_thumbnail.' . $photo['s_extension']; ?>"></div>
                                                        <a id="img-delete" class="dz-remove" data-id="<?php echo $photo['pk_i_id'] ?>" data-name="<?php echo $photo['s_name'] ?>" href="javascript:void(0);" data-dz-remove=""><?php _e('Delete Image'); ?></a>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($aux['edit']): ?>
                                <?php ItemForm::plugin_edit_item(); ?>
                            <?php else: ?>
                                <?php ItemForm::plugin_post_item(); ?>
                            <?php endif; ?>
                        </div>

                        <div class="col-xl-5">
                            <fieldset class="mb-3 mark rounded pl-4 pr-4 mt-3">
                                <h4 class="text-gray pt-3"><?php _e('User'); ?></h4>

                                <div class="row no-gutters mb-2">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="contactName" class="bmd-label-floating"><?php _e('Name'); ?></label>
                                            <?php ItemForm::contact_name_text(null, 'form-control'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters mb-2">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="contactName" class="bmd-label-floating"><?php _e('E-mail'); ?></label>
                                            <?php ItemForm::contact_email_text(null, 'form-control'); ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if($aux['edit']): ?>
                                    <div class="row no-gutters mb-2">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="contactName" class="bmd-label-floating"><?php _e('Ip Address'); ?></label>
                                                <input id="ipAddress" class="form-control" type="text" name="ipAddress" value="<?php echo osc_item_ip(); ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="row no-gutters mb-2">
                                    <div class="col-lg-12">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <?php ItemForm::show_email_checkbox(null, 'form-check-input'); ?>
                                                <?php _e('Show e-mail'); ?>
                                                <span class="form-check-sign">
                                                    <span class="check border-gray"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="mb-3 mark rounded pl-4 pr-4 mt-3">
                                <h4 class="text-gray pt-3"><?php _e('Location'); ?></h4>

                                <div class="row no-gutters">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="countryId" class="bmd-label-floating"><?php _e('Country'); ?></label>
                                            <?php ItemForm::country_select(null, null, 'selectpicker show-tick form-control', 'data-dropup-auto="false" data-size="7" data-width="100%" data-style="btn btn-info btn-sm"'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="regionId" class="bmd-label-floating"><?php _e('Region'); ?></label>
                                            <?php ItemForm::region_select(null, null, 'selectpicker show-tick form-control', 'data-dropup-auto="false" data-size="7" data-width="100%" data-style="btn btn-info btn-sm"'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="cityId" class="bmd-label-floating"><?php _e('City'); ?></label>
                                            <?php ItemForm::city_select(null, null, 'selectpicker show-tick form-control', 'data-dropup-auto="false" data-size="7" data-width="100%" data-style="btn btn-info btn-sm"'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters mb-2">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="cityArea" class="bmd-label-floating"><?php _e('City area'); ?></label>
                                            <?php ItemForm::city_area_text(null, 'form-control'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters mb-2">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="zip" class="bmd-label-floating"><?php _e('Zip code'); ?></label>
                                            <?php ItemForm::zip_text(null, 'form-control'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters mb-2">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="address" class="bmd-label-floating"><?php _e('Address'); ?></label>
                                            <?php ItemForm::address_text(null, 'form-control'); ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="mb-3 mark rounded pl-4 pr-4 mt-3">
                                <h4 class="text-gray pt-3"><?php _e('Expiration'); ?></h4>

                                <?php if($aux['edit']): ?>
                                    <div class="row no-gutters mb-2">
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input id="update_expiration" class="form-check-input" type="checkbox" name="update_expiration">
                                                    <?php _e('Update expiration?'); ?>
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="form-group fc-limited update_expiration">
                                                <?php ItemForm::expiration_input('edit', '', 'form-control'); ?>
                                                <small class="form-text text-muted"><?php _e('It could be an integer (days from original publishing date it will be expired, 0 to never expire) or a date in the format "yyyy-mm-dd hh:mm:ss"'); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row no-gutters mb-2">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <?php ItemForm::expiration_input('add', '', 'form-control'); ?>
                                                <small class="form-text text-muted"><?php _e('It could be an integer (days from original publishing date it will be expired, 0 to never expire) or a date in the format "yyyy-mm-dd hh:mm:ss"'); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>

                    <button id="item-post" type="button" class="btn btn-info">
                        <?php echo osc_esc_html($aux['btn_text']); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>