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
	return sprintf(__('Media Settings &raquo; %s'), $string);
}

function addHelp() {
	echo '<p>' . __('Manage the options for the images users can upload along with their listings. You can limit their size, the number of images per ad, include a watermark, etc.') . '</p>';
}

function customPageHeader() {
	_e('Settings');
}

function customHead() {
	?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $.validator.addMethod('regexp', function(value, element, param) {
                return this.optional(element) || value.match(param);
            }, '<?php echo osc_esc_js(__('Size is not in the correct format')); ?>');

            $("form[name='media_form']").validate({
                rules: {
                    dimThumbnail: {
                        required: true,
                        regexp: /^[0-9]+x[0-9]+$/i
                    },
                    dimPreview: {
                        required: true,
                        regexp: /^[0-9]+x[0-9]+$/i
                    },
                    dimNormal: {
                        required: true,
                        regexp: /^[0-9]+x[0-9]+$/i
                    },
                    maxSizeKb: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    dimThumbnail: {
                        required: '<?php echo osc_esc_js( __("Thumbnail size: this field is required")); ?>',
                        regexp: '<?php echo osc_esc_js( __("Thumbnail size: is not in the correct format")); ?>'
                    },
                    dimPreview: {
                        required: '<?php echo osc_esc_js( __("Preview size: this field is required")); ?>',
                        regexp: '<?php echo osc_esc_js( __("Preview size: is not in the correct format")); ?>'
                    },
                    dimNormal: {
                        required: '<?php echo osc_esc_js( __("Normal size: this field is required")); ?>',
                        regexp: '<?php echo osc_esc_js( __("Normal size: is not in the correct format")); ?>'
                    },
                    maxSizeKb: {
                        required: '<?php echo osc_esc_js( __("Maximum size: this field is required")); ?>',
                        digits: '<?php echo osc_esc_js( __("Maximum size: this field must only contain numeric characters")); ?>'
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

            var watermarkWarning = '<?php _e("We highly recommend you have the \'Keep original image\' option active when you use watermarks."); ?>';

            $('#watermark_none').bind('change', function() {
                if( $(this).prop('checked') ) {
                    $('#watermark_text_box').hide();
                    $('#watermark_image_box').hide();
                }
            });

            $('#watermark_text').on('change', function() {
                if( $(this).prop('checked') ) {
                    $('#watermark_text_box').show();
                    $('#watermark_image_box').hide();

                    if(!$('input[name="keep_original_image"]').prop('checked')) {
                        Swal.fire({
                            text: watermarkWarning,
                            type: 'warning',
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-info",
                            showCancelButton: false
                        });
                    }
                }
            });

            $('#watermark_image').on('change', function() {
                if( $(this).prop('checked') ) {
                    $('#watermark_text_box').hide();
                    $('#watermark_image_box').show();

                    if(!$('input[name="keep_original_image"]').prop('checked')) {
                        Swal.fire({
                            text: watermarkWarning,
                            type: 'warning',
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-info",
                            showCancelButton: false
                        });
                    }
                }
            });

            $('input[name="keep_original_image"]').on("change", function() {
                if(!$(this).prop('checked')) {
                    if(!$('#watermark_none').prop('checked')) {
                        Swal.fire({
                            text: watermarkWarning,
                            type: 'warning',
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-info",
                            showCancelButton: false
                        });
                    }
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

$maxPHPsize    = View::newInstance()->_get('max_size_upload');
$imagickLoaded = extension_loaded('imagick');

$aGD           = @gd_info();
$freeType      = array_key_exists('FreeType Support', $aGD);
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
        <h4 class="card-title"><?php _e('Media Settings'); ?></h4>
    </div>

    <div class="card-body">
        <form name="media_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="media_post" />

            <fieldset class="mb-3">
                <legend><?php _e('Image sizes'); ?></legend>

                <div class="row no-gutters">
                    <div class="col-lg-12">
                        <span><?php _e('The sizes listed below determine the maximum dimensions in pixels to use when uploading a image. Format: <b>Width</b> x <b>Height</b>.'); ?></span>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Thumbnail size'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="dimThumbnail" required="true" value="<?php echo osc_esc_html(osc_thumbnail_dimensions()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Preview size'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="dimPreview" required="true" value="<?php echo osc_esc_html(osc_preview_dimensions()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Normal size'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="dimNormal" required="true" value="<?php echo osc_esc_html(osc_normal_dimensions()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('Original size'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="keep_original_image" class="form-check-input" type="checkbox" <?php echo (osc_keep_original_image() ? 'checked' : '' ); ?> name="keep_original_image" value="1">
								<?php _e('Keep original image, unaltered after uploading.'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>

                        <span class="form-text text-muted"><?php _e('Image may occupy more space than usual.'); ?></span>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Restrictions'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('Force JPEG'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="force_jpeg" class="form-check-input" type="checkbox" <?php echo (osc_force_jpeg() ? 'checked' : '' ); ?> name="force_jpeg" value="1">
								<?php _e('Force JPEG extension.'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>

                        <span class="form-text text-muted"><?php _e('Uploaded images will be saved in JPG/JPEG format, it saves space but images will not have transparent background.'); ?></span>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('Force aspect'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="force_aspect_image" class="form-check-input" type="checkbox" <?php echo (osc_force_aspect_image() ? 'checked' : '' ); ?> name="force_aspect_image" value="1">
								<?php _e('Force image aspect.'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>

                        <span class="form-text text-muted"><?php _e('No white background will be added to keep the size.'); ?></span>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Maximum size'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="maxSizeKb" required="true" value="<?php echo osc_esc_html(osc_max_size_kb()); ?>" />

                            <span class="form-text text-muted">
                                <?php _e('Size in KB'); ?>

                                <strong class="text-info"><?php printf( __('Maximum size PHP configuration allows: %d KB'), $maxPHPsize ); ?></strong>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('ImageMagick'); ?></label>
                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="use_imagick" class="form-check-input" type="checkbox" <?php echo (($imagickLoaded && osc_use_imagick()) ? 'checked' : '' ); ?> name="use_imagick" value="1" <?php if(!$imagickLoaded) echo 'disabled'; ?>>
								<?php _e('Use ImageMagick instead of GD library'); ?>

                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>

							<?php if(!$imagickLoaded): ?>
                                <label class="label-on-right pt-0">
                                    <code><?php _e('ImageMagick library is not loaded'); ?></code>
                                </label>
							<?php endif; ?>
                        </div>

                        <span class="form-text text-muted">
                            <?php _e("It's faster and consumes less resources than GD library."); ?>
                        </span>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Watermark'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right label-checkbox"><?php _e('Watermark type'); ?></label>

                    <div class="col-xl-5 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="watermark_none" class="form-check-input" type="radio" name="watermark_type" value="none" <?php echo ((!osc_is_watermark_image() && !osc_is_watermark_text()) ? 'checked' : ''); ?>>
								<?php _e('None'); ?>

                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="watermark_text" class="form-check-input" type="radio" name="watermark_type" value="text" <?php echo (osc_is_watermark_text() ? 'checked' : ''); ?> <?php echo (!$freeType ? 'disabled' : ''); ?>>
								<?php _e('Text'); ?>

                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>

							<?php if(!$freeType): ?>
                                <label class="label-on-right pt-0">
                                    <code><?php printf( __('Freetype library is required. How to <a target="_blank" href="%s">install/configure</a>') , 'http://www.php.net/manual/en/image.installation.php' ); ?></code>
                                </label>
							<?php endif; ?>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input id="watermark_image" class="form-check-input" type="radio" name="watermark_type" value="image" <?php echo (osc_is_watermark_image() ? 'checked' : ''); ?>>
								<?php _e('Image'); ?>

                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
            </fieldset>

            <fieldset id="watermark_text_box" class="mb-3 <?php echo (osc_is_watermark_text() ? '' : 'fc-limited'); ?>">
                <legend><?php _e('Watermark Text Settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Text'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="text" class="form-control w-100 w-xl-50 text-center d-inline" name="watermark_text" value="<?php echo osc_esc_html(osc_watermark_text()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Color'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <input type="color" class="form-control w-100 w-xl-50 text-center d-inline" name="watermark_text_color" value="<?php echo osc_esc_html(osc_watermark_text_color()); ?>" />
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Position'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-25 pt-3" name="watermark_text_place" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected' : ''; ?>><?php _e('Centre'); ?></option>
                            <option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected' : ''; ?>><?php _e('Top Left'); ?></option>
                            <option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected' : ''; ?>><?php _e('Top Right'); ?></option>
                            <option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected' : ''; ?>><?php _e('Bottom Left'); ?></option>
                            <option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected' : ''; ?>><?php _e('Bottom Right'); ?></option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset id="watermark_image_box" class="mb-3 <?php echo (osc_is_watermark_image() ? '' : 'fc-limited'); ?>">
                <legend><?php _e('Watermark Image Settings'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Image'); ?></label>
                    <div class="col-xl-5">
                        <input id="watermark_image_file" type="file" name="watermark_image">

						<?php if(osc_is_watermark_image()): ?>
                            <div class="form-text text-muted">
                                <img width="100px" src="<?php echo osc_base_url() . str_replace(osc_base_path(), '', osc_uploads_path()) . "watermark.png" ?>" />
                            </div>
						<?php endif; ?>

                        <span class="form-text text-muted"><?php _e("It has to be a .PNG image"); ?></span>
                        <span class="form-text text-muted"><?php _e("Osclass doesn't check the watermark image size"); ?></span>
                    </div>
                </div>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Position'); ?></label>
                    <div class="col-xl-5">
                        <select class="selectpicker show-tick w-100 w-xl-25" name="watermark_image_place" data-dropup-auto="false" data-size="7" data-style="btn btn-info btn-sm">
                            <option value="centre" <?php echo (osc_watermark_place() == 'centre') ? 'selected' : ''; ?>><?php _e('Centre'); ?></option>
                            <option value="tl" <?php echo (osc_watermark_place() == 'tl') ? 'selected' : ''; ?>><?php _e('Top Left'); ?></option>
                            <option value="tr" <?php echo (osc_watermark_place() == 'tr') ? 'selected' : ''; ?>><?php _e('Top Right'); ?></option>
                            <option value="bl" <?php echo (osc_watermark_place() == 'bl') ? 'selected' : ''; ?>><?php _e('Bottom Left'); ?></option>
                            <option value="br" <?php echo (osc_watermark_place() == 'br') ? 'selected' : ''; ?>><?php _e('Bottom Right'); ?></option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend><?php _e('Regenerate images'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"></label>
                    <div class="col-xl-5">
                        <p>
							<?php _e("You can regenerate different image dimensions. If you have changed the dimension of thumbnails, preview or normal images, you might want to regenerate your images."); ?>
                        </p>

                        <a href="<?php echo osc_admin_base_url(true) . '?page=settings&action=images_post'.'&'.osc_csrf_token_url(); ?>" class="btn btn-info btn-link btn-outline-info">
                            <?php  _e('Regenerate'); ?>
                        </a>
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