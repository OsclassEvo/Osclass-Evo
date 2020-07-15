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
    return sprintf(__('Comment Statistics &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('See how many comments the listings published on your site have received.') . '</p>';
}

function customPageHeader() {
    _e('Statistics');
}

function customHead() {
    $comments = __get("comments");

    if(count($comments) > 0) {
        $comment_date = '';
        $comment = '';

        foreach($comments as $date => $num) {
            $comment_date .= "'". $date . "',";
            $comment .= $num . ",";
        }

        $comment_date = rtrim($comment_date, ',');
        $comment = rtrim($comment, ',');
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var data_comments = {
                labels: [<?php echo $comment_date; ?>],
                series: [
                    [<?php echo $comment; ?>]
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

            new Chartist.Line('#chart-comment-statistic', data_comments, options);
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
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=comments&amp;type_stat=day" class="btn ' . $is_day . '">' . __('Last 10 days') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=comments&amp;type_stat=week" class="btn ' . $is_week . '">' . __('Last 10 weeks') . '</a>';
$header_menu .= '<a href="' . osc_admin_base_url(true) . '?page=stats&amp;action=comments&amp;type_stat=month" class="btn ' . $is_month . '">' . __('Last 10 months') . '</a>';

$latest_comments = __get("latest_comments");
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-chart">
            <div class="card-header card-header-success">
                <div class="ct-chart" id="chart-comment-statistic"></div>
            </div>

            <div class="card-body">
                <h4 class="card-title"><?php _e('Comment Statistics'); ?></h4>
            </div>

            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">assessment</i><?php _e('Total number of comments'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?php _e('Latest comments on the web'); ?></h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-shopping">
                <thead class="text-muted">
                <tr>
                    <th class="pl-5">ID</th>
                    <th class="col-title"><?php _e('Title'); ?></th>
                    <th><?php _e('Author'); ?></th>
                    <th><?php _e('Comment'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(count($latest_comments) > 0): ?>
                    <?php foreach($latest_comments as $c): ?>
                        <tr>
                            <td class="pl-5"><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&amp;action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['pk_i_id']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&amp;action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_title']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&amp;action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_author_name'] . " - " . $c['s_author_email']; ?></a></td>
                            <td><a href="<?php echo osc_admin_base_url(true); ?>?page=comments&amp;action=comment_edit&amp;id=<?php echo $c['pk_i_id']; ?>"><?php echo $c['s_body']; ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td class="text-center" colspan="4">
                        <p><?php _e("There're no statistics yet"); ?></p>
                    </td>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>