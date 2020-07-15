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

osc_enqueue_script('tiny_mce5');

function customPageTitle($string) {
    return sprintf('Edit email template &raquo; %s', $string);
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            tinyMCE.init({
                mode : "textareas",
                skin: 'custom',
                mobile: {
                    // theme: 'mobile',
                    menubar: 'edit view insert format table'
                },
                menu: {
                    edit: {title: 'Edit', items: 'undo redo | selectall'}
                },
                menubar: 'edit view insert format table',
                width: "100%",
                height: "440px",
                language: 'en',
                branding: false,
                plugins : 'advlist autolink lists link image imagetools media charmap preview anchor searchreplace visualblocks code codesample fullscreen insertdatetime media table contextmenu',
                toolbar: 'undo redo | styleselect bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | codesample code',
                entity_encoding : "raw",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,
                media_live_embeds: true,
                image_advtab: true,
                paste_data_images: true,
                link_assume_external_targets: true,
                link_quicklink: true,
                file_picker_types: 'image media',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype == 'image') {
                        $('#upload').trigger('click');

                        $('#upload').on('change', function() {
                            var file = this.files[0];
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                callback(e.target.result, {
                                    alt: ''
                                });
                            };

                            reader.readAsDataURL(file);
                        });
                    }
                }
            });

            $('#language-tab li a').click(function(){
                var currentLocale = $(this).attr('href').replace('#','');

                $('div[id!="' + currentLocale + '#s_title"][class*="multilang"], div[id!="' + currentLocale + '#s_text"][class*="multilang"]').addClass('fc-limited');
                $('[name*="' + currentLocale + '"]').parents('.row').removeClass('fc-limited');
            });

            $('#btn-display-test-it').click(function() {
                Swal.fire({
                    title: '<?php echo osc_esc_js(__('Send email')); ?>',
                    input: 'text',
                    inputPlaceholder: '<?php echo osc_esc_js(__('Enter your email address')); ?>',
                    showCancelButton: true,
                    confirmButtonText: '<?php echo osc_esc_js(__('Send email')); ?>',
                    cancelButtonText: '<?php echo osc_esc_js(__('Cancel')); ?>',
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    showLoaderOnConfirm: true,
                    preConfirm: (email) => {
                        return new Promise(function (resolve, reject) {
                            setTimeout(function () {
                                if(email === '') {
                                    Swal.showValidationError('<?php echo osc_esc_js(__('Enter your email address')); ?>');
                                    Swal.hideLoading();
                                } else if(!/^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,24}$/.test(email)) {
                                    Swal.showValidationError('<?php echo osc_esc_js(__('Invalid email address')); ?>');
                                    Swal.hideLoading();
                                } else {
                                    resolve();
                                }
                            }, 1000)
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((email) => {
                    if (email.value) {
                        var name   = $('input[name*="#s_title"]:visible').attr('name');
                        var locale = name.replace('#s_title', '');

                        var idTinymce = locale + '#s_text';

                        $.post('<?php echo osc_admin_base_url(true); ?>',
                            {
                                page: 'ajax',
                                action: 'test_mail_template',
                                email:  email.value,
                                title:  $('input[name*="s_title"]:visible').val(),
                                body: tinyMCE.get(idTinymce).getContent({format : 'html'})
                            },
                            function(data) {
                                Swal.fire({
                                    type: 'success',
                                    confirmButtonClass: "btn btn-success",
                                    text: data.html
                                });
                            }, 'json');
                    }
                });
            });

            // Code for form validation
            $("form[name='register']").validate({
                rules: {
                    '<?php echo osc_current_admin_locale(); ?>#s_title': {
                        required: true
                    }
                },
                messages: {
                    '<?php echo osc_current_admin_locale(); ?>#s_title': {
                        required: "<?php _e("Title: this field is required"); ?>."
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

$email      = __get("email");
$aEmailVars = EmailVariables::newInstance()->getVariables( $email );

$locales = OSCLocale::newInstance()->listAllEnabled();
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">create</i>
        </div>
        <h4 class="card-title"><?php _e('Edit email template'); ?></h4>
    </div>

    <div class="card-body">
        <?php printLocaleTabs(); ?>

        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" name="register" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="emails" />
            <input type="hidden" name="action" value="edit_post" />
            <input id="upload" class="fc-limited" type="file" name="image" >

            <?php PageForm::primary_input_hidden($email); ?>

            <?php printLocaleTitlePage($locales, $email); ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Internal name'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php PageForm::internal_name_input_text($email, 'form-control w-100 w-xl-75'); ?>
                        <span class="form-text text-muted"><?php _e("Used to identify the email template"); ?></span>
                    </div>
                </div>
            </div>

            <?php printLocaleDescriptionPage($locales, $email); ?>

            <?php if(count($aEmailVars)): ?>
                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"></label>
                    <div class="col-xl-5">
                        <div class="mark rounded p-3 w-100">
                            <h4 class="font-weight-bold"><?php _e('Legend') ?></h4>
                            <?php foreach($aEmailVars as $key => $value): ?>
                                <b><?php echo $key; ?></b> - <?php echo $value; ?>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row no-gutters">
                <div class="col-12 mt-4">
                    <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>

                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html(__('Save changes')); ?>
                    </button>

                    <a id="btn-display-test-it" class="btn btn-warning"><?php _e('Test it'); ?></a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>