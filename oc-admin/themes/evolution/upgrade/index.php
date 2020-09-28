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
    return sprintf(__('Upgrade'), $string);
}

function customPageHeader() {
    _e('Tools');
}

//customize Head
function customHead() { ?>
    <script type="text/javascript">
        $(document).ready(function(){
            if (typeof $.uniform != 'undefined') {
                $('textarea, button,select, input:file').uniform();
            }

            <?php if(Params::getParam('confirm') == 'true'): ?>
                $('#output').show();
                $('#to-hide').hide();

                $.get('<?php echo osc_admin_base_url(true); ?>?page=upgrade&action=upgrade-funcs' , function() {
                    setTimeout(function() {
                        window.location = "<?php echo osc_admin_base_url(true); ?>?page=tools&action=version";
                    }, 3000);
                });
            <?php endif; ?>
        });
    </script>
<?php }

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php _e('Upgrade'); ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">get_app</i>
        </div>
        <h4 class="card-title"><?php _e('Upgrade'); ?></h4>
    </div>

    <div class="card-body">
        <div id="output" class="row no-gutters fc-limited">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><?php _e('Upgrading your Osclass Evolution installation (this could take a while):', 'admin'); ?> <img id="loading_image" style="width: 25px;" src="<?php echo osc_current_admin_theme_url('img/category-preloader.gif'); ?>" ></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="to-hide" class="row no-gutters">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <?php _e('You have uploaded a new version of Osclass Evolution, you need to upgrade Osclass Evolution for it to work correctly.'); ?>
                        </p>

                        <a class="btn btn-info" href="<?php echo osc_admin_base_url(true); ?>?page=upgrade&confirm=true"><?php _e('Upgrade now'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>