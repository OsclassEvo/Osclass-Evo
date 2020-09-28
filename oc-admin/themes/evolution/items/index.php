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
    return sprintf(__('Manage listings &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Manage all the listings on your site: edit, delete or block the latest listings published. You can also filter by several parameters: user, region, city, etc.') . '</p>';
}

function customPageHeader() {
    _e('Manage listings');
}

function customHead() {
    ItemForm::location_javascript_new('admin');
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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=items&action=settings" class="btn btn-info btn-fab"><i class="material-icons md-24">settings</i></a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=items&action=post" class="btn btn-success"><i class="material-icons md-18">add</i> ' . __('Add listing') . '</a>';

$categories  = __get('categories');
$withFilters = __get('withFilters');

$iDisplayLength = __get('iDisplayLength');

$aData      = __get('aData');
$aRawRows   = __get('aRawRows');
$sort       = Params::getParam('sort');
$direction  = Params::getParam('direction');

$columns    = $aData['aColumns'];
$rows       = $aData['aRows'];
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>
    <div class="row no-gutters">
        <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
    </div>

    <div class="row no-gutters">
        <div class="col-12 text-center text-sm-left col-md-4 col-lg-4 col-xl-3">
            <form class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <div class="form-group no-gutters">
                    <div class="col-xl-12">
                        <?php osc_print_bulk_actions('bulk-actions', '', __get('bulk_options'), 'selectpicker show-tick', 'data-size="15" data-width="fit" data-style="btn btn-info btn-sm"'); ?>
                        <input id="bulk-actions-btn" type="button" data-bulk-type="listings" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-8 col-lg-8 pr-lg-0 col-xl-5 pr-xl-3">
            <form class="form-inline items-per-page nocsrf mb-md-0" method="get" action="<?php echo osc_admin_base_url(true); ?>">
                <?php foreach(Params::getParamsAsArray('get') as $key => $value): ?>
                    <?php if($key != 'iDisplayLength'): ?>
                        <input type="hidden" name="<?php echo osc_esc_html($key); ?>" value="<?php echo osc_esc_html($value); ?>" />
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="form-group no-gutters">
                    <div class="col-12 col-md-<?php if($withFilters) echo 5; else echo 9; ?> col-xl-<?php if($withFilters) echo 7; else echo 9; ?> text-center text-sm-right pr-2">
                        <select class="selectpicker show-tick" name="iDisplayLength"  onchange="this.form.submit();" data-style="select-with-transition" data-size="7">
                            <option value="10"><?php printf(__('%d Listings'), 10); ?></option>
                            <option value="25" <?php if(Params::getParam('iDisplayLength') == 25) echo 'selected'; ?>><?php printf(__('%d Listings'), 25); ?></option>
                            <option value="50" <?php if(Params::getParam('iDisplayLength') == 50) echo 'selected'; ?>><?php printf(__('%d Listings'), 50); ?></option>
                            <option value="100" <?php if(Params::getParam('iDisplayLength') == 100) echo 'selected'; ?>><?php printf(__('%d Listings'), 100); ?></option>
                        </select>
                    </div>

                    <div class="col-12 col-md-<?php if($withFilters) echo 7; else echo 3; ?> col-xl-<?php if($withFilters) echo 5; else echo 3; ?> text-center text-sm-right">
                        <?php if($withFilters) { ?>
                            <a id="btn-hide-filters" class="btn btn-sm btn-outline btn-outline-danger" href="<?php echo osc_admin_base_url(true).'?page=items'; ?>">
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

        <div class="col-12 col-xl-4">
            <?php Params::getParam('shortcut-filter') ? $opt = Params::getParam('shortcut-filter') : $opt = "oPattern"; ?>

            <form id="shortcut-filters" class="form-inline" style="display: inline!important;" method="get" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="items" />
                <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength;?>" />

                <div class="form-group no-gutters">
                    <div class="col-4 col-md-2 col-xl-3 text-lg-left text-xl-right">
                        <select id="filter-select" name="shortcut-filter" class="selectpicker show-tick" data-size="7" data-width="fit" data-style="btn btn-round btn-info btn-sm">
                            <option value="oPattern" <?php if($opt == 'oPattern') echo 'selected="selected"'; ?>><?php _e('Pattern'); ?></option>
                            <option value="oUser" <?php if($opt == 'oUser') echo 'selected="selected"'; ?>><?php _e('Email'); ?></option>
                            <option value="oItemId" <?php if($opt == 'oItemId') echo 'selected="selected"'; ?>><?php _e('Item ID'); ?></option>
                        </select>
                    </div>

                    <div id="search-block" class="col-6 col-md-9 col-xl-7 autocomplete-search">
                        <?php if($opt == 'oPattern'): ?>
                            <input id="fPattern" class="form-control w-100 pl-2" type="text" name="sSearch" value="<?php echo osc_esc_html(Params::getParam('sSearch')); ?>" />
                        <?php endif; ?>

                        <?php if($opt == 'oUser'): ?>
                            <input id="fUser" class="form-control w-100 pl-2" type="text" name="user" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                            <input id="fUserId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
                        <?php endif; ?>

                        <?php if($opt == 'oItemId'): ?>
                            <input id="fItemId" class="form-control w-100 pl-2" type="text" name="itemId" value="<?php echo osc_esc_html(Params::getParam('itemId')); ?>" />
                        <?php endif; ?>
                    </div>

                    <div class="col-2 col-md-1 col-xl-2 text-lg-right text-xl-left">
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
                        <input type="hidden" name="page" value="items" />
                        <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength;?>" />
                        <input type="hidden" name="sort" value="<?php echo $sort; ?>" />
                        <input type="hidden" name="direction" value="<?php echo $direction; ?>" />

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('Pattern'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input id="sSearch" class="form-control" type="text" name="sSearch" value="<?php echo osc_esc_html(Params::getParam('sSearch')); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="user"><?php _e('Email'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group autocomplete-search">
                                            <input id="user" class="form-control" type="text" name="user" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                                            <input id="userId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('Category'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <?php ManageItemsForm::category_select($categories, null, null, true, 'selectpicker show-tick', 'data-size="15" data-width="100%" data-style="btn btn-info btn-sm"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="b_premium"><?php _e('Premium'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <select id="b_premium" class="selectpicker show-tick" data-size="7" data-width="100%" data-style="btn btn-info btn-sm" name="b_premium">
                                                <option value=""  <?php echo ((Params::getParam('b_premium') == '') ? 'selected="selected"' : '')?>><?php _e('Choose an option'); ?></option>
                                                <option value="1" <?php echo ((Params::getParam('b_premium') == '1') ? 'selected="selected"' : '')?>><?php _e('ON'); ?></option>
                                                <option value="0" <?php echo ((Params::getParam('b_premium') == '0') ? 'selected="selected"' : '')?>><?php _e('OFF'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('Country'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group autocomplete-search">
                                            <?php ManageItemsForm::country_text('form-control'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
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
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('Region'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group autocomplete-search">
                                            <?php ManageItemsForm::region_text('form-control'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="b_enabled"><?php _e('Block'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <select id="b_enabled" class="selectpicker show-tick" data-size="7" data-width="100%" data-style="btn btn-info btn-sm" name="b_enabled">
                                                <option value=""  <?php echo ((Params::getParam('b_enabled') == '') ? 'selected="selected"' : '')?>><?php _e('Choose an option'); ?></option>
                                                <option value="0" <?php echo ((Params::getParam('b_enabled') == '0') ? 'selected="selected"' : '')?>><?php _e('ON'); ?></option>
                                                <option value="1" <?php echo ((Params::getParam('b_enabled') == '1') ? 'selected="selected"' : '')?>><?php _e('OFF'); ?></option>
                                                <option value="moderation" <?php echo ((Params::getParam('b_enabled') == 'moderation') ? 'selected="selected"' : '')?>><?php _e('UNDER MODERATION ONLY'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="sSearch"><?php _e('City'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group autocomplete-search">
                                            <?php ManageItemsForm::city_text('form-control'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-left text-sm-right" for="b_spam"><?php _e('Spam'); ?></label>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <select id="b_spam" class="selectpicker show-tick" data-size="7" data-width="100%" data-style="btn btn-info btn-sm" name="b_spam">
                                                <option value=""  <?php echo ((Params::getParam('b_spam') == '') ? 'selected="selected"' : '')?>><?php _e('Choose an option'); ?></option>
                                                <option value="1" <?php echo ((Params::getParam('b_spam') == '1') ? 'selected="selected"' : '')?>><?php _e('ON'); ?></option>
                                                <option value="0" <?php echo ((Params::getParam('b_spam') == '0') ? 'selected="selected"' : '')?>><?php _e('OFF'); ?></option>
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
                    <button type="button" onclick="location.href = '<?php echo osc_admin_base_url(true).'?page=items'; ?>'" class="btn btn-link"><?php _e('Reset filters'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-rose card-header-icon">
            <div class="card-icon">
                <i class="material-icons">view_list</i>
            </div>
            <h4 class="card-title"><?php _e('Manage listings'); ?></h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                    <input type="hidden" name="page" value="items" />
                    <input type="hidden" name="action" value="bulk_actions" />
                    <input id="bulk_actions" type="hidden" name="bulk_actions" value="" />

                    <table class="table table-striped table-shopping">
                        <thead class="text-muted">
                        <?php foreach($columns as $k => $v) {
                            $hidden_cols = '';
                            if($k == 'expiration') $hidden_cols = 'd-none d-xl-table-cell';

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
                                            if($k == 'expiration') $hidden_cols = 'd-none d-xl-table-cell';
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
                    <div class="col-12">
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
        <input type="hidden" name="page" value="items" />
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id[]" value="" />
    </form>

<?php osc_current_admin_theme_path('parts/footer.php'); ?>