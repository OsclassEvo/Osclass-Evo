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
        return sprintf(__('Reported listings &raquo; %s'), $string);
    }

    function addHelp() {
        echo '<p>' . __('From here, you can edit or delete the listings reported by users (spam, misclassified, duplicate, expired, offensive). You can also delete the report if you consider it mistaken.') . '</p>';
    }

    function customPageHeader() {
        _e('Reported listings');
    }

    function customHead() { ?>
        <script type="text/javascript">
            // autocomplete users
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
    $header_menu = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';

    $aData      = __get('aData');

    $columns    = $aData['aColumns'];
    $rows       = $aData['aRows'];
    $sort       = Params::getParam('sort');
    $direction  = Params::getParam('direction');
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>
<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-md-7 col-lg-8">
        <form class="form-inline text-center text-sm-left" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
            <div class="form-group no-gutters">
                <div class="col-12">
                    <select id="bulk-actions" name="" class="selectpicker show-tick" data-size="15" data-width="fit" data-style="btn btn-info btn-sm" tabindex="-98">
                        <option value=""><?php _e('Bulk actions'); ?></option>
                        <option value="delete_all" data-dialog-content="<?php _e('Are you sure you want to Delete the selected items?'); ?>"><?php _e('Delete'); ?></option>
                        <option value="clear_all" data-dialog-content="<?php _e('Are you sure you want to clear all the reportings of the selected items?'); ?>"><?php _e('Clear All'); ?></option>
                        <option value="clear_spam_all" data-dialog-content="<?php _e('Are you sure you want to clear the spam reportings of the selected items?'); ?>"><?php _e('Clear Spam'); ?></option>
                        <option value="clear_bad_all" data-dialog-content="<?php _e('Are you sure you want to clear the misclassified reportings of the selected items?'); ?>"><?php _e('Clear Missclassified'); ?></option>
                        <option value="clear_dupl_all" data-dialog-content="<?php _e('Are you sure you want to clear the duplicated reportings of the selected items?'); ?>"><?php _e('Clear Duplicated'); ?></option>
                        <option value="clear_expi_all" data-dialog-content="<?php _e('Are you sure you want to clear the expired reportings of the selected items?'); ?>"><?php _e('Clear Expired'); ?></option>
                        <option value="clear_offe_all" data-dialog-content="<?php _e('Are you sure you want to clear the offensive reportings of the selected items?'); ?>"><?php _e('Clear Offensive'); ?></option>
                    </select>
                    <input id="bulk-actions-btn" type="button" data-bulk-type="listings" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-5 col-lg-4">
        <form class="form-inline text-center text-sm-right items-per-page nocsrf" method="get" action="<?php echo osc_admin_base_url(true); ?>">
            <?php foreach(Params::getParamsAsArray('get') as $key => $value): ?>
                <?php if($key != 'iDisplayLength'): ?>
                    <input type="hidden" name="<?php echo osc_esc_html($key); ?>" value="<?php echo osc_esc_html($value); ?>" />
                <?php endif; ?>
            <?php endforeach; ?>

            <select class="selectpicker show-tick" name="iDisplayLength"  onchange="this.form.submit();" data-style="select-with-transition" data-size="7">
                <option value="10"><?php printf(__('%d Listings'), 10); ?></option>
                <option value="25" <?php if(Params::getParam('iDisplayLength') == 25) echo 'selected'; ?>><?php printf(__('%d Listings'), 25); ?></option>
                <option value="50" <?php if(Params::getParam('iDisplayLength') == 50) echo 'selected'; ?>><?php printf(__('%d Listings'), 50); ?></option>
                <option value="100" <?php if(Params::getParam('iDisplayLength') == 100) echo 'selected'; ?>><?php printf(__('%d Listings'), 100); ?></option>
            </select>
        </form>
    </div>
</div>

<!-- REPORTED ITEMS -->
<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">view_list</i>
        </div>
        <h4 class="card-title"><?php _e('Reported listings'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="items" />
                <input type="hidden" name="action" value="bulk_actions" />
                <input type="hidden" name="bulk_actions" value="" />

                <table class="table table-striped table-shopping">
                    <thead class="text-muted">
                        <?php foreach($columns as $k => $v) {
                            $hidden_cols = '';
                            if($k == 'date' || $k == 'expiration') $hidden_cols = 'd-none d-xl-table-cell';

                            echo '<th class="col-' . $k . ' ' . $hidden_cols . '">' . $v . ' ' . ($sort == $k ? ($direction == 'desc' ? '<i class="material-icons table-header-icons">
arrow_drop_down</i>' : '<i class="material-icons table-header-icons">arrow_drop_up</i>') : '') . '</th>';
                        }; ?>
                    </thead>
                    <tbody>
                        <?php if( count($rows) > 0 ) { ?>
                            <?php foreach($rows as $key => $row) { ?>
                                <tr class="<?php echo implode(' ', osc_apply_filter('datatable_listing_class', array(), $aRawRows[$key], $row)); ?>">
                                    <?php foreach($row as $k => $v) { ?>
                                        <?php
                                            $hidden_cols = '';
                                            if($k == 'date' || $k == 'expiration') $hidden_cols = 'd-none d-xl-table-cell';
                                        ?>
                                        <td class="col-<?php echo $k . ' ' . $hidden_cols; ?>"><?php echo $v; ?></td>
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
                    <?php osc_show_pagination_admin($aData); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #REPORTED ITEMS -->

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="items" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id[]" value="" />
</form>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>