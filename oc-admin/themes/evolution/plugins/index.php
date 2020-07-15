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
    return sprintf(__('Plugins &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Install or uninstall the plugins available in your installation. In some cases, you\'ll have to configure the plugin in order to get it to work.') . '</p>';
}

function customPageHeader() {
    _e('Plugins');
}

//customize Head
function customHead() { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('a#plugin-uninstall').click(function(e) {
                var plugin = $(this).attr('data-plugin');
                
                e.preventDefault();

                Swal.fire({
                    title: '<?php _e('Confirm action'); ?>',
                    text: '<?php _e('This action can not be undone. Uninstalling plugins may result in a permanent loss of data. Are you sure you want to continue?'); ?>',
                    type: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-success",
                    cancelButtonClass: "btn btn-danger",
                    confirmButtonText: '<?php _e('Uninstall'); ?>',
                    cancelButtonText: '<?php _e('Cancel'); ?>',
                }).then((result) => {
                    if (result.value) {
                        $('input[name="plugin"]').val(plugin);

                        $('#plugin-uninstall-form').submit();
                    }
                });
            });
        });
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';
$header_menu .= '<a id="add-field" href="' . osc_admin_base_url(true) . '?page=plugins&action=add" class="btn btn-success"><i class="material-icons md-18">add</i> ' . __('Add plugin') . '</a>';

$iDisplayLength = __get('iDisplayLength');
$aData          = __get('aPlugins');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<?php if(Params::getParam('error')): ?>
    <div id="flashmessage" class="alert flashmessage flashmessage-error" data-dismiss="alert">
        <a class="btn ico btn-mini ico-close close">x</a>
        <?php _e("Plugin couldn't be installed because it triggered a <strong>fatal error</strong>"); ?>
        <div class="plugin-error-reporting" data-url="<?php echo osc_admin_base_url(true); ?>?page=plugins&amp;action=error_plugin&amp;plugin=<?php echo Params::getParam('error'); ?>"></div>
    </div>
<?php endif; ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row no-gutters">
    <div class="col-md-12">
        <form class="form-inline items-per-page nocsrf text-center text-sm-right" method="get" action="<?php echo osc_admin_base_url(true); ?>">
            <?php foreach(Params::getParamsAsArray('get') as $key => $value): ?>
                <?php if($key != 'iDisplayLength'): ?>
                    <input type="hidden" name="<?php echo osc_esc_html($key); ?>" value="<?php echo osc_esc_html($value); ?>" />
                <?php endif; ?>
            <?php endforeach; ?>

            <select class="selectpicker show-tick" name="iDisplayLength"  onchange="this.form.submit();" data-style="select-with-transition" data-size="7">
                <option value="10" <?php if( Params::getParam('iDisplayLength') == 10 ) echo 'selected'; ?> ><?php printf(__('%d plugins'), 10); ?></option>
                <option value="25" <?php if( Params::getParam('iDisplayLength') == 25 ) echo 'selected'; ?> ><?php printf(__('%d plugins'), 25); ?></option>
                <option value="50" <?php if( Params::getParam('iDisplayLength') == 50 ) echo 'selected'; ?> ><?php printf(__('%d plugins'), 50); ?></option>
                <option value="100" <?php if( Params::getParam('iDisplayLength') == 100 ) echo 'selected'; ?> ><?php printf(__('%d plugins'), 100); ?></option>
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">extension</i>
        </div>
        <h4 class="card-title"><?php _e('Manage Plugins'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-shopping">
                <thead class="text-muted">
                    <tr>
                        <th class="col-status-border"></th>
                        <th class="col-status" style="width: 150px;"><?php _e('Status'); ?></th>
                        <th><?php _e('Name'); ?></th>
                        <th><?php _e('Description'); ?></th>
                        <th class="col-actions"><?php _e('Actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($aData['aaData']) > 0) { ?>
                    <?php foreach($aData['aaData'] as $array) { ?>
                        <tr>
                            <td class="col-status-border"></td>
                            <?php foreach($array as $key => $value) { ?>
                                <td <?php if($key ==  3) echo 'class="col-actions"'; ?>><?php echo $value; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            <p><?php _e('No data available in table'); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="row no-gutters">
                <div class="col-md-12">
                    <?php
                    function showingResults(){
                        $aData = __get('aPlugins');
                        echo '<ul class="showing-results"><li><span>'.osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aaData']), $aData['iTotalDisplayRecords']).'</span></li></ul>';
                    }

                    osc_add_hook('before_show_pagination_admin','showingResults');
                    osc_show_pagination_admin($aData);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="plugin-uninstall-form" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="uninstall" />
    <input type="hidden" name="plugin" value="" />
</form>

<form id="item-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="plugin" value="" />
</form>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>