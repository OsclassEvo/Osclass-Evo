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

    function customPageHeader() {
        echo osc_apply_filter('custom_plugin_title', __('Plugins'));
    }

    function customPageTitle($string) {
        return sprintf(__('Plugins &raquo; %s'), $string);
    }

    osc_add_filter('admin_title', 'customPageTitle');
    osc_add_hook('admin_page_header','customPageHeader');

    osc_current_admin_theme_path( 'parts/header.php' );

    osc_register_script('jquery-ui', osc_current_admin_theme_js_url('plugins/jquery-ui.min.js'), 'jquery');
    osc_enqueue_script('jquery-ui');
    osc_load_script('jquery-ui');

    $file = __get('file');
?>

    <div class="card">
        <div class="card-body card-plugins">
            <?php
            if(strpos($file, '../') === false && strpos($file, '..\\') == false && file_exists($file)) {
                require_once $file;
            }
            ?>
        </div>
    </div>

    <input id="msg-choose-file" type="hidden" value="<?php _e('Choose file'); ?>" />

<?php
osc_enqueue_style('admin-plugins', osc_current_admin_theme_styles_url('plugins.css?v=' . time()));
osc_load_style('admin-plugins');

osc_register_script('admin-plugins', osc_current_admin_theme_js_url('plugins.js?v=' . time()), 'jquery');
osc_enqueue_script('admin-plugins');
osc_load_script('admin-plugins');
?>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>