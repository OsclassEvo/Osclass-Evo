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
    return sprintf(__('Add plugin &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Install or uninstall the plugins available in your installation. In some cases, you\'ll have to configure the plugin in order to get it to work.') . '</p>';
}

function customPageHeader() {
    _e('Plugins');
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
            <i class="material-icons">add</i>
        </div>
        <h4 class="card-title"><?php _e('Add plugin'); ?></h4>
    </div>

    <div class="card-body">
        <?php if(is_writable(osc_plugins_path())): ?>
            <form name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data" class="has-form-actions">
                <input type="hidden" name="action" value="add_post" />
                <input type="hidden" name="page" value="plugins" />

                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row no-gutters">
                                    <label class="col-12 col-sm-3 col-xl-1 col-form-label text-left"><?php _e('Plugin package (.zip)'); ?></label>
                                    <div class="col-12 col-sm-9 col-xl-10">
                                        <input id="package" type="file" name="package">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>

                                <button type="submit" class="btn btn-info">
                                    <?php echo osc_esc_html( __('Upload') ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="alert alert-danger"><?php _e("Cannot install new plugin"); ?></div>
                    <p><?php _e("The plugin folder is not writable on your server so you cannot upload plugins from the administration panel. Please make the folder writable and try again."); ?></p>
                    <p><?php _e('To make the directory writable under UNIX execute this command from the shell:'); ?></p>
                    <pre class="mark rounded p-3 w-100">chmod 0755 <?php echo osc_plugins_path(); ?></pre>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div id="market_installer" class="has-form-actions d-none">
        <form action="" method="post">
            <input type="hidden" name="market_code" id="market_code" value="" />
            <div class="osc-modal-content-market">
                <img src="" id="market_thumb" class="float-left"/>
                <table class="table" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr class="table-first-row">
                        <td><?php _e('Name'); ?></td>
                        <td><span id="market_name"><?php _e("Loading data"); ?></span></td>
                    </tr>
                    <tr class="even">
                        <td><?php _e('Version'); ?></td>
                        <td><span id="market_version"><?php _e("Loading data"); ?></span></td>
                    </tr>
                    <tr>
                        <td><?php _e('Author'); ?></td>
                        <td><span id="market_author"><?php _e("Loading data"); ?></span></td>
                    </tr>
                    <tr class="even">
                        <td><?php _e('URL'); ?></td>
                        <td><span id="market_url_span"><a id="market_url" href="#"><?php _e("Download manually"); ?></a></span></td>
                    </tr>
                    </tbody>
                </table>
                <div class="clear"></div>
            </div>
            <div class="form-actions">
                <div class="wrapper">
                    <button id="market_cancel" class="btn btn-red" ><?php _e('Cancel'); ?></button>
                    <button id="market_install" class="btn btn-submit" ><?php _e('Continue install'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>