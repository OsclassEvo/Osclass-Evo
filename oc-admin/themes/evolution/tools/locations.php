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
    return sprintf(__('Location stats &raquo; %s'), $string);
}

function customPageHeader() {
    _e('Locations stats');
}

//customize Head
function customHead() {
    $all = osc_get_preference('location_todo');
    $worktodo   = LocationsTmp::newInstance()->count();

    if($all == '') $all = 0;
    ?>
    <script type="text/javascript">
        function reload() {
            window.location = '<?php echo osc_admin_base_url(true).'?page=tools&action=locations'; ?>';
        }

        function load_stats() {
            $.ajax({
                type: "POST",
                url: '<?php echo osc_admin_base_url(true)?>?page=ajax&action=location_stats&<?php echo osc_csrf_token_url(); ?>',
                dataType: 'json',
                success: function(data) {
                    if(data.status == 'done') {
                        setTimeout(function () {
                            $('div#percent').attr('style', 'width:100%;').attr('aria-valuenow', 100).html('<?php _e('Completed') ?>');

                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: $('#msg-stats-successfull').val(),
                                showConfirmButton: false,
                                timer: 2500
                            });
                        }, 500);
                    }else{
                        var pending = data.pending;
                        var all = <?php echo osc_esc_js($all);?>;
                        var percent = parseInt(((all - pending) * 100) / all);
                        $('div#percent').attr('style', 'width:' + percent + '%;').attr('aria-valuenow', percent).html(percent + '%');

                        load_stats();
                    }
                }
            });
        }

        $(document).ready(function(){
            if(<?php echo $worktodo;?> > 0) {
                load_stats();
            }
        });
    </script>
    <?php
}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

$all        = osc_get_preference('location_todo');
$worktodo   = LocationsTmp::newInstance()->count();
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">bar_chart</i>
        </div>
        <h4 class="card-title"><?php _e('Locations stats'); ?></h4>
    </div>

    <div class="card-body">
        <form id="backup_form" name="backup_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="has-form-actions">
            <input type="hidden" name="page" value="tools" />
            <input type="hidden" name="action" value="locations_post" />

            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($worktodo > 0): ?>
                                <div class="progress progress-line-success w-25">
                                    <div id="percent" class="progress-bar progress-bar-success progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            <?php endif; ?>
                            <p>
                                <?php _e('You can recalculate your location stats. This is useful if you upgrade from versions older than Osclass 2.4'); ?>.
                            </p>
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-info">
                                <?php echo osc_esc_html( __('Calculate location stats') ); ?>
                                <div class="ripple-container"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>