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
	return sprintf(__('Advanced Settings &raquo; %s'), $string);
}

function addHelp() {
	echo '<p>' . __("Change advanced configuration of your Osclass. <strong>Be careful</strong> when modifying default values if you're not sure what you're doing!") . '</p>';
}

function customPageHeader() {
	_e('Settings');
}

function customHead() {}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$current_host = parse_url(Params::getServerParam('HTTP_HOST'), PHP_URL_HOST);
if ($current_host === null) {
	$current_host = Params::getServerParam('HTTP_HOST');
}

$cache_type = Object_Cache_Factory::newInstance()->_get_cache();

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
        <h4 class="card-title"><?php _e('Advanced Settings'); ?></h4>
    </div>

    <div class="card-body">
        <?php if($cache_type != 'default'): ?>
            <form name="advanced_settings_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
                <input type="hidden" name="page" value="settings" />
                <input type="hidden" name="action" value="advanced_cache_flush" />

                <fieldset>
                    <legend><?php _e('Flush cache'); ?></legend>

                    <div class="row no-gutters">
                        <div class="col-12 col-xl-6">
                            <div class="form-group">
                                <span class="form-text text-muted"><?php _e('Remove all data from cache.'); ?> <b><?php echo $cache_type; ?></b></span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="row no-gutters">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info btn-outline-info">
							<?php echo osc_esc_html( __('Flush cache') ); ?>
                            <div class="ripple-container"></div>
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>

        <form name="advanced_settings_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="advanced_post" />

            <fieldset class="mb-3">
                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Subdomain type'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-50" name="e_type" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="" <?php if(osc_subdomain_type() == '') echo 'selected'; ?>><?php _e('No subdomains'); ?></option>
                            <option value="category" <?php if(osc_subdomain_type() == 'category') echo 'selected'; ?>><?php _e('Category based'); ?></option>
                            <option value="country" <?php if(osc_subdomain_type() == 'country') echo 'selected'; ?>><?php _e('Country based'); ?></option>
                            <option value="region" <?php if(osc_subdomain_type() == 'region') echo 'selected'; ?>><?php _e('Region based'); ?></option>
                            <option value="city" <?php if(osc_subdomain_type() == 'city') echo 'selected'; ?>><?php _e('City based'); ?></option>
                            <option value="user" <?php if(osc_subdomain_type() == 'user') echo 'selected'; ?>><?php _e('User based'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Host'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="s_host" value="<?php echo osc_esc_html(osc_subdomain_host()); ?>" />

                            <span class="form-text text-muted">
                                <?php _e('Your host is required to know the subdomain.'); ?><br>
                                <?php printf(__('Your current host is "%s". Add it without "www".'), $current_host); ?><br>
                                <?php _e('Remember to enable cookies for the subdomains too.'); ?>
                            </span>
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