<?php
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
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex, nofollow, noarchive"/>
    <meta name="googlebot" content="noindex, nofollow, noarchive"/>

    <!--    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">-->
    <!--    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo osc_page_title(); ?> &raquo; <?php _e('Change your password'); ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" media="screen" rel="stylesheet"/>
    <link type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" media="screen" rel="stylesheet"/>
    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url('waitMe.min.css'); ?>" media="screen" rel="stylesheet"/>
    <link type="text/css" href="<?php echo osc_current_admin_theme_styles_url('dashboard.css?v=' . time()); ?>" media="screen" rel="stylesheet"/>

    <?php osc_run_hook('admin_login_header'); ?>
</head>

<body class="off-canvas-sidebar">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
    <div class="container">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:void(0);"><?php _e('Change Password Page'); ?></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="<?php echo osc_admin_base_url(); ?>" class="nav-link">
                        <i class="material-icons">vpn_key</i>
                        <?php _e('Log in'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo osc_base_url(); ?>" class="nav-link">
                        <i class="material-icons">home</i>
                        <?php _e('Back to site'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<div class="wrapper wrapper-full-page">
    <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('<?php echo osc_current_admin_theme_url('img/'); ?>lock.jpg'); background-size: cover; background-position: top center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 ml-auto mr-auto">
                    <form id="changepasswordform" class="form" name="recoverform" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                        <input id="form-validated" type="hidden" name="form_validated" value="0" />
                        <input type="hidden" name="page" value="login"/>
                        <input type="hidden" name="action" value="forgot_post"/>
                        <input type="hidden" name="adminId" value="<?php echo Params::getParam('adminId', true); ?>"/>
                        <input type="hidden" name="code" value="<?php echo Params::getParam('code', true); ?>"/>

                        <div class="card card-login card-hidden">
                            <div class="card-header card-header-info text-center">
                                <h4 class="card-title"><?php _e('Change Password'); ?></h4>
                            </div>
                            <div class="card-body ">
                                <div class="bmd-form-group">
                                    <div class="col-md-12 pr-0"><?php osc_show_flash_message('admin'); ?></div>
                                </div>

                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                        </div>
                                        <input id="new_password" class="form-control" type="password" name="new_password" placeholder="<?php _e('New password'); ?>..." required="true">
                                    </div>
                                </div>

                                <div class="bmd-form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                        </div>
                                        <input id="new_password2" class="form-control" type="password" name="new_password2" placeholder="<?php _e('Repeat password'); ?>..." required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer justify-content-center">
                                <button type="submit" class="btn btn-info btn-link btn-lg"><?php echo osc_esc_html( __('Change Password')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <nav class="float-left">
                    <ul>
                        <li>
                            <a href="https://forum.osclass-evo.com/" title="<?php _e('Forum'); ?>" target="_blank">
                                <?php _e('Forum'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="https://osclass.market/" title="<?php _e('Osclass Market'); ?>" target="_blank">
                                <?php _e('Osclass Market'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="https://osclass-evo.com/docs" title="<?php _e('Documentation'); ?>" target="_blank">
                                <?php _e('Documentation'); ?>
                            </a>
                        </li>
                        <li>
                            <a id="ninja" href="javascript:;" title="<?php _e('Donate to us'); ?>">
                                <?php _e('Donate to us'); ?>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="copyright float-right">
                    &copy; <?php echo date('Y'); ?>, <strong>Osclass Evolution v. <?php echo preg_replace('|.0$|', '', OSCLASS_VERSION); ?></strong>

                    <?php printf(__('made with %s for a better web by <a href="%s" target="_blank">Osclass Evolution Team</a>'), '<i class="material-icons">favorite</i>', 'https://osclass-evo.com/'); ?>
                </div>
            </div>
        </footer>

        <form id="donate-form" name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank">
            <input type="hidden" name="cmd" value="_donations">
            <input type="hidden" name="business" value="donate@osclass.market">
            <input type="hidden" name="item_name" value="Osclass Evolution">
            <input type="hidden" name="return" value="<?php echo osc_admin_base_url(); ?>">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="lc" value="US" />
        </form>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('core/popper.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('core/bootstrap-material-design.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/perfect-scrollbar.jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/sweetalert2.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/bootstrap-selectpicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/bootstrap-notify.js'); ?>"></script>
<script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('plugins/waitMe.min.js'); ?>"></script>
<script src="<?php echo osc_current_admin_theme_js_url('dashboard.js?v=' . time()); ?>"></script>

<script>
    $(document).ready(function() {
        var $ninja = $('#ninja');

        $ninja.click(function(){
            jQuery('#donate-form').submit();
            return false;
        });

        $('#changepasswordform').submit(function(e) {
            if($('#form-validated').val() == 0) {
                e.preventDefault();
                $('#form-validated').val(1)
            }

            $('#changepasswordform').waitMe({
                effect : 'stretch',
                text : '<?php _e('Please wait...'); ?>',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000',
                maxSize : '',
                waitTime : 3000,
                textPos : 'vertical',
                fontSize : '18px',
                onClose : function() {
                    $('#changepasswordform').waitMe({
                        effect : 'stretch',
                        text : '<?php _e('Please wait...'); ?>',
                        bg : 'rgba(255,255,255,0.7)',
                        color : '#000',
                        maxSize : '',
                        waitTime : -1,
                        textPos : 'vertical',
                        fontSize : '18px'
                    });

                    $('#changepasswordform').submit();
                }
            });
        });

        md.checkFullPageBackgroundImage();
        setTimeout(function() {
            $('.card').removeClass('card-hidden');
        }, 700);
    });
</script>

<style>
    @media all and (max-width: 500px) {
        .off-canvas-sidebar .footer {
            display: none;
        }
    }
</style>

<?php osc_run_hook('admin_login_footer'); ?>
</body>
</html>