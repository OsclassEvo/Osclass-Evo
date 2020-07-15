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
    $rule = __get('rule');
    $return = array();

    if( isset($rule['pk_i_id']) ) {
        $return['edit']       = true;
        $return['title']      = __('Edit rule');
        $return['icon']      = __('create');
        $return['action_frm'] = 'edit_ban_rule_post';
        $return['btn_text']   = __('Update rule');
    } else {
        $return['edit']       = false;
        $return['title']      = __('Add new ban rule');
        $return['icon']      = __('add');
        $return['action_frm'] = 'create_ban_rule_post';
        $return['btn_text']   = __('Add new ban rule');
    }
    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Ban rules');
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');

$aux = customFrmText();
$rule      = __get('rule');
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
            <input type="hidden" name="page" value="users" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />
            <?php BanRuleForm::primary_input_hidden($rule); ?>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Ban name / Reason'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php BanRuleForm::name_text($rule, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('IP rule'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php BanRuleForm::ip_text($rule, 'form-control w-100 w-xl-75'); ?>
                        <span class="bmd-help text-info"><?php _e('(e.g. 192.168.10-20.*)'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('E-mail rule'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php BanRuleForm::email_text($rule, 'form-control w-100 w-xl-75'); ?>
                        <span class="bmd-help text-info"><?php _e('(e.g. *@badsite.com, *@subdomain.badsite.com, *@*badsite.com)'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>

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