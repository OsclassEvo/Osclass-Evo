<?php if ( !defined('ABS_PATH') ) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
 * Model database for Dump database tables
 *
 * @package Osclass
 * @subpackage Model
 * @since unknown
 */
class Dump extends DAO
{
    /**
     * It references to self object: Dump.
     * It is used as a singleton
     *
     * @access private
     * @since unknown
     * @var Dump
     */
    private static $instance;

    public $fh_rtl;
    public $fh_log;
    public $rtl;

    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Set data
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Return all tables from database
     *
     * @return array
     */
    function showTables()
    {
        $res = $this->dao->query('SHOW TABLES;');
        if($res) {
            return $res->result();
        } else {
            return array();
        }
    }

    protected function addLog($str){
        fwrite($this->fh_log, $str . "\n");
    }

    protected function log_write($job){
        $conn = DBConnectionClass::newInstance()->connId;
        $conn->select_db(osc_db_name());

        $conn->query("SET SQL_QUOTE_SHOW_CREATE = 1");

        if(osc_db_mysql_version(true) > 40101) $conn->query("SET SESSION character_set_results = 'binary'") or die('Error');

        $no_cache = osc_db_mysql_version(true) < 40101 ? 'SQL_NO_CACHE ' : '';

        foreach($job['todo'] AS $t => $o) {
            if(empty($this->rtl[4])) $this->rtl[4] = $t;
            elseif ($this->rtl[4] != $t) continue;

            foreach($o AS $n) {
                if(empty($this->rtl[5])) {
                    $this->rtl[5] = $n[1];
                    $this->rtl[7] = 0;
                    $this->rtl[8] = !empty($n[4]) ? $n[4] : 0;
                } elseif ($this->rtl[5] != $n[1]) continue;

                $from = '';
                if($this->rtl[7] == 0) {
                    $r = $conn->query("SHOW CREATE TABLE `{$n[1]}`") or die('Error');

                    $this->addLog(sprintf(__('Export table: `%s`'), $n[1]));
                    $this->rtl[7] = 0;
                }

                $r = $conn->query("SHOW COLUMNS FROM `{$n[1]}`") or die('Error');
                $r = $conn->query("SELECT {$no_cache}* FROM `{$n[1]}`{$from}", MYSQLI_USE_RESULT);

                while($row = $r->fetch_row()) {
                    $this->rtl[7]++;
                    $this->rtl[10]++;
                }

                unset($row);

                $this->rtl[5] = '';
            }

            $this->rtl[4] = '';
        }

        $this->rtl[5] = round(microtime(1) - $this->rtl[11], 4);
        $this->rtl[6] = '';
        $this->rtl[7] = 0;
        $this->rtl[8] = 0;

        fclose($this->fh_log);
        $this->rtl[4] = 'EOJ';
        fseek($this->fh_rtl, 0);
        fwrite($this->fh_rtl, implode("\t", $this->rtl));
        fclose($this->fh_rtl);

        unset($this->rtl);
    }

    function dump_log($file) {
        $conn = DBConnectionClass::newInstance()->connId;
        $conn->select_db(osc_db_name());

        $job['obj'] = array('TA' => array('*'));

        $queries = array(
            array('TABLE STATUS', 'Name', 'TA')
        );

        $todo = $header = array();
        $tabs = $rows = 0;
        $only_create = explode(' ', 'MRG_MyISAM MERGE HEAP MEMORY');

        foreach($queries AS $query){
            $t = $query[2];

            $r = $conn->query('SHOW ' . $query[0]) or die('Error');

            if (!$r) continue;

            $todo[$t] = array();
            $header[$t] = array();

            while($item = $r->fetch_assoc()){
                $n = $item[$query[1]];

                $engine = osc_db_mysql_version(true) > 40101 ? $item['Engine'] : $item['Type'];

                $t = in_array($engine, $only_create) ? 'TC' : 'TA';

                $todo['TA'][]   = array($t, $n, !empty($item['Collation']) ? $item['Collation'] : '', $item['Auto_increment'], $item['Rows'], $item['Data_length']);
                $header['TA'][] = "{$n}`{$item['Rows']}`{$item['Data_length']}";
                $tabs++;
                $rows += $item['Rows'];
            }

        }

        $file_size = 0;

        if(file_exists($file)) {
            $file_size = filesize($file);
        }

        $job['job'] = uniqid('dump_');

        $job['file_job'] = osc_content_path() . 'downloads/oc-backup/' . $job['job'] . '.job.php';
        $job['file_rtl'] = osc_content_path() . 'downloads/oc-backup/' . $job['job'] . '.rtl';
        $job['file_log'] = osc_content_path() . 'downloads/oc-backup/' . $job['job'] . '.log';

        $this->fh_rtl = fopen($job['file_rtl'], 'wb');
        $this->fh_log = fopen($job['file_log'], 'wb');
        $this->rtl = array(time(), time(), $rows, $file_size, '', '', '', 0, 0, 0, 0, microtime(1), "\n");

        $job['todo'] = $todo;

        file_put_contents(osc_content_path() . 'downloads/oc-backup/' . $job['job'] . '.job.php', "<?php\n\$log = " . var_export($job, true) . ";");

        $this->log_write($job);

        return $job;
    }

    /**
     * Dump into path the table structure of $table
     *
     * @param string $path
     * @param string $table
     * @return bool
     */
    function table_structure($path, $file, $table)
    {
        if ( !is_writable($path) ) return false;

        $file_path = $path . $file;

        $_str = "--\n";
        $_str .= "-- TABLE STRUCTURE FOR TABLE: `" . $table . "`\n";
        $_str .= "--\n\n";

        $sql = 'SHOW CREATE TABLE `' . $table . '`;';

        $result = $this->dao->query($sql);
        if($result) {
            $result =  $result->result();
        } else {
            $result =  array();
        }

        foreach($result as $_line) {
            $str_sql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $_line['Create Table'] . ';');
//            $str_sql .= "\n";
        }

        preg_match_all('#\s\s[^PRIMARY\s][A-Z].+#', $str_sql, $alter_tables);

        $clear_table = preg_replace('#\s\s([^PRIMARY\s][A-Z]).+(\s)#', '', $str_sql);
        $clear_table = preg_replace('#,\s\)#', "\n)", $clear_table) . "\n";

        $f = fopen($file_path, "a");
        fwrite($f, $_str . $clear_table);
        fclose($f);

        if(is_array($alter_tables) && count($alter_tables[0])) {
            $alter_table = "--\n";
            $alter_table .= "-- TABLE INDEXES FOR TABLE `" . $table . "`\n";
            $alter_table .= "--\n";

            $alter_table .= "ALTER TABLE `" . $table . "`\n";

            foreach($alter_tables[0] as $index => $a) {
                $alter_table .= "\tADD" . $a;

                if(count($alter_tables[0]) == $index + 1) {
                    $alter_table .= ";\n";
                }

                $alter_table .= "\n";
            }

            $fa = fopen($path . 'alter_tables.sql', "a");
            fwrite($fa, $alter_table);
            fclose($fa);
        }

        return true;
    }

    /**
     * Dump all table rows into path
     *
     * @param type $path
     * @param type $table
     * @return bool
     */
    function table_data($path, $file, $table)
    {
        if ( !is_writable($path) ) return false;

        $file_path = $path . $file;

        $this->dao->select();
        $this->dao->from($table);
        $res = $this->dao->get();
        if($res) {
            $result = $res->result();
        } else {
            $result = array();
        }

        $_str = '';
        if($res) {
            $num_rows   = $res->numRows();
            $num_fields = $res->numFields();
            $fields     = $res->resultId->fetch_fields();

            if( $num_rows > 0 ) {
                $_str .= "\n--\n";
                $_str .= "-- DUMP DATA TABLE: `" . $table . "`\n";
                $_str .= "--\n\n";

                $field_type = array();
                $i = 0;

                while ($meta = $res->resultId->fetch_field()) {
                    array_push($field_type, $meta->type);
                }

                $_str .= 'INSERT INTO `' . $table . '` VALUES';
                $_str .= "\n";

                $index = 0;

                if($table==DB_TABLE_PREFIX.'t_category') {
                    $this->_dump_table_category($result, $num_fields, $field_type, $fields, $index, $num_rows, $_str);
                } else {
                    foreach($result as $row) {
                        $_str .= "(";
                        for( $i = 0; $i < $num_fields; $i++ ) {
                            $v = $row[$fields[$i]->name];
                            if(is_null($v)) {
                                $_str .= 'null';
                            } else {
                                $this->_quotes($fields[$i]->type, $_str, $row[$fields[$i]->name]);
                            }
                            if($i < $num_fields-1) {
                                $_str .= ',';
                            }
                        }
                        $_str .= ')';

                        if($index < $num_rows-1) {
                            $_str .= ',';
                        } else {
                            $_str .= ';';
                        }
                        $_str .= "\n";

                        $index++;
                    }
                }

                $_str .= "\n-- --------------------------------------------------------\n";
            }
        }

        $_str .= "\n";

        $f = fopen($file_path, "a");
        fwrite($f, $_str);
        fclose($f);

        return true;
    }

    /**
     * Specific dump for t_category table
     *
     * @param type $result
     * @param type $num_fields
     * @param type $field_type
     * @param type $fields
     * @param type $index
     * @param type $num_rows
     * @param type $_str
     */
    private function _dump_table_category($result, $num_fields, $field_type, $fields, $index, $num_rows, &$_str)
    {
        $short_rows = array();
        $unshort_rows = array();
        foreach($result as $row) {
            if(($row['fk_i_parent_id']) == NULL) {
                $short_rows[] = $row;
            } else {
                $unshort_rows[$row['pk_i_id']] = $row;
            }
        }

        while(!empty($unshort_rows)) {
            foreach($unshort_rows as $k => $v) {
                foreach($short_rows as $r) {
                    if($r['pk_i_id']==$v['fk_i_parent_id']) {
                        unset($unshort_rows[$k]);
                        $short_rows[] = $v;
                    }
                }
            }
        }

        foreach($short_rows as $row) {
            $_str .= "(";
            for( $i = 0; $i < $num_fields; $i++ ) {
                $v = $row[$fields[$i]->name];
                if(is_null($v)) {
                    $_str .= 'null';
                } else {
                    $this->_quotes($fields[$i]->type, $_str, $v);
                }
                if($i < $num_fields-1) {
                    $_str .= ',';
                }
            }
            $_str .= ')';

            if($index < $num_rows-1) {
                $_str .= ',';
            } else {
                $_str .= ';';
            }
            $_str .= "\n";

            $index++;
        }
    }

    /**
     * Add quotes if it's necessary
     *
     * data type =>  http://www.php.net/manual/es/mysqli-result.fetch-field.php#106064
     *
     * @param type $type
     * @param type $_str
     * @param type $value
     */
    private function _quotes($type, &$_str, $value)
    {
//            * numeric *
//            BIT: 16 - TINYINT: 1 - BOOL: 1 - SMALLINT: 2 - MEDIUMINT: 9
//            INTEGER: 3 - BIGINT: 8 - SERIAL: 8 - FLOAT: 4 - DOUBLE: 5
//            DECIMAL: 246 - NUMERIC: 246 - FIXED: 246
//            * dates *
//            DATE: 10 - DATETIME: 12 - TIMESTAMP: 7 - TIME: 11 - YEAR: 13
//            * strings & binary *
//            CHAR: 254 - VARCHAR: 253 - ENUM: 254 - SET: 254 - BINARY: 254
//            VARBINARY: 253 - TINYBLOB: 252 - BLOB: 252 - MEDIUMBLOB: 252
//            TINYTEXT: 252 - TEXT: 252 - MEDIUMTEXT: 252 - LONGTEXT: 252

        $aNumeric = array(16, 1, 2, 9, 3, 8, 4, 5, 246 );
        $aDates   = array(10, 12, 7, 11, 13 );
        $aString  = array(254, 253, 252 );

        if(in_array($type, $aNumeric) ) {
            $_str .= $value;
        } else if(in_array($type, $aDates) ) {
            $_str .= '\'' . $this->dao->connId->real_escape_string($value) . '\'';
        } else if(in_array($type, $aString) ) {
            $_str .= '\'' . $this->dao->connId->real_escape_string($value) . '\'';
        }
    }
}
/* file end: ./oc-includes/osclass/model/Dump.php */
?>