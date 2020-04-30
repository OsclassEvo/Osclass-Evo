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
    return sprintf(__('Maintenance &raquo; %s'), $string);
}

function customPageHeader() {
    _e('Maintenance');
}

function addHelp() {
    echo '<p>' . __('Show a "Site in maintenance mode" message to your users while you\'re updating your site or modifying its configuration.') . '</p>';
}

//customize Head
function customHead() {}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';

$maintenance = file_exists( osc_base_path() . '.maintenance');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">pause_presentation</i>
        </div>
        <h4 class="card-title"><?php _e('Maintenance'); ?></h4>
    </div>

    <div class="card-body">
        <form id="backup_form" name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions">
            <input type="hidden" name="page" value="tools" />
            <input type="hidden" name="action" value="maintenance" />
            <input type="hidden" name="mode" value="<?php echo ($maintenance ? 'off' : 'on'); ?>" />

            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                <?php _e('While in maintenance mode, users can\'t access your website. Useful if you need to make changes on your website. Use the following button to toggle maintenance mode ON/OFF.'); ?>.
                            </p>

                            <p>
                                <?php printf( __('Maintenance mode is: <strong>%s</strong>'), ($maintenance ? __('ON') : __('OFF') ) ); ?>
                            </p>
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-info">
                                <?php echo ($maintenance ? osc_esc_html(__('Disable maintenance mode')) : osc_esc_html(__('Enable maintenance mode'))); ?>
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