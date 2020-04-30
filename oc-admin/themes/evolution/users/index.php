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
    return sprintf(__('Manage users &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Add, edit or delete information associated to registered users. Keep in mind that deleting a user also deletes all the listings the user published.') . '</p>';
}

function customPageHeader() {
    _e('Users');
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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=users&action=create" class="btn btn-success"><i class="material-icons md-18">add</i> ' . __('Add User') . '</a>';

$aData      = __get('aData');
$aRawRows   = __get('aRawRows');
$iDisplayLength = __get('iDisplayLength');
$sort       = Params::getParam('sort');
$direction  = Params::getParam('direction');

$columns    = $aData['aColumns'];
$rows       = $aData['aRows'];
$withFilters = __get('withFilters');
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>
<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-12 col-xl-3 mt-3 mt-sm-0">
        <form class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
            <div class="form-group no-gutters text-center text-sm-left">
                <div class="col-12">
                    <?php osc_print_bulk_actions('bulk-actions', '', __get('bulk_options'), 'selectpicker show-tick', 'data-size="15" data-width="fit" data-style="btn btn-info btn-sm"'); ?>
                    <input id="bulk-actions-btn" type="button" data-bulk-type="users" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-9 col-xl-6">
        <form class="form-inline items-per-page nocsrf text-left text-xl-right" method="get" action="<?php echo osc_admin_base_url(true); ?>">
            <?php foreach(Params::getParamsAsArray('get') as $key => $value): ?>
                <?php if($key != 'iDisplayLength'): ?>
                    <input type="hidden" name="<?php echo osc_esc_html($key); ?>" value="<?php echo osc_esc_html($value); ?>" />
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="form-group no-gutters text-center text-xl-right float-left float-xl-right">
                <div class="col-12">
                    <select class="selectpicker show-tick mr-0 mr-xl-3 mb-3 mb-sm-0" name="iDisplayLength"  onchange="this.form.submit();" data-style="select-with-transition" data-size="7">
                        <option value="10"><?php printf(__('%d Users'), 10); ?></option>
                        <option value="25" <?php if(Params::getParam('iDisplayLength') == 25) echo 'selected'; ?>><?php printf(__('%d Users'), 25); ?></option>
                        <option value="50" <?php if(Params::getParam('iDisplayLength') == 50) echo 'selected'; ?>><?php printf(__('%d Users'), 50); ?></option>
                        <option value="100" <?php if(Params::getParam('iDisplayLength') == 100) echo 'selected'; ?>><?php printf(__('%d Users'), 100); ?></option>
                    </select>

                    <?php if($withFilters) { ?>
                        <a id="btn-hide-filters" class="btn btn-sm btn-outline btn-outline-danger" href="<?php echo osc_admin_base_url(true).'?page=users'; ?>">
                            <?php _e('Reset filters'); ?>
                        </a>
                    <?php } ?>
                    <a id="btn-display-filters" href="javascript:;" class="btn btn-sm btn-outline <?php if($withFilters) echo 'btn-outline-info'; else echo 'btn-outline-info'; ?>"  data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#filtersModal">
                        <?php _e('Show filters'); ?>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="col-sm-3 col-xl-3">
        <form id="shortcut-filters" class="form-inline" style="display: inline!important;" method="get" action="<?php echo osc_admin_base_url(true); ?>">
            <input type="hidden" name="page" value="users" />
            <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength;?>" />

            <div class="form-group no-gutters">
                <div id="search-block" class="col-9 col-sm-8 col-xl-7 offset-xl-3 mr-2 mr-sm-0 autocomplete-search">
                    <input id="fUser" class="form-control w-100 pl-2" type="text" name="user" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                    <input id="fUserId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
                </div>

                <div class="col-2">
                    <button type="submit" class="btn btn-info btn-sm"><?php echo osc_esc_html( __('Find') ); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="filtersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Filters'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="display-filters" class="has-form-actions hide nocsrf">
                    <input type="hidden" name="page" value="users" />
                    <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength;?>" />
                    <input type="hidden" name="sort" value="<?php echo $sort; ?>" />
                    <input type="hidden" name="direction" value="<?php echo $direction; ?>" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('Email'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input id="s_email" class="form-control" type="text" name="s_email" value="<?php echo osc_esc_html(Params::getParam('s_email')); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="user"><?php _e('Country'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group autocomplete-search">
                                        <input id="countryName" class="form-control" name="countryName" type="text" value="<?php echo osc_esc_html(Params::getParam('countryName')); ?>" />
                                        <input id="countryId" name="countryId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('countryId')); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('Name'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input id="s_name" class="form-control" name="s_name" type="text" value="<?php echo osc_esc_html(Params::getParam('s_name')); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="b_premium"><?php _e('Region'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input id="region" class="form-control" name="region" type="text" value="<?php echo osc_esc_html(Params::getParam('region')); ?>" />
                                        <input id="regionId" name="regionId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('regionId')); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="user"><?php _e('Username'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group autocomplete-search">
                                        <input id="s_username" class="form-control" name="s_username" type="text" value="<?php echo osc_esc_html(Params::getParam('s_username')); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="b_active"><?php _e('City'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input id="city" class="form-control" name="city" type="text" value="<?php echo osc_esc_html(Params::getParam('city')); ?>" />
                                        <input id="cityId" name="cityId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('cityId')); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="b_active"><?php _e('Active'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <select id="b_active" class="selectpicker show-tick" data-size="7" data-width="100%" data-style="btn btn-info btn-sm" name="b_active">
                                            <option value=""  <?php echo ((Params::getParam('b_active') == '') ? 'selected="selected"' : '')?>><?php _e('Choose an option'); ?></option>
                                            <option value="1" <?php echo ((Params::getParam('b_active') == '1') ? 'selected="selected"' : '')?>><?php _e('ON'); ?></option>
                                            <option value="0" <?php echo ((Params::getParam('b_active') == '0') ? 'selected="selected"' : '')?>><?php _e('OFF'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label text-left text-sm-right" for="b_enabled"><?php _e('Block'); ?></label>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <select id="b_enabled" class="selectpicker show-tick" data-size="7" data-width="100%" data-style="btn btn-info btn-sm" name="b_enabled">
                                            <option value=""  <?php echo ((Params::getParam('b_enabled') == '') ? 'selected="selected"' : '')?>><?php _e('Choose an option'); ?></option>
                                            <option value="0" <?php echo ((Params::getParam('b_enabled') == '0') ? 'selected="selected"' : '')?>><?php _e('ON'); ?></option>
                                            <option value="1" <?php echo ((Params::getParam('b_enabled') == '1') ? 'selected="selected"' : '')?>><?php _e('OFF'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="$('#display-filters').submit();" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Apply filters') ); ?></button>
                <button type="button" onclick="location.href = '<?php echo osc_admin_base_url(true).'?page=users'; ?>'" class="btn btn-link"><?php _e('Reset filters'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">people_alt</i>
        </div>
        <h4 class="card-title"><?php _e('Manage users'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="users" />
                <input id="bulk_actions" type="hidden" name="action" value="" />

                <table class="table table-striped table-shopping">
                    <thead class="text-muted">
                    <?php foreach($columns as $k => $v) {
                        $hidden_cols = '';
                        if ($k == 'update_date') {
                            $hidden_cols = 'd-none d-xl-table-cell';
                        }

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
                                        if($k == 'update_date') $hidden_cols = 'd-none d-xl-table-cell';
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

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="users" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id[]" value="" />
</form>

<?php osc_current_admin_theme_path('parts/footer.php'); ?>