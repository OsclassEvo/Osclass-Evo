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
	return sprintf(__('Currencies &raquo; %s'), $string);
}

function addHelp() {
	echo '<p>' . __("Add new currencies or edit existing currencies so users can publish listings in their country's currency.") . '</p>';
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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=settings&action=currencies&type=add" class="btn btn-success"><i class="material-icons md-18">add</i> ' . __('Add currency') . '</a>';

$aCurrencies = __get('aCurrencies');
$aData = array();

foreach($aCurrencies as $currency) {
	$row = array();

	$row[] = '<div class="form-check">
                    <label class="form-check-label">
                        <input id="item-selected" class="form-check-input" type="checkbox" name="code[]" value="' . osc_esc_html($currency['pk_c_code']) . '"/>
                        <span class="form-check-sign">
                            <span class="check"></span>
                        </span>
                    </label>
                </div>';

	$btn_actions = '<a href="' . osc_admin_base_url(true) . '?page=settings&amp;action=currencies&amp;type=edit&amp;code=' . $currency['pk_c_code'] . '" rel="tooltip" class="btn btn-warning" title="' . __('Edit') . '"><i class="material-icons">edit</i></a>';

	$btn_actions .= '<a id="listing-delete" data-delete-type="currency" data-listing-id="' . $currency['pk_c_code'] . '" href="' . osc_admin_base_url(true) . '?page=settings&amp;action=currencies&amp;type=delete&amp;code=' . $currency['pk_c_code'] . '" rel="tooltip" class="btn btn-danger" title="' . __('Delete') . '"><i class="material-icons">delete</i></a>';

	$row[] = $currency['pk_c_code'];
	$row[] = $currency['s_name'];
	$row[] = $currency['s_description'];
	$row[] = $btn_actions;
	$aData[] = $row;
}
?>

<?php osc_current_admin_theme_path('parts/header.php'); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-md-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-12 col-xl-3 text-center text-md-left">
        <form class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
            <div class="form-group no-gutters">
                <div class="col-12">
                    <select id="bulk-actions" class="selectpicker show-tick" data-size="15" data-width="fit" data-style="btn btn-info btn-sm">
                        <option value=""><?php _e('Bulk actions'); ?></option>
                        <option value="delete_all" data-dialog-content="<?php printf(__('Are you sure you want to %s the selected currencies?'), strtolower(__('Delete'))); ?>"><?php _e('Delete'); ?></option>
                    </select>

                    <input id="bulk-actions-btn" type="button" data-bulk-type="currencies" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">attach_money</i>
        </div>
        <h4 class="card-title"><?php _e('Currencies'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="settings" />
                <input type="hidden" name="action" value="currencies" />
                <input type="hidden" name="type" value="delete" />

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
                        <th><?php _e('Code'); ?></th>
                        <th><?php _e('Name'); ?></th>
                        <th><?php _e('Description'); ?></th>
                        <th class="col-actions"><?php _e('Actions'); ?></th>
                    </thead>
                    <tbody>

                    <?php if( count($aData) > 0 ) { ?>
						<?php foreach($aData as $array): ?>
                            <tr>
								<?php foreach($array as $key => $value): ?>
									<td <?php if(!$key) echo 'class="col-bulkactions"'; ?><?php if($key == 4) echo 'class="col-actions"'; ?>>
                                        <?php echo $value; ?>
                                    </td>
								<?php endforeach; ?>
                            </tr>
						<?php endforeach; ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <p><?php _e('No data available in table'); ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="settings" />
    <input type="hidden" name="action" value="currencies" />
    <input type="hidden" name="type" value="delete" />
    <input type="hidden" name="code" value="" />
</form>

<?php osc_current_admin_theme_path('parts/footer.php'); ?>