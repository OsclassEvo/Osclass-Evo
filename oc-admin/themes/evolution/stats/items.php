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
    return sprintf(__('Listing Statistics &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Quickly find out how many new listings have been published on your site and how many visits each of the listings gets.') . '</p>';
}

function customPageHeader() {
    _e('Statistics');
}

function customHead() {
    $items        = __get("items");
    $reports      = __get("reports");
    $alerts       = __get("alerts");
    $subscribers  = __get("subscribers");

    if(count($items) > 0) {
        $item_date = '';
        $item_num = '';

        foreach($items as $date => $num) {
            $item_date .= "'". $date . "',";
            $item_num .= $num . ",";
        }

        $item_date = rtrim($item_date, ',');
        $item_num = rtrim($item_num, ',');

        $report_date = '';
        $report_num = '';

        foreach($reports as $date => $num) {
            $report_date .= "'". $date . "',";
            $report_num .= $num['views'] . ",";
        }

        $report_date = rtrim($report_date, ',');
        $report_num = rtrim($report_num, ',');

        $alert_date = '';
        $alert_num = '';

        foreach($alerts as $date => $num) {
            $alert_date .= "'". $date . "',";
            $alert_num .= $num . ",";
        }

        $alert_date = rtrim($alert_date, ',');
        $alert_num = rtrim($alert_num, ',');

        $subscribe_date = '';
        $subscribe_num = '';

        foreach($subscribers as $date => $num) {
            $subscribe_date .= "'". $date . "',";
            $subscribe_num .= $num . ",";
        }

        $subscribe_date = rtrim($subscribe_date, ',');
        $subscribe_num = rtrim($subscribe_num, ',');
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var data_listings = {
                labels: [<?php echo $item_date; ?>],
                series: [[<?php echo $item_num; ?>]]
            };

            var data_listings_views = {
                labels: [<?php echo $report_date; ?>],
                series: [[<?php echo $report_num; ?>]]
            };

            var data_alerts = {
                labels: [<?php echo $alert_date; ?>],
                series: [[<?php echo $alert_num; ?>]]
            };

            var data_subscribers = {
                labels: [<?php echo $subscribe_date; ?>],
                series: [[<?php echo $subscribe_num; ?>]]
            };

            var options = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 5
                }),
                low: 0,
                height: 250,
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

            new Chartist.Line('#chart-items-statistic', data_listings, options);
            new Chartist.Line('#chart-items-views-statistic', data_listings_views, options);
            new Chartist.Line('#chart-alerts-statistic', data_alerts, options);
            new Chartist.Line('#chart-subscribers-statistic', data_subscribers, options);
        });
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$type         = Params::getParam('type_stat');

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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=items&amp;type_stat=day" class="btn ' . $is_day . '">' . __('Last 10 days') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=items&amp;type_stat=week" class="btn ' . $is_week . '">' . __('Last 10 weeks') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=items&amp;type_stat=month" class="btn ' . $is_month . '">' . __('Last 10 months') . '</a>';
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header card-header-success">
                <div class="ct-chart" id="chart-items-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e('New listings'); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php _e('Number of new listings'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header card-header-info">
                <div class="ct-chart" id="chart-items-views-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e("Listing's views"); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php _e("Total number of listing's views"); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header card-header-warning">
                <div class="ct-chart" id="chart-alerts-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e('New alerts'); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php _e('Number of new alerts'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card card-chart">
            <div class="card-header card-header-rose">
                <div class="ct-chart" id="chart-subscribers-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e('New subscribers'); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php _e('Number of new subscribers'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>