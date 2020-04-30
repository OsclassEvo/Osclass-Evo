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

    function customPageHeader() {
        echo osc_apply_filter('custom_plugin_title', __('Plugins'));
    }

    function customPageTitle($string) {
        return sprintf(__('Plugins &raquo; %s'), $string);
    }

    //customize Head
    function customHead() { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("ul#field-categories-tree").hummingbird();

            $("a#categories-check_all").click(function () {
                $("ul#field-categories-tree").hummingbird("checkAll");
            });

            $("a#categories-uncheck_all").click(function () {
                $("ul#field-categories-tree").hummingbird("uncheckAll");
            });
        });
    </script>
    <?php
}

    osc_add_hook('admin_page_header','customPageHeader');
    osc_add_filter('admin_title', 'customPageTitle');
    osc_add_hook('admin_header','customHead', 10);

    osc_current_admin_theme_path( 'parts/header.php' );

    $categories  = __get('categories');
    $selected    = __get('selected');
    $plugin_data = __get('plugin_data');
?>

<div class="card">
    <div class="card-body">
        <form id="plugin-frm" action="<?php echo osc_admin_base_url(true); ?>?page=plugins" method="post" class="has-form-actions">
            <input type="hidden" name="action" value="configure_post" />
            <input type="hidden" name="plugin" value="<?php echo $plugin_data['filename']; ?>" />
            <input type="hidden" name="plugin_short_name" value="<?php echo $plugin_data['short_name']; ?>" />

            <div class="row no-gutters">
                <div class="col-md-12">
                    <h3 class="render-title"><?php  echo $plugin_data['plugin_name']; ?></h3>
                    <p class="text"><?php echo $plugin_data['description']; ?></p>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <?php _e('Select the categories where you want to apply this attribute:'); ?>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <label class="col-form-label text-right" for="user">
                            <a id="categories-check_all" href="javascript:void(0);"><?php _e('Check all'); ?></a> &middot;
                            <a id="categories-uncheck_all" href="javascript:void(0);"><?php _e('Uncheck all'); ?></a>
                        </label>

                        <div class="col-12 col-md-9">
                            <div class="form-group hummingbird-treeview">
                                <ul id="field-categories-tree" class="hummingbird-base">
                                    <?php CategoryForm::categories_custom_field_tree($categories, $selected); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-info">
                        <?php echo osc_esc_html( __('Update') ); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .render-title {
        margin: 0;
        margin-bottom: 20px;
        color: #616161;
        font-size: 21px;
        font-weight: normal;
    }
</style>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>