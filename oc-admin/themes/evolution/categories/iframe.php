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

    $locales  = OSCLocale::newInstance()->listAllEnabled();
?>
<div class="modal fade" id="categoryEditModal<?php echo $category['pk_i_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Edit category'); ?></h4>
                <button type="button" class="close clickable" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="post-category<?php echo $category['pk_i_id']; ?>" class="has-form-actions">
                    <input type="hidden" name="page" value="categories" />
                    <input type="hidden" name="action" value="category_edit" />
                    <input type="hidden" name="id" value="<?php echo $category['pk_i_id']; ?>">

                    <div class="row mb-3 mb-md-0">
                        <label class="col-md-4 col-xl-2 col-form-label form-label text-left text-xl-right" for="i_expiration_days<?php echo $category['pk_i_id']; ?>"><?php _e('Expiration dates'); ?></label>

                        <div class="col-md-8 col-xl-10">
                            <div class="form-group">
                                <input id="i_expiration_days<?php echo $category['pk_i_id']; ?>" type="text" class="form-control w-100 w-sm-2 text-center d-inline clickable" name="i_expiration_days" value="<?php echo $category['i_expiration_days']; ?>" />
                                <small class="form-text text-muted"><?php _e("If the value is zero, it means this category doesn't have an expiration"); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <label class="form-check-label clickable">
                                    <input id="b_price_enabled<?php echo $category['pk_i_id']; ?>" class="form-check-input" type="checkbox" name="b_price_enabled" value="1" <?php if($category['b_price_enabled']) echo 'checked'; ?>>
                                    <?php _e('Enable / Disable the price field'); ?>

                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($category['categories']) && count($category['categories']) > 0): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <label class="form-check-label clickable">
                                        <input id="apply_changes_to_subcategories<?php echo $category['pk_i_id']; ?>" class="form-check-input" type="checkbox" name="apply_changes_to_subcategories" value="1" checked>
                                        <?php _e('Apply the expiration date and price field changes to children categories'); ?>

                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php if(count($locales) > 1): ?>
                                <ul class="nav nav-pills nav-pills-info" role="tablist">
                                    <?php foreach ($locales as $key => $locale): ?>
                                        <li class="nav-item">
                                            <a class="nav-link <?php if(osc_language() == $locale['pk_c_code']) echo 'active'; ?>" data-toggle="tab" href="#<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>" role="tablist">
                                                <?php echo $locale['s_name']; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <div class="tab-content tab-space">
                                    <?php foreach ($locales as $locale): ?>
                                        <div class="tab-pane <?php if(osc_language() == $locale['pk_c_code']) echo 'active'; ?>" id="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>">
                                            <div class="row">
                                                <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>#s_name"><?php _e('Name'); ?></label>

                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <input id="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>#s_name" type="text" class="form-control w-100 d-inline clickable" name="<?php echo $locale['pk_c_code']; ?>#s_name" value="<?php if(isset($category['locale'][$locale['pk_c_code']]['s_name'])) echo osc_esc_html(htmlentities($category['locale'][$locale['pk_c_code']]['s_name'], ENT_COMPAT, "UTF-8")); ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>#s_slug"><?php _e('URL'); ?></label>

                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <input id="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>#s_slug" type="text" class="form-control w-100 d-inline clickable" name="<?php echo $locale['pk_c_code']; ?>#s_slug" value="<?php if(isset($category['locale'][$locale['pk_c_code']]['s_slug'])) echo osc_esc_html(htmlentities($category['locale'][$locale['pk_c_code']]['s_slug'], ENT_COMPAT, "UTF-8")); ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>#s_description"><?php _e('Description'); ?></label>

                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <textarea id="<?php echo $locale['pk_c_code'] . $category['pk_i_id']; ?>#s_description" class="form-control w-100 d-inline clickable" name="<?php echo $locale['pk_c_code']; ?>#s_description" rows="5" /><?php if(isset($category['locale'][$locale['pk_c_code']]['s_description'])) echo osc_esc_html(htmlentities($category['locale'][$locale['pk_c_code']]['s_description'], ENT_COMPAT, "UTF-8")); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locales[0]['pk_c_code'] . $category['pk_i_id']; ?>#s_name"><?php _e('Name'); ?></label>

                                    <div class="col-xl-10">
                                        <div class="form-group">
                                            <input id="<?php echo $locales[0]['pk_c_code'] . $category['pk_i_id']; ?>#s_name" type="text" class="form-control w-100 d-inline clickable" name="<?php echo $locales[0]['pk_c_code']; ?>#s_name" value="<?php echo osc_esc_html(htmlentities($category['s_name'], ENT_COMPAT, "UTF-8")); ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locales[0]['pk_c_code'] . $category['pk_i_id']; ?>#s_slug"><?php _e('URL'); ?></label>

                                    <div class="col-xl-10">
                                        <div class="form-group">
                                            <input id="<?php echo $locales[0]['pk_c_code'] . $category['pk_i_id']; ?>#s_slug" type="text" class="form-control w-100 d-inline clickable" name="<?php echo $locales[0]['pk_c_code']; ?>#s_slug" value="<?php echo osc_esc_html(htmlentities($category['s_slug'], ENT_COMPAT, "UTF-8")); ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locales[0]['pk_c_code'] . $category['pk_i_id']; ?>#s_description"><?php _e('Description'); ?></label>

                                    <div class="col-xl-10">
                                        <div class="form-group">
                                            <textarea id="<?php echo $locales[0]['pk_c_code'] . $category['pk_i_id']; ?>#s_description" class="form-control w-100 d-inline clickable" name="<?php echo $locales[0]['pk_c_code']; ?>#s_description" rows="5" /><?php echo osc_esc_html(htmlentities($category['s_description'], ENT_COMPAT, "UTF-8")); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button onclick="$('#post-category<?php echo $category['pk_i_id']; ?>').submit();" type="button" class="btn btn-info btn-link clickable"><?php echo osc_esc_html( __('Save changes') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link clickable"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>