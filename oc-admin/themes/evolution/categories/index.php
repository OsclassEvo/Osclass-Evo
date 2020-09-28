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
    return sprintf(__('Categories &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Add, edit or delete the categories or subcategories in which users can post listings. Reorder sections by dragging and dropping, or nest a subcategory in an expanded category. <strong>Be careful</strong>: If you delete a category, all listings associated will also be deleted!') . '</p>';
}

function customPageHeader() {
    _e('Categories');
}

//customize Head
function customHead() {
    $locales  = OSCLocale::newInstance()->listAllEnabled();
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var list_original = '';

            var options = {
                currElClass: 'nestable-drag',
                placeholderClass: 'nestable-placeholder',
                hintClass: 'nestable-hint',
                ignoreClass: 'clickable',
                insertZonePlus: false,
                insertZone: 50,
                scroll: 20,
                opener: {
                    active: true,
                    as: 'html',
                    close: '<i class="material-icons">keyboard_arrow_down</i>',
                    open: '<i class="material-icons">keyboard_arrow_right</i>',
                    openerClass: 'nestable-collapse-btn'
                },
                onDragStart: function(e, el) {
                    $('body').addClass('body-list-drag');
                    $('.wrapper').addClass('list-drag');

                    list_original = $('#sortable-categories').sortableListsToArray();
                },
                complete: function(currEl) {
                    var list = '';

                    list = $('#sortable-categories').sortableListsToArray();

                    if(JSON.stringify(list) != JSON.stringify(list_original)) {
                        var plist = list.reduce(function ( total, current, index ) {
                            if(current.parentId === undefined) {
                                current.parentId = 'root';
                            }

                            total[index] = {'c' : current.id, 'p' : current.parentId};
                            return total;
                        }, {});

                        $.ajax({
                            type: 'POST',
                            url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=categories_order&" . osc_csrf_token_url(); ?>",
                            data: {'list' : JSON.stringify(plist)},
                            success: function(res){
                                var ret = eval("(" + res + ")");

                                if(ret.ok) {
                                    Swal.fire({
                                        position: 'top-end',
                                        type: 'success',
                                        title: ret.ok,
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        type: 'error',
                                        title: ret.error,
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                }
                            }
                        });
                    }
                }
            };

            $('#sortable-categories').sortableLists(options);

            $('body').on('click', '#category-add', function() {
                var $msg  = '',
                    $name = $('#post-category input[id="<?php echo $locales[0]['pk_c_code']; ?>#s_name"]').val(),
                    $slug = $('#post-category input[id="<?php echo $locales[0]['pk_c_code']; ?>#s_slug"]').val();

                var $err = '';

                if($name == '') {
                    $msg += '<p><?php echo osc_esc_js(__('Field "Name" is required.')); ?></p>';
                    $err = true;
                }

                if($slug == '') {
                    $msg += '<p><?php echo osc_esc_js(__('Field "URL" is required.')); ?></p>';
                    $err = true;
                }

                if($err == '') {
                    $('#post-category').submit();
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        html: $msg
                    });
                }
            });

            $('body').on('click', 'a#category-change_status', function() {
                var $this = $(this),
                    $status = $.trim(parseInt($this.attr('data-status'))),
                    $categoryId = $this.attr('data-category-id');

                var $icon, $newStatus;

                if($status == 1) {
                    $icon = 'pause';
                    $newStatus = 0;
                } else {
                    $icon = 'play_arrow';
                    $newStatus = 1;
                }

                var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=enable_category&<?php echo osc_csrf_token_url(); ?>&id=' + $categoryId + '&enabled=' + $status;

                $.ajax({
                    url: url,
                    //context: document.body,
                    success: function(res) {
                        var ret = eval( "(" + res + ")");
                        var message = "";

                        if(ret.ok) {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: ret.ok,
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $this.attr('data-status', $newStatus).find('i').html($icon);

                            for(var i = 0; i < ret.affectedIds.length; i++) {
                                var id =  ret.affectedIds[i].id;

                                $('a#category-change_status[data-category-id="' + id + '"]').attr('data-status', $newStatus).find('i').html($icon);
                            }
                        }

                        if(ret.error) {
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                title: ret.error,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                });
            });
        });
    </script>

    <?php
}

function drawAdminCategories($categories, $level = 0) {
    if($level) echo '<ul>';

    foreach($categories as $i => $category) {
        if($category['b_enabled']) {
            $status_btn = '<a id="category-change_status" data-status="0" data-category-id="' . $category['pk_i_id'] . '" href="javascript:void(0);" class="btn btn-just-icon btn-link btn-category" title="' . __('Disable') . '"><i class="material-icons clickable">pause</i></a>';
        } else {
            $status_btn = '<a id="category-change_status" data-status="1" data-category-id="' . $category['pk_i_id'] . '" href="javascript:void(0);" class="btn btn-just-icon btn-link btn-category" title="' . __('Enable') . '"><i class="material-icons clickable">play_arrow</i></a>';
        }

        echo '<li id="' . $category['pk_i_id'] . '" class="nestable-item nestable-item-handle"><div class="list-category-name nestable-content clickable"><p class="nestable-handle"><span class="material-icons">swap_vert</span></p>' . $category['s_name'];
        echo '<span class="category-action_block">';
        echo '<a href="javascript:;" class="btn btn-just-icon btn-link btn-category" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#categoryEditModal' . $category['pk_i_id'] . '" title="Edit"><i class="material-icons clickable">edit</i></a>';
        echo $status_btn;
        echo '<a id="listing-delete" data-delete-type="category" data-listing-id="' . $category['pk_i_id'] . '" href="' . osc_admin_base_url(true) . '?page=ajax&action=delete_category&' . osc_csrf_token_url() . '&id=' . $category['pk_i_id'] . '" class="btn btn-just-icon btn-link btn-category" title="Delete"><i class="material-icons clickable">delete</i></a>';
        echo '</span>';
        echo '</div>';

        if(count($category['categories']) > 0) {
            $level++;

            drawAdminCategories($category['categories'], $level);
        } else {
            echo '</li>';
        }

        if($level && $i == count($categories) - 1) {
            echo '</ul></li>';
        }
    }
}

function drawEditCategories($categories, $level = 0) {
    foreach($categories as $i => $cat) {
        $category = Category::newInstance()->findByPrimaryKey($cat['pk_i_id'], 'all');

        if(isset($cat['categories'])) {
            $category['categories'] = $cat['categories'];
        }

        require osc_admin_base_path() . 'themes/evolution/categories/iframe.php';

        if(count($cat['categories']) > 0) {
            $level++;

            drawEditCategories($cat['categories'], $level);
        }
    }
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';
$header_menu .= '<a id="add-field" href="javascript:;" class="btn btn-success" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#fieldModal"><i class="material-icons md-18">add</i> ' . __('Add category') . '</a>';

$categories = __get('categories');
$locales  = OSCLocale::newInstance()->listAllEnabled();

if(Params::getParam('action') == 'category_add') {
    osc_csrf_check();

    $default_locale = osc_language();

    $expiration_days = Params::getParam("i_expiration_days") ? Params::getParam("i_expiration_days") : 0;
    $price_enabled = Params::getParam("b_price_enabled") == "1" ? 1 : 0;

    $fields['fk_i_parent_id'] = NULL;
    $fields['i_expiration_days'] = $expiration_days;
    $fields['i_position'] = 0;
    $fields['b_enabled'] = 1;
    $fields['b_price_enabled'] = $price_enabled;

    if(count($locales) > 1) {
        foreach ($locales as $locale) {
            if(Params::getParam($locale['pk_c_code'] . "#s_name")) {
                if(Params::getParam($locale['pk_c_code'] . "#s_slug")) {
                    $aFieldsDescription[$locale['pk_c_code']]['s_name'] = Params::getParam($locale['pk_c_code'] . "#s_name");
                    $aFieldsDescription[$locale['pk_c_code']]['s_description'] = Params::getParam($locale['pk_c_code'] . "#s_description");
                    $aFieldsDescription[$locale['pk_c_code']]['s_slug'] = Params::getParam($locale['pk_c_code'] . "#s_slug");
                } else {
                    $error = 2; // URL is empty
                }
            } else {
                if($locale['pk_c_code'] == $default_locale) {
                    $error = 1;
                } else {
                    $aFieldsDescription[$locale['pk_c_code']]['s_name'] = Params::getParam($default_locale . "#s_name");
                    $aFieldsDescription[$locale['pk_c_code']]['s_description'] = Params::getParam($locale['pk_c_code'] . "#s_description");
                    $aFieldsDescription[$locale['pk_c_code']]['s_slug'] = Params::getParam($default_locale . "#s_slug");
                }
            }
        }
    } else {
        if(Params::getParam($default_locale . "#s_name") && Params::getParam($default_locale . "#s_slug")) {
            $aFieldsDescription[$default_locale]['s_name'] = Params::getParam($default_locale . "#s_name");
            $aFieldsDescription[$default_locale]['s_description'] = Params::getParam($default_locale . "#s_description");
            $aFieldsDescription[$default_locale]['s_slug'] = Params::getParam($default_locale . "#s_slug");
        } else {
            $error = 1; // Name or URL is empty
        }
    }

    if(!$error) {
        $categoryId = Category::newInstance(osc_current_admin_locale())->insert($fields, $aFieldsDescription);

        $rootCategories = Category::newInstance(osc_current_admin_locale())->findRootCategories();

        foreach($rootCategories as $cat){
            $order = $cat['i_position'];
            $order++;
            Category::newInstance(osc_current_admin_locale())->updateOrder($cat['pk_i_id'],$order);
        }

        Category::newInstance(osc_current_admin_locale())->updateOrder($categoryId, '0');

        osc_run_hook('add_category', (int)($categoryId));

        ob_get_clean();
        osc_add_flash_ok_message(__('The category has been added'), 'admin');
    } else {
        if($error == 1) {
            $message = __('Fields "Name" and "URL" is required.');
        } else if($error == 2) {
            $message = __('Field "URL" is required.');
        }

        ob_get_clean();
        osc_add_flash_error_message($message, 'admin');
    }

    osc_redirect_to(osc_admin_base_url(true) . '?page=categories');
}

if(Params::getParam('action') == 'category_edit') {
    osc_csrf_check(false);

    $default_locale = osc_language();

    $id = Params::getParam("id");
    $fields['i_expiration_days'] = (Params::getParam("i_expiration_days") != '') ? Params::getParam("i_expiration_days") : 0;
    $fields['b_price_enabled'] = (Params::getParam('b_price_enabled') != '') ? 1 : 0;
    $apply_changes_to_subcategories = Params::getParam('apply_changes_to_subcategories') == 1 ? true : false;

    $error = 0;

    if(count($locales) > 1) {
        foreach ($locales as $locale) {
            if(Params::getParam($locale['pk_c_code'] . "#s_name")) {
                if(Params::getParam($locale['pk_c_code'] . "#s_slug")) {
                    $aFieldsDescription[$locale['pk_c_code']]['s_name'] = Params::getParam($locale['pk_c_code'] . "#s_name");
                    $aFieldsDescription[$locale['pk_c_code']]['s_description'] = Params::getParam($locale['pk_c_code'] . "#s_description");
                    $aFieldsDescription[$locale['pk_c_code']]['s_slug'] = Params::getParam($locale['pk_c_code'] . "#s_slug");
                } else {
                    $error = 2; // URL is empty
                }
            } else {
                if($locale['pk_c_code'] == $default_locale) {
                    $error = 1;
                } else {
                    $aFieldsDescription[$locale['pk_c_code']]['s_name'] = Params::getParam($default_locale . "#s_name");
                    $aFieldsDescription[$locale['pk_c_code']]['s_description'] = Params::getParam($locale['pk_c_code'] . "#s_description");
                    $aFieldsDescription[$locale['pk_c_code']]['s_slug'] = Params::getParam($default_locale . "#s_slug");
                }
            }
        }
    } else {
        if(Params::getParam($default_locale . "#s_name") && Params::getParam($default_locale . "#s_slug")) {
            $aFieldsDescription[$default_locale]['s_name'] = Params::getParam($default_locale . "#s_name");
            $aFieldsDescription[$default_locale]['s_description'] = Params::getParam($default_locale . "#s_description");
            $aFieldsDescription[$default_locale]['s_slug'] = Params::getParam($default_locale . "#s_slug");
        } else {
            $error = 1; // Name or URL is empty
        }
    }

    osc_run_hook('edited_category', (int)($id), $error);

    if(!$error) {
        $categoryManager = Category::newInstance();

        $res = $categoryManager->updateByPrimaryKey(array('fields' => $fields, 'aFieldsDescription' => $aFieldsDescription), $id);
        $categoryManager->updateExpiration($id, $fields['i_expiration_days'], $apply_changes_to_subcategories);
        $categoryManager->updatePriceEnabled($id, $fields['b_price_enabled'], $apply_changes_to_subcategories);

        ob_get_clean();
        osc_add_flash_ok_message(__('The category has been edited'), 'admin');
    } else {
        if($error == 1) {
            $message = __('Fields "Name" and "URL" is required.');
        } else if($error == 2) {
            $message = __('Field "URL" is required.');
        }

        ob_get_clean();
        osc_add_flash_error_message($message, 'admin');
    }

    osc_redirect_to(osc_admin_base_url(true) . '?page=categories');
}
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<style>
    .category-preloading {
        background: #fff url('<?php echo osc_current_admin_theme_url(); ?>img/category-preloader.gif') no-repeat center center;
        width: 100%;
        min-height: 350px;
    }
    .category-preloading #sortable-categories {
        display: none;
    }
</style>

<div class="modal fade" id="fieldModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Add category'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>

            <div class="modal-body">
                <form method="post" action="<?php echo osc_admin_base_url(true); ?>" id="post-category" class="has-form-actions">
                    <input type="hidden" name="page" value="categories" />
                    <input type="hidden" name="action" value="category_add" />

                    <div class="row mb-3 mb-md-0">
                        <label class="col-md-4 col-xl-2 col-form-label form-label text-left text-xl-right" for="i_expiration_days"><?php _e('Expiration dates'); ?></label>

                        <div class="col-md-8 col-xl-10">
                            <div class="form-group">
                                <input id="i_expiration_days" type="text" class="form-control w-100 w-sm-25 text-center d-inline" name="i_expiration_days" value="0" />
                                <small class="form-text text-muted"><?php _e("If the value is zero, it means this category doesn't have an expiration"); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input id="b_price_enabled" class="form-check-input" type="checkbox" name="b_price_enabled" value="1" checked>
                                    <?php _e('Enable / Disable the price field'); ?>

                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php if(count($locales) > 1): ?>
                                <ul class="nav nav-pills nav-pills-info" role="tablist">
                                    <?php foreach ($locales as $key => $locale): ?>
                                        <li class="nav-item">
                                            <a class="nav-link <?php if(osc_language() == $locale['pk_c_code']) echo 'active'; ?>" data-toggle="tab" href="#<?php echo $locale['pk_c_code']; ?>" role="tablist">
                                                <?php echo $locale['s_name']; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <div class="tab-content tab-space">
                                    <?php foreach ($locales as $locale): ?>
                                        <div class="tab-pane <?php if(osc_language() == $locale['pk_c_code']) echo 'active'; ?>" id="<?php echo $locale['pk_c_code']; ?>">
                                            <div class="row">
                                                <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locale['pk_c_code']; ?>#s_name"><?php _e('Name'); ?></label>

                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <input id="<?php echo $locale['pk_c_code']; ?>#s_name" type="text" class="form-control w-100 d-inline" name="<?php echo $locale['pk_c_code']; ?>#s_name" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locale['pk_c_code']; ?>#s_slug"><?php _e('URL'); ?></label>

                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <input id="<?php echo $locale['pk_c_code']; ?>#s_slug" type="text" class="form-control w-100 d-inline" name="<?php echo $locale['pk_c_code']; ?>#s_slug" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locale['pk_c_code']; ?>#s_description"><?php _e('Description'); ?></label>

                                                <div class="col-xl-10">
                                                    <div class="form-group">
                                                        <textarea id="<?php echo $locale['pk_c_code']; ?>#s_description" class="form-control w-100 d-inline" name="<?php echo $locale['pk_c_code']; ?>#s_description" rows="5" /></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locales[0]['pk_c_code']; ?>#s_name"><?php _e('Name'); ?></label>

                                    <div class="col-xl-10">
                                        <div class="form-group">
                                            <input id="<?php echo $locales[0]['pk_c_code']; ?>#s_name" type="text" class="form-control w-100 d-inline" name="<?php echo $locales[0]['pk_c_code']; ?>#s_name" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locales[0]['pk_c_code']; ?>#s_slug"><?php _e('URL'); ?></label>

                                    <div class="col-xl-10">
                                        <div class="form-group">
                                            <input id="<?php echo $locales[0]['pk_c_code']; ?>#s_slug" type="text" class="form-control w-100 d-inline" name="<?php echo $locales[0]['pk_c_code']; ?>#s_slug" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-12 col-xl-2 col-form-label form-label text-left text-xl-right" for="<?php echo $locales[0]['pk_c_code']; ?>#s_description"><?php _e('Description'); ?></label>

                                    <div class="col-xl-10">
                                        <div class="form-group">
                                            <textarea id="<?php echo $locales[0]['pk_c_code']; ?>#s_description" class="form-control w-100 d-inline" name="<?php echo $locales[0]['pk_c_code']; ?>#s_description" rows="5" /></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="category-add" type="button" class="btn btn-info btn-link"><?php echo osc_esc_html( __('Save changes') ); ?></button>
                <button type="button" data-dismiss="modal" class="btn btn-link"><?php _e('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php if(count($categories)): ?>
    <?php drawEditCategories($categories); ?>
<?php endif; ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="card <?php if(!osc_admin_pages_preloading()) echo 'category-preloading'; ?>">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">vertical_split</i>
        </div>
        <h4 class="card-title"><?php _e('Categories'); ?></h4>
    </div>

    <div class="card-body">
        <?php if(count($categories) > 0) { ?>
            <ul id="sortable-categories" class="nestable-list">
                <?php drawAdminCategories($categories); ?>
            </ul>
        <?php } else { ?>
            <p><?php _e('No data available in table'); ?></p>
        <?php } ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.category-preloading').removeClass('category-preloading')
        }, 1000);
    });
</script>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>