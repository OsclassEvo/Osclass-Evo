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
        return sprintf(__('Comments &raquo; %s'), $string);
    }
    
    function addHelp() {
        echo '<p>' . __('Manage the comments that users publish on the listings on your site. You can also edit, delete, activate or block comments.') . '</p>';
    }
    
    function customPageHeader() {
        _e('Comments');
    }

    //customize Head
    function customHead() { ?>
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
    $header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=settings&action=comments" class="btn btn-info btn-fab"><i class="material-icons md-24">settings</i></a>';

    if(Params::getParam('showAll') != 'off') {
        $header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=comments&showAll=off" class="btn btn-danger"><i class="material-icons md-18">visibility_off</i> ' . __('Hidden comments') . '</a>';
    } else {
        $header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=comments" class="btn btn-success"><i class="material-icons md-18">visibility</i> ' . __('All comments') . '</a>';
    }
    

    $aData      = __get('aData');
    $aRawRows   = __get('aRawRows');
    $sort       = Params::getParam('sort');
    $direction  = Params::getParam('direction');

    $columns    = $aData['aColumns'];
    $rows       = $aData['aRows'];
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-md-12">
        <form class="form-inline text-center text-sm-left" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
            <div class="form-group no-gutters">
                <div class="col-12">
                    <?php osc_print_bulk_actions('bulk-actions', '', __get('bulk_options'), 'selectpicker show-tick', 'data-size="15" data-width="fit" data-style="btn btn-info btn-sm"'); ?>
                    <input id="bulk-actions-btn" type="button" data-bulk-type="comments" class="btn btn-info btn-sm" value="<?php echo osc_esc_html( __('Apply') ); ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">comment</i>
        </div>
        <h4 class="card-title"><?php _e('Comments'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <form id="bulk-actions-form" class="form-inline" method="post"  style="display: inline!important;" action="<?php echo osc_admin_base_url(true); ?>">
                <input type="hidden" name="page" value="comments" />
                <input type="hidden" name="action" value="bulk_actions" />
                <input id="bulk_actions" type="hidden" name="bulk_actions" value="" />
                
                <table class="table table-striped table-shopping">
                    <thead class="text-muted">
                        <?php foreach($columns as $k => $v) {
                            switch($k) {
                                case 'status':
                                case 'bulkactions':
                                    $columns = 'w-25 w-xl-5';
                                    break;
                                case 'actions':
                                    $columns = 'w-25';
                                    break;
                                default:
                                    $columns = '';
                                    break;
                            }
                            echo '<th class="col-' . $k . ' ' . $columns . '">' . $v . '</th>';
                        }; ?>
                    </thead>
                    <tbody>
                        <?php if( count($rows) > 0 ) { ?>
                            <?php foreach($rows as $key => $row) { ?>
                                <tr class="<?php echo implode(' ', osc_apply_filter('datatable_comment_class', array(), $aRawRows[$key], $row)); ?>">
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

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="comments" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" value="" />
</form>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>
