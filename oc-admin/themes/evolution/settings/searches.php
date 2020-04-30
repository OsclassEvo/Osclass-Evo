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
	return sprintf(__('Latest searches Settings &raquo; %s'), $string);
}

function addHelp() {
	echo '<p>' . __("Save the searches users do on your site. In this way, you can get information on what they're most interested in. From here, you can manage the options on how much information you want to save.") . '</p>';
}

function customPageHeader() {
	_e('Settings');
}

function customHead() {
	?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $.validator.addMethod('customrule', function(value, element) {
                if($('input[name="purge_searches"]:checked').val() == 'custom') {
                    if($.trim($("#custom_queries").val()) == '') {
                        return false;
                    }
                }
                return true;
            });

            $("form[name='latest_searches_form']").validate({
                rules: {
                    custom_queries: {
                        digits: true,
                        customrule: true
                    }
                },
                messages: {
                    custom_queries: {
                        digits: '<?php echo osc_esc_js(__('Custom number: this field must only contain numeric characters')); ?>.',
                        customrule: '<?php echo osc_esc_js(__('Custom number: this field cannot be left empty')); ?>.'
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
                    $(element).closest('.form-check').append(error);
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
            <i class="material-icons">search</i>
        </div>
        <h4 class="card-title"><?php _e('Latest searches Settings'); ?></h4>
    </div>

    <div class="card-body">
        <form name="latest_searches_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="latestsearches_post" />

            <fieldset class="mb-3">
                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('Latest searches'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="selectable_parent_categories" class="form-check-input" type="checkbox" <?php echo (osc_save_latest_searches() ? 'checked' : ''); ?> name="save_latest_searches" value="1">
								<?php _e('Save the latest user searches'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>

                        <span class="form-text text-muted"><?php _e('It may be useful to know what queries users make.'); ?></span>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('How long queries are stored'); ?></label>

                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="stored_hour" class="form-check-input" type="radio" name="purge_searches" value="hour" <?php echo ((osc_purge_latest_searches() == 'hour') ? 'checked' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'hour';">
                                <?php _e('One hour'); ?>

                                <span class="circle">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="stored_day" class="form-check-input" type="radio" name="purge_searches" value="day" <?php echo ((osc_purge_latest_searches() == 'day') ? 'checked' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'day';">
                                <?php _e('One day'); ?>

                                <span class="circle">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="stored_week" class="form-check-input" type="radio" name="purge_searches" value="week" <?php echo ((osc_purge_latest_searches() == 'week') ? 'checked' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'week';">
                                <?php _e('One week'); ?>

                                <span class="circle">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="stored_forever" class="form-check-input" type="radio" name="purge_searches" value="forever" <?php echo ((osc_purge_latest_searches() == 'forever') ? 'checked' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = 'forever';">
                                <?php _e('Forever'); ?>

                                <span class="circle">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="stored_1000" class="form-check-input" type="radio" name="purge_searches" value="1000" <?php echo ((osc_purge_latest_searches() == '1000') ? 'checked' : ''); ?> onclick="javascript:document.getElementById('customPurge').value = '1000';">
                                <?php _e('Store 1000 queries'); ?>

                                <span class="circle">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="custom" class="form-check-input" type="radio" name="purge_searches" value="custom" <?php echo (!in_array(osc_purge_latest_searches(), array('hour', 'day', 'week', 'forever', '1000')) ? 'checked' : ''); ?>>

                                <div class="form-group" style="margin-top: -10px;">
									<?php _e("Store"); ?>
                                    <input id="purge_searches" type="text" class="form-control text-center d-inline w-25" name="custom_queries" <?php echo (!in_array( osc_purge_latest_searches(), array('hour', 'day', 'week', 'forever', '1000')) ? 'value="' . osc_esc_html(osc_purge_latest_searches()) . '"' : ''); ?> onkeyup="javascript:document.getElementById('customPurge').value = this.value;" />
									<?php _e("queries"); ?>
                                </div>

                                <span class="circle">
                                <span class="check"></span>
                            </span>
                            </label>
                        </div>
                        <span class="form-text text-muted"><?php _e("This feature can generate a lot of data. It's recommended to purge this data periodically."); ?></span>
                    </div>
                </div>

                <input type="hidden" id="customPurge" name="customPurge" value="<?php echo osc_esc_html(osc_purge_latest_searches()); ?>" />
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