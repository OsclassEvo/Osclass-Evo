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

osc_enqueue_script('jquery-validate');

function customPageTitle($string) {
    return sprintf(__('General Settings &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Change the basic configuration of your Osclass. From here, you can modify variables such as the site’s name, the default currency or how lists of listings are displayed. <strong>Be careful</strong> when modifying default values if you're not sure what you're doing!") . '</p>';
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $("form[name='settings_form']").validate({
                rules: {
                    pageTitle: {
                        required: true,
                        minlength: 1
                    },
                    contactEmail: {
                        required: true,
                        email: true
                    },
                    num_rss_items: {
                        required: true,
                        digits: true
                    },
                    max_latest_items_at_home: {
                        required: true,
                        digits: true
                    },
                    default_results_per_page: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    pageTitle: {
                        required: '<?php echo osc_esc_js(__("Page title: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js(__("Page title: this field is required")); ?>.'
                    },
                    contactEmail: {
                        required: '<?php echo osc_esc_js(__("Email: this field is required")); ?>.',
                        email: '<?php echo osc_esc_js(__("Invalid email address")); ?>.'
                    },
                    num_rss_items: {
                        required: '<?php echo osc_esc_js(__("Listings shown in RSS feed: this field is required")); ?>.',
                        digits: '<?php echo osc_esc_js(__("Listings shown in RSS feed: this field must only contain numeric characters")); ?>.'
                    },
                    max_latest_items_at_home: {
                        required: '<?php echo osc_esc_js(__("Latest listings shown: this field is required")); ?>.',
                        digits: '<?php echo osc_esc_js(__("Latest listings shown: this field must only contain numeric characters")); ?>.'
                    },
                    default_results_per_page: {
                        required: '<?php echo osc_esc_js(__("The search page shows: this field is required")); ?>.',
                        digits: '<?php echo osc_esc_js(__("The search page shows: this field must only contain numeric characters")); ?>.'
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
        });

        function custom_date(date_format) {
            $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=date_format",
                {"format" : date_format},
                function(data){
                    if(data.str_formatted!='') {
                        $("#custom_date").text(' <?php _e('Preview'); ?>: ' + data.str_formatted)
                    } else {
                        $("#custom_date").text('');
                    }
                }
            );
        }

        function custom_time(time_format) {
            $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=date_format",
                {"format" : time_format},
                function(data){
                    if(data.str_formatted!='') {
                        $("#custom_time").text(' <?php _e('Preview'); ?>: ' + data.str_formatted)
                    } else {
                        $("#custom_time").text('');
                    }
                }
            );
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

$dateFormats = array('F j, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
$timeFormats = array('g:i a', 'g:i A', 'H:i');

$aLanguages  = __get('aLanguages');
$aCurrencies = __get('aCurrencies');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">settings</i>
        </div>
        <h4 class="card-title"><?php _e('Settings'); ?></h4>
    </div>

    <div class="card-body">
        <form name="settings_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="update" />

            <fieldset class="mb-3">
                <legend><?php _e('General Settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Page title'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-left text-xl-center d-inline" name="pageTitle" required="true" value="<?php echo osc_esc_html(osc_page_title()); ?>" />
                            <span class="bmd-help text-danger"><?php _e('Field is required'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Page description'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-left text-xl-center d-inline" name="pageDesc" value="<?php echo osc_esc_html(osc_page_description()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Contact e-mail'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-left text-xl-center d-inline" name="contactEmail" required="true" value="<?php echo osc_esc_html(osc_contact_email()); ?>" />
                            <span class="bmd-help text-danger"><?php _e('Field is required'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Admin Theme'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="adminTheme" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="evolution" <?php if(osc_admin_theme() == 'evolution') echo 'selected'; ?>><?php _e('Evolution'); ?></option>
                            <option value="modern" <?php if(osc_admin_theme() == 'modern') echo 'selected'; ?>><?php _e('Modern'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Default language'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="language" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <?php foreach( $aLanguages as $lang ) { ?>
                                <option value="<?php echo $lang['pk_c_code']; ?>" <?php echo ((osc_language() == $lang['pk_c_code']) ? 'selected' : ''); ?>><?php echo $lang['s_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Default currency'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="currency" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <?php foreach($aCurrencies as $currency) { ?>
                                <option value="<?php echo osc_esc_html($currency['pk_c_code']); ?>" <?php echo ((osc_currency() == $currency['pk_c_code']) ? 'selected' : ''); ?>><?php echo $currency['pk_c_code'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Week starts on'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="weekStart" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="0" <?php if(osc_week_starts_at() == '0') echo 'selected'; ?>><?php _e('Sunday'); ?></option>
                            <option value="1" <?php if(osc_week_starts_at() == '1') echo 'selected'; ?>><?php _e('Monday'); ?></option>
                            <option value="2" <?php if(osc_week_starts_at() == '2') echo 'selected'; ?>><?php _e('Tuesday'); ?></option>
                            <option value="3" <?php if(osc_week_starts_at() == '3') echo 'selected'; ?>><?php _e('Wednesday'); ?></option>
                            <option value="4" <?php if(osc_week_starts_at() == '4') echo 'selected'; ?>><?php _e('Thursday'); ?></option>
                            <option value="5" <?php if(osc_week_starts_at() == '5') echo 'selected'; ?>><?php _e('Friday'); ?></option>
                            <option value="6" <?php if(osc_week_starts_at() == '6') echo 'selected'; ?>><?php _e('Saturday'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Timezone'); ?></label>

                    <?php require osc_lib_path() . 'osclass/timezones.php'; ?>
                    <?php $selected_tz = osc_timezone(); ?>

                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="timezone" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="" <?php if(!$selected_tz) echo 'selected'; ?>><?php _e('Select a timezone...'); ?></option>
                            <?php foreach ($timezone as $tz) { ?>
                                <option value="<?php echo $tz; ?>" <?php if($selected_tz == $tz) echo 'selected'; ?>><?php echo $tz; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Date & time format'); ?></label>
                    <div class="col-xl-6">
                        <div class="row no-gutters">
                            <div class="col-6 col-xl-4 checkbox-radios" style="max-width: 250px!important;">
                                <?php $custom_checked = true; ?>

                                <?php foreach($dateFormats as $df): ?>
                                    <?php
                                    $checked = false;
                                    if($df == osc_date_format()) {
                                        $custom_checked = false;
                                        $checked        = true;
                                    }
                                    ?>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="<?php echo $df; ?>" class="form-check-input" type="radio" name="df" value="<?php echo $df; ?>" <?php echo ($checked ? 'checked' : ''); ?> onclick="javascript:document.getElementById('dateFormat').value = '<?php echo $df; ?>';"> <?php echo date($df); ?>
                                            <span class="circle">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input id="df_custom" class="form-check-input" type="radio" name="df" value="df_custom" <?php echo ($custom_checked ? 'checked' : ''); ?>>

                                        <div class="form-group" style="margin-top: -15px;">
                                            <input type="text" class="form-control text-center d-inline" name="df_custom_text" <?php echo ($custom_checked ? 'value="' . osc_esc_html(osc_date_format()) . '"' : ''); ?> onchange="javascript:document.getElementById('dateFormat').value = this.value;" onkeyup="javascript:custom_date(this.value);" />
                                            <span id="custom_date" class="bmd-help text-info"></span>

                                            <input type="hidden" name="dateFormat" id="dateFormat" value="<?php echo osc_date_format(); ?>" />
                                        </div>

                                        <span class="circle">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-6 col-xl-4 checkbox-radios" style="max-width: 250px!important;">
                                <?php $custom_checked = true; ?>

                                <?php foreach($timeFormats as $tf): ?>
                                    <?php
                                    $checked = false;
                                    if($tf == osc_time_format()) {
                                        $custom_checked = false;
                                        $checked        = true;
                                    }
                                    ?>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="<?php echo $tf; ?>" class="form-check-input" type="radio" name="tf" value="<?php echo $tf; ?>" <?php echo ($checked ? 'checked' : ''); ?> onclick="javascript:document.getElementById('timeFormat').value = '<?php echo $tf; ?>';"> <?php echo date($tf); ?>
                                            <span class="circle">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input id="tf_custom" class="form-check-input" type="radio" name="tf" value="tf_custom" <?php echo ($custom_checked ? 'checked' : ''); ?>>

                                        <div class="form-group" style="margin-top: -15px;">
                                            <input type="text" class="form-control text-center d-inline" name="df_custom_text" <?php echo ($custom_checked ? 'value="' . osc_esc_html(osc_time_format()) . '"' : ''); ?> onchange="javascript:document.getElementById('timeFormat').value = this.value;" onkeyup="javascript:custom_time(this.value);" />
                                            <span id="custom_time" class="bmd-help text-info"></span>

                                            <input id="timeFormat" type="hidden" name="timeFormat" value="<?php echo osc_esc_html(osc_time_format()); ?>" />
                                        </div>

                                        <span class="circle">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <span class="form-text text-muted"><a href="http://php.net/date" target="_blank"><?php _e('Documentation on date and time formatting'); ?></a></span>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('RSS shows'); ?></label>
                    <div class="col-md-8 col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="num_rss_items" value="<?php echo osc_esc_html(osc_num_rss_items()); ?>" />
                            <?php _e('listings at most'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Latest listings shown'); ?></label>
                    <div class="col-md-8 col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="max_latest_items_at_home" value="<?php echo osc_esc_html(osc_max_latest_items_at_home()); ?>" />
                            <?php _e('at most'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Search page shows'); ?></label>
                    <div class="col-md-8 col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="default_results_per_page" value="<?php echo osc_esc_html(osc_default_results_per_page_at_search()); ?>" />
                            <?php _e('listings at most'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Pages preloading'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="admin_pages_preloading" class="form-check-input" type="checkbox" <?php echo (osc_admin_pages_preloading() ? 'checked' : ''); ?> name="enabled_admin_pages_preloading" value="1">
                                <?php _e('Enable preloading pages in the admin panel'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Scrolling dragging mouse'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="admin_scrolling_mouse" class="form-check-input" type="checkbox" <?php echo (osc_admin_scrolling_mouse() ? 'checked' : ''); ?> name="enabled_admin_scrolling_mouse" value="1">
                                <?php _e('Enable pages scrolling by dragging the scrollbar in the admin panel'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Category settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Parent categories'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="selectable_parent_categories" class="form-check-input" type="checkbox" <?php echo (osc_selectable_parent_categories() ? 'checked' : ''); ?> name="selectable_parent_categories" value="1">
                                <?php _e('Allow users to select a parent category as a category when inserting or editing a listing '); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Contact Settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Attachments'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="enabled_attachment" class="form-check-input" type="checkbox" <?php echo (osc_contact_attachment() ? 'checked' : ''); ?> name="enabled_attachment" value="1">
                                <?php _e('Allow people to attach a file to the contact form'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Cron Settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Automatic cron process'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="auto_cron" class="form-check-input" type="checkbox" <?php echo (osc_auto_cron() ? 'checked' : ''); ?> name="auto_cron" value="1">
                                <?php printf(__('Allow Osclass to run a built-in <a href="%s" target="_blank">cron</a> automatically without setting crontab'), 'http://en.wikipedia.org/wiki/Cron'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>

                        <span class="form-text text-muted"><?php _e('It is <b>recommended</b> to have this option enabled, because some features require it.'); ?></span>
                    </div>
                </div>
            </fieldset>

            <?php if (osc_market_api_connect()): ?>
            <fieldset class="mb-3">
                <legend><?php _e('Market Settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Connect ID'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-group">
                            <p class="form-control-static"><?php echo osc_market_api_connect(); ?></p>
                            <span class="form-text text-muted"><a href="javascript:void(0);" id="market_disconnect"><?php _e('Disconnect from osclass.market'); ?></a></span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php endif; ?>

            <fieldset class="mb-3">
                <legend><?php _e('Software updates'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Core updates'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="auto_update[]" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="disabled"><?php _e('Disabled'); ?></option>
                            <option value="core" <?php if(strpos(osc_auto_update(),'core') !== false) echo 'selected'; ?>><?php _e('Enabled'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Plugin updates'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="auto_cron" class="form-check-input" type="checkbox" <?php echo ((strpos(osc_auto_update(),'plugins') !== false) ? 'checked' : '' ); ?> name="auto_update[]" value="plugins">
                                <?php _e('Allow auto-updates plugins'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Theme updates'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="auto_cron" class="form-check-input" type="checkbox" <?php echo ((strpos(osc_auto_update(),'themes') !== false) ? 'checked' : '' ); ?> name="auto_update[]" value="themes">
                                <?php _e('Allow auto-updates of themes'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Language updates'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="auto_cron" class="form-check-input" type="checkbox" <?php echo ((strpos(osc_auto_update(),'languages') !== false) ? 'checked' : '' ); ?> name="auto_update[]" value="languages">
                                <?php _e('Allow auto-updates languages'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"><?php _e('Market external sources'); ?></label>
                    <div class="col-md-8 col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="auto_cron" class="form-check-input" type="checkbox" <?php echo (osc_market_external_sources() ? 'checked' : ''); ?> name="market_external_sources" value="plugins">
                                <?php _e('Allow updates and installations of non-official plugins and themes'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-md-1 col-form-label form-label text-left text-xl-right"></label>
                    <div class="col-md-8 col-xl-5">
                        <span class="form-text text-muted">
                            <?php printf(__('Last checked on %s'), osc_format_date(date('d-m-Y h:i:s', osc_get_preference('themes_last_version_check')))); ?>

                            <a id="check-updates" class="btn btn-info btn-sm" href="<?php echo osc_admin_base_url(true); ?>?page=settings&action=check_updates"><?php _e('Check updates');?></a>
                        </span>
                    </div>
                </div>
            </fieldset>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html( __('Save changes') ); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>