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

osc_enqueue_script('jquery-validate');

function customPageTitle($string) {
    return sprintf(__('Permalinks &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Activate this option if you want your site's URLs to be more attractive to search engines and intelligible for users. <strong>Be careful</strong>: depending on your hosting service, this might not work correctly.") . '</p>';
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#rewrite_enabled").click(function(){
                $("#custom_rules").toggle();
            });

            $("#show_rules").click(function(){
                $("#inner_rules").toggle();

                if($.trim($(this).text()) == '<?php echo osc_esc_js(__('Show rules')); ?>') {
                    $(this).text('<?php echo osc_esc_js(__('Hide rules')); ?>');
                } else {
                    $(this).text('<?php echo osc_esc_js(__('Show rules')); ?>')
                }
            });
            // Code for form validation
            $("form[name='permalinks_form']").validate({
                rules: {
                    rewrite_item_url: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_page_url: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_cat_url: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_url: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_country: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_region: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_city: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_city_area: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_category: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_user: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_search_pattern: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_contact: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_feed: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_language: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_mark: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_send_friend: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_contact: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_activate: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_edit: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_delete: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_item_resource_delete: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_login: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_dashboard: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_logout: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_register: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_activate: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_activate_alert: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_profile: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_items: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_alerts: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_recover: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_forgot: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_change_password: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_change_email: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_change_username: {
                        required: true,
                        minlength: 1
                    },
                    rewrite_user_change_email_confirm: {
                        required: true,
                        minlength: 1
                    }
                },
                messages: {
                    rewrite_item_url: {
                        required: '<?php echo osc_esc_js( __("Listings url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Listings url: this field is required")); ?>.'
                    },
                    rewrite_page_url: {
                        required: '<?php echo osc_esc_js( __("Page url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Page url: this field is required")); ?>.'
                    },
                    rewrite_cat_url: {
                        required: '<?php echo osc_esc_js( __("Categories url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Categories url: this field is required")); ?>.'
                    },
                    rewrite_search_url: {
                        required: '<?php echo osc_esc_js( __("Search url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search url: this field is required")); ?>.'
                    },
                    rewrite_search_country: {
                        required: '<?php echo osc_esc_js( __("Search country: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search country: this field is required")); ?>.'
                    },
                    rewrite_search_region: {
                        required: '<?php echo osc_esc_js( __("Search region: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search region: this field is required")); ?>.'
                    },
                    rewrite_search_city: {
                        required: '<?php echo osc_esc_js( __("Search city: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search city: this field is required")); ?>.'
                    },
                    rewrite_search_city_area: {
                        required: '<?php echo osc_esc_js( __("Search city area: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search city area: this field is required")); ?>.'
                    },
                    rewrite_search_category: {
                        required: '<?php echo osc_esc_js( __("Search category: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search category: this field is required")); ?>.'
                    },
                    rewrite_search_user: {
                        required: '<?php echo osc_esc_js( __("Search user: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search user: this field is required")); ?>.'
                    },
                    rewrite_search_pattern: {
                        required: '<?php echo osc_esc_js( __("Search pattern: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Search pattern: this field is required")); ?>.'
                    },
                    rewrite_contact: {
                        required: '<?php echo osc_esc_js( __("Contact url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Contact url: this field is required")); ?>.'
                    },
                    rewrite_feed: {
                        required: '<?php echo osc_esc_js( __("Feed url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Feed url: this field is required")); ?>.'
                    },
                    rewrite_language: {
                        required: '<?php echo osc_esc_js( __("Language url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Language url: this field is required")); ?>.'
                    },
                    rewrite_item_mark: {
                        required: '<?php echo osc_esc_js( __("Listing mark url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Listing mark url: this field is required")); ?>.'
                    },
                    rewrite_item_send_friend: {
                        required: '<?php echo osc_esc_js( __("Listing send friend url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Listing send friend url: this field is required")); ?>.'
                    },
                    rewrite_item_contact: {
                        required: '<?php echo osc_esc_js( __("Listing contact url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Listing contact url: this field is required")); ?>.'
                    },
                    rewrite_item_new: {
                        required: '<?php echo osc_esc_js( __("New listing url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("New listing url: this field is required")); ?>.'
                    },
                    rewrite_item_activate: {
                        required: '<?php echo osc_esc_js( __("Activate listing url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Activate listing url: this field is required")); ?>.'
                    },
                    rewrite_item_edit: {
                        required: '<?php echo osc_esc_js( __("Edit listing url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Edit listing url: this field is required")); ?>.'
                    },
                    rewrite_item_delete: {
                        required: '<?php echo osc_esc_js( __("Delete listing url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Delete listing url: this field is required")); ?>.'
                    },
                    rewrite_item_resource_delete: {
                        required: '<?php echo osc_esc_js( __("Delete listing resource url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Delete listing resource url: this field is required")); ?>.'
                    },
                    rewrite_user_login: {
                        required: '<?php echo osc_esc_js( __("Login url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Login url: this field is required")); ?>.'
                    },
                    rewrite_user_dashboard: {
                        required: '<?php echo osc_esc_js( __("User dashboard url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("User dashboard url: this field is required")); ?>.'
                    },
                    rewrite_user_logout: {
                        required: '<?php echo osc_esc_js( __("Logout url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Logout url: this field is required")); ?>.'
                    },
                    rewrite_user_register: {
                        required: '<?php echo osc_esc_js( __("User register url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("User register url: this field is required")); ?>.'
                    },
                    rewrite_user_activate: {
                        required: '<?php echo osc_esc_js( __("Activate user url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Activate user url: this field is required")); ?>.'
                    },
                    rewrite_user_activate_alert: {
                        required: '<?php echo osc_esc_js( __("Activate alert url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Activate alert url: this field is required")); ?>.'
                    },
                    rewrite_user_profile: {
                        required: '<?php echo osc_esc_js( __("User profile url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("User profile url: this field is required")); ?>.'
                    },
                    rewrite_user_items: {
                        required: '<?php echo osc_esc_js( __("User listings url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("User listings url: this field is required")); ?>.'
                    },
                    rewrite_user_alerts: {
                        required: '<?php echo osc_esc_js( __("User alerts url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("User alerts url: this field is required")); ?>.'
                    },
                    rewrite_user_recover: {
                        required: '<?php echo osc_esc_js( __("Recover user url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Recover user url: this field is required")); ?>.'
                    },
                    rewrite_user_forgot: {
                        required: '<?php echo osc_esc_js( __("User forgot url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("User forgot url: this field is required")); ?>.'
                    },
                    rewrite_user_change_password: {
                        required: '<?php echo osc_esc_js( __("Change password url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Change password url: this field is required")); ?>.'
                    },
                    rewrite_user_change_email: {
                        required: '<?php echo osc_esc_js( __("Change email url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Change email url: this field is required")); ?>.'
                    },
                    rewrite_user_change_username: {
                        required: '<?php echo osc_esc_js( __("Change username url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Change username url: this field is required")); ?>.'
                    },
                    rewrite_user_change_email_confirm: {
                        required: '<?php echo osc_esc_js( __("Change email confirm url: this field is required")); ?>.',
                        minlength: '<?php echo osc_esc_js( __("Change email confirm url: this field is required")); ?>.'
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
            <i class="material-icons">router</i>
        </div>
        <h4 class="card-title"><?php _e('Permalinks'); ?></h4>
    </div>

    <div class="card-body">
        <form name="permalinks_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="permalinks_post" />

            <fieldset class="mb-3">
                <legend class="regular"><?php _e('By default Osclass uses web URLs which have question marks and lots of numbers in them. However, Osclass offers you friendly urls. This can improve the aesthetics, usability, and forward-compatibility of your links'); ?></legend>

                <div class="row no-gutters">
                    <div class="col-xl-6">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="rewrite_enabled" class="form-check-input" type="checkbox" name="rewrite_enabled" value="1" <?php echo (osc_rewrite_enabled() ? 'checked' : ''); ?>> <?php _e('Enable friendly urls'); ?>
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="custom_rules" class="row no-gutters <?php if(!osc_rewrite_enabled()) echo 'fc-limited'; ?>">
                    <div class="col-xl-6">
                        <a id="show_rules" href="javascript:void(0);" class="btn btn-info btn-link btn-outline-info"><?php _e('Show rules'); ?></a>
                    </div>
                </div>

                <div id="inner_rules" class="row no-gutters fc-limited">
                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing URL:'); ?></label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_url" minLength="1" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_url')); ?>" />
                                <span class="form-text text-muted"><?php echo sprintf(__('Accepted keywords: %s'), '{ITEM_ID},{ITEM_TITLE},{ITEM_CITY},{CATEGORIES}'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Page URL:'); ?></label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_page_url" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_page_url')); ?>" />
                                <span class="form-text text-muted"><?php echo sprintf(__('Accepted keywords: %s'), '{PAGE_ID}, {PAGE_SLUG}'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Category URL:'); ?></label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_cat_url" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_cat_url')); ?>" />
                                <span class="form-text text-muted"><?php echo sprintf(__('Accepted keywords: %s'), '{CATEGORY_ID},{CATEGORY_NAME},{CATEGORIES}'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search prefix URL:'); ?></label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="seo_url_search_prefix" value="<?php echo osc_esc_html(osc_get_preference('seo_url_search_prefix')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search URL:'); ?></label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_url" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_url')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword country'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_country" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_country')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword region'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_region" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_region')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword city'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_city" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_city')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword city area'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_city_area" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_city_area')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword category'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_category" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_category')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword user'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_user" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_user')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Search keyword pattern'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_search_pattern" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_search_pattern')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Contact'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_contact" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_contact')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Feed'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_feed" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_feed')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Language'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_language" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_language')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing mark'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_mark" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_mark')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing send friend'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_send_friend" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_send_friend')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing contact'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_contact" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_contact')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing new'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_new" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_new')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing activate'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_activate" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_activate')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing edit'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_edit" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_edit')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing delete'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_delete" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_delete')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Listing resource delete'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_item_resource_delete" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_item_resource_delete')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User login'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_login" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_login')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User dashboard'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_dashboard" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_dashboard')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User logout'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_logout" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_logout')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User register'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_register" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_register')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User activate'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_activate" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_activate')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User activate alert'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_activate_alert" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_activate_alert')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User profile'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_profile" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_profile')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User listings'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_items" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_items')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User alerts'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_alerts" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_alerts')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User recover'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_recover" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_recover')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User forgot'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_forgot" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_forgot')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User change password'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_change_password" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_password')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User change email'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_change_email" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_email')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User change email confirm'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-xl-center d-inline" name="rewrite_user_change_email_confirm" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_email_confirm')); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('User change username'); ?>:</label>
                        <div class="col-xl-5">
                            <div class="form-group">
                                <input type="text" class="form-control w-100 w-xl-50 text-left text-xl-center d-inline" name="rewrite_user_change_username" required="true" value="<?php echo osc_esc_html(osc_get_preference('rewrite_user_change_username')); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(osc_rewrite_enabled()): ?>
                    <?php if(file_exists(osc_base_path() . '.htaccess')): ?>
                        <div class="row no-gutters">
                            <div class="col-xl-6">
                                <h4><?php _e('Your .htaccess file') ?></h4>

                                <pre class="mark rounded p-3 w-100 w-xl-50">
<?php
$htaccess_content =  file_get_contents(osc_base_path() . '.htaccess');
echo htmlentities($htaccess_content);
?>
                                </pre>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col-xl-6">
                                <h4><?php _e('What your .htaccess file should look like') ?></h4>

                                <pre class="mark rounded p-3 w-100 w-xl-50">
<?php
$rewrite_base = REL_WEB_URL;
$htaccess     = <<<HTACCESS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$rewrite_base}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$rewrite_base}index.php [L]
</IfModule>
HTACCESS;
echo htmlentities($htaccess);
?>
                                </pre>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
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