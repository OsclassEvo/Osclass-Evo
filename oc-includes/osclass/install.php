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

error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE);

define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );
define( 'LIB_PATH', ABS_PATH . 'oc-includes/' );
define( 'CONTENT_PATH', ABS_PATH . 'oc-content/' );
define( 'TRANSLATIONS_PATH', CONTENT_PATH . 'languages/' );
define( 'OSC_INSTALLING', 1 );

if(extension_loaded('mysqli')) {
    require_once LIB_PATH . 'osclass/Logger/Logger.php';
    require_once LIB_PATH . 'osclass/Logger/LogDatabase.php';
    require_once LIB_PATH . 'osclass/Logger/LogOsclass.php';
    require_once LIB_PATH . 'osclass/classes/database/DBConnectionClass.php';
    require_once LIB_PATH . 'osclass/classes/database/DBCommandClass.php';
    require_once LIB_PATH . 'osclass/classes/database/DBRecordsetClass.php';
    require_once LIB_PATH . 'osclass/classes/database/DAO.php';
    require_once LIB_PATH . 'osclass/model/Preference.php';
    require_once LIB_PATH . 'osclass/helpers/hPreference.php';
}
require_once LIB_PATH . 'osclass/core/iObject_Cache.php';
require_once LIB_PATH . 'osclass/core/Object_Cache_Factory.php';
require_once LIB_PATH . 'osclass/helpers/hCache.php';

require_once LIB_PATH . 'osclass/core/Session.php';
require_once LIB_PATH . 'osclass/core/Params.php';
require_once LIB_PATH . 'osclass/helpers/hDatabaseInfo.php';
require_once LIB_PATH . 'osclass/helpers/hDefines.php';
require_once LIB_PATH . 'osclass/helpers/hErrors.php';
require_once LIB_PATH . 'osclass/helpers/hLocale.php';
require_once LIB_PATH . 'osclass/helpers/hSearch.php';
require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
require_once LIB_PATH . 'osclass/helpers/hTranslations.php';
require_once LIB_PATH . 'osclass/helpers/hSanitize.php';
require_once LIB_PATH . 'osclass/default-constants.php';
require_once LIB_PATH . 'osclass/install-functions.php';
require_once LIB_PATH . 'osclass/utils.php';
require_once LIB_PATH . 'osclass/core/Translation.php';
require_once LIB_PATH . 'osclass/classes/Plugins.php';
require_once LIB_PATH . 'osclass/locales.php';


Params::init();
Session::newInstance()->session_start();

$locales = osc_listLocales();

if(Params::getParam('install_locale')) {
    Session::newInstance()->_set('userLocale', Params::getParam('install_locale'));
    Session::newInstance()->_set('adminLocale', Params::getParam('install_locale'));
}

if(Session::newInstance()->_get('adminLocale') && array_key_exists(Session::newInstance()->_get('adminLocale'), $locales)) {
    $current_locale = Session::newInstance()->_get('adminLocale');
} else if(isset($locales['en_US'])) {
    $current_locale = 'en_US';
} else {
    $current_locale = key($locales);
}

Session::newInstance()->_set('userLocale', $current_locale);
Session::newInstance()->_set('adminLocale', $current_locale);


$translation = Translation::newInstance(true);

$step = Params::getParam('step');
if( !is_numeric($step) ) {
    $step = '1';
}

if( is_osclass_installed( ) ) {
    $message = __("Looks like you've already installed Osclass Evolution. To reinstall please clear your old database tables first.");
    osc_die('Osclass Evolution &raquo; Error', $message);
}

switch( $step ) {
    case 1:
        $requirements = get_requirements();
        $error        = check_requirements($requirements);
        break;
    case 2:
        if( Params::getParam('save_stats') == '1'  || isset($_COOKIE['osclass_save_stats'])) {
            setcookie('osclass_save_stats', 1, time() + (24*60*60) );
        } else {
            setcookie('osclass_save_stats', 0, time() + (24*60*60) );
        }

        if( isset($_COOKIE['osclass_ping_engines']) ) {
            setcookie('osclass_ping_engines', 1, time() + (24*60*60) );
        }

        break;
    case 3:
        if( Params::getParam('dbname') != '' ) {
            $error = oc_install();
        } else {
            $title   = 'Osclass Evolution &raquo; Error';
            $message = '<p>' . __('Field "Database Name" is required') . '</p>';
            $message .= '<p><a class="btn btn-info btn-md" href="' . get_absolute_url() . 'oc-includes/osclass/install.php?step=2"><span class="material-icons">arrow_left</span>' . __('Go back') . '</a></p>';
            osc_die($title, $message);
        }
        break;
    case 4:
        if( Params::getParam('result') != '' ) {
            $error = Params::getParam('result');
        }
        $password = Params::getParam('password', false, false);
        break;
    case 5:
        $password = Params::getParam('password', false, false);
        break;
    default:
        break;
}
?>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex, nofollow, noarchive"/>
        <meta name="googlebot" content="noindex, nofollow, noarchive"/>

        <!--    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">-->
        <!--    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title><?php _e('Osclass Evolution Installation'); ?></title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

        <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" media="screen" rel="stylesheet"/>
        <link type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" media="screen" rel="stylesheet"/>
        <link type="text/css" href="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/css/waitMe.min.css" media="screen" rel="stylesheet"/>
        <link type="text/css" href="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/css/dashboard.css?v=<?php echo time(); ?>" media="screen" rel="stylesheet"/>

        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/js/plugins/waitMe.min.js"></script>
    </head>

    <body class="off-canvas-sidebar">
        <div class="wrapper wrapper-full-page">
            <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('<?php echo osc_get_absolute_url(); ?>oc-admin/themes/evolution/img/lock.jpg'); background-size: cover; background-position: top center;">
                <div class="container container-install">
                    <div class="row">
                        <div class="col-lg-10 ml-auto mr-auto">
                            <div class="card card-login">
                                <div class="card-header card-header-info text-center">
                                    <h4 class="card-title"><?php _e('Osclass Evolution Installation'); ?></h4>
                                </div>

                                <div class="card-body ">
                                    <div class="row no-gutters">
                                        <div class="col-md-11 ml-auto mr-auto">
                                            <?php if($step == 1): ?>
                                                <?php if($error): ?>
                                                    <h4><?php _e('Oops! You need a compatible Hosting');?></h4>
                                                    <p class="text-danger"><?php _e('Your hosting seems to be not compatible, check your settings.');?></p>
                                                <?php endif; ?>

                                                <form action="install.php" method="post">
                                                    <input type="hidden" name="step" value="2" />

                                                    <?php if(count($locales) > 1): ?>
                                                        <div class="bmd-form-group">
                                                            <label for="install_locale" class="col-lg-12 col-form-label form-label text-left"><?php _e('Choose language'); ?></label>
                                                            <div class="input-group">
                                                                <select id="install_locale" class="selectpicker select-login show-tick mt-1 mb-3" name="install_locale" onchange="window.location.href='?install_locale='+document.getElementById(this.id).value" data-dropup-auto="false" data-size="7" data-width="60%" data-style="btn btn-info">
                                                                    <?php foreach($locales as $k => $locale): ?>
                                                                        <option value="<?php echo $k; ?>" <?php if($k == $current_locale) echo 'selected'; ?>><?php echo $locale['name']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($error): ?>
                                                        <p><?php _e('Check the next requirements:');?></p>
                                                        <div class="requirements_help">
                                                            <p><b><?php _e('Requirements help:'); ?></b></p>
                                                            <ul class="list-group list-group-install mb-3">
                                                                <?php foreach($requirements as $k => $v): ?>
                                                                    <?php if(!$v['fn'] && $v['solution']): ?>
                                                                        <li class="list-group-item pt-0 pb-0"><?php echo $v['solution']; ?></li>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="mb-1"><?php _e('All right! All the requirements have met:');?></p>
                                                    <?php endif; ?>

                                                    <ul class="list-group list-group-install mb-3">
                                                        <?php foreach($requirements as $k => $v): ?>
                                                            <li class="list-group-item pt-0 pb-0">
                                                                <span class="material-icons <?php echo $v['fn'] ? 'text-success' : 'text-danger'; ?>"><?php echo $v['fn'] ? 'check' : 'clear'; ?></span>
                                                                <?php echo $v['requirement']; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>

                                                    <div class="row no-gutters mb-3">
                                                        <div class="col-lg-12 checkbox-radios">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input id="save_stats" class="form-check-input" type="checkbox" name="save_stats" value="1" />
                                                                    <?php _e('Help make Osclass Evolution better by automatically sending usage statistics and crash reports to Osclass.');?>
                                                                    <span class="form-check-sign">
                                                                        <span class="check"></span>
                                                                    </span>
                                                                </label>
                                                                <br>
                                                                <span class="pl-3">&nbsp;&nbsp;
                                                                     <?php _e("I accept Osclass Evolution SL’s <a href=\"https://osclass-evo.com/privacy-policy/\">Privacy Policy</a> and grant them permission to manage my data."); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row no-gutters">
                                                        <div class="col-md-12 mb-3">
                                                            <?php if($error): ?>
                                                                <button type="button" class="btn btn-outline-danger btn-md" onclick="document.location = 'install.php?step=1'">
                                                                    <span class="material-icons">replay</span>
                                                                    <?php echo osc_esc_html( __('Try again')); ?>
                                                                </button>
                                                            <?php else: ?>
                                                                <button type="submit" class="btn btn-info">
                                                                    <span class="material-icons">arrow_right</span>
                                                                    <?php echo osc_esc_html( __('Run the install') ); ?>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php elseif($step == 2): ?>
                                                <?php display_database_config(); ?>
                                            <?php elseif($step == 3): ?>
                                                <?php if(!isset($error["error"])): ?>
                                                    <?php display_target(); ?>
                                                <?php else: ?>
                                                    <?php display_database_error($error, ($step - 1)); ?>
                                                <?php endif; ?>
                                            <?php elseif($step == 4): ?>
                                                <?php
                                                ping_search_engines($_COOKIE['osclass_ping_engines']);
                                                setcookie('osclass_save_stats', '', time() - 3600);
                                                setcookie('osclass_ping_engines', '', time() - 3600);

                                                $source = LIB_PATH . 'osclass/installer/robots.txt';
                                                $destination = ABS_PATH . 'robots.txt';

                                                if (function_exists('copy')) {
                                                    @copy($source, $destination);
                                                } else {
                                                    $contentx = @file_get_contents($source);
                                                    $openedfile = fopen($destination, "w");

                                                    fwrite($openedfile, $contentx);
                                                    fclose($openedfile);

                                                    if ($contentx === FALSE) {
                                                        $status = false;
                                                    } else {
                                                        $status = true;
                                                    }
                                                }

                                                display_finish($password);
                                                ?>
                                            <?php endif; ?>
                                        </div>
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
                            &copy; <?php echo date('Y'); ?>, <strong>Osclass Evolution v. <?php echo preg_replace('|.0$|', '', '4.0.0'); ?></strong>

                            <?php printf('made with %s for a better web by <a href="%s" target="_blank">Osclass Evolution Team</a>', '<i class="material-icons">favorite</i>', 'https://osclass-evo.com/'); ?>
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

                md.checkFullPageBackgroundImage();

                $('.off-canvas-sidebar').perfectScrollbar();
            });
        </script>
    </body>
</html>
