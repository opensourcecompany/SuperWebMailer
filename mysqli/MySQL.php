<?php
 class MySQL
 {
    /**
     * Object instance
     *
     * @var MySQL
     */
    protected static $_instance;

    /**
     * Instances of the Db
     *
     * Start position @ 1
     *
     * @array mysqli
     */
    protected $_instances = array(array());

    /**
     * Db Instances params
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Get singelton instance
     *
     * @return MySQL
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * mysql_connect
     * http://www.php.net/manual/en/function.mysql-connect.php
     */
    public function mysql_connect($server, $username, $password, $newLink = false, $clientFlags = false, $PERSISTENT = false)
    {
        // If we don't have to create a new instance and we have an instance, return it
        if ($newLink == false && count($this->_instances) > 1) {
            return 1;
        }

        // Set connection element
        $usePosition = count($this->_instances) + 1;

        // Set connection params
        $this->_params[$usePosition] = array (
            'server'        => $server,
            'username'      => $username,
            'password'      => $password,
            'newLink'       => $newLink,
            'clientFlags'   => $clientFlags,
            'errno'         => 0,
            'error'         => "",
            'rowCount'      => -1,
            'lastQuery'     => false,
        );

        // Create new instance

        // Add instance
        if(!$PERSISTENT)
          $this->_instances[$usePosition] = new mysqli($server, $username, $password);
          else
          $this->_instances[$usePosition] = new mysqli("p:".$server, $username, $password);

        if (mysqli_connect_error()) {
             $this->_instances[$usePosition] = array();
             $this->_loadError($usePosition, mysqli_connect_errno(), mysqli_connect_error());
             return false;
        }

        return $usePosition;

    }

    /**
     * mysql_pconnect
     * http://www.php.net/manual/en/function.mysql-pconnect.php
     */
    public function mysql_pconnect($server, $username, $password, $newLink = false, $clientFlags = false)
    {
        return $this->mysql_connect($server, $username, $password, $newLink, $clientFlags, true);
    }

    /**
     * mysql_select_db
     * http://www.php.net/manual/en/function.mysql-select-db.php
     */
    public function mysql_select_db($databaseName, $link = false)
    {
        $link = $this->_getLastLink($link);

        // Select the DB
        $this->_loadError($link, 0);
        $this->_params[$link]['databaseName'] = $databaseName;
        $db = $this->_instances[$link]->select_db($databaseName);
        if(!$db)
           $this->_loadError($link, $this->_instances[$link]->errno, $this->_instances[$link]->error);
        return $db;
    }

    /**
     * mysql_query
     * http://www.php.net/manual/en/function.mysql-query.php
     */
    public function mysql_query($query, $link = false, $resultmode = MYSQLI_STORE_RESULT)
    {
        $link = $this->_getLastLink($link);

        if(empty($this->_instances[$link])){
          $errorCode[1] = 9999;
          $errorCode[2] = "Can't execute query: $query no link to database.";
          $this->_loadError($link, $errorCode[1], $errorCode[2]);
          return false;
        }

        if ($res = $this->_instances[$link]->query($query, $resultmode)) {
           if(is_object($res))
             $this->_params[$link]['rowCount'] = $res->num_rows;
             else
             $this->_params[$link]['rowCount'] = 0;

           $this->_params[$link]['lastQuery'] = $query;
           $this->_loadError($link, 0);
           if(is_object($res))
              return array("queryresult" => $res, "link" => $link);
              else
              return $res;
        }

        $this->_params[$link]['rowCount'] = -1;
        $this->_params[$link]['lastQuery'] = false;

        // Set query error
        $this->_loadError($link, $this->_instances[$link]->errno, $this->_instances[$link]->error);
        return false;
    }

    /**
     * mysql_unbuffered_query
     * http://www.php.net/manual/en/function.mysql-unbuffered-query.php
     */
    public function mysql_unbuffered_query($query, $link = false)
    {
        return $this->mysql_query($query, $link, MYSQLI_USE_RESULT);
    }

    /**
     * mysql_fetch_array
     * http://www.php.net/manual/en/function.mysql-fetch-array.php
     */
    public function mysql_fetch_array($result, $resultType = 3)
    {
        if (!$result || !is_array($result) || !is_object($result["queryresult"])) {
            // ignore it trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $link = $result["link"];
        $this->_loadError($link, 0);

        $res = false;
        switch ($resultType) {
                case 1:
                    // by field names only as array
                    $res = $result["queryresult"]->fetch_array(MYSQLI_ASSOC);
                    break;
                case 2:
                    // by field position only as num
                    $res = $result["queryresult"]->fetch_array(MYSQLI_NUM);
                    break;
                case 3:
                    // by both field name/position as array
                    $res = $result["queryresult"]->fetch_array(MYSQLI_BOTH);
                    break;
                case 4:
                    // by field names as object
                    $res = $result["queryresult"]->fetch_object();
                    break;
        }

        if(!$res){
         $this->_loadError($link, $this->_instances[$link]->errno, $this->_instances[$link]->error);
        }

        return $res;
    }

    /**
     * mysql_fetch_assoc
     * http://www.php.net/manual/en/function.mysql-fetch-assoc.php
     */
    public function mysql_fetch_assoc($result)
    {
        return $this->mysql_fetch_array($result, 1);
    }

    /**
     * mysql_fetch_row
     * http://www.php.net/manual/en/function.mysql-fetch-row.php
     */
    public function mysql_fetch_row($result)
    {
        return $this->mysql_fetch_array($result, 2);
    }

    /**
     * mysql_fetch_object
     * http://www.php.net/manual/en/function.mysql-fetch-object.php
     */
    public function mysql_fetch_object($result)
    {
        return $this->mysql_fetch_array($result, 4);
    }

    /**
     * mysql_num_fields
     * http://www.php.net/manual/en/function.mysql-num-fields.php
     */
    public function mysql_num_fields($result)
    {
      if (!$result || !is_array($result)) {
         trigger_error('Supplied argument is not a valid MySQL result resource', E_USER_WARNING);
         return false;
      }
      return $result["queryresult"]->field_count;
    }

    /**
     * mysql_num_rows
     * http://www.php.net/manual/en/function.mysql-num-rows.php
     */
    public function mysql_num_rows($result)
    {
      if (!$result || !is_array($result)) {
         trigger_error('Supplied argument is not a valid MySQL result resource', E_USER_WARNING);
         return false;
      }
      return $result["queryresult"]->num_rows;
    }

    /**
     * mysql_ping
     * http://www.php.net/manual/en/function.mysql-ping.php
     */
    public function mysql_ping($link = false)
    {
        $link = $this->_getLastLink($link);
        $this->_loadError($link, 0);

        $res = $this->mysql_query("SELECT 1", $link);
        if ($res){
          $this->mysql_free_result($res);
          return true;
        }

        $res = $this->mysql_connect(
                    $this->_params[$link]['server'],
                    $this->_params[$link]['username'],
                    $this->_params[$link]['password'],
                    $this->_params[$link]['newLink'],
                    $this->_params[$link]['clientFlags'],
                    $link
        );

        if (isset($this->_params[$link]['databaseName'])) {
          $set = $this->mysql_select_db($this->_params[$link]['databaseName'], $link);
          if (!$set) {
             return false;
          }
        }

        return true;

    }

    /**
     * mysql_affected_rows
     * http://www.php.net/manual/en/function.mysql-affected-rows.php
     */
    public function mysql_affected_rows($link = false)
    {
        $link = $this->_getLastLink($link);

        return $this->_instances[$link]->affected_rows;
    }

    /**
     * mysql_client_encoding
     * http://www.php.net/manual/en/function.mysql-client-encoding.php
     */
    public function mysql_client_encoding($link = false)
    {
        $link = $this->_getLastLink($link);

        $res = $this->_instances[$link]->query('SELECT @@character_set_database');
        if($res){
          $x = $res->fetch_array(MYSQLI_NUM);
          $this->mysql_free_result($res);
          return $x[0];
        }

        return false;
    }

    /**
     * mysql_close
     * http://www.php.net/manual/en/function.mysql-close.php
     */
    public function mysql_close($link = NULL)
    {
        $link = $this->_getLastLink($link);

        if (isset($this->_instances[$link])) {
            if(is_object($this->_instances[$link])){
             try{ $this->_instances[$link]->close();} catch (Exception $e) {}
            }
            $this->_instances[$link] = null;
            unset($this->_instances[$link]);
            return true;
        }

        return false;
    }

    /**
     * mysql_create_db
     * http://www.php.net/manual/en/function.mysql-create-db.php
     */
    public function mysql_create_db($databaseName, $link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->prepare('CREATE DATABASE ' . $databaseName)->execute();
    }

    /**
     * mysql_data_seek
     * http://www.php.net/manual/en/function.mysql-data-seek.php
     */
    public function mysql_data_seek($result, $rowNumber)
    {
        if (!$result || !is_array($result)) {
            trigger_error('Supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        return $result["queryresult"]->data_seek($rowNumber);
    }

    /**
     * mysql_list_dbs
     * http://www.php.net/manual/en/function.mysql-list-dbs.php
     */
    public function mysql_list_dbs($link = false)
    {
        $link = $this->_getLastLink($link);

        return $this->_instances[$link]->query('SHOW DATABASES');
    }

    /**
     * mysql_db_name
     * http://www.php.net/manual/en/function.mysql-db-name.php
     */
    public function mysql_db_name($result, $row, $field = 'Database')
    {
        $res = $this->mysql_query("SELECT DATABASE()");
        if(!$res) return "";
        $cnt = $this->mysql_num_rows($res);
        $i=0;
        while($arow = $this->mysql_fetch_row($res)){
          if($i == $row){
            if(isset($arow[$row])){
              $this->mysql_free_result($res);
              return $arow[$row];
            } else{
              $this->mysql_free_result($res);
              return "";
            }
          }
          $i++;
        }

        $this->mysql_free_result($res);

        return '';
    }

    /**
     * mysql_db_query
     * http://www.php.net/manual/en/function.mysql-db-query.php
     */
    public function mysql_db_query($databaseName, $query, $link = false)
    {
        $link = $this->_getLastLink($link);

        $this->mysql_select_db($databaseName, $link);

        return $this->mysql_query($query, $link);
    }

    /**
     * mysql_drop_db
     * http://www.php.net/manual/en/function.mysql-drop-db.php
     */
    public function mysql_drop_db($databaseName, $link = false)
    {
        $link = $this->_getLastLink($link);

        return $this->_instances[$link]->prepare('DROP DATABASE ' . $databaseName)->execute();
    }

    /**
     * mysql_thread_id
     * http://www.php.net/manual/en/function.mysql-thread-id.php
     */
    public function mysql_thread_id($link = false)
    {
        $link = $this->_getLastLink($link);

        $res = $this->_instances[$link]
            ->query('SELECT CONNECTION_ID()')->fetch_array(MYSQLI_NUM);

        return $res[0];
    }

    /**
     * mysql_list_tables
     * http://www.php.net/manual/en/function.mysql-list-tables.php
     */
    public function mysql_list_tables($databaseName, $link = false)
    {
        $link = $this->_getLastLink($link);

        return $this->_instances[$link]->query('SHOW TABLES FROM ' . $databaseName);
    }

    /**
     * mysql_tablename
     * http://www.php.net/manual/en/function.mysql-tablename.php
     */
    public function mysql_tablename($result, $row)
    {
        $res = $this->mysql_query("SHOW TABLES");
        if(!$res) return "";
        $cnt = $this->mysql_num_rows($res);
        $i=0;
        while($arow = $this->mysql_fetch_row($res)){
          if($i == $row){
            if(isset($arow[$row])){
              $this->mysql_free_result($res);
              return $arow[$row];
            } else{
              $this->mysql_free_result($res);
              return "";
            }
          }
          $i++;
        }

        $this->mysql_free_result($res);

        return '';
    }

    /**
     * mysql_fetch_lengths
     * http://www.php.net/manual/en/function.mysql-fetch-lengths.php
     */
    public function mysql_fetch_lengths($result)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        return $result["queryresult"]->lengths;
    }

    /**
     * mysql_field_len
     * http://www.php.net/manual/en/function.mysql-field-len.php
     */
    public function mysql_field_len($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        return $result["queryresult"]->lengths[$fieldOffset];
    }

    /**
     * mysql_field_flags
     * http://www.php.net/manual/en/function.mysql-field-flags.php
     */
    public function mysql_field_flags($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $x = $result["queryresult"]->fetch_fields();
        return $x[$fieldOffset]->flags;
    }

    /**
     * mysql_field_name
     * http://www.php.net/manual/en/function.mysql-field-name.php
     */
    public function mysql_field_name($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $x = $result["queryresult"]->fetch_fields();
        return $x[$fieldOffset]->name;
    }

    /**
     * mysql_field_type
     * http://www.php.net/manual/en/function.mysql-field-type.php
     */
    public function mysql_field_type($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $x = $result["queryresult"]->fetch_fields();
        return $x[$fieldOffset]->type;
    }

    /**
     * mysql_field_table
     * http://www.php.net/manual/en/function.mysql-field-table.php
     */
    public function mysql_field_table($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $x = $result["queryresult"]->fetch_fields();
        return $x[$fieldOffset]->table;
    }
    /**
     * mysql_fetch_field
     * http://www.php.net/manual/en/function.mysql-fetch-field.php
     */
    public function mysql_fetch_field($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $x = $result["queryresult"]->fetch_fields();
        return $x[$fieldOffset];
    }

    /**
     * mysql_field_seek
     * http://www.php.net/manual/en/function.mysql-field-seek.php
     */
    public function mysql_field_seek($result, $fieldOffset = false)
    {
        if (!$result || !is_array($result)) {
            trigger_error('mysql_fetch_*(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }

        $result["queryresult"]->field_seek($fieldOffset);
    }

    /**
     * mysql_stat
     * http://www.php.net/manual/en/function.mysql-stat.php
     */
    public function mysql_stat($link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->stat();
    }

    /**
     * mysql_get_server_info
     * http://www.php.net/manual/en/function.mysql-get-server-info.php
     */
    public function mysql_get_server_info($link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->get_server_info();
    }

    /**
     * mysql_get_proto_info
     * http://www.php.net/manual/en/function.mysql-get-proto-info.php
     */
    public function mysql_get_proto_info($link = false)
    {
        $link = $this->_getLastLink($link);

        $res = $this->_instances[$link]
            ->query("SHOW VARIABLES LIKE 'protocol_version'")->fetch_array(MYSQLI_NUM);

        return (int) $res[1];
    }

    /**
     * mysql_get_host_info
     * http://www.php.net/manual/en/function.mysql-get-server-info.php
     */
    public function mysql_get_host_info($link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->host_info();
    }

    /**
     * mysql_get_client_info
     * http://www.php.net/manual/en/function.mysql-get-client-info.php
     */
    public function mysql_get_client_info($link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->get_client_info();
    }

    /**
     * mysql_free_result
     * http://www.php.net/manual/en/function.mysql-free-result.php
     */
    public function mysql_free_result(&$result)
    {
        if (is_array($result) && isset($result['queryresult'])) {
            if( is_object($result['queryresult']) ){
               $result['queryresult']->free();
            }
            $result['queryresult'] = false;
            $result = false;
            return true;
        } else
          return false;
    }

    /**
     * mysql_result
     * http://www.php.net/manual/en/function.mysql-result.php
     */
    public function mysql_result($result, $row, $field = false)
    {

        return '';
    }

    /**
     * mysql_list_processes
     * http://www.php.net/manual/en/function.mysql-list-processes.php
     */
    public function mysql_list_processes($link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->query("SHOW PROCESSLIST");
    }

    /**
     * mysql_set_charset
     * http://www.php.net/manual/en/function.mysql-set-charset.php
     */
    public function mysql_set_charset($charset, $link = false)
    {
        $link = $this->_getLastLink($link);
        $set = "SET character_set_results = '$charset', character_set_client = '$charset', character_set_connection = '$charset', character_set_database = '$charset', character_set_server = '$charset'";
        return $this->_instances[$link]->query($set);
    }

    /**
     * mysql_insert_id
     * http://www.php.net/manual/en/function.mysql-insert-id.php
     */
    public function mysql_insert_id($link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->insert_id;
    }

    /**
     * mysql_list_fields
     * http://www.php.net/manual/en/function.mysql-list-fields.php
     */
    public function mysql_list_fields($databaseName, $tableName, $link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_SCHEMA = '$databaseName' AND TABLE_NAME = '$tableName'")->fetch_array();
    }

    /**
     * mysql_errno
     * http://www.php.net/manual/en/function.mysql-errno.php
     */
    public function mysql_errno($link = false)
    {
        $link = $this->_getLastLink($link, false);
        if($this->_params[$link]['errno'] == 0)
         return $this->_instances[$link]->errno;
         else
         return $this->_params[$link]['errno'];
    }

    /**
     * mysql_error
     * http://www.php.net/manual/en/function.mysql-error.php
     */
    public function mysql_error($link = false)
    {
        $link = $this->_getLastLink($link, false);
        if($this->_params[$link]['error'] == "")
         return $this->_instances[$link]->error;
         else
         return $this->_params[$link]['error'];
    }

    /**
     * mysql_real_escape_string
     * http://www.php.net/manual/en/function.mysql-real-escape-string.php
     */
    public function mysql_real_escape_string($string, $link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->real_escape_string($string);
    }

    /**
     * mysql_escape_string
     * http://www.php.net/manual/en/function.mysql-escape-string.php
     */
    public function mysql_escape_string($string, $link = false)
    {
        $link = $this->_getLastLink($link);
        return $this->_instances[$link]->escape_string($string);
    }

    /**
     * mysql_info
     *
     * Not sure how to get the actual result message from MySQL
     * so the best I could do was to get the affected rows
     * and construct a message that way. If you have a better way
     * or know of a more accurate method, send it to me @
     * azizsaleh@gmail.com and I'll update the code with it. All I got is
     * the affected rows, so it will be missing changed, warnings,
     * skipped, and rows matched
     *
     * http://www.php.net/manual/en/function.mysql-escape-string.php
     */
    public function mysql_info($link = false)
    {
        $link = $this->_getLastLink($link);

        $query = $this->_params[$link]['lastQuery'];

        $affected = $this->_instances[$link]->affected_rows;

        if (strtoupper(substr($query->queryString, 0, 5)) == 'INSERT INTO') {
            return "Records: {$affected}  Duplicates: 0  Warnings: 0";
        }

        if (strtoupper(substr($query->queryString, 0, 9)) == 'LOAD DATA') {
            return "Records: {$affected}  Deleted: 0  Skipped: 0  Warnings: 0";
        }

        if (strtoupper(substr($query->queryString, 0, 11)) == 'ALTER TABLE') {
            return "Records: {$affected}  Duplicates: 0  Warnings: 0";
        }

        if (strtoupper(substr($query->queryString, 0, 6)) == 'UPDATE') {
            return "Rows matched: {$affected}  Changed: {$affected}  Warnings: 0";
        }

        if (strtoupper(substr($query->queryString, 0, 6)) == 'DELETE') {
            return "Records: 0  Deleted: {$affected}  Skipped: 0  Warnings: 0";
        }

        return false;
    }

    /**
     * Close all connections
     *
     * @return void
     */
    public function mysql_close_all()
    {
        // Free connections
        foreach ($this->_instances as $id => $pdo) {
            $this->_instances[$id] = null;
        }

        // Reset arrays
        $this->_instances = array(array());
        $this->_params    = array();
    }

    /**
     * Load data into array
     *
     * @param int                       $link
     * @param int                       $errorcode
     * @param string                       $errortext
     *
     * @return void
     */
    protected function _loadError($link, $errorcode, $errortext = "")
    {
        // Reset errors
        if ($errorcode == 0) {
            $this->_params[$link]['errno'] = 0;
            $this->_params[$link]['error'] = "";
            return;
        }
        // Set error
        $this->_params[$link]['errno'] = $errorcode;
        $this->_params[$link]['error'] = $errortext;
    }

    /**
     * get last db connection
     *
     * @param   int     $link
     * @param   boolean $validate
     *
     * @return int
     */
    protected function _getLastLink($link, $validate = true)
    {
        if ($link === NULL || $link === FALSE) {
            $link = count($this->_instances);
        }

        if ($validate === true && !isset($this->_instances[$link]) || empty($this->_instances[$link])) {
            $error = '';
            if (isset($this->_instances[$link])) {
               // die($this->_params[$link]['errno'] .': ' . $this->_params[$link]['error']);
            } else {
                die('No db at instance #' . ($link - 1));
            }
        }
        return $link;
    }

}