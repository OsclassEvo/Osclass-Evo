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
    return sprintf(__('Manage alerts &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Add, edit or delete information associated to alerts.') . '</p>';
}

function customPageHeader() {
    _e('Alerts');
}

function customHead() {
    ?>
    <script type="text/javascript">
        // autocomplete users
        $(document).ready(function(){
            $('#filter-select').change( function () {
                var option = $(this).find('option:selected').val();
                var html;

                if(option == 'oPattern') {
                    html = '<input id="fPattern" class="form-control w-100 pl-2" type="text" name="sSearch" value="" />';
                } else if(option == 'oUser'){
                    html = '<input id="fUser" class="form-control w-100 pl-2" type="text" name="user" value="" /><input id="fUserId" name="userId" type="hidden" value="" />';
                } else {
                    html = '<input id="fItemId" class="form-control w-100 pl-2" type="text" name="itemId" value="" />';
                }

                $('#search-block').html(html);
            });

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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=users&action=settings" class="btn btn-info btn-fab"><i class="material-icons md-24">settings</i></a>';

$aData      = __get('aData');
$aRawRows   = __get('aRawRows');
$sort       = Params::getParam('sort');
$direction  = Params::getParam('direction');

$columns    = $aData['aColumns'];
$rows       = $aData['aRows'];
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>
<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-12 col-sm-7 col-xl-9 text-center text-sm-left">
        <form class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
            <div class="form-group no-gutters">
                <div class="col-12">
                    <select name="alert_action" id="bulk-actions" class="selectpicker show-tick" data-size="15" data-width="fit" data-style="btn btn-info btn-sm">
                        <option value=""><?php _e('Bulk Actions'); ?></option>
                        <option value="activate" data-dialog-content="<?php printf(__('Are you sure you want to %s the selected alerts?'), strtolower(__('Activate'))); ?>"><?php _e('Activate'); ?></option>
                        <option value="deactivate" data-dialog-content="<?php printf(__('Are you sure you want to %s the selected alerts?'), strtolower(__('Deactivate'))); ?>"><?php _e('Deactivate'); ?></option>
                        <option value="delete" data-dialog-content="<?php printf(__('Are you sure you want to %s the selected alerts?'), strtolower(__('Delete'))); ?>"><?php _e('Delete'); ?></option>
                    </select>

                    <input id="bulk-actions-btn" type="button" data-bulk-type="users" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                </div>
            </div>
        </form>
    </div>

    <div class="col-12 col-sm-5 col-xl-3">
        <form id="shortcut-filters" class="form-inline" style="display: inline!important;" method="get" action="<?php echo osc_admin_base_url(true); ?>">
            <input type="hidden" name="page" value="users" />
            <input type="hidden" name="action" value="alerts" />

            <div class="form-group no-gutters">
                <div id="search-block" class="col-9 col-xl-7 offset-xl-3 autocomplete-search">
                    <input id="fPattern" class="form-control w-100 pl-2" type="text" name="sSearch" value="<?php echo osc_esc_html(Params::getParam('sSearch')); ?>" />
                </div>

                <div class="col-3 col-xl-2 text-right text-xl-left">
                    <button type="submit" class="btn btn-info btn-sm"><?php echo osc_esc_html( __('Find') ); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">warning</i>
        </div>
        <h4 class="card-title"><?php _e('Manage alerts'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="users" />
                <input type="hidden" name="action" value="status_alerts" />
                <input id="bulk_actions" type="hidden" name="status" value="0" />

                <table class="table table-striped table-shopping">
                    <thead class="text-muted">
                    <?php foreach($columns as $k => $v) {
                        echo '<th class="col-' . $k . '">' . $v . '</th>';
                    }; ?>
                    </thead>
                    <tbody>
                    <?php if( count($rows) > 0 ) { ?>
                        <?php foreach($rows as $key => $row) { ?>
                            <tr class="<?php echo implode(' ', osc_apply_filter('datatable_listing_class', array(), $aRawRows[$key], $row)); ?>">
                                <?php foreach($row as $k => $v) { ?>
                                    <td class="col-<?php echo $k; ?>"><?php echo $v; ?></td>
                                <?php }; ?>
                            </tr>
                        <?php }; ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="<?php echo count($columns); ?>" class="text-center">
                                <p><?php _e('No data available in table'); ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>

            <div class="row no-gutters">
                <div class="col-md-12">
                    <?php
                    function showingResults() {
                        $aData = __get("aData");
                        echo '<ul class="showing-results"><li><span>'.osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aRows']), $aData['iTotalDisplayRecords'], $aData['iTotalRecords']).'</span></li></ul>';
                    }
                    osc_add_hook('before_show_pagination_admin','showingResults');
                    osc_show_pagination_admin($aData);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="alert-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="users" />
    <input type="hidden" name="action" value="delete_alerts" />
    <input type="hidden" name="alert_id[]" id="alert_id" value="" />
    <input type="hidden" name="alert_user_id" value="" />
</form>

<?php osc_current_admin_theme_path('parts/footer.php'); ?>