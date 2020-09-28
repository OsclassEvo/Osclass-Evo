<?php
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

define('ABS_PATH', str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/'));
define('OC_ADMIN', true);

require_once ABS_PATH . 'oc-load.php';

if( file_exists(ABS_PATH . '.maintenance') ) {
    define('__OSC_MAINTENANCE__', true);
}

Cookie::newInstance()->push('Set-Cookie', 'HttpOnly;Secure;SameSite=Strict');

if(osc_get_preference('admin_theme') == 'evolution') {
    // evolution admin scripts
    osc_register_script('jquery', '//code.jquery.com/jquery-1.12.4.min.js');
    osc_register_script('jquery-ui', osc_current_admin_theme_js_url('plugins/jquery-ui.min.js'), 'jquery');
    osc_register_script('admin-autocomplete', osc_current_admin_theme_js_url('plugins/jquery-ui.min.js'), 'jquery');
    osc_register_script('admin-popper', osc_current_admin_theme_js_url('core/popper.min.js'), 'jquery');
    osc_register_script('admin-moment', osc_current_admin_theme_js_url('plugins/moment.min.js'), 'jquery');
    osc_register_script('admin-bs_material', osc_current_admin_theme_js_url('core/bootstrap-material-design.min.js'), 'jquery');
    osc_register_script('admin-scrollbar', osc_current_admin_theme_js_url('plugins/perfect-scrollbar.jquery.min.js'), 'jquery');
    osc_register_script('admin-sweetalert', osc_current_admin_theme_js_url('plugins/sweetalert2.js'), 'jquery');
    osc_register_script('admin-validate', osc_current_admin_theme_js_url('plugins/jquery.validate.min.js'), 'jquery');
    osc_register_script('admin-bs_selectpicker', osc_current_admin_theme_js_url('plugins/bootstrap-selectpicker.js'), 'jquery');
    osc_register_script('admin-bs_datepicker', osc_current_admin_theme_js_url('plugins/bootstrap-datetimepicker.min.js'), 'jquery');
    osc_register_script('admin-chartist', osc_current_admin_theme_js_url('plugins/chartist.min.js'), 'jquery');
    osc_register_script('admin-chartist-pointlabels', osc_current_admin_theme_js_url('plugins/chartist-plugin-pointlabels.js'), 'jquery');
    osc_register_script('admin-chartist-legend', osc_current_admin_theme_js_url('plugins/chartist-plugin-legend.js'), 'jquery');
    osc_register_script('admin-chart-canvas', osc_current_admin_theme_js_url('plugins/jquery.canvasjs.min.js'), 'jquery');
    osc_register_script('admin-bs_notify', osc_current_admin_theme_js_url('plugins/bootstrap-notify.js'), 'jquery');
    osc_register_script('admin-bs_treeview', osc_current_admin_theme_js_url('plugins/hummingbird-treeview.js'), 'jquery');
    osc_register_script('admin-sortable_lists', osc_current_admin_theme_js_url('plugins/jquery-sortable-lists.js'), 'jquery');
    osc_register_script('admin-sortable_lists-mobile', osc_current_admin_theme_js_url('plugins/jquery-sortable-lists-mobile.js'), 'jquery');
    osc_register_script('admin-dropzone', osc_current_admin_theme_js_url('plugins/dropzone.js'), 'jquery');
    osc_register_script('admin-equalize', osc_current_admin_theme_js_url('plugins/equalize.min.js'), 'jquery');
    osc_register_script('admin-fileinput', osc_current_admin_theme_js_url('plugins/jasny-fileinput.min.js'), 'jquery');
    osc_register_script('admin-fileinput-auto', osc_current_admin_theme_js_url('plugins/jasny-fileinput.auto.min.js'), 'jquery');
    osc_register_script('admin-tooltip', osc_current_admin_theme_js_url('plugins/zebra_tooltips.min.js'), 'jquery');
    osc_register_script('admin-osc', osc_current_admin_theme_js_url('osc.js'), 'jquery');
    osc_register_script('core-upgrade', osc_current_admin_theme_js_url('upgrade.js'), 'jquery');

    // enqueue scripts
    osc_enqueue_script('jquery');
    osc_enqueue_script('jquery-ui');
    osc_enqueue_script('admin-autocomplete');
    osc_enqueue_script('admin-popper');
    osc_enqueue_script('admin-moment');
    osc_enqueue_script('admin-bs_material');
    osc_enqueue_script('admin-sweetalert');
    osc_enqueue_script('admin-validate');
    osc_enqueue_script('admin-bs_selectpicker');
    osc_enqueue_script('admin-scrollbar');
    osc_enqueue_script('admin-bs_datepicker');
    osc_enqueue_script('admin-chartist');
    osc_enqueue_script('admin-chartist-pointlabels');
    osc_enqueue_script('admin-chartist-legend');
    osc_enqueue_script('admin-chart-canvas');
    osc_enqueue_script('admin-bs_notify');
    osc_enqueue_script('admin-bs_treeview');
    osc_enqueue_script('admin-sortable_lists');
    osc_enqueue_script('admin-sortable_lists-mobile');
    osc_enqueue_script('admin-dropzone');
    osc_enqueue_script('admin-equalize');
    osc_enqueue_script('admin-fileinput');
    osc_enqueue_script('admin-fileinput-auto');
    osc_enqueue_script('admin-tooltip');
    osc_enqueue_script('admin-osc');

    // evolution css styles
    osc_enqueue_style('admin-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons');
    osc_enqueue_style('admin-fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');
    osc_enqueue_style('jquery-ui', osc_current_admin_theme_styles_url('jquery-ui/jquery-ui.css?v=' . time()));
    osc_enqueue_style('admin-bs_treeview', osc_current_admin_theme_styles_url('hummingbird-treeview.css?v=' . time()));
    osc_enqueue_style('admin-dropzone', osc_current_admin_theme_styles_url('dropzone.css?v=' . time()));
    osc_enqueue_style('admin-tooltip', osc_current_admin_theme_styles_url('zebra_tooltips.css?v=' . time()));
    osc_enqueue_style('admin-sortable', osc_current_admin_theme_styles_url('sortable.css?v=' . time()));
    osc_enqueue_style('admin-dashboard', osc_current_admin_theme_styles_url('dashboard.css?v=' . time()));
} else {
    osc_enqueue_script('jquery');
    // register admin scripts
    osc_register_script('admin-osc', osc_current_admin_theme_js_url('osc.js'), 'jquery');
    osc_register_script('admin-ui-osc', osc_current_admin_theme_js_url('ui-osc.js'), 'jquery');
    osc_register_script('admin-location', osc_current_admin_theme_js_url('location.js'), 'jquery');
    osc_register_script('core-upgrade', osc_current_admin_theme_js_url('upgrade.js'), 'jquery');

    // enqueue scripts
    osc_enqueue_script('jquery-ui');
    osc_enqueue_script('admin-osc');
    osc_enqueue_script('admin-ui-osc');

    // enqueue css styles
    osc_enqueue_style('jquery-ui', osc_assets_url('css/jquery-ui/jquery-ui.css'));
    osc_enqueue_style('admin-dashboard', osc_current_admin_theme_styles_url('main.css'));

    osc_add_hook('admin_footer', array('FieldForm', 'i18n_datePicker') );
}

switch( Params::getParam('page') )
{
    case('items'):      require_once(osc_admin_base_path() . 'items.php');
        $do = new CAdminItems();
        $do->doModel();
        break;
    case('comments'):   require_once(osc_admin_base_path() . 'comments.php');
        $do = new CAdminItemComments();
        $do->doModel();
        break;
    case('media'):      require_once(osc_admin_base_path() . 'media.php');
        $do = new CAdminMedia();
        $do->doModel();
        break;
    case ('login'):     require_once(osc_admin_base_path() . 'login.php');
        $do = new CAdminLogin();
        $do->doModel();
        break;
    case('categories'): require_once(osc_admin_base_path() . 'categories.php');
        $do = new CAdminCategories();
        $do->doModel();
        break;
    case('emails'):     require_once(osc_admin_base_path() . 'emails.php');
        $do = new CAdminEmails();
        $do->doModel();
        break;
    case('pages'):      require_once(osc_admin_base_path() . 'pages.php');
        $do = new CAdminPages();
        $do->doModel();
        break;
    case('settings'):   require_once(osc_admin_base_path() . 'settings.php');
        $do = new CAdminSettings();
        $do->doModel();
        break;
    case('plugins'):    require_once(osc_admin_base_path() . 'plugins.php');
        $do = new CAdminPlugins();
        $do->doModel();
        break;
    case('languages'):  require_once(osc_admin_base_path() . 'languages.php');
        $do = new CAdminLanguages();
        $do->doModel();
        break;
    case('admins'):     require_once(osc_admin_base_path() . 'admins.php');
        $do = new CAdminAdmins();
        $do->doModel();
        break;
    case('users'):      require_once(osc_admin_base_path() . 'users.php');
        $do = new CAdminUsers();
        $do->doModel();
        break;
    case('ajax'):       require_once(osc_admin_base_path() . 'ajax/ajax.php');
        $do = new CAdminAjax();
        $do->doModel();
        break;
    case('appearance'): require_once(osc_admin_base_path() . 'appearance.php');
        $do = new CAdminAppearance();
        $do->doModel();
        break;
    case('tools'):      require_once(osc_admin_base_path() . 'tools.php');
        $do = new CAdminTools();
        $do->doModel();
        break;
    case('stats'):      require_once(osc_admin_base_path() . 'stats.php');
        $do = new CAdminStats();
        $do->doModel();
        break;
    case('cfields'):    require_once(osc_admin_base_path() . 'custom_fields.php');
        $do = new CAdminCFields();
        $do->doModel();
        break;
    case('upgrade'):    require_once(osc_admin_base_path() . 'upgrade.php');
        $do = new CAdminUpgrade();
        $do->doModel();
        break;
    case('market'):   require_once(osc_admin_base_path() . 'market.php');
        $do = new CAdminMarket();
        $do->doModel();
        break;
    default:            //login of oc-admin
        require_once(osc_admin_base_path() . 'main.php');
        $do = new CAdminMain();
        $do->doModel();
}

/* file end: ./oc-admin/index.php */