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
	return sprintf(__('Mail Settings &raquo; %s'), $string);
}

function addHelp() {
	echo '<p>' . __("Modify the settings of the mail server from which your site's emails are sent. <strong>Be careful</strong>: these settings can vary depending on your hosting or server. If you run into any issues, check your hosting's help section.") . '</p>';
}

function customPageHeader() {
	_e('Settings');
}

function customHead() {
	?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('select[name="mailserver_type"]').bind('change', function(){
                if( $(this).val() == 'gmail' ) {
                    $('input[name="mailserver_host"]').val('smtp.gmail.com');
                    $('input[name="mailserver_host"]').attr('readonly', true);
                    $('input[name="mailserver_port"]').val('465');
                    $('input[name="mailserver_port"]').attr('readonly', true);
                    $('input[name="mailserver_username"]').val('');
                    $('input[name="mailserver_password"]').val('');
                    $('input[name="mailserver_ssl"]').val('ssl');
                    $('input[name="mailserver_auth"]').prop('checked', true);
                    $('input[name="mailserver_pop"]').prop('checked', false);
                } else {
                    $('input[name="mailserver_host"]').val('localhost');
                    $('input[name="mailserver_host"]').attr('readonly', false);
                    $('input[name="mailserver_port"]').val(0);
                    $('input[name="mailserver_port"]').attr('readonly', false);
                    $('input[name="mailserver_ssl"]').val('');
                    $('input[name="mailserver_auth"]').prop('checked', false);
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
            <i class="material-icons">contact_mail</i>
        </div>
        <h4 class="card-title"><?php _e('Mail Settings'); ?></h4>
    </div>

    <div class="card-body">
        <form name="mail_server_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="mailserver_post" />

            <fieldset class="mb-3">
                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Server type'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="mailserver_type" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="custom" <?php echo (osc_mailserver_type() == 'custom') ? 'selected' : ''; ?>><?php _e('Custom Server'); ?></option>
                            <option value="gmail" <?php echo (osc_mailserver_type() == 'gmail') ? 'selected' : ''; ?>><?php _e('GMail Server'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Hostname'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="mailserver_host" value="<?php echo osc_esc_html(osc_mailserver_host()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Mail from'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="mailserver_mail_from" value="<?php echo osc_esc_html(osc_mailserver_mail_from()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Name from'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="mailserver_name_from" value="<?php echo osc_esc_html(osc_mailserver_name_from()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Server port'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-50 w-xl-25 text-center d-inline" name="mailserver_port" value="<?php echo osc_esc_html(osc_mailserver_port()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Username'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="mailserver_username" value="<?php echo osc_esc_html(osc_mailserver_username()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Password'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="mailserver_password" value="<?php echo osc_esc_html(osc_mailserver_password()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Encryption'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-md-50 w-xl-25 text-center d-inline" name="mailserver_ssl" value="<?php echo osc_esc_html(osc_mailserver_ssl()); ?>" />

							<?php if(php_sapi_name() == 'cgi-fcgi' || php_sapi_name() == 'cgi') { ?>
                                <span class="label-on-right pl-0 pl-md-1 pt-0 mt-3 mb-3 mt-md-0 mb-md-0 d-inline-block text-left text-md-rigt">
                                    <code><?php _e('Cannot be sure that Apache Module <b>mod_ssl</b> is loaded.'); ?></code>
                                </span>
							<?php } else if(!@apache_mod_loaded('mod_ssl')) { ?>
                                <span class="label-on-right pl-0 pl-md-1 pt-0 mt-3 mb-3 mt-md-0 mb-md-0 d-inline-block text-left text-md-rigt">
                                    <code><?php _e('Apache Module <b>mod_ssl</b> is not loaded'); ?></code>
                                </span>
							<?php } ?>

                            <span class="form-text text-muted">
                                <?php _e("Options: blank, ssl or tls"); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('SMTP'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="mailserver_auth" class="form-check-input" type="checkbox" <?php echo (osc_mailserver_auth() ? 'checked' : '' ); ?> name="mailserver_auth" value="1">
                                <?php _e('SMTP authentication enabled'); ?>

                                <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('POP'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="mailserver_pop" class="form-check-input" type="checkbox" <?php echo (osc_mailserver_pop() ? 'checked' : '' ); ?> name="mailserver_pop" value="1">
								<?php _e('Use POP before SMTP'); ?>

                                <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html( __('Save changes') ); ?>
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>