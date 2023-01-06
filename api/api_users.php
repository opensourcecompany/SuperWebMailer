<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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
  global $UserType, $UserId, $_QLttI;
  global $_I18lo, $_IfOtC;

  if(defined("DEMO")){
    return $this->api_Error("DEMO VERSION.");
  }

  if($UserType == "SuperAdmin")
     $_QLfol = "SELECT * FROM $_I18lo WHERE UserType="._LRAFO("Admin");
     else
     if($UserType == "Admin")
        $_QLfol = "SELECT * FROM $_I18lo LEFT JOIN $_IfOtC ON id=users_id WHERE owner_id=$UserId";
        else
        $_QLfol = "";
  if($_QLfol == "")
     return array();
  $_I8oIo = array();
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
    unset($_QLO0f["Password"]); unset($_QLO0f["2FASecret"]);
    $_I8oIo[] = $_QLO0f;
  }
  mysql_free_result($_QL8i1);

		return $_I8oIo;
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
  global $UserType, $UserId, $OwnerOwnerUserId, $_QLttI;
  global $_I18lo, $_IfOtC, $AppName;

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
       $_QLfol = "SELECT COUNT(*) AS C FROM $_I18lo WHERE UserType='Admin'";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
       if( $_QLO0f["C"] > 0)
         return false;
    }
  }

  $_QLfol = "SELECT COUNT(*) AS C FROM $_I18lo WHERE Username="._LRAFO($apiUsername);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
    if($_QLO0f["C"] > 0) return $this->api_Error("User with this name always exists.");
    mysql_free_result($_QL8i1);
  }

  if(!trim($apiPassword) || !trim($apiUsername))
    return $this->api_Error("Username and password are required.");

  if($UserType == "SuperAdmin"){
    $_I8l8o = _JJQOE($apiUsername, "Admin", $apiLanguageCode, $apiAccountType, $apiAccountTypeLimitedMailCountLimited);
  } else {
    $_QLfol = "INSERT INTO $_I18lo (`Username`, `UserType`, `Language`) VALUES("._LRAFO(trim($apiUsername)).", 'User', "._LRAFO($apiLanguageCode).")";
    mysql_query($_QLfol, $_QLttI);
    $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
    $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_I8l8o = $_QLO0f[0];
    mysql_free_result($_QL8i1);
    if($_I8l8o){
      $_QLfol = "INSERT INTO $_IfOtC SET users_id=$_I8l8o, owner_id=$UserId";
      mysql_query($_QLfol, $_QLttI);
      $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
    }


    if($_I8l8o && count($apiNotAllowedPrivileges) && $UserType == "Admin"){

       $_Iflj0 = array();
       $_QLfol = "SHOW COLUMNS FROM $_I18lo";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if ($_QL8i1 && mysql_num_rows($_QL8i1)) {
           while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
              foreach ($_QLO0f as $key => $_QltJO) {
                 if($key == "Field" && strpos($_QltJO, "Privilege") !== false) {
                    $_Iflj0[] = $_QltJO;
                    break;
                 }
              }
           }
           mysql_free_result($_QL8i1);
       }

       reset($apiNotAllowedPrivileges);
       $_QLfol = array();
       foreach($apiNotAllowedPrivileges as $key){
         if(in_array($key, $_Iflj0))
           $_QLfol[] = "`$key`=0";
       }
       if(count($_QLfol) > 0){
         $_QLfol = "UPDATE $_I18lo SET ".join(", ", $_QLfol)." WHERE id=$_I8l8o";
         mysql_query($_QLfol, $_QLttI);
         $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
       }

    }
  }

  if($_I8l8o){
    $_I8li6 = _LAPE1();
    $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
    if(!$_It0IQ)
      $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.$apiPassword).") ), `EMail`="._LRAFO($apiEMail).", `FirstName`="._LRAFO($apiFirstName).", `LastName`="._LRAFO($apiLastName).", `ThemesId`=$apiThemesId WHERE id=$_I8l8o";
      else
      $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.$apiPassword).", 224) ), `EMail`="._LRAFO($apiEMail).", `FirstName`="._LRAFO($apiFirstName).", `LastName`="._LRAFO($apiLastName).", `ThemesId`=$apiThemesId WHERE id=$_I8l8o";
    mysql_query($_QLfol, $_QLttI);
    $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
  }


  return $_I8l8o;
	}

 /**
  * remove a user
  *
  * @param int $ToRemoveUserId
	 * @return boolean
  * @access public
	 */
 function api_removeUser($ToRemoveUserId) {
  global $UserType, $UserId, $OwnerOwnerUserId, $_QLttI;
  global $_I18lo, $_IfOtC, $_QL88I, $AppName;

  if(defined("DEMO")){
    return $this->api_Error("DEMO VERSION.");
  }

  $ToRemoveUserId = intval($ToRemoveUserId);

  $_QLfol = "";
  if($UserType == "SuperAdmin") {
    $_QLfol = "SELECT count(id) FROM $_I18lo WHERE id=".$ToRemoveUserId." AND `UserType`='Admin'";
  } else
    if($UserType == "Admin") {
      $_QLfol = "SELECT count(id) FROM $_I18lo WHERE id=".$ToRemoveUserId." AND `UserType`='User'";
    }
  if($_QLfol == "")
     return $this->api_Error("Unknown user.");

  $_It16Q = mysql_query($_QLfol, $_QLttI);
  $_I1OfI = mysql_fetch_row($_It16Q);
  mysql_free_result($_It16Q);
  if($_I1OfI[0] == 0)
     return $this->api_Error("User not found.");

  if($UserType == "SuperAdmin") {
    $_QLfol = "SELECT count(id) FROM $_QL88I WHERE users_id=".$ToRemoveUserId;
  } else {
    $_QLfol = "SELECT count(users_id) FROM $_IfOtC WHERE owner_id=".$ToRemoveUserId;
  }

  $_It16Q = mysql_query($_QLfol, $_QLttI);
  $_I1OfI = mysql_fetch_row($_It16Q);
  mysql_free_result($_It16Q);
  if($_I1OfI[0] > 0)
     return $this->api_Error("User not removable it has references.");

  $_IQ0Cj = array();
  _J68QL(array($ToRemoveUserId), $_IQ0Cj);
  if(count($_IQ0Cj) == 0)
    return true;
  return $this->api_Error("Error while removing users: ".join("\n", $_IQ0Cj));
 }


 /**
  * gets all defined user privileges
  *
	 * @return array
  * @access public
	 */
 function api_getUserPrivilegesFieldNames() {
   global $_QLttI, $_I18lo;

   $_Iflj0 = array();
   $_QLfol = "SHOW COLUMNS FROM $_I18lo";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if ($_QL8i1 && mysql_num_rows($_QL8i1)) {
       while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          foreach ($_QLO0f as $key => $_QltJO) {
             if($key == "Field" && strpos($_QltJO, "Privilege") !== false) {
                $_Iflj0[] = $_QltJO;
                break;
             }
          }
       }
       mysql_free_result($_QL8i1);
   }
   return $_Iflj0;
 }

}
