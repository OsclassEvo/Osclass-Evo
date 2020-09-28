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
    return sprintf(__('Add language &raquo; %s'), $string);
}

function customPageHeader() {
    _e('Settings');
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">add</i>
        </div>
        <h4 class="card-title"><?php _e('Add language'); ?></h4>
    </div>

    <div class="card-body">
        <?php if(is_writable(osc_translations_path())): ?>
            <form name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data" class="has-form-actions">
                <input type="hidden" name="action" value="add_post" />
                <input type="hidden" name="page" value="languages" />

                <div class="row no-gutters d-none">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <?php printf( __('Download more languages at %s'), '<a class="text-warning" href="'.osc_admin_base_url(true) . '?page=market&action=languages">Market</a>'); ?>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row no-gutters">
                                    <label class="col-12 col-sm-3 col-xl-1 col-form-label text-left"><?php _e('Language package (.zip)'); ?></label>
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
                    <div class="alert alert-danger"><?php _e("Can't install a new language"); ?></div>
                    <p><?php _e("The translations folder is not writable on your server so you can't upload translations from the administration panel. Please make the translation folder writable and try again."); ?></p>
                    <p><?php _e('To make the directory writable under UNIX execute this command from the shell:'); ?></p>
                    <pre class="mark rounded p-3 w-100">chmod 0755 <?php echo osc_translations_path(); ?></pre>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>