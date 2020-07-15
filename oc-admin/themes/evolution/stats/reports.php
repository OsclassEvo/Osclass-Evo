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
    return sprintf(__('Report Statistics &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('See how many listings from your site have been reported as spam, expired, duplicate, etc.') . '</p>';
}

function customPageHeader() {
    _e('Statistics');
}

function customHead() {
    $reports = __get("reports");

    if(count($reports) > 0) {
        $report_date = '';
        $report_spam = '';
        $report_repeated = '';
        $report_classified = '';
        $report_offensive = '';
        $report_expired = '';

        foreach($reports as $date => $data) {
            $report_date .= "'". $date . "',";
            $report_spam .= $data['spam'] . ",";
            $report_repeated .= $data['repeated'] . ",";
            $report_classified .= $data['bad_classified'] . ",";
            $report_offensive .= $data['offensive'] . ",";
            $report_expired .= $data['expired'] . ",";
        }

        $report_date = rtrim($report_date, ',');
        $report_spam = rtrim($report_spam, ',');
        $report_repeated = rtrim($report_repeated, ',');
        $report_classified = rtrim($report_classified, ',');
        $report_offensive = rtrim($report_offensive, ',');
        $report_expired = rtrim($report_expired, ',');
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var data_reports = {
                labels: [<?php echo $report_date; ?>],
                series: [
                    [<?php echo $report_spam; ?>],
                    [<?php echo $report_repeated; ?>],
                    [<?php echo $report_classified; ?>],
                    [<?php echo $report_offensive; ?>],
                    [<?php echo $report_expired; ?>]
                ]
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
                    }),
                    Chartist.plugins.legend({
                        legendNames: ['<?php echo osc_esc_js(__('Spam')); ?>', '<?php echo osc_esc_js(__('Duplicated')); ?>', '<?php echo osc_esc_js(__('Bad category')); ?>', '<?php echo osc_esc_js(__('Offensive')); ?>', '<?php echo osc_esc_js(__('Expired')); ?>'],
                        classNames: ['ct-spam', 'ct-dublicated', 'ct-bad-category', 'ct-offensive', 'ct-expired']
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

            new Chartist.Line('#chart-report-statistic', data_reports, options);
        });
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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=reports&amp;type_stat=day" class="btn ' . $is_day . '">' . __('Last 10 days') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=reports&amp;type_stat=week" class="btn ' . $is_week . '">' . __('Last 10 weeks') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=reports&amp;type_stat=month" class="btn ' . $is_month . '">' . __('Last 10 months') . '</a>';
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-chart">
            <div class="card-header card-header-success">
                <div class="ct-chart" id="chart-report-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e('Report Statistics'); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php _e('Total number of reports'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>