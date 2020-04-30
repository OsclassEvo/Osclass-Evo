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
    $comment = __get('comment');
    $return = array();

    if(isset($comment['pk_i_id'])) {
        $return['edit']       = true;
        $return['title']      = __('Edit comment');
        $return['icon']       = __('create');
        $return['action_frm'] = 'comment_edit_post';
        $return['btn_text']   = __('Update comment');
    } else {
        $return['edit']       = false;
        $return['title']      = __('Add comment');
        $return['icon']      = __('add');
        $return['action_frm'] = 'add_comment_post';
        $return['btn_text']   = __('Add');
    }
    return $return;
}

function customPageTitle($string) {
    $aux = customFrmText();
    return sprintf('%s &raquo; %s', $aux['title'], $string);
}

function customPageHeader() {
    _e('Listing');
}

function customHead() {
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            // Code for form validation
            $("form[name='comment']").validate({
                rules: {
                    body: {
                        required: true,
                        minlength: 1
                    },
                    authorEmail: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    authorEmail: {
                        required: "<?php _e("Email: this field is required"); ?>.",
                        email: "<?php _e("Invalid email address"); ?>."
                    },
                    body: {
                        required: "<?php _e("Comment: this field is required"); ?>.",
                        minlength: "<?php _e("Comment: this field is required"); ?>."
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
$comment = __get('comment');
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
        <form action="<?php echo osc_admin_base_url(true); ?>" method="post" name="comment" class="has-form-actions form-horizontal">
            <input type="hidden" name="page" value="comments" />
            <input type="hidden" name="action" value="<?php echo $aux['action_frm']; ?>" />
            <input type="hidden" name="id" value="<?php echo (isset($comment['pk_i_id'])) ? $comment['pk_i_id'] : '' ?>" />

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Status'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <a class="btn btn-sm btn-<?php echo (($comment['b_active'] == 1) ? __('danger') : __('success') ); ?>" href="<?php echo osc_admin_base_url( true ); ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id']; ?>&value=<?php echo (($comment['b_active'] == 1) ? 'INACTIVE' : 'ACTIVE'); ?>"><?php echo (($comment['b_active'] == 1) ? __('Deactivate') : __('Activate') ); ?></a> (<?php echo ($comment['b_active'] ? __('Active') : __('Inactive')); ?>)
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Status'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <a class="btn btn-sm btn-<?php echo (($comment['b_enabled'] == 1) ? __('danger') : __('success') ); ?>" href="<?php echo osc_admin_base_url( true ); ?>?page=comments&action=status&id=<?php echo $comment['pk_i_id']; ?>&value=<?php echo (($comment['b_enabled'] == 1) ? 'DISABLE' : 'ENABLE'); ?>"><?php echo (($comment['b_enabled'] == 1) ? __('Block') : __('Unblock') ); ?></a> (<?php echo ($comment['b_enabled'] ? __('Unblocked') : __('Blocked')); ?>)
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Title'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php CommentForm::title_input_text($comment, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Author'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php CommentForm::author_input_text($comment, 'form-control w-100 w-xl-75'); ?>
                        <?php if(isset($comment['fk_i_user_id']) && $comment['fk_i_user_id']): ?>
                            <span class="form-text text-muted"><?php _e("Registered user"); ?> <a href="<?php echo osc_admin_base_url(true); ?>?page=users&action=edit&id=<?php echo $comment['fk_i_user_id']; ?>"><?php _e('Edit user'); ?></a></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e("Author's e-mail"); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php CommentForm::email_input_text($comment, 'form-control w-100 w-xl-75'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-12 col-xl-1 col-form-label form-label text-left"><?php _e('Comment'); ?></label>
                <div class="col-xl-5">
                    <div class="form-group">
                        <?php CommentForm::body_input_textarea($comment, 'form-control w-100 w-xl-75 h-50'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <?php if( $aux['edit'] ) { ?>
                        <a href="javascript:history.go(-1);" class="btn btn-link btn-light"><?php _e('Cancel'); ?></a>
                    <?php } ?>

                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html($aux['btn_text']); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>