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
    return sprintf(__('Upgrade &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Check to see if you're using the latest version of Osclass. If you're not, the system will let you know so you can update and use the newest features.") . '</p>';
}

function customPageHeader() {
    _e('Upgrade');
}

//customize Head
function customHead() {
    ?>
    <script type="text/javascript">
        //$(document).ready(function() {
        //    $("#steps_div").hide();
        //});
        //
        <?php
        //$perms = osc_save_permissions();
        //$ok    = osc_change_permissions();
        //foreach($perms as $k => $v) {
        //    @chmod($k, $v);
        //}
        //if( $ok ) {
        //?>
        //$(function() {
        //    var steps_div = document.getElementById('steps_div');
        //    steps_div.style.display = '';
        //    var steps = document.getElementById('steps');
        //    var version = <?php //echo osc_version(); ?>//;
        //    var fileToUnzip = '';
        //    steps.innerHTML += '<?php //echo osc_esc_js( sprintf( __('Checking for updates (Current version %s)'), osc_version() )); ?>// ';
        //
        //    $.getJSON("https://osclass.org/latest_version_v1.php?callback=?", function(data) {
        //        if(data.version <= version) {
        //            steps.innerHTML += '<?php //echo osc_esc_js( __('Congratulations! Your Osclass installation is up to date!')); ?>//';
        //        } else {
        //            steps.innerHTML += '<?php //echo osc_esc_js( __('New version to update:')); ?>// ' + oscEscapeHTML(data.version); + "<br />";
        //            <?php //if(Params::getParam('confirm')=='true') {?>
        //            steps.innerHTML += '<img id="loading_image" src="<?php //echo osc_current_admin_theme_url('images/loading.gif'); ?>//" /><?php //echo osc_esc_js(__('Upgrading your Osclass installation (this could take a while):')); ?>//';
        //
        //            var tempAr = data.url.split('/');
        //            fileToUnzip = tempAr.pop();
        //            $.getJSON('<?php //echo osc_admin_base_url(true); ?>//?page=ajax&action=upgrade&<?php //echo osc_csrf_token_url(); ?>//' , function(data) {
        //                if(data.error==0 || data.error==6) {
        //                    window.location = "<?php //echo osc_admin_base_url(true); ?>//?page=tools&action=version";
        //                }
        //                var loading_image = document.getElementById('loading_image');
        //                loading_image.style.display = "none";
        //                steps.innerHTML += $("<div>").text(data.message).html();+"<br />";
        //            });
        //            <?php //} else { ?>
        //            steps.innerHTML += '<input type="button" value="<?php //echo osc_esc_html( __('Upgrade')); ?>//" onclick="window.location.href=\'<?php //echo osc_admin_base_url(true); ?>//?page=tools&action=upgrade&confirm=true\';" />';
        //            <?php //} ?>
        //        }
        //    });
        //});
        <?php //} ?>
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
                <i class="material-icons">get_app</i>
            </div>
            <h4 class="card-title"><?php _e('Upgrade'); ?></h4>
        </div>

        <div class="card-body">
            <p><?php _e('This feature is under development now. Expect this in future updates.'); ?></p>
        </div>
    </div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>