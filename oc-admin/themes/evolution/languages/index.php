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
    return sprintf(__('Languages &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Add, edit or delete the language in which your Osclass is displayed, both the part that's viewable by users and the admin panel.") . '</p>';
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // check_all bulkactions
            $("#check_all").change(function(){
                var isChecked = $(this).prop("checked");
                $('.col-bulkactions input').each( function() {
                    if( isChecked == 1 ) {
                        this.checked = true;
                    } else {
                        this.checked = false;
                    }
                });
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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=languages&amp;action=add" class="btn btn-success"><i class="material-icons md-18">add</i> ' . __('Add language') . '</a>';

$iDisplayLength = __get('iDisplayLength');
$aData          = __get('aLanguages');
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-md-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-md-5 col-xl-3 text-center text-md-left">
        <form class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
            <div class="form-group no-gutters">
                <div class="col-12">
                    <?php osc_print_bulk_actions('bulk-actions', '', __get('bulk_options'), 'selectpicker show-tick', 'data-size="15" data-width="fit" data-style="btn btn-info btn-sm"'); ?>
                    <input id="bulk-actions-btn" type="button" data-bulk-type="languages" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">public</i>
        </div>
        <h4 class="card-title"><?php _e('Manage Languages'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="languages" />
                <input id="bulk_actions" type="hidden" name="action" value="" />

                <table class="table table-striped table-shopping">
                    <thead class="text-muted">
                        <th class="col-bulkactions">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input id="check_all" class="form-check-input" type="checkbox" />
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </th>
                        <th><?php _e('Name'); ?></th>
                        <th><?php _e('Short name'); ?></th>
                        <th><?php _e('Description'); ?></th>
                        <th><?php _e('Enabled (website)'); ?></th>
                        <th><?php _e('Enabled (oc-admin)'); ?></th>
                        <th class="col-actions"><?php _e('Actions'); ?></th>
                    </thead>
                    <tbody>
                    <?php if( count($aData['aaData']) > 0 ) { ?>
                        <?php foreach($aData['aaData'] as $array) { ?>
                            <tr>
                                <?php foreach($array as $key => $value) { ?>
                                    <td <?php if(!$key) echo 'class="col-bulkactions"'; if($key == 6) echo 'class="col-actions"'; ?>><?php echo $value; ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <p><?php _e('No data available in table'); ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>

            <div class="row no-gutters">
                <div class="col-md-12">
                    <?php osc_show_pagination_admin($aData); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="languages" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id[]" value="" />
</form>

<?php osc_current_admin_theme_path('parts/footer.php'); ?>