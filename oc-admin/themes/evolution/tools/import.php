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
    return sprintf(__('Import &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Upload registers from other Osclass installations or upload new geographic information to your site. <strong>Be careful</strong>: don’t use this option if you're not 100% sure what you're doing.") . '</p>';
}

function customPageHeader() {
    _e('Import');
}

//customize Head
function customHead() {}

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
            <i class="material-icons">cloud_upload</i>
        </div>
        <h4 class="card-title"><?php _e('Import'); ?></h4>
    </div>

    <div class="card-body">
        <form name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" enctype="multipart/form-data" class="has-form-actions">
            <input type="hidden" name="page" value="tools" />
            <input type="hidden" name="action" value="import_post" />

            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row no-gutters">
                                <label class="col-12 col-sm-3 col-xl-1 col-form-label text-left"><?php _e('File (.sql)'); ?></label>
                                <div class="col-12 col-sm-9 col-xl-10">
                                    <input id="import-data" type="file" name="sql">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-info">
                                <?php echo osc_esc_html( __('Import data') ); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>