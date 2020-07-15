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

function customFrmText() {
    $user      = __get('user');
    $return = array();

    if( isset($user['pk_i_id']) ) {
        $return['edit']       = true;
        $return['title']      = __('Edit user');
        $return['icon']      = __('create');
        $return['action_frm'] = 'edit_post';
        $return['btn_text']   = __('Update user');
        $return['alerts'] = Alerts::newInstance()->findByUser($user['pk_i_id'], true);
    } else {
        $return['edit']       = false;
        $return['title']      = __('Add new user');
        $return['icon']      = __('add');
        $return['action_frm'] = 'create_post';
        $return['btn_text']   = __('Add new user');
        $return['alerts'] = array();
    }
    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Users');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $("form[name='register']").validate({
                rules: {
                    s_name: {
                        required: true
                    },
                    s_email: {
                        required: true,
                        email: true
                    },
                    <?php if(Params::getParam('action') == 'create'): ?>
                    s_password: {
                        required: true,
                        minlength: 5
                    },
                    <?php endif; ?>
                    s_password2: {
                        <?php if(Params::getParam('action') == 'create'): ?>
                        required: true,
                        minlength: 5,
                        <?php endif; ?>
                        equalTo: "#s_password"
                    }
                },
                messages: {
                    s_name: {
                        required: "<?php _e("Name: this field is required"); ?>."
                    },
                    s_email: {
                        required: "<?php _e("Email: this field is required"); ?>.",
                        email: "<?php _e("Invalid email address"); ?>."
                    },
                    s_password: {
                        required: "<?php _e("Password: this field is required"); ?>.",
                        minlength: "<?php _e("Password: enter at least 5 characters"); ?>."
                    },
                    s_password2: {
                        required: "<?php _e("Second password: this field is required"); ?>.",
                        minlength: "<?php _e("Second password: enter at least 5 characters"); ?>.",
                        equalTo: "<?php _e("Passwords don't match"); ?>."
                    }
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
                                // $("#city").before('<select name="cityId" id="cityId" ></select>');
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

            if( $("#countryId").prop('type').match(/select/)) {
                if( $("#countryId").val() == "") {
                    $("#regionId option[value != '']").remove();
                    $("#regionId").attr('disabled', true);

                    $('.selectpicker').selectpicker('refresh');
                }
            }

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
        });
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$aux = customFrmText();
$user      = __get('user');
$countries = __get('countries');
$regions   = __get('regions');
$cities    = __get('cities');
$locales   = __get('locales')
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
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" name="register" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="users" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />

            <?php UserForm::primary_input_hidden($user); ?>
            <?php if($aux['edit']) { ?>
                <input type="hidden" name="b_enabled" value="<?php echo $user['b_enabled']; ?>" />
                <input type="hidden" name="b_active" value="<?php echo $user['b_active']; ?>" />
            <?php } ?>

            <?php if(__get('user') != ''): ?>
                 <?php $actions = __get('actions'); ?>
                <div class="row no-gutters">
                    <div class="col-md-4 offset-md-8 text-right">
                        <?php foreach($actions as $action): ?>
                            <?php echo $action; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong><?php _e('Last access'); ?>:</strong>
                            <?php echo sprintf(__("%s on %s"), $user['s_access_ip'], $user['dt_access_date']);?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <fieldset class="mb-3">
                <legend><?php _e('Contact info'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Name'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::name_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Username'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::username_text($user, 'form-control w-100 w-xl-75'); ?>
                            <span id="available" class="bmd-help"></span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('E-mail'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::email_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Cell phone'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::mobile_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Phone'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::phone_land_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Website'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::website_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('About you'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('User type'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::is_company_select($user, null, null, 'selectpicker show-tick w-100 w-xl-75', 'data-size="7" data-dropup-auto="false" data-style="btn btn-info btn-sm"'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Additional information'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::multilanguage_info($locales, $user, 'form-control w-100 w-xl-75 h-50'); ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Location'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Country'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::country_select($countries, $user, 'form-control w-100 w-xl-75 selectpicker show-tick', 'data-size="7" data-dropup-auto="false" data-style="btn btn-info btn-sm"'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Region'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::region_select($regions, $user, 'form-control w-100 w-xl-75 selectpicker show-tick', 'data-size="7" data-dropup-auto="false" data-style="btn btn-info btn-sm"'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('City'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::city_select($cities, $user, 'form-control w-100 w-xl-75 selectpicker show-tick', 'data-size="7" data-dropup-auto="false" data-style="btn btn-info btn-sm"'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('City area'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::city_area_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Zip code'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::zip_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Address'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::address_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Password'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('New password'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::password_text($user, 'form-control w-100 w-xl-75'); ?>

                            <?php if($aux['edit']): ?>
                                <span class="form-text text-muted"><?php _e("If you'd like to change the password, type a new one. Otherwise leave this blank"); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Confirm password'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php UserForm::check_password_text($user, 'form-control w-100 w-xl-75'); ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <?php if(!$aux['edit']) {
                osc_run_hook('user_register_form');
            } else {
                osc_run_hook('user_profile_form', $user);
                osc_run_hook('user_form', $user);
            } ?>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <?php if( $aux['edit'] ) { ?>
                        <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>
                    <?php } ?>

                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html($aux['btn_text']); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>