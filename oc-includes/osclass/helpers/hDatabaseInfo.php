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
    * Helper Database Info
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets database name
     *
     * @return string
     */
    function osc_db_name() {
        return getSiteInfo('s_db_name', DB_NAME);
    }

    /**
     * Gets database host
     *
     * @return string
     */
    function osc_db_host() {
        return getSiteInfo('s_db_host', DB_HOST);
    }

    /**
     * Gets database user
     *
     * @return string
     */
    function osc_db_user() {
        return getSiteInfo('s_db_user', DB_USER);
    }

    /**
     * Gets database password
     *
     * @return string
     */
    function osc_db_password() {
        return getSiteInfo('s_db_password', DB_PASSWORD);
    }

    /**
     * Gets database MySQL version
     *
     * @since Osclass Evolution 4.3.0
     * @return string
     */
    function osc_db_mysql_version($int = false) {
        $conn = DBConnectionClass::newInstance()->connId;
        $version = $conn->server_info;

        if($int) {
            return preg_match("/^(\d+)\.(\d+)\.(\d+)/", $version, $m) ? (int)sprintf("%d%02d%02d", $m[1], $m[2], $m[3]) : 0;
        }

        return $version;
    }

    /**
     * Gets database tables tree
     *
     * @since Osclass Evolution 4.3.0
     * @return mixed
     */
    function osc_db_tables() {
        $conn = DBConnectionClass::newInstance()->connId;
        $conn->select_db(osc_db_name());

        $tables = $conn->query('SHOW TABLE STATUS');

        $objects = array();

        if($tables){
            $db_size = 0;

            while($item = $tables->fetch_assoc()) {
                if(osc_db_mysql_version(true) > 40101 && is_null($item['Engine']) && preg_match('/^VIEW/i', $item['Comment'])) {
                    $objects[]= $item['Name'];
                } else{
                    $objects[] = array($item['Name'], $item['Rows'], $item['Data_length']);

                    $db_size += $item['Data_length'];
                }
            }

            if (osc_db_mysql_version(true) > 50014) {
                $shows = array(
                    "PROCEDURE STATUS WHERE db='{" . osc_db_name() . "}'",
                    "FUNCTION STATUS WHERE db='{" . osc_db_name() . "}'",
                    'TRIGGERS'
                );

                if(osc_db_mysql_version(true) > 50100) $shows[] = "EVENTS WHERE db='{" . osc_db_name() . "}'";

                for($i = 0, $l = count($shows); $i < $l; $i++) {
                    $tables = $conn->query('SHOW ' . $shows[$i]);

                    if($tables && $tables->num_rows > 0) {
                        $col_name = $shows[$i] == 'TRIGGERS' ? 'Trigger' : 'Name';
                        $type = substr($shows[$i], 0, 2);

                        while($item = $tables->fetch_assoc()) {
                            $objects[$type][] = $item[$col_name];
                        }
                    }
                }
            }
        }

        $objects['db_size'] = $db_size;

        return $objects;
    }

    /**
     * Gets multisite url
     *
     * @return string
     */
    function osc_multisite_url() {
        if( getSiteInfo('s_site_mapping', '') !== '' ) {
            return getSiteInfo('s_site_mapping', '');
        }
        return getSiteInfo('s_site', '');
    }

    /**
     * Gets multisite url
     *
     * @return string
     */
    function osc_multisite_upload_path() {
        return getSiteInfo('s_upload_path', '');
    }

    //PRIVATE FUNCTION FOR GETTING NO BOOLEAN INFORMATION (if there was a class :P)
    /**
     * Gets site info
     *
     * @param string $key
     * @param string $default_value
     * @return string
     */
    function getSiteInfo($key, $default_value) {
        if (MULTISITE) {
            $_P = SiteInfo::newInstance();
            return($_P->get($key));
        }

        return $default_value;
    }
?>
