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
    return sprintf(__('User Settings &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Manage the options related to users on your site. Here, you can decide if users must register or if email confirmation is necessary, among other options.") . '</p>';
}

function customPageHeader() {
    _e('Users');
}

//customize Head
function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('change', '#moderate_items', function() {
                if($(this).is(':checked')) {
                    $('div[data-id="validate-items"]').removeClass('d-none');
                } else {
                    $('div[data-id="validate-items"]').addClass('d-none');
                }
            });
        });
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';
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
            <h4 class="card-title"><?php _e('User Settings'); ?></h4>
        </div>

        <div class="card-body">
            <form action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions">
                <input type="hidden" name="page" value="users" />
                <input type="hidden" name="action" value="settings_post" />

                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <fieldset>
                                    <legend><?php _e('Settings'); ?></legend>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input id="enabled_users" class="form-check-input" type="checkbox" <?php echo (osc_users_enabled() ? 'checked="checked"' : ''); ?> name="enabled_users" value="1">
                                                <?php _e('Users enabled'); ?>

                                                <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input id="enabled_user_registration" class="form-check-input" type="checkbox" <?php echo (osc_user_registration_enabled() ? 'checked="checked"' : ''); ?> name="enabled_user_registration" value="1">
                                                <?php _e('Anyone can register'); ?>

                                                <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input id="enabled_user_validation" class="form-check-input" type="checkbox" <?php echo (osc_user_validation_enabled() ? 'checked="checked"' : ''); ?> name="enabled_user_validation" value="1">
                                                <?php _e('Users need to validate their account'); ?>

                                                <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <?php _e('Username blacklist'); ?>
                                        <input type="text" class="form-control w-50 w-xl-25 text-center d-inline" name="username_blacklist" value="<?php echo osc_esc_html(osc_username_blacklist()); ?>" />

                                        <span class="form-text text-muted"><?php _e('List of terms not allowed in usernames, separated by commas'); ?></span>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12 mt-2">
                                <fieldset>
                                    <legend><?php _e('Admin notifications'); ?></legend>

                                    <div class="col-md-12 mb-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input id="notify_new_user" class="form-check-input" type="checkbox" <?php echo (osc_notify_new_user() ? 'checked="checked"' : ''); ?> name="notify_new_user" value="1">
                                                <?php _e('When a new user is registered'); ?>

                                                <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>



                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-info">
                                    <?php echo osc_esc_html( __('Save changes') ); ?>
                                    <div class="ripple-container"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>