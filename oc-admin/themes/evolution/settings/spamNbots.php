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
	return sprintf(__('Spam and bots &raquo; %s'), $string);
}

function addHelp() {
	echo '<p>' . __('Keep spammers from publishing on your site by configuring reCAPTCHA and Akismet. Be careful: in order to use these services, you must register on their sites first and follow their instructions.') . '</p>';
}

function customPageHeader() {
	_e('Settings');
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');

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
            <i class="material-icons">vpn_key</i>
        </div>
        <h4 class="card-title"><?php _e('Spam and bots'); ?></h4>
    </div>

    <div class="card-body">
        <ul class="nav nav-pills nav-pills-rose" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#recaptcha" role="tablist">
                    <?php _e('reCAPTCHA'); ?>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#akismet" role="tablist">
					<?php _e('Akismet'); ?>
                </a>
            </li>
        </ul>
        <div class="tab-content tab-space">
            <div class="tab-pane active" id="recaptcha">
                <form name="comments_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="recaptcha_post" />

                    <fieldset class="mb-3">
                        <legend class="regular"><?php printf(__('reCAPTCHA helps prevent automated abuse of your site by using a CAPTCHA to ensure that only humans perform certain actions. <a href="%s" target="_blank">Get your key</a>'), 'https://www.google.com/recaptcha/admin#whyrecaptcha'); ?></legend>

                        <div class="row no-gutters">
                            <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('ReCAPTCHA Version'); ?></label>
                            <div class="col-xl-5">
                                <select class="selectpicker show-tick w-100 w-xl-50" name="recaptchaVersion" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                                    <option value="2" <?php echo (osc_recaptcha_version() == '2' ? 'selected' : ''); ?>><?php _e('reCAPTCHA v.2'); ?></option>
                                    <option value="3" <?php echo (osc_recaptcha_version() == '3' ? 'selected' : ''); ?>><?php _e('reCAPTCHA v.3'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row no-gutters mb-3 mb-md-0">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <?php _e('Site key'); ?>
                                    <input type="text" class="form-control w-100 w-md-50 w-xl-25 text-center d-inline ml-0 ml-md-3" name="recaptchaPubKey" value="<?php echo (osc_recaptcha_public_key() ? osc_esc_html(osc_recaptcha_public_key()) : ''); ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <?php _e('Secret key'); ?>
                                    <input type="text" class="form-control w-100 w-md-50 w-xl-25 text-center d-inline" name="recaptchaPrivKey" value="<?php echo (osc_recaptcha_private_key() ? osc_esc_html(osc_recaptcha_private_key()) : ''); ?>" />
                                </div>
                            </div>
                        </div>

                        <?php if(osc_recaptcha_public_key()): ?>
                            <div class="row no-gutters">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <?php if(osc_recaptcha_version() == '2'): ?>
                                            <?php _e('If you see the reCAPTCHA form it means that you have correctly entered the public key'); ?>
                                        <?php else: ?>
                                            <?php _e('If you see the reCAPTCHA icon in the lower right corner of the screen, it means that you have correctly entered the public key'); ?>
                                        <?php endif; ?>
                                        <?php osc_show_recaptcha(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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

            <div class="tab-pane" id="akismet">
                <form name="comments_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
                    <input type="hidden" name="page" value="settings" />
                    <input type="hidden" name="action" value="akismet_post" />

                    <fieldset class="mb-3">
                        <legend class="regular"><?php _e('Akismet is a hosted web service that saves you time by automatically detecting comment and trackback spam. It\'s hosted on our servers, but we give you access to it through plugins and our API.'); ?></legend>

                        <div class="row no-gutters">
                            <div class="col-xl-6">
                                <div class="form-group">
									<?php _e('Akismet API Key'); ?>
                                    <input type="text" class="form-control w-100 w-md-50 w-xl-25 text-center d-inline" name="akismetKey" value="<?php echo (osc_akismet_key() ? osc_esc_html(osc_akismet_key()) : ''); ?>" />

									<?php
									$akismet_status = View::newInstance()->_get('akismet_status');
									$alert_msg      = '';
									$alert_type     = 'error';
									switch($akismet_status) {
										case 1:
											$alert_type = 'success';
											$alert_msg  = __('This key is valid');
											break;
										case 2:
											$alert_type = 'danger';
											$alert_msg  = __('The key you entered is invalid. Please double-check it');
											break;
										case 3:
											$alert_type = 'warning';
											$alert_msg  = sprintf(__('Akismet is disabled, please enter an API key. <a href="%s" target="_blank">(Get your key)</a>'), 'http://akismet.com/get/');;
											break;
									}
									?>

                                    <span class="form-text text-muted text-<?php echo $alert_type; ?>"><?php echo $alert_msg; ?></span>
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
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>