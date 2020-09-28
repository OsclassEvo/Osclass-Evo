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
            installation: '<?php _e('Upgrades installation started'); ?>'
        };

        $(document).ready(function() {
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
                    Swal.fire({
                        title: osc.translations.msg_confirm_action,
                        text: 'Are you sure you want to start upgrading the system without backing up your data?',
                        type: 'warning',
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonClass: "btn btn-success",
                        cancelButtonClass: "btn btn-danger",
                        confirmButtonText: osc.translations.msg_confirm,
                        cancelButtonText: osc.translations.msg_cancel,
                    }).then((result) => {
                        if (result.value) {
                            downloadUpgrades();
                        }
                    });
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
            <?php if(osc_need_core_update(false)): ?>
                <div class="row no-gutters">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h4><?php _e('System upgrade required!'); ?></h4>
                            <?php printf(__('A new version of <strong>Osclass Evolution v.%s</strong> is available NOW!'), osc_get_latest_core_version(false)); ?>
                        </div>
                    </div>
                </div>

                <form class="has-form-actions">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <fieldset>
                                        <legend class="d-block mb-3"><?php _e('Upgrading the system core'); ?></legend>

                                        <div id="upgrade-processing-block" class="fc-limited">
                                            <div class="row no-gutters">
                                                <div class="col-xl-12">
                                                    <div id="logs-block" class="mark p-3 w-100 w-xl-50 pre-scrollable">
                                                        <div class="table-responsive">
                                                            <table class="table table-logs">
                                                                <thead class="text-muted">
                                                                <th class="w-25"><?php _e('Date\Time'); ?></th>
                                                                <th><?php _e('Action'); ?></th>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row no-gutters">
                                                <div class="col-md-12 col-xl-6 mt-3">
                                                    <strong><?php _e('Status:'); ?></strong>
                                                    <div id="progress-bar" class="progress progress-line-info"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3 mb-2 make-backup">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input id="backup" class="form-check-input" type="checkbox" name="backup_system" value="1">
                                                    <?php _e('Make a backup of files and database before upgrading the system core'); ?>

                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button id="upgrade-btn" upgrade-type="start" type="button" class="btn btn-info">
                                        <?php echo osc_esc_html( __('Start process') ); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <p><?php _e('You are using the latest version of Osclass Evolution. The update is not required.'); ?></p>
            <?php endif; ?>
        </div>
    </div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>