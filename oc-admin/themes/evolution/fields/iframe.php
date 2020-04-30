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

$selected_categories = Field::newInstance()->categories($field['pk_i_id']);

if ($selected_categories == null) {
    $selected_categories = array();
};
?>

<div class="modal fade" id="fieldEditModal<?php echo $field['pk_i_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Edit Custom Field'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="post-field<?php echo $field['pk_i_id']; ?>" class="has-form-actions">
                    <input type="hidden" name="page" value="cfields" />
                    <input type="hidden" name="action" value="field_edit" />
                    <?php FieldForm::primary_input_hidden($field); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="s_name<?php echo $field['pk_i_id']; ?>"><?php _e('Name'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <?php FieldForm::name_input_text($field, 'form-control', $field['pk_i_id']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="field_type<?php echo $field['pk_i_id']; ?>"><?php _e('Type'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <?php FieldForm::type_select($field, 'selectpicker show-tick', 'id="field_type' . $field['pk_i_id'] . '" data-size="7" data-width="100%" data-style="btn btn-info btn-sm"'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="field-options" class="row <?php if($field['e_type'] != 'DROPDOWN' && $field['e_type'] != 'RADIO'): ?>d-none<?php endif; ?>">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="s_options<?php echo $field['pk_i_id']; ?>"><?php _e('Options'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <?php FieldForm::options_input_text($field, 'form-control', $field['pk_i_id']); ?>
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
                                                    <?php FieldForm::required_checkbox($field, 'form-check-input', $field['pk_i_id']); ?>
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
                                            <?php CategoryForm::categories_custom_field_tree($categories, $selected_categories, $field['pk_i_id']); ?>
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
                                <label class="col-lg-3 col-form-label text-left text-lg-right" for="field_slug<?php echo $field['pk_i_id']; ?>"><?php _e('Identifier name'); ?></label>

                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <input id="field_slug<?php echo $field['pk_i_id']; ?>" type="text" class="form-control" name="field_slug" value="<?php echo $field['s_slug']; ?>" />
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
                                            <?php FieldForm::searchable_checkbox($field, 'form-check-input', $field['pk_i_id']); ?>
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
                <button type="button" onclick="$('#post-field<?php echo $field['pk_i_id']; ?>').submit();" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Save changes') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>