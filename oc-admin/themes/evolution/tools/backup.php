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
    return sprintf(__('Backup &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Save a backup of all of your site's information: listings, users and configuration. You can save a backup on your server or on your computer.") . '</p>';
}

function customPageHeader() {
    _e('Backup');
}

//customize Head
function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('click', '#backup_submit_btn', function() {
                var backup_type = $(this).attr('backup-type');

                $('input[name="action"]').val('backup-' + backup_type);
                $('#backup_form').submit();
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
            <i class="material-icons">cloud_download</i>
        </div>
        <h4 class="card-title"><?php _e('Backup'); ?></h4>
    </div>

    <div class="card-body">
        <form id="backup_form" name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions">
            <input type="hidden" name="page" value="tools" />
            <input type="hidden" name="action" value="" />

            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row no-gutters">
                                <label class="col-12 col-xl-1 col-form-label text-left"><?php _e('Backup folder'); ?></label>
                                <div class="col-12 col-xl-10">
                                    <input type="text" class="form-control w-50 text-center d-inline" name="bck_dir" value="<?php echo osc_esc_html(osc_base_path()); ?>" />

                                    <small class="form-text text-muted">
                                        <?php _e("<strong>WARNING</strong>: If you don't specify a backup folder, the backup files will be created in the root of your Osclass installation."); ?>
                                        <br>
                                        <?php _e("This is the folder in which your backups will be created. We recommend that you choose a non-public path."); ?>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4 text-center text-sm-left">
                            <button id="backup_submit_btn" backup-type="sql" type="button" class="btn btn-info">
                                <?php echo osc_esc_html( __('Backup SQL (store on server)') ); ?>
                                <div class="ripple-container"></div>
                            </button>

                            <button id="backup_submit_btn" backup-type="sql_file" type="button" class="btn btn-info">
                                <?php echo osc_esc_html( __('Backup SQL (download file)') ); ?>
                                <div class="ripple-container"></div>
                            </button>

                            <button id="backup_submit_btn" backup-type="zip" type="button" class="btn btn-info">
                                <?php echo osc_esc_html( __('Backup files (store on server)') ); ?>
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