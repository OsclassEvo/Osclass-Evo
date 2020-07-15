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

    $numItemsPerCategory = __get('numItemsPerCategory');
    $numItems            = __get('numItems');
    $numUsers            = __get('numUsers');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader() {
       _e('Dashboard');
    }

    function customPageTitle($string) {
        return sprintf(__('Dashboard &raquo; %s'), $string);
    }
    
    osc_add_filter('admin_title', 'customPageTitle');

    function customHead() {
        $items = __get('item_stats');
        $users = __get('user_stats');

        $item_date = '';
        $item_num = '';

        foreach($items as $date => $num) {
            $item_date .= "'". date_format(date_create($date), 'd M') . "',";
            $item_num .= $num . ",";
        }
        
        $item_date = rtrim($item_date, ',');
        $item_num = rtrim($item_num, ',');

        $user_date = '';
        $user_num = '';

        foreach($users as $date => $num) {
            $user_date .= "'". date_format(date_create($date), 'd M') . "',";
            $user_num .= $num . ",";
        }
        
        $user_date = rtrim($user_date, ',');
        $user_num = rtrim($user_num, ',');
?>
    <script type="text/javascript">
    $(document).ready(function() {
        var data_listings = {
            labels: [<?php echo $item_date; ?>],
            series: [[<?php echo $item_num; ?>]]
        };
        
        var data_users = {
            labels: [<?php echo $user_date; ?>],
            series: [[<?php echo $user_num; ?>]]
        };
        
        var options = {
            lineSmooth: Chartist.Interpolation.cardinal({
                tension: 5
            }),
            low: 0,
            height: 250,
            axisY: {
              scaleMinSpace: 40
            },
            chartPadding: {
                top: 40,
                right: 0,
                bottom: 0,
                left: 0
            }
        }
        
        new Chartist.Line('#chart-items-statistic', data_listings, options);
        new Chartist.Line('#chart-users-statistic', data_users, options);
    });
    </script>
<?php } ?>

<?php osc_add_hook('admin_header', 'customHead', 10); ?>
<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row">
    <div class="col-sm-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header card-header-success">
                <div class="ct-chart" id="chart-items-statistic"></div>
            </div>
            
            <div class="card-body">
                <h4 class="card-title"><?php _e('New listings'); ?></h4>
            </div>
            
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php printf(__('Total number of listings: %s'), $numItems); ?>
                </div>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=items" class="btn"><?php _e('Listing statistics'); ?></a>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header card-header-info">
                <div class="ct-chart" id="chart-users-statistic"></div>
            </div>
            
            <div class="card-body">
                <h4 class="card-title"><?php _e('New users'); ?></h4>
            </div>
            
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php printf(__('Total number of users: %s'), $numUsers); ?>
                </div>
                <a href="<?php echo osc_admin_base_url(true); ?>?page=stats&amp;action=users" class="btn"><?php _e('User statistics'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">view_list</i>
                </div>
                <h4 class="card-title"><?php _e('Listings by category'); ?></h4>
            </div>
            
            <div class="card-body">
                <div class="table-responsive table-sales">
                    <?php if(!empty($numItemsPerCategory)): ?>
                        <table class="table">
                            <tbody>
                                <?php foreach($numItemsPerCategory as $category): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo osc_admin_base_url(true); ?>?page=items&catId=<?php echo $category['pk_i_id']; ?>"><?php echo $category['s_name']; ?></a>
                                        </td>
                                        <td  class="text-right">
                                            <?php echo $category['i_num_items'] . "&nbsp;" . (($category['i_num_items'] == 1) ? __('Listing') : __('Listings')); ?>
                                        </td>
                                    </tr>
                                    
                                    <?php foreach($category['categories'] as $sub_category): ?>
                                        <tr>
                                            <td class="children-category">
                                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&catId=<?php echo $sub_category['pk_i_id'];?>"><?php echo $sub_category['s_name']; ?></a>
                                            </td>
                                            <td class="text-right">
                                                <?php echo $sub_category['i_num_items'] . " " . (($sub_category['i_num_items'] == 1) ? __('Listing') : __('Listings')); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <?php _e("There aren't any uploaded listing yet"); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>