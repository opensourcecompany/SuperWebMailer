<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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

include("../superadmin.inc.php");
include_once("../users_ops.inc.php");
require_once('api_base.php');

/**
 * Users API
 */
class api_Users extends api_base {


 /**
  * gets Users
  *
	 * @return array
  * @access public
	 */
 function api_getUsers() {
  global $UserType, $UserId, $_Q61I1;
  global $_Q8f1L, $_QLtQO;

  if(defined("DEMO")){
    return $this->api_Error("DEMO VERSION.");
  }

  if($UserType == "SuperAdmin")
     $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE UserType="._OPQLR("Admin");
     else
     if($UserType == "Admin")
        $_QJlJ0 = "SELECT * FROM $_Q8f1L LEFT JOIN $_QLtQO ON id=users_id WHERE owner_id=$UserId";
        else
        $_QJlJ0 = "";
  if($_QJlJ0 == "")
     return array();
  $_QlOjC = array();
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    unset($_Q6Q1C["Password"]);
    $_QlOjC[] = $_Q6Q1C;
  }
  mysql_free_result($_Q60l1);

		return $_QlOjC;
	}

 /**
  * create a new User
  * use apiNotAllowedPrivileges for users only NOT for admins, with api_getUserPrivilegesFieldNames() you can get all privileges
  * apiAccountType MUST be 'Unlimited','Limited' or 'Payed'
  *
  * @param string $apiUsername
	 * @param string $apiPassword
	 * @param string $apiEMail
  * @param string $apiFirstName
  * @param string $apiLastName
	 * @param string $apiLanguageCode
  * @param int $apiThemesId
	 * @param string $apiAccountType
  * @param int $apiAccountTypeLimitedMailCountLimited
  * @param array $apiNotAllowedPrivileges
	 * @return int
  * @access public
	 */
	function api_createUser($apiUsername, $apiPassword, $apiEMail, $apiFirstName, $apiLastName, $apiLanguageCode, $apiThemesId, $apiAccountType = 'Unlimited', $apiAccountTypeLimitedMailCountLimited = 1000, $apiNotAllowedPrivileges = array()) {
  global $UserType, $UserId, $OwnerOwnerUserId, $_Q61I1;
  global $_Q8f1L, $_QLtQO, $AppName;

  $apiAccountTypeLimitedMailCountLimited = intval($apiAccountTypeLimitedMailCountLimited);
  $apiThemesId = intval($apiThemesId);
  if($apiThemesId == 0)
    $apiThemesId = 1;
  if($apiLanguageCode == "")
    $apiLanguageCode = "de";

  if(defined("DEMO")){
    return $this->api_Error("DEMO VERSION.");
  }

  if(defined("SWM")){
    if($UserType != "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90))
       return false;

    if($UserType == "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ){
       $_QJlJ0 = "SELECT COUNT(*) AS C FROM $_Q8f1L WHERE UserType='Admin'";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       if( $_Q6Q1C["C"] > 0)
         return false;
    }
  }

  $_QJlJ0 = "SELECT COUNT(*) AS C FROM $_Q8f1L WHERE Username="._OPQLR($apiUsername);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    if($_Q6Q1C["C"] > 0) return $this->api_Error("User with this name always exists.");
    mysql_free_result($_Q60l1);
  }

  if(!trim($apiPassword) || !trim($apiUsername))
    return $this->api_Error("Username and password are required.");

  if($UserType == "SuperAdmin"){
    $_QlLfl = _LLEQD($apiUsername, "Admin", $apiLanguageCode, $apiAccountType, $apiAccountTypeLimitedMailCountLimited);
  } else {
    $_QJlJ0 = "INSERT INTO $_Q8f1L (`Username`, `UserType`, `Language`) VALUES("._OPQLR(trim($apiUsername)).", 'User', "._OPQLR($apiLanguageCode).")";
    mysql_query($_QJlJ0, $_Q61I1);
    $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
    $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_QlLfl = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);
    if($_QlLfl){
      $_QJlJ0 = "INSERT INTO $_QLtQO SET users_id=$_QlLfl, owner_id=$UserId";
      mysql_query($_QJlJ0, $_Q61I1);
      $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
    }


    if($_QlLfl && count($apiNotAllowedPrivileges) && $UserType == "Admin"){

       $_QLLjo = array();
       $_QJlJ0 = "SHOW COLUMNS FROM $_Q8f1L";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if ($_Q60l1 && mysql_num_rows($_Q60l1)) {
           while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
              foreach ($_Q6Q1C as $key => $_Q6ClO) {
                 if($key == "Field" && strpos($_Q6ClO, "Privilege") !== false) {
                    $_QLLjo[] = $_Q6ClO;
                    break;
                 }
              }
           }
           mysql_free_result($_Q60l1);
       }

       reset($apiNotAllowedPrivileges);
       $_QJlJ0 = array();
       foreach($apiNotAllowedPrivileges as $key){
         if(in_array($key, $_QLLjo))
           $_QJlJ0[] = "`$key`=0";
       }
       if(count($_QJlJ0) > 0){
         $_QJlJ0 = "UPDATE $_Q8f1L SET ".join(", ", $_QJlJ0)." WHERE id=$_QlLfl";
         mysql_query($_QJlJ0, $_Q61I1);
         $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
       }

    }
  }

  if($_QlLfl){
    $_QlLOL = _OC1CF();
    $_QJlJ0 = "UPDATE $_Q8f1L SET Password=CONCAT("._OPQLR($_QlLOL).", PASSWORD("._OPQLR($_QlLOL.$apiPassword).") ), `EMail`="._OPQLR($apiEMail).", `FirstName`="._OPQLR($apiFirstName).", `LastName`="._OPQLR($apiLastName).", `ThemesId`=$apiThemesId WHERE id=$_QlLfl";
    mysql_query($_QJlJ0, $_Q61I1);
    $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
  }


  return $_QlLfl;
	}

 /**
  * remove a user
  *
  * @param int $ToRemoveUserId
	 * @return boolean
  * @access public
	 */
 function api_removeUser($ToRemoveUserId) {
  global $UserType, $UserId, $OwnerOwnerUserId, $_Q61I1;
  global $_Q8f1L, $_QLtQO, $_Q60QL, $AppName;

  if(defined("DEMO")){
    return $this->api_Error("DEMO VERSION.");
  }

  $ToRemoveUserId = intval($ToRemoveUserId);

  $_QJlJ0 = "";
  if($UserType == "SuperAdmin") {
    $_QJlJ0 = "SELECT count(id) FROM $_Q8f1L WHERE id=".$ToRemoveUserId." AND `UserType`='Admin'";
  } else
    if($UserType == "Admin") {
      $_QJlJ0 = "SELECT count(id) FROM $_Q8f1L WHERE id=".$ToRemoveUserId." AND `UserType`='User'";
    }
  if($_QJlJ0 == "")
     return $this->api_Error("Unknown user.");

  $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q8OiJ = mysql_fetch_row($_Qllf1);
  mysql_free_result($_Qllf1);
  if($_Q8OiJ[0] == 0)
     return $this->api_Error("User not found.");

  if($UserType == "SuperAdmin") {
    $_QJlJ0 = "SELECT count(id) FROM $_Q60QL WHERE users_id=".$ToRemoveUserId;
  } else {
    $_QJlJ0 = "SELECT count(users_id) FROM $_QLtQO WHERE owner_id=".$ToRemoveUserId;
  }

  $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q8OiJ = mysql_fetch_row($_Qllf1);
  mysql_free_result($_Qllf1);
  if($_Q8OiJ[0] > 0)
     return $this->api_Error("User not removable it has references.");

  $_QtIiC = array();
  _L6OBB(array($ToRemoveUserId), $_QtIiC);
  if(count($_QtIiC) == 0)
    return true;
  return $this->api_Error("Error while removing users: ".join("\n", $_QtIiC));
 }


 /**
  * gets all defined user privileges
  *
	 * @return array
  * @access public
	 */
 function api_getUserPrivilegesFieldNames() {
   global $_Q61I1, $_Q8f1L;

   $_QLLjo = array();
   $_QJlJ0 = "SHOW COLUMNS FROM $_Q8f1L";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if ($_Q60l1 && mysql_num_rows($_Q60l1)) {
       while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          foreach ($_Q6Q1C as $key => $_Q6ClO) {
             if($key == "Field" && strpos($_Q6ClO, "Privilege") !== false) {
                $_QLLjo[] = $_Q6ClO;
                break;
             }
          }
       }
       mysql_free_result($_Q60l1);
   }
   return $_QLLjo;
 }

}
