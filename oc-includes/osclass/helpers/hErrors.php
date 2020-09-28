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

    /**
    * Helper Error
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Kill Osclass with an error message
     *
     * @since 1.2
     *
     * @param string $message Error message
     * @param string $title Error title
     */
    function osc_die($title, $message) {
        ?>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <meta name="robots" content="noindex, nofollow, noarchive"/>
                <meta name="googlebot" content="noindex, nofollow, noarchive"/>

                <link rel="apple-touch-icon" sizes="32x32" href="../../assets/images/apple-icon.png">
                <link rel="icon" type="image/png" href="../../assets/images/favicon.png">
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <title><?php echo $title; ?></title>
                <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

                <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" media="screen" rel="stylesheet"/>
                <link type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" media="screen" rel="stylesheet"/>
                <link type="text/css" href="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/css/dashboard.css?v=<?php echo time(); ?>" media="screen" rel="stylesheet"/>
            </head>

            <body class="off-canvas-sidebar">
                <div class="wrapper wrapper-full-page">
                    <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/img/lock.jpg'); background-size: cover; background-position: top center;">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-10 ml-auto mr-auto">
                                    <div class="card card-login">
                                        <div class="card-header card-header-info text-center">
                                            <h4 class="card-title"><?php echo $title; ?></h4>
                                        </div>

                                        <div class="card-body ">
                                            <div class="row no-gutters mb-4">
                                                <div class="col-md-11 ml-auto mr-auto"><?php echo $message; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <footer class="footer">
                            <div class="container">
                                <nav class="float-left">
                                    <ul>
                                        <li>
                                            <a href="https://forum.osclass-evo.com/" title="<?php echo 'Forum'; ?>" target="_blank">
                                                <?php echo 'Forum'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://osclass.market/" title="<?php echo 'Osclass Market'; ?>" target="_blank">
                                                <?php echo 'Osclass Market'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://osclass-evo.com/docs" title="<?php echo 'Documentation'; ?>" target="_blank">
                                                <?php echo 'Documentation'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a id="ninja" href="javascript:;" title="<?php echo 'Donate to us'; ?>">
                                                <?php echo 'Donate to us'; ?>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>

                                <div class="copyright float-right">
                                    &copy; <?php echo date('Y'); ?>, <strong>Osclass Evolution v. <?php echo preg_replace('|.0$|', '', OSCLASS_VERSION); ?></strong>

                                    <?php printf('made with %s for a better web by <a href="%s" target="_blank">Osclass Evolution Team</a>', '<i class="material-icons">favorite</i>', 'https://osclass-evo.com/'); ?>
                                </div>
                            </div>
                        </footer>

                        <form id="donate-form" name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank">
                            <input type="hidden" name="cmd" value="_donations">
                            <input type="hidden" name="business" value="donate@osclass.market">
                            <input type="hidden" name="item_name" value="Osclass Evolution">
                            <input type="hidden" name="return" value="<?php echo osc_get_absolute_url(); ?>oc-admin/">
                            <input type="hidden" name="currency_code" value="USD">
                            <input type="hidden" name="lc" value="US" />
                        </form>
                    </div>
                </div>

                <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/jquery-ui.min.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/core/popper.min.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/core/bootstrap-material-design.min.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/perfect-scrollbar.jquery.min.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/sweetalert2.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/jquery.validate.min.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/bootstrap-selectpicker.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/bootstrap-notify.js"></script>
                <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/dashboard.js?v="></script>

                <script>
                    $(document).ready(function() {
                        var $ninja = $('#ninja');

                        $ninja.click(function(){
                            jQuery('#donate-form').submit();
                            return false;
                        });

                        setTimeout(function() {
                            $('.card').removeClass('card-hidden');
                        }, 700);
                    });
                </script>
            </body>
        </html>
        <?php die(); ?>
    <?php }

    function getErrorParam($param, $htmlencode = false, $quotes_encode = true)
    {
        if ($param == "") return '';
        if (!isset($_SERVER[$param])) return '';
        $value = $_SERVER[$param];
        if ($htmlencode) {
            if($quotes_encode) {
                return htmlspecialchars(stripslashes($value), ENT_QUOTES);
            } else {
                return htmlspecialchars(stripslashes($value), ENT_NOQUOTES);
            }
        }

        return ($value);
    }

    function osc_get_absolute_url() {
        $protocol = (getErrorParam('HTTPS') == 'on'  || getErrorParam('HTTPS') == 1  || getErrorParam('HTTP_X_FORWARDED_PROTO')=='https')? 'https' : 'http';
        return $protocol . '://' . getErrorParam('HTTP_HOST') . preg_replace('/((oc-admin)|(oc-includes)|(oc-content)|([a-z]+\.php)|(\?.*)).*/i', '', getErrorParam('REQUEST_URI', false, false));
    }
