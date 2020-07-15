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
    return sprintf(__('Appearance &raquo; %s'), $string);
}

function customPageHeader() {
    if(Params::getParam('action') == 'edit_widget') {
        $title  = __('Edit widget');
    } else {
        $title  = __('Add widget');
    }

    echo $title;
}

function customHead() {
    $info   = __get("info");
    $widget = __get("widget");
    if( Params::getParam('action') == 'edit_widget' ) {
        $title  = __('Edit widget');
        $edit   = true;
        $button = osc_esc_html( __('Save changes') );
    } else {
        $title  = __('Add widget');
        $edit   = false;
        $button = osc_esc_html( __('Add widget') );
    }
    ?>
    <script type="text/javascript">
        var mce_width;

        if(screen.width <= 1200) {
            mce_width = "100%";
        } else {
            mce_width = "500px";
        }

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
            width: mce_width,
            height: "340px",
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

        $(document).ready(function(){
            // Code for form validation
            $("form[name='widget_form']").validate({
                rules: {
                    description: {
                        required: true
                    }
                },
                messages: {
                    description: {
                        required:  '<?php echo osc_esc_js(__("Description: this field is required")); ?>.'
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
<?php }

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header', 'customHead', 10);

$info   = __get("info");
$widget = __get("widget");

if(Params::getParam('action') == 'edit_widget') {
    $title  = __('Edit widget');
    $edit   = true;
    $button = osc_esc_html( __('Save changes') );
} else {
    $title  = __('Add widget');
    $edit   = false;
    $button = osc_esc_html( __('Add widget') );
}
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">add</i>
        </div>
        <h4 class="card-title"><?php echo $title; ?></h4>
    </div>

    <div class="card-body">
        <form class="has-form-actions form-horizontal" name="widget_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="action" value="<?php echo ( $edit ? 'edit_widget_post' : 'add_widget_post' ); ?>" />
            <input type="hidden" name="page" value="appearance" />
            <input id="upload" class="fc-limited" type="file" name="image" >

            <?php if($edit): ?>
                <input type="hidden" name="id" value="<?php echo Params::getParam('id', true); ?>" />
            <?php endif; ?>

            <input type="hidden" name="location" value="<?php echo Params::getParam('location', true); ?>" />

            <div class="row no-gutters">
                <label class="col-12 col-md-1 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Description (for internal purposes only)'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 w-xl-75 text-center d-inline" name="description" required="true" value="<?php if($edit) { echo osc_esc_html($widget['s_description']); } ?>" />
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('HTML Code for the Widget'); ?></label>

                <div class="col-xl-5">
                    <textarea name="content" id="body"><?php if($edit) { echo osc_esc_html($widget['s_content']); } ?></textarea>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-info">
                        <?php echo $button; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>