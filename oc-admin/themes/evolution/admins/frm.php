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
    $admin = __get("admin");
    $return = array();
    if( isset($admin['pk_i_id']) ) {
        $return['admin_edit'] = true;
        $return['title']      = __('Edit admin');
        $return['icon']      = __('create');
        $return['action_frm'] = 'edit_post';
        $return['btn_text']   = __('Save Changes');
    } else {
        $return['admin_edit']  = false;
        $return['title']      = __('Add admin');
        $return['icon']      = __('add');
        $return['action_frm'] = 'add_post';
        $return['btn_text']   = __('Add');
    }
    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Admins');
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');

$aux = customFrmText();
$admin = __get("admin");
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
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions form-horizontal">
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />
            <input type="hidden" name="page" value="admins" />
            <?php AdminForm::primary_input_hidden($admin); ?>
            <?php AdminForm::js_validation(); ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Name'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php AdminForm::name_text($admin, 'form-control w-100 w-xl-75'); ?>
                        <span class="bmd-help text-danger"><?php _e('Field is required'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Username'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php AdminForm::username_text($admin, 'form-control w-100 w-xl-75'); ?>
                        <span class="bmd-help text-danger"><?php _e('Field is required'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('E-mail'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php AdminForm::email_text($admin, 'form-control w-100 w-xl-75'); ?>
                        <span class="bmd-help text-danger"><?php _e('Field is required'); ?></span>
                    </div>
                </div>
            </div>

            <?php if(!$aux['admin_edit'] || ($aux['admin_edit'] && Params::getParam('id')!= osc_logged_admin_id() && Params::getParam('id')!='')): ?>
                <div class="row no-gutters">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Admin type'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php AdminForm::type_select($admin, 'selectpicker show-tick w-100 w-xl-75', 'data-size="7" data-dropup-auto="false" data-style="btn btn-info btn-sm"'); ?>
                            <span class="form-text text-muted"><?php _e('Administrators have total control over all aspects of your installation, while moderators are only allowed to moderate listings, comments and media files'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('New password'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php AdminForm::password_text($admin, 'form-control w-100 w-xl-75'); ?>
                        <span class="bmd-help text-danger"><?php _e('Field is required'); ?></span>
                    </div>
                </div>
            </div>

            <?php if($aux['admin_edit']): ?>
                <div class="row no-gutters border-bottom border-secondary pb-5">
                    <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Confirm password'); ?></label>
                    <div class="col-xl-5">
                        <div class="form-group">
                            <?php AdminForm::check_password_text($admin, 'form-control w-100 w-xl-75'); ?>
                            <span class="bmd-help text-danger"><?php _e('Type your new password again'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right"><?php _e('Your current password'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php AdminForm::old_password_text($admin, 'form-control w-100 w-xl-75'); ?>
                        <span class="form-text text-muted"><?php _e('For security, type <b>your current password</b>'); ?></span>
                    </div>
                </div>
            </div>

            <?php osc_run_hook('admin_profile_form', $admin); ?>
            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <?php if( $aux['admin_edit'] ) { ?>
                        <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>
                    <?php } ?>

                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html($aux['btn_text']); ?>
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>