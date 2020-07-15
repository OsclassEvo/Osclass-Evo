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
    return sprintf(__('User Statistics &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Stay up-to-date on the number of users registered on your site. You can also see a breakdown of the countries and regions where users live among those available on your site.') . '</p>';
}

function customPageHeader() {
    _e('Statistics');
}

function customHead() {
    $users            = __get("users");
    $users_by_country = __get("users_by_country");
    $users_by_region  = __get("users_by_region");

    if(count($users) > 0) {
        $user_date = '';
        $user_num = '';
        $user_country_num = '';
        $user_region_num = '';

        foreach($users as $date => $num) {
            $user_date .= "'". $date . "',";
            $user_num .= $num . ",";
        }

        $user_date = rtrim($user_date, ',');
        $user_num = rtrim($user_num, ',');

        foreach($users_by_country as $i => $country) {
            $country['s_country'] == NULL ? $country_name = __('Unknown') : $country_name = $country['s_country'];

            $user_country_num .= '{ y: ' . $country['num'] . ', name: "' . $country_name . '"},';
        }

        $user_country_num = rtrim($user_country_num, ',');

        foreach($users_by_region as $i => $region) {
            $region['s_region'] == NULL ? $region_name = __('Unknown') : $region_name = $region['s_region'];

            $user_region_num .= '{ y: ' . $region['num'] . ', name: "' . $region_name . '"},';
        }

        $user_region_num = rtrim($user_region_num, ',');
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var data_users = {
                labels: [<?php echo $user_date; ?>],
                series: [
                    [<?php echo $user_num; ?>]
                ]
            };

            var options = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 5
                }),
                low: 0,
                height: 187,
                plugins: [
                    Chartist.plugins.ctPointLabels({
                        textAnchor: 'middle'
                    })
                ],
                axisY: {
                    scaleMinSpace: 40
                },
                chartPadding: {
                    top: 40,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            };

            new Chartist.Line('#chart-users-statistic', data_users, options);

            var user_region = new CanvasJS.Chart("chart-users-region-statistic", {
                animationEnabled: true,
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{name}: <strong>{y}</strong>",
                    indexLabel: "{name} - {y}",
                    dataPoints: [
                        <?php echo $user_region_num; ?>
                    ]
                }]
            });

            user_region.render();

            var user_country = new CanvasJS.Chart("chart-users-country-statistic", {
                animationEnabled: true,
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{name}: <strong>{y}</strong>",
                    indexLabel: "{name} - {y}",
                    dataPoints: [
                        <?php echo $user_country_num; ?>
                    ]
                }]
            });

            user_country.render();
        });

        function explodePie (e) {
            if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
                e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
            } else {
                e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
            }
            e.chart.render();
        }
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$type = Params::getParam('type_stat');

$is_day = '';
$is_week = '';
$is_month = '';

switch($type){
    case 'week':
        $type_stat = __('Last 10 weeks');
        $is_week = 'btn-success';
        break;
    case 'month':
        $type_stat = __('Last 10 months');
        $is_month = 'btn-success';
        break;
    default:
        $type_stat = __('Last 10 days');
        $is_day = 'btn-success';
}

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=users&amp;type_stat=day" class="btn ' . $is_day . '">' . __('Last 10 days') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=users&amp;type_stat=week" class="btn ' . $is_week . '">' . __('Last 10 weeks') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=users&amp;type_stat=month" class="btn ' . $is_month . '">' . __('Last 10 months') . '</a>';

$item = __get("item");
$latest_users     = __get("latest_users");
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-chart">
            <div class="card-header card-header-success">
                <div class="ct-chart" id="chart-users-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e('New users'); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php printf( __('Listings per user: %s'), number_format($item, 2) ); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header">
                <h4 class="card-title"><?php _e('Users per country'); ?></h4>
            </div>
            <div class="card-body">
                <div id="chart-users-country-statistic" class="ct-chart js-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header">
                <h4 class="card-title"><?php _e('Users per region'); ?></h4>
            </div>
            <div class="card-body">
                <div id="chart-users-region-statistic" class="ct-chart js-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?php _e('Latest users on the web'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-shopping">
                <thead class="text-muted">
                    <tr>
                        <th class="pl-5">ID</th>
                        <th><?php _e('E-Mail'); ?></th>
                        <th><?php _e('Name'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($latest_users) > 0): ?>
                    <?php foreach($latest_users as $u): ?>
                        <tr>
                            <td class="pl-5"><a href="<?php echo osc_admin_base_url(true); ?>?page=users&amp;action=edit&amp;id=<?php echo $u['pk_i_id']; ?>"><?php echo $u['pk_i_id']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=users&amp;action=edit&amp;id=<?php echo $u['pk_i_id']; ?>"><?php echo $u['s_email']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=users&amp;action=edit&amp;id=<?php echo $u['pk_i_id']; ?>"><?php echo $u['s_name']; ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="3">
                            <p><?php _e('No data available in table'); ?></p>
                        </td>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>