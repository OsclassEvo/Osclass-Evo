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
    return sprintf('Edit language &raquo; %s', $string);
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $("form[name='language_form']").validate({
                rules: {
                    s_name: {
                        required: true,
                        minlength: 1
                    },
                    s_short_name: {
                        required: true,
                        minlength: 1
                    },
                    s_description: {
                        required: true,
                        minlength: 1
                    },
                    s_currency_format: {
                        required: true,
                        minlength: 1
                    },
                    i_num_dec: {
                        required: true,
                        digits:true
                    },
                    s_dec_point: {
                        required: true,
                        minlength: 1
                    },
                    s_thousand_sep: {
                        required: true,
                        minlength: 1
                    },
                    s_date_format: {
                        required: true,
                        minlength: 1
                    }
                },
                messages: {
                    s_name: {
                        required: "<?php _e("Name: this field is required"); ?>.",
                        minlength: "<?php _e("Name: this field is required"); ?>."
                    },
                    s_short_name: {
                        required: "<?php _e("Short name: this field is required"); ?>.",
                        minlength: "<?php _e("Short name: this field is required"); ?>."
                    },
                    s_description: {
                        required: "<?php _e("Description: this field is required"); ?>.",
                        minlength: "<?php _e("Description: this field is required"); ?>."
                    },
                    s_currency_format: {
                        required: "<?php _e("Currency format: this field is required"); ?>.",
                        minlength: "<?php _e("Currency format: this field is required"); ?>."
                    },
                    i_num_dec: {
                        required: "<?php _e("Number of decimals: this field is required"); ?>.",
                        digits: "<?php _e("Number of decimals: this field must only contain numeric characters"); ?>."
                    },
                    s_dec_point: {
                        required: "<?php _e("Decimal point: this field is required"); ?>.",
                        minlength: "<?php _e("Decimal point: this field is required"); ?>."
                    },
                    s_thousand_sep: {
                        required: "<?php _e("Thousands separator: this field is required"); ?>.",
                        minlength: "<?php _e("Thousands separator: this field is required"); ?>."
                    },
                    s_date_format: {
                        required: "<?php _e("Date format: this field is required"); ?>.",
                        minlength: "<?php _e("Date format: this field is required"); ?>."
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
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$aLocale = __get('aLocale');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">create</i>
        </div>
        <h4 class="card-title"><?php _e('Edit language'); ?></h4>
    </div>

    <div class="card-body">
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" name="language_form" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="languages" />
            <input type="hidden" name="action" value="edit_post" />

            <?php LanguageForm::primary_input_hidden($aLocale); ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Name'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::name_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Short name'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::short_name_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Description'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::description_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Currency format'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::currency_format_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Number of decimals'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::num_dec_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Decimal point'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::dec_point_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Thousands separator'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::thousands_sep_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Date format'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::date_format_input_text($aLocale, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Stopwords'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php LanguageForm::description_textarea($aLocale, 'form-control w-100 w-xl-75 h-50'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox d-none d-xl-inline-block"></label>
                <div class="col-xl-5 checkbox-radios">
                    <div class="form-check">
                        <label class="form-check-label">
                            <?php LanguageForm::enabled_input_checkbox($aLocale, 'form-check-input'); ?>
                            <?php _e('Enabled for the public website'); ?>

                            <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox d-none d-xl-inline-block"></label>
                <div class="col-xl-5 checkbox-radios">
                    <div class="form-check">
                        <label class="form-check-label">
                            <?php LanguageForm::enabled_bo_input_checkbox($aLocale, 'form-check-input'); ?>
                            <?php _e('Enabled for the backoffice (oc-admin)'); ?>

                            <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>

                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html(__('Save changes')); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>