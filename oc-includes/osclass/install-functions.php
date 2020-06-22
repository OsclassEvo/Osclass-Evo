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


require_once dirname(dirname(__FILE__)) . '/htmlpurifier/HTMLPurifier.auto.php';
require_once dirname(dirname(__FILE__)) . '/osclass/compatibility.php';
function _purify($value, $xss_check)
{
    if( !$xss_check ) {
        return $value;
    }

    $_config = HTMLPurifier_Config::createDefault();
    $_config->set('HTML.Allowed', '');
    $_config->set('Cache.SerializerPath', dirname(dirname(dirname(dirname(__FILE__)))) . '/oc-content/uploads/');

    $_purifier = new HTMLPurifier($_config);


    if( is_array($value) ) {
        foreach($value as $k => &$v) {
            $v = _purify($v, $xss_check); // recursive
        }
    } else {
        $value = $_purifier->purify($value);
    }

    return $value;
}
function getServerParam($param, $htmlencode = false, $xss_check = true, $quotes_encode = true)
{
    if ($param == "") return '';
    if (!isset($_SERVER[$param])) return '';
    $value = _purify($_SERVER[$param], $xss_check);
    if ($htmlencode) {
        if($quotes_encode) {
            return htmlspecialchars(stripslashes($value), ENT_QUOTES);
        } else {
            return htmlspecialchars(stripslashes($value), ENT_NOQUOTES);
        }
    }

    if(get_magic_quotes_gpc()) {
        $value = strip_slashes_extended($value);
    }

    return ($value);
}

/*
 * The url of the site
 *
 * @since 1.2
 *
 * @return string The url of the site
 */
function get_absolute_url( ) {
    $protocol = ( getServerParam('HTTPS') == 'on' || getServerParam('HTTP_X_FORWARDED_PROTO')=='https') ? 'https' : 'http';
    $pos      = strpos(getServerParam('REQUEST_URI'), 'oc-includes');
    $URI      = rtrim( substr( getServerParam('REQUEST_URI'), 0, $pos ), '/' ) . '/';
    return $protocol . '://' . getServerParam('HTTP_HOST') . $URI;
}

/*
 * The relative url on the domain url
 *
 * @since 1.2
 *
 * @return string The relative url on the domain url
 */
function get_relative_url( ) {
    $url = Params::getServerParam('REQUEST_URI', false, false);
    return substr($url, 0, strpos($url, '/oc-includes')) . "/";
}

/*
 * Get the requirements to install Osclass
 *
 * @since 1.2
 *
 * @return array Requirements
 */
function get_requirements( ) {
    $array = array(
        'PHP version >= 5.6.x' => array(
            'requirement' => __('PHP version >= 5.6.x'),
            'fn' => version_compare(PHP_VERSION, '5.6.0', '>='),
            'solution' => __('At least PHP5.6 (PHP 7.0 or higher recommended) is required to run Osclass. You may talk with your hosting to upgrade your PHP version.')),

        'MySQLi extension for PHP' => array(
            'requirement' => __('MySQLi extension for PHP'),
            'fn' => extension_loaded('mysqli'),
            'solution' => __('MySQLi extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/mysqli.setup.php">install/configure</a>.')),

        'GD extension for PHP' => array(
            'requirement' => __('GD extension for PHP'),
            'fn' => extension_loaded('gd'),
            'solution' => __('GD extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/image.setup.php">install/configure</a>.')),

        'Folder <code>oc-content/uploads</code> exists' => array(
            'requirement' => __('Folder <code>oc-content/uploads</code> exists'),
            'fn' => file_exists( ABS_PATH . 'oc-content/uploads/' ),
            'solution' => sprintf(__('You have to create <code>uploads</code> folder, i.e.: <code>mkdir %soc-content/uploads/</code>' ), ABS_PATH)),

        'Folder <code>oc-content/uploads</code> is writable' => array(
            'requirement' => __('<code>oc-content/uploads</code> folder is writable'),
            'fn' => is_writable( ABS_PATH . 'oc-content/uploads/' ),
            'solution' => sprintf(__('<code>uploads</code> folder has to be writable, i.e.: <code>chmod 0755 %soc-content/uploads/</code>'), ABS_PATH)),
        // oc-content/downlods
        'Folder <code>oc-content/downloads</code> exists' => array(
            'requirement' => __('Folder <code>oc-content/downloads</code> exists'),
            'fn' => file_exists( ABS_PATH . 'oc-content/downloads/' ),
            'solution' => sprintf(__('You have to create <code>downloads</code> folder, i.e.: <code>mkdir %soc-content/downloads/</code>' ), ABS_PATH)),

        'Folder <code>oc-content/downloads</code> is writable' => array(
            'requirement' => __('<code>oc-content/downloads</code> folder is writable'),
            'fn' => is_writable( ABS_PATH . 'oc-content/downloads/' ),
            'solution' => sprintf(__('<code>downloads</code> folder has to be writable, i.e.: <code>chmod 0755 %soc-content/downloads/</code>'), ABS_PATH)),
        // oc-content/languages
        'Folder <code>oc-content/languages</code> exists' => array(
            'requirement' => __('Folder <code>oc-content/languages</code> folder exists'),
            'fn' => file_exists( ABS_PATH . 'oc-content/languages/' ),
            'solution' => sprintf(__('You have to create the <code>languages</code> folder, i.e.: <code>mkdir %soc-content/languages/</code>'), ABS_PATH)),

        'Folder <code>oc-content/languages</code> is writable' => array(
            'requirement' => __('<code>oc-content/languages</code> folder is writable'),
            'fn' => is_writable( ABS_PATH . 'oc-content/languages/' ),
            'solution' => sprintf(__('<code>languages</code> folder has to be writable, i.e.: <code>chmod 0755 %soc-content/languages/</code>'), ABS_PATH)),
    );

    $config_writable = false;
    $root_writable = false;
    $config_sample = false;
    if( file_exists(ABS_PATH . 'config.php') ) {
        if( is_writable(ABS_PATH . 'config.php') ) {
            $config_writable = true;
        }
        $array['File <code>config.php</code> is writable'] = array(
            'requirement' => __('<code>config.php</code> file is writable'),
            'fn' => $config_writable,
            'solution' => sprintf(__('<code>config.php</code> file has to be writable, i.e.: <code>chmod 0755 %sconfig.php</code>'), ABS_PATH));
    } else {
        if (is_writable(ABS_PATH) ) {
            $root_writable = true;
        }
        $array['Root directory is writable'] = array(
            'requirement' => __('Root directory is writable'),
            'fn' => $root_writable,
            'solution' => sprintf(__('Root folder has to be writable, i.e.: <code>chmod 0755 %s</code>'), ABS_PATH));

        if( file_exists(ABS_PATH . 'config-sample.php') ) {
            $config_sample = true;
        }
        $array['File <code>config-sample.php</code> exists'] = array(
            'requirement' => __('<code>config-sample.php</code> file exists'),
            'fn' => $config_sample,
            'solution' => __('<code>config-sample.php</code> file is required, you should re-download Osclass.'));
    }

    return $array;
}


/**
 * Check if some of the requirements to install Osclass are correct or not
 *
 * @since 1.2
 *
 * @return boolean Check if all the requirements are correct
 */
function check_requirements($array) {
    foreach($array as $k => $v) {
        if( !$v['fn'] ) return true;
    }
    return false;
}

/**
 * Check if allowed to send stats to Osclass
 *
 * @return boolean Check if allowed to send stats to Osclass
 */
function reportToOsclass() {
    return $_COOKIE['osclass_save_stats'];
}

/**
 * insert/update preference allow_report_osclass
 * @param boolean $bool
 */
function set_allow_report_osclass($value) {
    $values = array(
        's_section' => 'osclass',
        's_name'    => 'allow_report_osclass',
        's_value'   => $value,
        'e_type'    => 'BOOLEAN'
    );

    Preference::newInstance()->insert($values);
}

/*
 * Install Osclass database
 *
 * @since 1.2
 *
 * @return mixed Error messages of the installation
 */
function oc_install( ) {
    $dbhost      = Params::getParam('dbhost');
    $dbname      = Params::getParam('dbname');
    $username    = Params::getParam('username');
    $password    = Params::getParam('password', false, false);
    $tableprefix = Params::getParam('tableprefix');
    $createdb    = false;
    require_once LIB_PATH . 'osclass/helpers/hSecurity.php';

    if( $tableprefix == '' ) {
        $tableprefix = 'oc_';
    }

    if( Params::getParam('createdb') != '' ) {
        $createdb = true;
    }

    if ( $createdb ) {
        $adminuser = Params::getParam('admin_username');
        $adminpwd  = Params::getParam('admin_password', false, false);

        $master_conn = new DBConnectionClass($dbhost, $adminuser, $adminpwd, '');
        $error_num   = $master_conn->getErrorConnectionLevel();

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__('Cannot connect to the database. Error number: %s') , $error_num ), __FILE__."::".__LINE__);
            }

            switch ($error_num) {
                case 1049:  return array('error' => __("The database doesn't exist. You should check the \"Create DB\" checkbox and fill in a username and password with the right privileges"));
                    break;
                case 1045:  return array('error' => __('Cannot connect to the database. Check if the user has privileges.'));
                    break;
                case 1044:  return array('error' => __('Cannot connect to the database. Check if the username and password are correct.'));
                    break;
                case 2005:  return array('error' => __("Can't resolve MySQL host. Check if the host is correct."));
                    break;
                default:    return array('error' => sprintf(__('Cannot connect to the database. Error number: %s')), $error_num);
                break;
            }
        }

        $m_db = $master_conn->getOsclassDb();
        $comm = new DBCommandClass( $m_db );
        $comm->query( sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname) );

        $error_num = $comm->getErrorLevel();

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__("Can't create the database. Error number: %s"), $error_num) , __FILE__."::".__LINE__);
            }

            if( in_array( $error_num, array(1006, 1044, 1045) ) ) {
                return array('error' => __("Can't create the database. Check if the admin username and password are correct."));
            }

            return array('error' => sprintf(__("Can't create the database. Error number: %s"),  $error_num));
        }

        unset($conn);
        unset($comm);
        unset($master_conn);
    }

    $conn      = new DBConnectionClass($dbhost, $username, $password, $dbname);
    $error_num = $conn->getErrorConnectionLevel();

    if( $error_num == 0 ) {
        $error_num = $conn->getErrorLevel();
    }

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__('Cannot connect to the database. Error number: %s'), $error_num) , __FILE__."::".__LINE__);
        }

        switch( $error_num ) {
            case 1049:  return array('error' => __("The database doesn't exist. You should check the \"Create DB\" checkbox and fill in a username and password with the right privileges"));
                break;
            case 1045:  return array('error' => __('Cannot connect to the database. Check if the user has privileges.'));
                break;
            case 1044:  return array('error' => __('Cannot connect to the database. Check if the username and password are correct.'));
                break;
            case 2005:  return array('error' => __("Can't resolve MySQL host. Check if the host is correct."));
                break;
            default:    return array('error' => sprintf(__('Cannot connect to the database. Error number: %s'), $error_num));
            break;
        }
    }

    if( file_exists(ABS_PATH . 'config.php') ) {
        if( !is_writable(ABS_PATH . 'config.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__("Can't write in config.php file. Check if the file is writable.") , __FILE__."::".__LINE__);
            }
            return array('error' => __("Can't write in config.php file. Check if the file is writable."));
        }
        create_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    } else {
        if( !file_exists(ABS_PATH . 'config-sample.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__("config-sample.php doesn't exist. Check if everything is decompressed correctly.") , __FILE__."::".__LINE__);
            }

            return array('error' => __("config-sample.php doesn't exist. Check if everything is decompressed correctly."));
        }
        if( !is_writable(ABS_PATH) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__('Can\'t copy config-sample.php. Check if the root directory is writable.') , __FILE__."::".__LINE__);
            }

            return array('error' => __('Can\'t copy config-sample.php. Check if the root directory is writable.'));
        }
        copy_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    }

    require_once ABS_PATH . 'config.php';

    $sql = file_get_contents( ABS_PATH . 'oc-includes/osclass/installer/struct.sql' );

    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );
    $comm->importSQL($sql);

    $error_num = $comm->getErrorLevel();

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__("Can't create the database structure. Error number: %s"), $error_num)  , __FILE__."::".__LINE__);
        }

        switch ($error_num) {
            case 1050:  return array('error' => __('There are tables with the same name in the database. Change the table prefix or the database and try again.'));
                break;
            default:    return array('error' => sprintf(__("Can't create the database structure. Error number: %s"), $error_num));
            break;
        }
    }

    require_once LIB_PATH . 'osclass/model/OSCLocale.php';
    $localeManager = OSCLocale::newInstance();

    $locales = osc_listLocales();
    $values = array(
        'pk_c_code'         => $locales[osc_current_admin_locale()]['code'],
        's_name'            => $locales[osc_current_admin_locale()]['name'],
        's_short_name'      => $locales[osc_current_admin_locale()]['short_name'],
        's_description'     => $locales[osc_current_admin_locale()]['description'],
        's_version'         => $locales[osc_current_admin_locale()]['version'],
        's_author_name'     => $locales[osc_current_admin_locale()]['author_name'],
        's_author_url'      => $locales[osc_current_admin_locale()]['author_url'],
        's_currency_format' => $locales[osc_current_admin_locale()]['currency_format'],
        's_date_format'     => $locales[osc_current_admin_locale()]['date_format'],
        'b_enabled'         => 1,
        'b_enabled_bo'      => 1
    );

    if( isset($locales[osc_current_admin_locale()]['stop_words']) ) {
        $values['s_stop_words'] = $locales[osc_current_admin_locale()]['stop_words'];
    }
    $localeManager->insert($values);


    $required_files = array(
        ABS_PATH . 'oc-includes/osclass/installer/basic_data.sql',
        ABS_PATH . 'oc-includes/osclass/installer/pages.sql',
        ABS_PATH . 'oc-content/languages/' . osc_current_admin_locale() . '/mail.sql',
    );

    $sql = '';
    foreach($required_files as $file) {
        if ( !file_exists($file) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__('The file %s doesn\'t exist'), $file) , __FILE__."::".__LINE__);
            }

            return array('error' => sprintf(__('The file %s doesn\'t exist'), $file) );
        } else {
            $sql .= file_get_contents($file);
        }
    }
    $comm->importSQL($sql);

    $error_num = $comm->getErrorLevel();

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__("Can't insert basic configuration. Error number: %s"), $error_num)  , __FILE__."::".__LINE__);
        }

        switch ($error_num) {
            case 1471:  return array('error' => __("Can't insert basic configuration. This user has no privileges to 'INSERT' into the database."));
                break;
            default:    return array('error' => sprintf(__("Can't insert basic configuration. Error number: %s"), $error_num));
            break;
        }
    }

    osc_set_preference('language', osc_current_admin_locale());
    osc_set_preference('admin_language', osc_current_admin_locale());
    osc_set_preference('csrf_name', 'CSRF'.mt_rand(0,mt_getrandmax()));

    oc_install_example_data();

    if( reportToOsclass() ) {
        set_allow_report_osclass( true );
    } else {
        set_allow_report_osclass( false );
    }

    return false;
}

/*
 * Insert the example data (categories and emails) on all available locales
 *
 * @since 2.4
 *
 * @return mixed Error messages of the installation
 */
function oc_install_example_data() {
    require_once LIB_PATH . 'osclass/formatting.php';
    require LIB_PATH . 'osclass/installer/basic_data.php';
    require_once LIB_PATH . 'osclass/model/Category.php';
    $mCat = Category::newInstance();

    if(!function_exists('osc_apply_filter')) {
        function osc_apply_filter($dummyfilter, $str) {
            return $str;
        }
    }


    foreach($categories as $category) {

        $fields['pk_i_id']              = $category['pk_i_id'];
        $fields['fk_i_parent_id']       = $category['fk_i_parent_id'];
        $fields['i_position']           = $category['i_position'];
        $fields['i_expiration_days']    = 0;
        $fields['b_enabled']            = 1;

        $aFieldsDescription[osc_current_admin_locale()]['s_name'] = $category['s_name'];

        $mCat->insert($fields, $aFieldsDescription);
    }

    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/model/ItemComment.php';
    require_once LIB_PATH . 'osclass/model/ItemLocation.php';
    require_once LIB_PATH . 'osclass/model/ItemResource.php';
    require_once LIB_PATH . 'osclass/model/ItemStats.php';
    require_once LIB_PATH . 'osclass/model/User.php';
    require_once LIB_PATH . 'osclass/model/Country.php';
    require_once LIB_PATH . 'osclass/model/Region.php';
    require_once LIB_PATH . 'osclass/model/City.php';
    require_once LIB_PATH . 'osclass/model/CityArea.php';
    require_once LIB_PATH . 'osclass/model/Field.php';
    require_once LIB_PATH . 'osclass/model/Page.php';
    require_once LIB_PATH . 'osclass/model/Log.php';

    require_once LIB_PATH . 'osclass/model/CategoryStats.php';
    require_once LIB_PATH . 'osclass/model/CountryStats.php';
    require_once LIB_PATH . 'osclass/model/RegionStats.php';
    require_once LIB_PATH . 'osclass/model/CityStats.php';

    require_once LIB_PATH . 'osclass/helpers/hSecurity.php';
    require_once LIB_PATH . 'osclass/helpers/hValidate.php';
    require_once LIB_PATH . 'osclass/helpers/hUsers.php';
    require_once LIB_PATH . 'osclass/ItemActions.php';

    $mItem = new ItemActions(true);

    foreach($item as $k => $v) {
        if($k=='description' || $k=='title') {
            Params::setParam($k, array(osc_current_admin_locale() => $v));
        } else {
            Params::setParam($k, $v);
        }
    }

    $mItem->prepareData(true);
    $successItem = $mItem->add();

    $successPageresult = Page::newInstance()->insert(
        array(
            's_internal_name' => $page['s_internal_name'],
            'b_indelible' => 0,
            's_meta' => json_encode('')
        ),
        array(
            osc_current_admin_locale() => array(
                's_title' => $page['s_title'],
                's_text' => $page['s_text']
            )
        ));

}


/*
 * Create config file from scratch
 *
 * @since 1.2
 *
 * @param string $dbname Database name
 * @param string $username User of the database
 * @param string $password Password for user of the database
 * @param string $dbhost Database host
 * @param string $tableprefix Prefix for table names
 * @return mixed Error messages of the installation
 */
function create_config_file($dbname, $username, $password, $dbhost, $tableprefix) {
    $password = addslashes($password);
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_text = <<<CONFIG
<?php
/**
 * The base MySQL settings of Osclass
 */
define('MULTISITE', 0);

/** MySQL database name for Osclass */
define('DB_NAME', '$dbname');

/** MySQL database username */
define('DB_USER', '$username');

/** MySQL database password */
define('DB_PASSWORD', '$password');

/** MySQL hostname */
define('DB_HOST', '$dbhost');

/** Database Table prefix */
define('DB_TABLE_PREFIX', '$tableprefix');

define('REL_WEB_URL', '$rel_url');

define('WEB_PATH', '$abs_url');

CONFIG;

    file_put_contents(ABS_PATH . 'config.php', $config_text);
}

/*
 * Create config from config-sample.php file
 *
 * @since 1.2
 */
function copy_config_file($dbname, $username, $password, $dbhost, $tableprefix) {
    $password = addslashes($password);
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_sample = file(ABS_PATH . 'config-sample.php');

    foreach ($config_sample as $line_num => $line) {
        switch (substr($line, 0, 16)) {
            case "define('DB_NAME'":
                $config_sample[$line_num] = str_replace("database_name", $dbname, $line);
                break;
            case "define('DB_USER'":
                $config_sample[$line_num] = str_replace("'username'", "'$username'", $line);
                break;
            case "define('DB_PASSW":
                $config_sample[$line_num] = str_replace("'password'", "'$password'", $line);
                break;
            case "define('DB_HOST'":
                $config_sample[$line_num] = str_replace("localhost", $dbhost, $line);
                break;
            case "define('DB_TABLE":
                $config_sample[$line_num] = str_replace('oc_', $tableprefix, $line);
                break;
            case "define('REL_WEB_":
                $config_sample[$line_num] = str_replace('rel_here', $rel_url, $line);
                break;
            case "define('WEB_PATH":
                $config_sample[$line_num] = str_replace('http://localhost', $abs_url, $line);
                break;
        }
    }

    $handle = fopen(ABS_PATH . 'config.php', 'w');
    foreach( $config_sample as $line ) {
        fwrite($handle, $line);
    }
    fclose($handle);
    chmod(ABS_PATH . 'config.php', 0666);
}


function is_osclass_installed( ) {
    if( !file_exists( ABS_PATH . 'config.php' ) ) {
        return false;
    }

    require_once ABS_PATH . 'config.php';

    $conn = new DBConnectionClass( osc_db_host(), osc_db_user(), osc_db_password(), osc_db_name() );
    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );
    $rs   = $comm->query( sprintf( "SELECT * FROM %st_preference WHERE s_name = 'osclass_installed'", DB_TABLE_PREFIX ) );

    if( $rs == false ) {
        return false;
    }

    if( $rs->numRows() != 1 ) {
        return false;
    }

    return true;
}

function finish_installation( $password ) {
    require_once LIB_PATH . 'osclass/model/Admin.php';
    require_once LIB_PATH . 'osclass/model/Category.php';
    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
    require_once LIB_PATH . 'osclass/classes/Plugins.php';

    $data = array();

    $mAdmin = new Admin();

    $mPreference = Preference::newInstance();
    $mPreference->insert (
        array(
            's_section' => 'osclass'
        ,'s_name' => 'osclass_installed'
        ,'s_value' => '1'
        ,'e_type' => 'BOOLEAN'
        )
    );

    $admin = $mAdmin->findByPrimaryKey(1);

    $data['s_email'] = $admin['s_email'];
    $data['admin_user'] = $admin['s_username'];
    $data['password'] = $password;

    return $data;
}

/* Menus */
function display_database_config() {
    ?>
    <form id="form-db-install" class="has-form-actions form-horizontal" action="install.php" method="post">
        <input type="hidden" name="step" value="3" />
        <input id="form-validated" type="hidden" name="form_validated" value="0" />

        <fieldset class="mb-3">
            <legend><?php _e('Database information'); ?></legend>

            <div class="row no-gutters mt-3">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Host'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 d-inline" name="dbhost" required="true" value="localhost" />
                        <span class="form-text text-muted"><?php _e('Server name or IP where the database engine resides'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Database name'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 d-inline" name="dbname" required="true" value="osclass" />
                        <span class="form-text text-muted"><?php _e('The name of the database you want to run Osclass Evolution in'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('User Name'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="username" type="text" class="form-control w-100 d-inline" name="username" required="true" />
                        <span class="form-text text-muted"><?php _e('Your MySQL username'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Password'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="password" type="password" class="form-control w-100 d-inline" name="password" required="true" />
                        <span class="form-text text-muted"><?php _e('Your MySQL password'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Table prefix'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input type="text" class="form-control w-100 d-inline" name="tableprefix" required="true" value="oc_" />
                        <span class="form-text text-muted"><?php _e('If you want to run multiple Osclass installations in a single database, change this'); ?></span>
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="row no-gutters">
            <div class="col-lg-12 border-bottom border-info mb-3 ml-3">
                <a id="advanced_install" class="shrink" href="javascript:;">
                    <span class="material-icons">chevron_right</span>
                    <span><?php _e('Advanced'); ?></span>
                </a>
            </div>
        </div>

        <fieldset id="more-options" class="mb-3 fc-limited">
            <div class="row no-gutters">
                <div class="col-lg-11 ml-3 checkbox-radios">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input id="createdb" class="form-check-input" type="checkbox" name="createdb" value="1" />
                            <?php _e('Create DB');?>
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>

                        <span class="form-text text-muted"><?php _e('Check here if the database is not created and you want to create it now'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('DB admin username'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="admin_username" type="text" class="form-control w-100 d-inline" name="admin_username" required="true" disabled />
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('DB admin password'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="admin_password" type="password" class="form-control w-100 d-inline" name="admin_password" required="true" disabled />
                        <span id="password_copied" class="form-text text-success fc-limited"><?php _e('Password copied from above'); ?></span>
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="row no-gutters">
            <div class="col-md-12 mb-3 ml-3">
                <button id="btn-db-install" type="submit" class="btn btn-info">
                    <span class="material-icons">forward</span>
                    <?php echo osc_esc_html( __('Next') ); ?>
                </button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#advanced_install').click(function() {
                $('#more-options').toggle();
                if( $('#advanced_install').attr('class') == 'shrink' ) {
                    $('#advanced_install').removeClass('shrink');
                    $('#advanced_install .material-icons').text('expand_more');
                } else {
                    $('#advanced_install').addClass('shrink');
                    $('#advanced_install .material-icons').text('chevron_right');
                }
            });

            $('#createdb').on('click', function() {
                if($("#createdb").is(':checked')) {
                    $("#admin_username").removeAttr('disabled');
                    $("#admin_password").removeAttr('disabled');

                    if($("#admin_username").val() == '') {
                        $("#admin_username").val($("#username").val());
                    }

                    if($("#admin_password").val() == '') {
                        $("#admin_password").val($("#password").val());

                        if($("#password").val()) {
                            $("#password_copied").show();
                        }
                    }
                } else {
                    $("#admin_username").val('');
                    $("#admin_password").val('');
                    $("#admin_username").attr('disabled', true);
                    $("#admin_password").attr('disabled', true);
                    $("#password_copied").hide();
                }
            });

            $('#form-db-install').submit(function(e) {
                if($('#form-validated').val() == 0) {
                    e.preventDefault();
                    $('#form-validated').val(1)
                }

                $('#form-db-install').waitMe({
                    effect : 'stretch',
                    text : '<?php _e('Please wait...'); ?>',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000',
                    maxSize : '',
                    waitTime : 5000,
                    textPos : 'vertical',
                    fontSize : '18px',
                    onClose : function() {
                        $('#form-db-install').waitMe({
                            effect : 'stretch',
                            text : '<?php _e('Please wait...'); ?>',
                            bg : 'rgba(255,255,255,0.7)',
                            color : '#000',
                            maxSize : '',
                            waitTime : -1,
                            textPos : 'vertical',
                            fontSize : '18px'
                        });

                        $('#form-db-install').submit();
                    }
                });
            });
        });
    </script>
<?php
}

function display_target() {
    require_once LIB_PATH . 'osclass/helpers/hUtils.php';
    /*
    $country_list = osc_file_get_contents('https://geo.osclass.org/newgeo.services.php?action=countries');
    $country_list = json_decode(substr($country_list, 1, strlen($country_list)-2), true);

    $region_list = array();

    $country_ip = '';
    if(preg_match('|([a-z]{2})-([A-Z]{2})|', Params::getServerParam('HTTP_ACCEPT_LANGUAGE'), $match)) {
        $country_ip = $match[2];
        $region_list = osc_file_get_contents('https://geo.osclass.org/newgeo.services.php?action=regions&country='.$match[2]);
        $region_list = json_decode(substr($region_list, 1, strlen($region_list)-2), true);
    }
    */

    $countries = osc_get_countries_list_api();

    $country_ip = '';

    if(preg_match('/([a-z]{2})-([A-Z]{2})|([a-z]{2})/', Params::getServerParam('HTTP_ACCEPT_LANGUAGE'), $match)) {
        if(!$match[1]) {
            $country_ip = $match[0];
        } else {
            $country_ip = $match[1];
        }
    }

    if(!isset($countries[0]) || !isset($countries[0]['name'])) {
        $internet_error = true;
    }
    ?>
    <form id="target_form" class="has-form-actions form-horizontal" name="target_form" action="#" method="post">
        <input id="form-validated" type="hidden" name="form_validated" value="0" />

        <fieldset class="mb-3">
            <legend><?php _e('Admin user'); ?></legend>

            <div class="row no-gutters mt-3">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Username'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="admin_user" type="text" class="form-control w-100 d-inline" name="s_name" required="true" value="admin" />
                        <span class="form-text text-muted"><?php _e('You can modify username and password if you like, just change the input value.'); ?></span>
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Password'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="s_passwd" type="password" class="form-control w-100 d-inline" name="s_passwd" />
                        <span class="form-text text-muted"><?php _e('A password will be automatically generated for you if you leave this blank.'); ?></span>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend><?php _e('Contact information'); ?></legend>

            <div class="row no-gutters mt-3">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Web title'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="webtitle" type="text" class="form-control w-100 d-inline" name="webtitle" required="true" />
                    </div>
                </div>
            </div>

            <div class="row no-gutters">
                <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Contact e-mail'); ?></label>
                <div class="col-lg-9">
                    <div class="form-group">
                        <input id="email" type="email" class="form-control w-100 d-inline" name="email" required="true" />
                        <span class="form-text text-muted"><?php _e('Put your e-mail here'); ?></span>
                    </div>
                </div>
            </div>
        </fieldset>

        <?php if(!$internet_error): ?>
            <input type="hidden" id="skip-location-input" name="skip_location_input" value="0" />
            <input type="hidden" id="country-input" name="country-input" value="" />
            <input type="hidden" id="region-input" name="region-input" value="" />
            <input type="hidden" id="city-input" name="city-input" value="" />

            <fieldset class="mb-3">
                <legend><?php _e('Location'); ?></legend>

                <div class="row no-gutters">
                    <label class="col-lg-1 col-form-label form-label text-left"><?php _e('Choose country where your target users are located'); ?></label>
                    <div class="col-lg-9">
                        <select id="location-api" class="selectpicker show-tick" name="location_api" data-dropup-auto="false" data-size="7" data-width="75%" data-style="btn btn-info btn-sm">
                            <option value="skip"><?php _e("Skip location"); ?></option>

                            <?php foreach($countries as $c): ?>
                                <option value="<?php echo $c['country_code']; ?>" <?php if($c['country_code'] == $country_ip) echo 'selected'; ?>><?php echo $c['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </fieldset>
        <?php else: ?>
            <input type="hidden" id="skip-location-input" name="skip_location_input" value="1" />
        <?php endif; ?>

        <div class="row no-gutters">
            <div class="col-md-12 mb-3 ml-3">
                <button id="btn-db-install" type="submit" class="btn btn-info">
                    <span class="material-icons">forward</span>
                    <?php echo osc_esc_html( __('Next') ); ?>
                </button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#target_form').submit(function(e) {
                if($('#form-validated').val() == 0) {
                    e.preventDefault();
                    $('#form-validated').val(1)
                }

                var input = $("#target_form input, #target_form select");

                $('#target_form').waitMe({
                    effect : 'stretch',
                    text : '<?php _e('Please wait...'); ?>',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000',
                    maxSize : '',
                    waitTime : 5000,
                    textPos : 'vertical',
                    fontSize : '18px',
                    onClose : function() {
                        $('#target_form').waitMe({
                            effect : 'stretch',
                            text : '<?php _e('Please wait...'); ?>',
                            bg : 'rgba(255,255,255,0.7)',
                            color : '#000',
                            maxSize : '',
                            waitTime : -1,
                            textPos : 'vertical',
                            fontSize : '18px'
                        });

                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: 'install-location.php',
                            data: input,
                            timeout: 600000,
                            success: function(data) {
                                if(data.status == true) {
                                    var form = document.createElement("form");
                                    form.setAttribute("method", 'POST');
                                    form.setAttribute("action", 'install.php');

                                    var hiddenField = document.createElement("input");
                                    hiddenField.setAttribute("type", "hidden");
                                    hiddenField.setAttribute("name", 'step');
                                    hiddenField.setAttribute("value", '4');
                                    form.appendChild(hiddenField);

                                    hiddenField = document.createElement("input");
                                    hiddenField.setAttribute("type", "hidden");
                                    hiddenField.setAttribute("name", 'result');
                                    hiddenField.setAttribute("value", data.email_status);
                                    form.appendChild(hiddenField);

                                    hiddenField = document.createElement("input");
                                    hiddenField.setAttribute("type", "hidden");
                                    hiddenField.setAttribute("name", 'password');
                                    hiddenField.setAttribute("value", data.password);
                                    form.appendChild(hiddenField);

                                    document.body.appendChild(form);
                                    form.submit();

                                } else {
                                    $('#target_form').waitMe('hide');

                                    alert("Error:<br/>"+data);
                                    window.location = 'install.php?step=4&error_location=1';
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php
}

function display_database_error($error ,$step) {
    ?>
    <p><?php echo $error['error']?></p>
    <p><a href="<?php echo get_absolute_url(); ?>oc-includes/osclass/install.php?step=<?php echo $step; ?>" class="btn btn-info btn-md"><?php _e('Go back'); ?></a></p>
<?php
}

function ping_search_engines($bool){
    $mPreference = Preference::newInstance();
    if($bool == 1){
        $mPreference->insert (
            array(
                's_section' => 'osclass'
            ,'s_name'   => 'ping_search_engines'
            ,'s_value'  => '1'
            ,'e_type'   => 'BOOLEAN'
            )
        );
        // GOOGLE
        osc_doRequest( 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode(osc_search_url(array('sFeed' => 'rss') )), array());
        // BING
        osc_doRequest( 'http://www.bing.com/webmaster/ping.aspx?siteMap='.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
        // YAHOO!
        osc_doRequest( 'http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
    } else {
        $mPreference->insert (
            array(
                's_section' => 'osclass'
            ,'s_name'   => 'ping_search_engines'
            ,'s_value'  => '0'
            ,'e_type'   => 'BOOLEAN'
            )
        );
    }
}
function display_finish($password) {
    $data = finish_installation( $password );
    ?>
    <?php if(Params::getParam('error_location') == 1): ?>
        <div class="alert alert-danger">
            <?php _e('The selected location could not been installed'); ?>
        </div>
    <?php endif; ?>

    <h3 class="text-success mt-0"><?php _e('Congratulations!');?></h3>
    <p class="ml-3"><?php _e("Osclass Evolution has been installed. Were you expecting more steps? Sorry to disappoint you!"); ?> <span class="material-icons">tag_faces</span></p>
    <p class="ml-3"><?php echo sprintf(__('An e-mail with the password for oc-admin has been sent to: <strong class="text-info">%s</strong>'), $data['s_email']);?></p>

    <div class="mark rounded p-3 mb-3">
        <p><strong><?php _e('Username'); ?></strong>: <?php echo $data['admin_user']; ?></p>
        <p class="mb-0"><strong><?php _e('Password'); ?></strong>: <?php echo osc_esc_html($data['password']); ?></p>
    </div>

    <a target="_blank" href="<?php echo get_absolute_url() ?>oc-admin/index.php" class="btn btn-success btn-md mb-4">
        <span class="material-icons">double_arrow</span>
        <?php _e('Finish and go to the administration panel');?>
    </a>
<?php
}
?>
