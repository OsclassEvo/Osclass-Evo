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
    return sprintf(__('Comment Settings &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Modify the options that allow your users to publish comments on your site's listings.") . '</p>';
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $("form[name='comments_form']").validate({
                rules: {
                    num_moderate_comments: {
                        required: true,
                        digits: true
                    },
                    comments_per_page: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    num_moderate_comments: {
                        required: '<?php echo osc_esc_js(__("Moderated comments: this field is required")); ?>.',
                        digits: '<?php echo osc_esc_js(__("Moderated comments: this field must only contain numeric characters")); ?>.'
                    },
                    comments_per_page: {
                        required: '<?php echo osc_esc_js(__("Comments per page: this field is required")); ?>.',
                        digits: '<?php echo osc_esc_js(__("Comments per page: this field must only contain numeric characters")); ?>.'
                    }
                },
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
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
            <i class="material-icons">comment</i>
        </div>
        <h4 class="card-title"><?php _e('Comment Settings'); ?></h4>
    </div>

    <div class="card-body">
        <form name="comments_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="comments_post" />

            <fieldset class="mb-3">
                <legend><?php _e('Default comment settings'); ?></legend>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="enabled_comments" class="form-check-input" type="checkbox" name="enabled_comments" value="1" <?php echo (osc_comments_enabled() ? 'checked' : ''); ?>> <?php _e('Allow people to post comments on listings'); ?>
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="reg_user_post_comments" class="form-check-input" type="checkbox" name="reg_user_post_comments" value="1" <?php echo (osc_reg_user_post_comments() ? 'checked' : ''); ?>> <?php _e('Users must be registered and logged in to comment'); ?>
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="moderate_comments" class="form-check-input" type="checkbox" name="moderate_comments" value="1" <?php echo (osc_moderate_comments() != -1 ? 'checked' : ''); ?>> <?php _e('A comment is being held for moderation'); ?>
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <?php printf(__('Before a comment appears, comment author must have at least %s previously approved comments'), '<input type="text" class="form-control w-25 w-md-5 text-center d-inline" name="num_moderate_comments" value="' . ((osc_moderate_comments() == -1) ? '0' : osc_esc_html(osc_moderate_comments())) . '" />'); ?>

                            <span class="form-text text-muted"><?php _e('If the value is zero, an administrator must always approve comments'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <?php printf(__('Break comments into pages with %s comments per page'), '<input type="text" class="form-control w-25 w-md-5 text-center d-inline" name="comments_per_page" value="' . osc_esc_html(osc_comments_per_page()) . '" />'); ?>

                            <span class="form-text text-muted"><?php _e('If the value is zero all comments are shown'); ?></span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Notifications'); ?></legend>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="notify_new_comment" class="form-check-input" type="checkbox" name="notify_new_comment" value="1" <?php echo (osc_notify_new_comment() ? 'checked' : ''); ?>> <?php _e('E-mail admin whenever a new comment is posted'); ?>
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="notify_new_comment_user" class="form-check-input" type="checkbox" name="notify_new_comment_user" value="1" <?php echo (osc_notify_new_comment_user() ? 'checked' : ''); ?>> <?php _e("E-mail user whenever there's a new comment on his listing"); ?>
                                <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html( __('Save changes') ); ?>
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>