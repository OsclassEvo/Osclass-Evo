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
    return sprintf(__('Appearance &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Modify your site\'s header or footer here. Only works with compatible themes, such as those available in the market.') . '</p>';
}

function customPageHeader() {
    _e('Manage Widgets');
}

//customize Head
function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('click', '#cfield-add', function() {
                var $msg  = '',
                    $name = $('#post-field #s_name').val(),
                    $type = $('#post-field #field_type').val(),
                    $option = $('#post-field #s_options').val(),
                    $slug = $('#post-field #field_slug').val();

                var $err = '';

                if($name == '') {
                    $msg += '<p><?php echo osc_esc_js(__('Field "Name" is required.')); ?></p>';
                    $err = true;
                }

                if($slug == '') {
                    $msg += '<p><?php echo osc_esc_js(__('Field "Identifier name" is required.')); ?></p>';
                    $err = true;
                }

                if(($type == 'DROPDOWN' || $type == 'RADIO') && $option == '') {
                    $msg += '<p><?php echo osc_esc_js(__('At least one option is required.')); ?></p>';
                    $err = true;
                }

                if($err == '') {
                    $('#post-field').submit();
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        html: $msg
                    });
                }
            });

            $('body').on('change', 'select[name="field_type"]', function() {
                if($(this).val() == 'DROPDOWN' || $(this).val() == 'RADIO') {
                    $(this).parents('.row').siblings('#field-options').removeClass('d-none');
                } else {
                    $(this).parents('.row').siblings('#field-options').addClass('d-none');
                }
            });

            $("ul#field-categories-tree").hummingbird();

            $("a#categories-check_all").click(function () {
                $("ul#field-categories-tree").hummingbird("checkAll");
            });

            $("a#categories-uncheck_all").click(function () {
                $("ul#field-categories-tree").hummingbird("uncheckAll");
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

$info = __get("info");
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">widgets</i>
        </div>
        <h4 class="card-title"><?php _e('Manage Widgets'); ?></h4>
    </div>

    <div class="card-body">
        <?php if(isset($info['locations']) && is_array($info['locations'])): ?>
            <div class="row justify-content-between">
                <?php foreach($info['locations'] as $location): ?>
                    <div class="col-12 col-xl-5 mb-5 ml-xl-5 mr-xl-5 border border-light rounded">
                        <div class="row bg-light">
                            <div class="col-12 col-md-8">
                                <h4 class="mt-3 mb-0"><?php printf(__('Section: %s'), $location); ?></h4>
                            </div>
                            <div class="col-12 col-md-4 text-left text-sm-right mt-0">
                                <a id="add_widget_<?php echo $location;?>" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=add_widget&location=<?php echo $location; ?>" class="btn btn-info btn-sm mt-3 mb-3">
                                    <?php _e('Add HTML widget'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="row mt-4 mb-2">
                            <?php $widgets = Widget::newInstance()->findByLocation($location); ?>

                            <?php if( count($widgets) > 0 ):?>
                                <?php foreach($widgets as $w): ?>
                                    <div class="col-md-12">
                                        <div class="row no-gutters table">
                                            <div class="col-md-2 font-weight-bold mt-2">
                                                <?php echo __('Widget') . ' ' . $w['pk_i_id']; ?>
                                            </div>
                                            <div class="col-8 col-md-8 mt-2">
                                                <?php printf(__('Description: %s'), $w['s_description']); ?>
                                            </div>
                                            <div class="col-4 col-md-2 text-right col-actions">
                                                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=edit_widget&id=<?php echo $w['pk_i_id']; ?>&location=<?php echo $location; ?>" rel="tooltip" class="btn btn-warning" title="<?php _e('Edit'); ?>"><i class="material-icons">edit</i><div class="ripple-container"></div></a>

                                                <a id="widget-delete" data-widget-id="<?php echo $w['pk_i_id']; ?>" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&action=delete_widget&id=<?php echo $w['pk_i_id']; ?>" rel="tooltip" class="btn btn-danger" title="<?php _e('Delete'); ?>"><i class="material-icons">delete</i><div class="ripple-container"></div></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php _e("Current theme does not support widgets"); ?>
        <?php endif; ?>
    </div>
</div>

<form id="widget-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="appearance" />
    <input type="hidden" name="action" value="delete_widget" />
    <input type="hidden" name="id" value="" />
</form>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>