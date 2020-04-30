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

function customFrmText() {
    $typeForm = __get('typeForm');
    $return = array();

    if($typeForm == 'add_post') {
        $return['title']      = __('Add currency');
        $return['icon']      = __('add');
        $return['btn_text']   = __('Add currency');
    } else {
        $return['title']      = __('Edit currency');
        $return['icon']      = __('create');
        $return['btn_text']   = __('Update currency');
    }

    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Settings');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $("form[name='currency_form']").validate({
                rules: {
                    pk_c_code: {
                        required: true,
                        minlength: 3,
                        maxlength: 3
                    },
                    s_description: {
                        required: true
                    },
                    s_name: {
                        required: true
                    }
                },
                messages: {
                    pk_c_code: {
                        required: '<?php echo osc_esc_js( __('Currency code: this field is required')); ?>.',
                        minlength: '<?php echo osc_esc_js( __('Currency code: must be a three-character code')); ?>.',
                        maxlength: '<?php echo osc_esc_js( __('Currency code: must be a three-character code')); ?>.'
                    },
                    s_description: {
                        required: '<?php echo osc_esc_js( __('Currency symbol: this field is required')); ?>.'
                    },
                    s_name: {
                        required: '<?php echo osc_esc_js( __('Name: this field is required')); ?>.'
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
$typeForm = __get('typeForm');
$aCurrency = View::newInstance()->_get('aCurrency');
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
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" name="currency_form" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="settings" />
            <input type="hidden" name="action" value="currencies" />
            <input type="hidden" name="type" value="<?php echo $typeForm; ?>" />

            <?php if($typeForm == 'edit_post'): ?>
                <input type="hidden" name="pk_c_code" value="<?php echo osc_esc_html($aCurrency['pk_c_code']); ?>" />
            <?php endif; ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Currency Code'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 w-xl-25" name="pk_c_code" value="<?php echo osc_esc_html($aCurrency['pk_c_code']); ?>" <?php if( $typeForm == 'edit_post' ) echo 'disabled'; ?> />
                        <span class="form-text text-muted"><?php printf(__('Must be a three-character code according to the <a href="%s" target="_blank">ISO 4217</a>'), 'http://en.wikipedia.org/wiki/ISO_4217'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Currency symbol'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 w-xl-25" name="s_description" value="<?php echo osc_esc_html($aCurrency['s_description']); ?>" />
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Name'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 w-xl-25" name="s_name" value="<?php echo osc_esc_html($aCurrency['s_name']); ?>" />
                    </div>
                </div>
            </div>

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