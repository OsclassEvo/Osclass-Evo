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


    osc_enqueue_script('core-upgrade');
    $perms = osc_save_permissions();
    $ok    = osc_change_permissions();

    //customize Head
    function customHead(){
        ?>
        <script type="text/javascript">
            var upgrade_translation = {
                wait: '<?php _e('Please wait...'); ?>',
                completed: '<?php _e('Completed Successfully'); ?>',
                task_completed: '<?php _e('Task completed successfully'); ?>',
                next: '<?php _e('Next'); ?>',
                records: '<?php _e('Records'); ?>',
                files: '<?php _e('Files'); ?>',
                file_size: '<?php _e('File size'); ?>',
                progress: '<?php _e('Progress'); ?>',
                _of: '<?php _e('of'); ?>',
                archive_file: '<?php _e('Archiving a file'); ?>',
                download: '<?php _e('Downloading upgrades'); ?>',
                download_completed: '<?php _e('Download completed! File size:'); ?>',
                unpacking: '<?php _e('Extracting upgrades has started'); ?>',
                unpacking_completed: '<?php _e('Extracting completed!'); ?>',
                installation: '<?php _e('Upgrades installation started'); ?>',
                installation_completed: '<?php _e('Installation completed!'); ?>'
            };

            $(document).ready(function() {
                $("#bulk-actions-submit").click(function() {
                    $('#dialog-bulk-actions').dialog('close');
                    downloadUpgrades();
                });

                $("#dialog-bulk-actions").dialog({
                    autoOpen: false,
                    modal: true
                });

                $("#bulk-actions-cancel").click(function() {
                    // $("#datatablesForm").attr('data-dialog-open', 'false');
                    $('#dialog-bulk-actions').dialog('close');
                });

                $('body').on('click', '#upgrade-btn[upgrade-type="start"]', function() {
                    var is_backup = $('#backup').is(':checked');

                    if(is_backup) {
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=db_backup&" . osc_csrf_token_url(); ?>",
                            success: function(res) {
                                showDumpLogs(res);
                            }
                        });
                    } else {
                        $("#dialog-bulk-actions").dialog('open');

                        // Swal.fire({
                        //     title: osc.translations.msg_confirm_action,
                        //     text: 'Are you sure you want to start upgrading the system without backing up your data?',
                        //     type: 'warning',
                        //     buttonsStyling: false,
                        //     showCancelButton: true,
                        //     confirmButtonClass: "btn btn-success",
                        //     cancelButtonClass: "btn btn-danger",
                        //     confirmButtonText: osc.translations.msg_confirm,
                        //     cancelButtonText: osc.translations.msg_cancel,
                        // }).then((result) => {
                        //     if (result.value) {
                        //         downloadUpgrades();
                        //     }
                        // });
                    }
                });

                $('body').on('click', '#upgrade-btn[upgrade-type="backup"]', function() {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=script_informer&" . osc_csrf_token_url(); ?>",
                        success: function(res) {
                            var json = JSON.parse(res);
                            var files = eval(json.files);
                            var total = json.total_files;
                            var file_size;

                            $('#upgrade-btn').text(upgrade_translation.wait)
                                .attr('disabled', true);

                            $('#upgrade-processing-block').show();

                            setTimeout(function() {
                                file_size = archiveScript();
                            }, 2000);

                            oscUpgrade.log.processing(files, 25, total, upgrade_translation.archive_file + ':');

                            setTimeout(function() {
                                oscUpgrade.log.finished('download', upgrade_translation.files, total, file_size.responseText);
                            }, total * 25 + 1000);
                        }
                    });
                });

                $('body').on('click', '#upgrade-btn[upgrade-type="download"]', function() {
                    downloadUpgrades();
                });

                function showDumpLogs(dump_log) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=db_informer&" . osc_csrf_token_url(); ?>",
                        data: {'log' : dump_log},
                        success: function(res) {
                            var json = JSON.parse(res);
                            var logs = eval(json.logs);
                            var total = json.rows_count;

                            $('#backup').attr('disabled', true);
                            $('#upgrade-btn').text(upgrade_translation.wait)
                                .attr('disabled', true);

                            $('#upgrade-processing-block').show();

                            oscUpgrade.log.processing(logs, 500, total);

                            setTimeout(function() {
                                oscUpgrade.log.finished('backup', upgrade_translation.records, json.records, json.file_size,);
                            }, total * 500 + 1000);
                        }
                    });
                }

                function archiveScript() {
                    return $.ajax({
                        type: 'POST',
                        url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=script_backup&" . osc_csrf_token_url(); ?>",
                    });
                }

                function downloadUpgrades() {
                    var table = oscUpgrade.el('upgrade-processing-block table');

                    $.ajax({
                        type: 'POST',
                        url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=core-upgrade&" . osc_csrf_token_url(); ?>",
                        beforeSend: function() {
                            $('#backup').attr('disabled', true);
                            $('#upgrade-btn').text(upgrade_translation.wait)
                                .attr('disabled', true);

                            $('#upgrade-processing-block').show();
                            oscUpgrade.log.clear();
                            oscUpgrade.progressBar.hide();
                            table.append(oscUpgrade.log.row(oscUpgrade.preloader.show(upgrade_translation.download), oscUpgrade.strDate()));
                        },
                        success: function(res) {
                            var json = JSON.parse(res);

                            oscUpgrade.upgrade.download(json);
                        }
                    });
                }
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function render_offset(){
        return 'row-offset';
    }

    function addHelp() {
        echo '<p>' . __("Check to see if you're using the latest version of Osclass. If you're not, the system will let you know so you can update and use the newest features.") . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() { ?>
        <h1><?php _e('Tools'); ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Upgrade &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div>
    <div id="backup-settings" class="form-horizontal">
        <h2 class="render-title"><?php _e('Upgrade'); ?></h2>

        <?php if(osc_need_core_update(false)): ?>
            <div class="form-row row-offset">
                <div class="col-12">
                    <div class="alert flashmessage flashmessage-warning" style="display:block;">
                        <h2><?php _e('System upgrade required!'); ?></h2>
                        <?php printf(__('A new version of <strong>Osclass Evolution v.%s</strong> is available NOW!'), osc_get_latest_core_version(false)); ?>
                    </div>
                </div>
            </div>

            <form class="has-form-actions">
                <div class="form-row row-offset">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-md-12 mt-2">
                                <fieldset>
                                    <h2 class="render-title"><?php _e('Upgrading the system core'); ?></h2>

                                    <div id="upgrade-processing-block" class="hide">
                                        <div class="form-row row-offset">
                                            <div class="grid-60">
                                                <div id="logs-block" class="mark pre-scrollable">
                                                    <table class="table table-logs">
                                                        <thead class="text-muted">
                                                            <th class="grid-25"><?php _e('Date\Time'); ?></th>
                                                            <th><?php _e('Action'); ?></th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row row-offset">
                                            <div class="grid-60">
                                                <strong><?php _e('Status:'); ?></strong>
                                                <div id="progress-bar" class="progress progress-line-info"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid-60 make-backup">
                                        <div class="separate-top-medium">
                                            <label>
                                                <input id="backup" class="form-check-input" type="checkbox" name="backup_system" value="1">
                                                <?php _e('Make a backup of files and database before upgrading the system core'); ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="separate-top">
                                <button id="upgrade-btn" upgrade-type="start" type="button" class="btn btn-submit">
                                    <?php echo osc_esc_html( __('Start process') ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="form-horizontal">
                <div class="form-row">
                    <?php _e("You are using the latest version of Osclass Evolution. The update is not required."); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="dialog-bulk-actions" title="<?php _e('Confirm action'); ?>" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to start upgrading the system without backing up your data?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a id="bulk-actions-submit" href="javascript:void(0);" class="btn btn-red" ><?php echo osc_esc_html( __('Confirm') ); ?></a>
                <a id="bulk-actions-cancel" class="btn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>