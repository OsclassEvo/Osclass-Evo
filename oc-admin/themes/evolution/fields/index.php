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
        return sprintf(__('Custom fields &raquo; %s'), $string);
    }
    
    function addHelp() {
        echo '<p>' . __('Create new fields for users to fill out when they publish a listing. You can require extra  information such as the number of bedrooms in real estate listings or fuel type in car listings, for example.') . '</p>';
    }
    
    function customPageHeader() {
        _e('Custom fields');
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
    $header_menu .= '<a id="add-field" href="javascript:;" class="btn btn-success" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#fieldModal"><i class="material-icons md-18">add</i> ' . __('Add custom field') . '</a>';
    

    $fields     = __get('fields');
    $categories = __get('categories');
    $selected   = __get('default_selected');

    $field = '';

    $csrf_token = osc_csrf_token_url();

    if(Params::getParam('action') == 'field_add') {
        $name = Params::getParam("s_name");
        $type = Params::getParam("field_type");
        $slug = Params::getParam("field_slug");
        $required = Params::getParam("field_required") == "1" ? 1 : 0;
        $searchable = Params::getParam("field_searchable") == "1" ? 1 : 0;
        $options = Params::getParam("s_options");
        $categories = Params::getParam("categories");

        // trim options
        $trim_options = '';
        $aOptions = explode(',', $options);

        foreach($aOptions as &$option) {
            $option = trim($option);
        }

        $trim_options = implode(',', $aOptions);

        $result = Field::newInstance()->insertField($name, $type, $slug, $required, $trim_options, $categories, $searchable);

        ob_get_clean();
        osc_add_flash_ok_message(__('The custom field has been added'), 'admin');
        osc_redirect_to(osc_admin_base_url(true) . '?page=cfields');
    }

    if(Params::getParam('action') == 'field_edit') {
        osc_csrf_check(false);

        $error = 0;
        $field = Field::newInstance()->findByName(Params::getParam('s_name'));

        if (!isset($field['pk_i_id']) || (isset($field['pk_i_id']) && $field['pk_i_id'] == Params::getParam('id'))) {
            Field::newInstance()->cleanCategoriesFromField(Params::getParam("id"));

            if($error == 0) {
                $slug = Params::getParam('field_slug') != '' ? Params::getParam('field_slug') : Params::getParam('s_name');
                $slug_tmp = $slug = preg_replace('|([-]+)|', '-', preg_replace('|[^a-z0-9_-]|', '-', strtolower($slug)));
                $slug_k = 0;

                while(true) {
                    $field = Field::newInstance()->findBySlug($slug);

                    if(!$field || $field['pk_i_id'] == Params::getParam('id')) {
                        break;
                    } else {
                        $slug_k++;
                        $slug = $slug_tmp . '_' . $slug_k;
                    }
                }

                $options = Params::getParam("s_options");

                // trim options
                $trim_options = '';
                $aOptions = explode(',', $options);

                foreach($aOptions as &$option) {
                    $option = trim($option);
                }

                $trim_options = implode(',', $aOptions);

                $res = Field::newInstance()->update(
                    array(
                        's_name'        => Params::getParam('s_name'),
                        'e_type'        => Params::getParam('field_type'),
                        's_slug'        => $slug,
                        'b_required'    => Params::getParam('field_required') == 1 ? 1 : 0,
                        'b_searchable'  => Params::getParam('field_searchable') == 1 ? 1 : 0,
                        's_options'     => $trim_options),
                    array('pk_i_id' => Params::getParam('id'))
                );

                if(is_bool($res) && !$res) {
                    $error = 1;
                }
            }

            if($error == 0) {
                $aCategories = Params::getParam('categories');
                if( is_array($aCategories) && count($aCategories) > 0) {
                    $res = Field::newInstance()->insertCategories(Params::getParam('id'), $aCategories);

                    if(!$res) {
                        $error = 1;
                    }
                }

                ob_get_clean();
                osc_add_flash_ok_message(__('The custom field has been edited'), 'admin');
            } else {
                if($error == 1) {
                    $message = __('An error occurred while updating.');
                }

                ob_get_clean();
                osc_add_flash_error_message($message, 'admin');
            }
        } else {
            $error = 1;
            $message = __('Sorry, you already have a field with that name');

            ob_get_clean();
            osc_add_flash_error_message($message, 'admin');
        }

        osc_redirect_to(osc_admin_base_url(true) . '?page=cfields');
    }
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="modal fade" id="fieldModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Add new Custom Field'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="post-field" class="has-form-actions">
                    <input type="hidden" name="page" value="cfields" />
                    <input type="hidden" name="action" value="field_add" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="s_name"><?php _e('Name'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <?php FieldForm::name_input_text($field, 'form-control'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="field_type"><?php _e('Type'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <?php FieldForm::type_select($field, 'selectpicker show-tick', 'data-size="7" data-width="100%" data-style="btn btn-info btn-sm"'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="field-options" class="row d-none">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="s_options"><?php _e('Options'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <?php FieldForm::options_input_text($field, 'form-control'); ?>
                                        <small class="form-text text-muted"><?php _e('Separate options with commas'); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-lg-9 offset-lg-3 mt-3 mt-lg-0">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <?php FieldForm::required_checkbox($field, 'form-check-input'); ?>
                                                    <?php _e('This field is required'); ?>
                                                    <span class="form-check-sign">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-11 offset-lg-1">
                                    <?php _e('Select the categories where you want to apply this attribute:'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="user">
                                    <a id="categories-check_all" href="javascript:void(0);"><?php _e('Check all'); ?></a> &middot;
                                    <a id="categories-uncheck_all" href="javascript:void(0);"><?php _e('Uncheck all'); ?></a>
                                </label>

                                <div class="col-lg-9">
                                    <div class="form-group hummingbird-treeview">
                                        <ul id="field-categories-tree" class="hummingbird-base">
                                            <?php CategoryForm::categories_custom_field_tree($categories, array()); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-11 offset-lg-1">
                                    <?php _e('Advanced options'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="field-advanced-options" class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="user"><?php _e('Identifier name'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <input id="field_slug" type="text" class="form-control" name="field_slug" value="<?php if(isset($field['s_slug'])) echo $field['s_slug']; ?>" />
                                        <small class="form-text text-muted"><?php _e('Only alphanumeric characters are allowed [a-z0-9_-]'); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="field-advanced-options" class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-9 offset-lg-3 mt-3 mt-lg-0">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <?php FieldForm::searchable_checkbox($field, 'form-check-input'); ?>
                                            <?php _e('Tick to allow searches by this field'); ?>
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="cfield-add" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Save changes') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">library_books</i>
        </div>
        <h4 class="card-title"><?php _e('Custom fields'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="custom-fields-table" class="table table-striped table-shopping">
                <thead class="text-muted">
                    <th><?php _e('Custom fields'); ?></th>
                    <th class="col-actions"><?php _e('ACTIONS'); ?></th>
                </thead>
                <tbody>

                <?php if(count($fields) > 0) { ?>
                    <?php foreach($fields as $field) { ?>
                        <tr data-field-id="<?php echo $field['pk_i_id']; ?>">
                            <td><?php echo $field['s_name']; ?></td>

                            <td style="width: 200px;" class="col-actions">
                                <a href="javascript:;" rel="tooltip" class="btn btn-warning" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#fieldEditModal<?php echo $field['pk_i_id']; ?>" title="<?php _e('Edit'); ?>"><i class="material-icons">edit</i><div class="ripple-container"></div><div class="ripple-container"></div></a>

                                <a id="listing-delete" data-delete-type="field" data-listing-id="<?php echo $field['pk_i_id']; ?>" href="<?php echo osc_admin_base_url(true); ?>?page=ajax&action=delete_field&<?php echo $csrf_token; ?>&id=<?php echo $field['pk_i_id']; ?>" rel="tooltip" class="btn btn-danger" title="<?php _e('Delete'); ?>"><i class="material-icons">delete</i><div class="ripple-container"></div></a>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2" class="text-center">
                            <p><?php _e('No data available in table'); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(count($fields) > 0): ?>
    <?php foreach($fields as $field): ?>
        <?php require osc_admin_base_path() . 'themes/evolution/fields/iframe.php'; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>