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
    return sprintf(__('Listing Settings &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Modify the general settings for your listings. Decide if users have to register in order to publish something, the number of pictures allowed for each listing, etc.") . '</p>';
}

function customPageHeader() {
    _e('Listing Settings');
}

//customize Head
function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('change', '#moderate_items', function() {
                if($(this).is(':checked')) {
                    $('div[data-id="validate-items"]').removeClass('d-none');
                } else {
                    $('div[data-id="validate-items"]').addClass('d-none');
                }
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
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">settings</i>
        </div>
        <h4 class="card-title"><?php _e('Listing Settings'); ?></h4>
    </div>

    <div class="card-body">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions">
            <input type="hidden" name="page" value="items" />
            <input type="hidden" name="action" value="settings_post" />

            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <fieldset>
                                <legend><?php _e('Settings'); ?></legend>

                                <div class="col-md-12 mb-2">
                                    <label class="form-label text-left font-weight-normal"><?php _e('Redirect after item posted'); ?></label>
                                    <select class="selectpicker show-tick w-100 w-xl-25" name="item_posted_redirect" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                                        <option value="category" <?php if(osc_item_posted_redirect() == 'category') echo 'selected'; ?>><?php _e('Category Page'); ?></option>
                                        <option value="item" <?php if(osc_item_posted_redirect() == 'item') echo 'selected'; ?>><?php _e('Item Page'); ?></option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <?php _e('Warn about expiration'); ?>
                                    <input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="warn_expiration" value="<?php echo osc_esc_html(osc_warn_expiration()); ?>" />
                                    <?php _e('days'); ?>

                                    <span class="form-text text-muted"><?php _e('This option will send an email X days before an ad expires to the author. 0 for no email.'); ?></span>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <?php _e('Title length'); ?>
                                    <?php printf( __('%s characters '), '<input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="max_chars_per_title" value="' . osc_max_characters_per_title() . '" />' ); ?>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <?php _e('Description length'); ?>
                                    <?php printf( __('%s characters '), '<input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="max_chars_per_description" value="' . osc_max_characters_per_description() . '" />' ); ?>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="editor" class="form-check-input" type="checkbox" <?php echo (osc_editor_enabled_at_items() ? 'checked' : ''); ?> name="enableField#editor@items" value="1">
                                            <?php _e('Admin panel description editor'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="items-posted-moderation" class="form-check-input" type="checkbox" <?php echo (osc_items_posted_moderation_enabled() ? 'checked' : ''); ?> name="enableField#listingsPostedModeration@items" value="1">
                                            <?php _e('Listings posted moderation'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="items-edited-moderation" class="form-check-input" type="checkbox" <?php echo (osc_items_edited_moderation_enabled() ? 'checked' : ''); ?> name="enableField#listingsEditedModeration@items" value="1">
                                            <?php _e('Listings edited moderation'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="f_price" class="form-check-input" type="checkbox" <?php echo (osc_price_enabled_at_items() ? 'checked' : ''); ?> name="enableField#f_price@items" value="1">
                                            <?php _e('Price'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="images" class="form-check-input" type="checkbox" <?php echo (osc_images_enabled_at_items() ? 'checked' : ''); ?> name="enableField#images@items" value="1">
                                            <?php _e('Attach images'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <?php printf( __('Attach %s images per listing'), '<input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="numImages@items" value="' . osc_max_images_per_item() . '" />' ); ?>
                                    <span class="form-text text-muted"><?php _e('If the value is zero, it means an unlimited number of images is allowed'); ?></span>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="reg_user_post" class="form-check-input" type="checkbox" <?php echo (osc_reg_user_post() ? 'checked' : ''); ?> name="reg_user_post" value="1">
                                            <?php _e('Only logged in users can post listings'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <?php printf( __('An user has to wait %s seconds between each listing added'), '<input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="items_wait_time" value="' . osc_items_wait_time() . '" />'); ?>
                                    <span class="form-text text-muted"><?php _e('If the value is set to zero, there is no wait period'); ?></span>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="moderate_items" class="form-check-input" type="checkbox" <?php echo ((osc_moderate_items() == -1) ? '' : 'checked'); ?> name="moderate_items" value="1">
                                            <?php _e('Users have to validate their listings'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div data-id="validate-items" class="col-md-12 mb-2 <?php if(osc_moderate_items() == -1): ?>d-none<?php endif; ?>">
                                    <?php printf( __('After %s validated listings the user doesn\'t need to validate the listings any more'), '<input type="text" class="form-control w-25 w-xl-5 text-center d-inline" name="num_moderate_items" value="' . ((osc_moderate_items() == -1) ? '0' : osc_moderate_items()) . '" />'); ?>

                                    <span class="form-text text-muted"><?php _e('If the value is zero, it means that each listing must be validated'); ?></span>
                                </div>

                                <div data-id="validate-items" class="col-md-12 mb-4 <?php if(osc_moderate_items() == -1): ?>d-none<?php endif; ?>">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="logged_user_item_validation" class="form-check-input" type="checkbox" <?php echo (osc_logged_user_item_validation() ? 'checked' : ''); ?> name="logged_user_item_validation" value="1">
                                            <?php _e('Logged in users don\'t need to validate their listings'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="enabled_recaptcha_items" class="form-check-input" type="checkbox" <?php echo ((osc_recaptcha_items_enabled() == '0') ? '' : 'checked' ); ?> name="enabled_recaptcha_items" value="1">
                                            <?php _e('Show reCAPTCHA in add/edit listing form'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>

                                    <span class="form-text text-muted"><?php _e('<strong>Remember</strong> that you must configure reCAPTCHA first'); ?></span>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend><?php _e('Contact publisher'); ?></legend>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="reg_user_can_contact" class="form-check-input" type="checkbox" <?php echo (osc_reg_user_can_contact() ? 'checked' : ''); ?> name="reg_user_can_contact" value="1">
                                            <?php _e('Only allow registered users to contact publisher'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="item_attachment" class="form-check-input" type="checkbox" <?php echo (osc_item_attachment() ? 'checked' : ''); ?> name="item_attachment" value="1">
                                            <?php _e('Allow attached files in contact publisher form'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-12">
                            <fieldset>
                                <legend><?php _e('Notifications'); ?></legend>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="notify_new_item" class="form-check-input" type="checkbox" <?php echo (osc_notify_new_item() ? 'checked' : ''); ?> name="notify_new_item" value="1">
                                            <?php _e('Notify admin when a new listing is added'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="notify_contact_item" class="form-check-input" type="checkbox" <?php echo (osc_notify_contact_item() ? 'checked' : ''); ?> name="notify_contact_item" value="1">
                                            <?php _e('Send admin a copy of the "contact publisher" email'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="notify_contact_friends" class="form-check-input" type="checkbox" <?php echo (osc_notify_contact_friends() ? 'checked' : ''); ?> name="notify_contact_friends" value="1">
                                            <?php _e('Send admin a copy to "share listing" email'); ?>

                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-info">
                                <?php echo osc_esc_html( __('Save changes') ); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>