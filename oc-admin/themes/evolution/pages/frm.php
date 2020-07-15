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

function customFrmText() {
    $page = __get('page');
    $return = array();

    if(isset($page['pk_i_id'])) {
        $return['edit']       = true;
        $return['title']      = __('Edit page');
        $return['icon']      = __('create');
        $return['action_frm'] = 'edit_post';
        $return['btn_text']   = __('Save changes');
    } else {
        $return['edit']       = false;
        $return['title']      = __('Add page');
        $return['icon']      = __('add');
        $return['action_frm'] = 'add_post';
        $return['btn_text']   = __('Add page');
    }

    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Pages');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
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

$aux = customFrmText();
$page       = __get('page');
$templates  = __get('templates');
$meta       = json_decode(@$page['s_meta'], true);

$template_selected = (isset($meta['template']) && $meta['template'] !='' ) ? $meta['template'] : 'default';
$locales = OSCLocale::newInstance()->listAllEnabled();
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons"><?php echo $aux['icon']; ?></i>
        </div>
        <h4 class="card-title"><?php echo $aux['title']; ?></h4>
    </div>

    <div class="card-body">
        <?php printLocaleTabs(); ?>

        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" name="register" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="pages" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />
            <input id="upload" class="fc-limited" type="file" name="image" >

            <?php PageForm::primary_input_hidden($page); ?>

            <?php printLocaleTitlePage($locales, $page); ?>

            <div class="row no-gutters">
                <label class="col-12 col-md-1 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Internal name'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php PageForm::internal_name_input_text($page, 'form-control w-100 w-xl-75'); ?>
                        <span class="form-text text-muted"><?php _e("Used to quickly identify this page"); ?></span>
                    </div>
                </div>
            </div>

            <?php if(count($templates) > 0): ?>
                <div class="row no-gutters">
                    <label class="col-12 col-md-1 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Page template'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <select class="selectpicker show-tick pt-1 w-100 w-xl-75" data-size="7" data-dropup-auto="false" data-style="btn btn-info btn-sm" name="meta[template]">
                                <option value="default" <?php if($template_selected=='default') { echo 'selected="selected"'; }; ?>><?php _e('Default template'); ?></option>

                                <?php foreach($templates as $template): ?>
                                    <option value="<?php echo $template; ?>" <?php if($template_selected == $template) { echo 'selected="selected"'; } ?>><?php echo $template; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php printLocaleDescriptionPage($locales, $page); ?>

            <div class="row no-gutters">
                <label class="col-12 col-md-1 col-xl-1 col-form-label form-label text-left text-xl-right"></label>
                <div class="col-12 col-xl-5 checkbox-radios">
                    <div class="form-check">
                        <label class="form-check-label">
                            <?php PageForm::link_checkbox($page, 'form-check-input w-100 w-xl-75'); ?>
                            <?php _e('Show a link in footer'); ?>
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <?php osc_run_hook('page_meta'); ?>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>

                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html($aux['btn_text']); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>