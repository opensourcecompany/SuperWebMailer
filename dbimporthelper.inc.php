<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################


# Wrapper MySQL, MSSQL

###########################################################

 ################ MS SQL emulation with sqlsrv_ functions
 $sqlsrv_functions = false;
 class sqlsrv_fields {
   var $name;
   var $type;
   var $max_length;
 }

 if(!function_exists("mssql_connect") && function_exists("sqlsrv_connect")){
   $sqlsrv_functions = true;
   function mssql_connect($SQLServerName, $SQLUserName, $SQLPassword, $SQLDatabase = "", $UTF8 = false) {
      if($SQLDatabase != "") {
          if($UTF8)
            $connectionInfo = array("UID" => $SQLUserName, "PWD" => $SQLPassword, "Database"=>$SQLDatabase, "CharacterSet"=>"UTF-8");
            else
            $connectionInfo = array("UID" => $SQLUserName, "PWD" => $SQLPassword, "Database"=>$SQLDatabase);
         }
         else {
           if($UTF8)
             $connectionInfo = array("UID" => $SQLUserName, "PWD" => $SQLPassword, "CharacterSet"=>"UTF-8");
             else
             $connectionInfo = array("UID" => $SQLUserName, "PWD" => $SQLPassword);
         }
      return sqlsrv_connect( $SQLServerName, $connectionInfo);
   }

   function mssql_select_db($db, $DBLink){
     if($DBLink)
       return true;
       else
       return false;
   }

   function mssql_close($conn){
     sqlsrv_close($conn);
   }

   function mssql_free_result($stmt){
     sqlsrv_free_stmt($stmt);
   }

   function mssql_get_last_message(){
     $s = sqlsrv_errors();
     if(is_array($s)) {
       $text = "";
       foreach($s as $key => $value)
         if(!is_array($value))
           $text .= "$key: $value";
           else
           foreach($value as $k => $v)
             $text .= "\r\n".$v;

       $s = $text;
     }
     return $s;
   }


   function mssql_query($sql, $DBLink){
     return sqlsrv_query($DBLink, $sql, array(), array( "Scrollable" => 'static' )); #static because sqlsrv_num_rows() doesn't work without it
   }

   function mssql_rows_affected($ressource){
     return sqlsrv_rows_affected($ressource);
   }

   function mssql_num_fields($ressource){
     return sqlsrv_num_fields($ressource);
   }

   function mssql_field_name($ressource, $fieldindex){
     $field=sqlsrv_get_field($ressource, $fieldindex);
     if($field === false) {
       if(sqlsrv_fetch($ressource) == false) return false;
       $field=sqlsrv_get_field($ressource, $fieldindex);
       sqlsrv_fetch($ressource, SQLSRV_SCROLL_ABSOLUTE, -1);
     }
     return $field;
   }

   function mssql_fetch_field($ressource, $fieldindex){

       $i=-1;
       foreach( sqlsrv_field_metadata($ressource) as $fieldMetadata)
       {
             $i++;
             if($i != $fieldindex) continue;

             $sqlsrv_fields = new sqlsrv_fields;
             foreach( $fieldMetadata as $name => $value)
             {
                  if($name == "Name")
                    $sqlsrv_fields->name = $value;
                  if($name == "Type")
                    $sqlsrv_fields->type = $value;
                  if($name == "Size")
                    $sqlsrv_fields->max_length = $value;
             }
             return $sqlsrv_fields;
       }

       return false;
   }

   function mssql_num_rows($ressource){
     return sqlsrv_num_rows($ressource);
   }

   function mssql_data_seek($ressource, $row_number){
     if($row_number > 0)
       return sqlsrv_fetch($ressource, SQLSRV_SCROLL_ABSOLUTE, $row_number - 1);
       else
       if(sqlsrv_num_rows($ressource) === false)
       return false;
       else
       if($row_number == 0 && sqlsrv_num_rows($ressource)>0) // nicht OK aber fuer Import OK
        return true;
        else
        return sqlsrv_fetch($ressource, SQLSRV_SCROLL_ABSOLUTE, -1); // nicht OK
   }

   function mssql_fetch_array($ressource){
     $ret = sqlsrv_fetch_array($ressource, SQLSRV_FETCH_BOTH);
     if($ret == null)
      return false;
      else
       return $ret;
   }

   function mssql_fetch_row($ressource){
     $ret = sqlsrv_fetch_array($ressource, SQLSRV_FETCH_NUMERIC);
     if($ret == null)
      return false;
      else
       return $ret;
   }

   function mssql_fetch_assoc($ressource){
     $ret = sqlsrv_fetch_array($ressource, SQLSRV_FETCH_ASSOC);
     if($ret == null)
      return false;
      else
       return $ret;
   }

 }

 ############
 function db_connect($SQLServerName, $SQLUserName, $SQLPassword, $DBType, $SQLDatabase = "", $UTF8 = false) {
   global $sqlsrv_functions;
   if($DBType == "MSSQL") {
       if($sqlsrv_functions)
         return mssql_connect($SQLServerName, $SQLUserName, $SQLPassword, $SQLDatabase, $UTF8);
         else {
           $conn = mssql_connect($SQLServerName, $SQLUserName, $SQLPassword);
           if($SQLDatabase != "" && $conn !== false)
             mssql_select_db("[$SQLDatabase]", $conn);
           return $conn;
         }
    }
    else {
     $conn = mysql_connect($SQLServerName, $SQLUserName, $SQLPassword, true);
     if($SQLDatabase != "" && $conn !== false) {
       if($UTF8){
         // UTF-8 connection
         @mysql_query("SET NAMES 'utf8'", $conn);
         @mysql_query("SET CHARACTER SET 'utf8'", $conn);
         // not STRICT mode
         @mysql_query('SET SQL_MODE=""', $conn);
       }
       mysql_select_db($SQLDatabase, $conn);
       if($UTF8){
         // UTF-8 connection
         @mysql_query("SET NAMES 'utf8'", $conn);
         @mysql_query("SET CHARACTER SET 'utf8'", $conn);
         // not STRICT mode
         @mysql_query('SET SQL_MODE=""', $conn);
       }
     }
     return $conn;
   }
 }

 function db_select_db($SQLDBName, $ConnectHandle, $DBType) {
   if($DBType == "MSSQL")
     return mssql_select_db("[$SQLDBName]", $ConnectHandle);
    else
     return mysql_select_db($SQLDBName, $ConnectHandle);
 }

 function db_close($ConnectHandle, $DBType) {
   if($DBType == "MSSQL")
     mssql_close($ConnectHandle);
    else
     mysql_close($ConnectHandle);
 }

 function db_free_result($ressource, $DBType) {
   if($DBType == "MSSQL")
     return mssql_free_result($ressource);
    else
     return mysql_free_result($ressource);
 }

 function db_error($ConnectHandle, $DBType) {
   if($DBType == "MSSQL") {
     $s = mssql_get_last_message();
     if(db_errno($ConnectHandle, $DBType) == 0)
        $s = "";
     return $s;
     }
     else
     if($ConnectHandle != 0)
        return mysql_error($ConnectHandle);
        else
        return mysql_error();
 }

 function db_errno($ConnectHandle, $DBType) {
   if($DBType == "MSSQL") {
     if($ConnectHandle == 0) return 1;
     $sql = "SELECT @@ERROR as ErrorCode";
     $result=db_query($sql, $ConnectHandle, $DBType);
     if($result && $row=db_fetch_array($result, $DBType)){
       db_free_result($result, $DBType);
       return $row[0];
     }
     return 0;
     }
     else
     return mysql_errno($ConnectHandle);
 }


 function db_get_tables($databasename, $ConnectHandle, $DBType) {
   $tables = array();
   if($DBType == "MSSQL") {
      $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE'";
      $result=db_query($sql, $ConnectHandle, $DBType);
      while ($row=db_fetch_array($result, $DBType)){
        $tables[] = $row["TABLE_NAME"];
      }
      db_free_result($result, $DBType);
     }
     else {
       $result = mysql_query("SHOW TABLES FROM `$databasename`", $ConnectHandle);
       while($row = mysql_fetch_row($result)) {
          $tables[] = $row[0];
       }
       mysql_free_result($result);
     }
   return $tables;
 }

 function db_query($sql, $ConnectHandle, $DBType) {
   if($DBType == "MSSQL")
     return mssql_query($sql, $ConnectHandle);
     else
     return mysql_query($sql, $ConnectHandle);
 }

 function db_affected_rows($ConnectHandle, $DBType) {
   if($DBType == "MSSQL")
     return mssql_rows_affected($ConnectHandle);
     else
     return mysql_affected_rows($ConnectHandle);
 }

 function db_num_fields($ressource, $DBType) {
   if($DBType == "MSSQL")
    return mssql_num_fields($ressource);
    else
    return mysql_num_fields($ressource);
 }

 function db_field_name($ressource, $fieldindex, $DBType) {
   if($DBType == "MSSQL")
    return mssql_field_name($ressource, $fieldindex);
    else
    return mysql_field_name($ressource, $fieldindex);
 }

 function db_fetch_field($ressource, $fieldindex, $DBType) {
   if($DBType == "MSSQL") {

      return mssql_fetch_field($ressource, $fieldindex);
    }
    else
    return mysql_fetch_field($ressource, $fieldindex);
 }

 function db_num_rows($ressource, $DBType) {
   if(!$ressource) return 0;
   if($DBType == "MSSQL")
    return mssql_num_rows($ressource);
    else
    return mysql_num_rows($ressource);
 }

 function db_data_seek($ressource, $row_number, $DBType) {
   if($DBType == "MSSQL")
    return mssql_data_seek($ressource, $row_number);
    else
    return mysql_data_seek($ressource, $row_number);
 }

 function db_fetch_array($ressource, $DBType) {
   if($DBType == "MSSQL")
    return mssql_fetch_array($ressource);
    else
    return mysql_fetch_array($ressource);
 }

 function db_fetch_row($ressource, $DBType) {
   if($DBType == "MSSQL")
    return mssql_fetch_row($ressource);
    else
    return mysql_fetch_row($ressource);
 }

 function db_fetch_assoc($ressource, $DBType) {
   if($DBType == "MSSQL") {
      if(function_exists("mssql_fetch_assoc"))
         return mssql_fetch_assoc($ressource);
         else
         return mssql_fetch_array($ressource, MSSQL_ASSOC);
    }
    else
     return mysql_fetch_assoc($ressource);
 }

 function db_GetNow($DBType) {
   if($DBType == "MSSQL")
     return "GETDATE()";
     else
     return "NOW()";
 }

 function db_LASTINSERTID($tablename, $ConnectHandle, $DBType) {
    if($DBType == "MSSQL")
      return db_query("SELECT IDENT_CURRENT('$tablename')", $ConnectHandle, $DBType);
    else
      return db_query("SELECT LAST_INSERT_ID()", $ConnectHandle, $DBType);
 }

?>
