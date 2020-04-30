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
    return sprintf(__('Email templates &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __("Modify the emails your site's users receive when they join your site, when someone shows interest in their ad, to recover their password... <strong>Be careful</strong>: don't modify any of the words that appear within brackets.") . '</p>';
}

function customPageHeader() {
    _e('Settings');
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';

$aData = __get('aEmails');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">email</i>
        </div>
        <h4 class="card-title"><?php _e('Emails templates'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="custom-fields-table" class="table table-striped table-shopping">
                <thead class="text-muted">
                    <th class="col-name"><?php _e('Name'); ?></th>
                    <th class="col-title"><?php _e('Title'); ?></th>
                    <th class="col-actions"><?php _e('Actions'); ?></th>
                </thead>
                <tbody>

                <?php if(count($aData['aaData']) > 0) { ?>
                    <?php foreach($aData['aaData'] as $array): ?>
                        <tr>
                            <?php foreach($array as $key => $value): ?>
                                <td <?php if($key == 2) echo 'class="col-actions"'; ?>>
                                    <?php echo $value; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="3" class="text-center">
                            <p><?php _e('No data available in table'); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="row no-gutters">
                <div class="col-md-12">
                    <?php osc_show_pagination_admin($aData); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>