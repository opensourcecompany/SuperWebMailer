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
  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if($UserType != "SuperAdmin"){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  $_8QJ8L = "@all_mailingLists#+~";

  $errors = array();
  $_Itfj8 = "";

  if(!isset($_POST["AuthSettingsEditBtn"])) {
    $_QLfol = "SELECT * FROM $_JQ00O LIMIT 0,1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    unset($_QLO0f["2FABlackListTableName"]);
    if(!$_QLO0f["2FAForSuperAdmin"])
      unset($_QLO0f["2FAForSuperAdmin"]);
    mysql_free_result($_QL8i1);
  } else {

    if(!isset($_POST["AuthType"]) || trim($_POST["AuthType"]) == ""){
      $errors[] = "AuthType";
      $_POST["AuthType"] = "db";
    }

    if($_POST["AuthType"] == "db2fa"){
      if(!isset($_POST["2FADiscrepancy"]) || intval($_POST["2FADiscrepancy"]) < 1)
        $_POST["2FADiscrepancy"] = 1;
      $_POST["2FADiscrepancy"] = intval($_POST["2FADiscrepancy"] );  
      if(version_compare(PHP_VERSION, "7.1.0", "<")){
         $errors[] = "AuthType";
         $_Itfj8 = "PHP 7.1 and newer required.";
      }
     
    }

    if($_POST["AuthType"] == "ldap"){
      if(!isset($_POST["ldap_address"]) || trim($_POST["ldap_address"]) == "")
        $errors[] = "ldap_address";
      if(!isset($_POST["ssl"]) || trim($_POST["ssl"]) == "")
        $errors[] = "ssl";
        else
         switch ($_POST["ssl"]){
           case 1:
             $_POST["ldap_tls"] = 1;
             break;
           case 2:
             $_POST["ldap_ssl"] = 1;
             break;
         }
      if(!isset($_POST["ldap_port"]) || trim($_POST["ldap_port"]) == "")
        $errors[] = "ldap_port";
      if(!isset($_POST["ldap_protocol_version"]) || trim($_POST["ldap_protocol_version"]) == ""){
         $errors[] = "ldap_protocol_version";
         $_POST["ldap_protocol_version"] = 3;
      }
      if(!isset($_POST["ldap_base_dn"]) || trim($_POST["ldap_base_dn"]) == "")
        $errors[] = "ldap_base_dn";
      if(isset($_POST["ldap_external_auth_username"]))
        $_POST["ldap_external_auth_username"] = trim($_POST["ldap_external_auth_username"]);
      if(!isset($_POST["ldap_field_uid"]) || trim($_POST["ldap_field_uid"]) == ""){
         $errors[] = "ldap_field_uid";
         $_POST["ldap_field_uid"] = "uid";
      }
      if(!isset($_POST["ldap_default_db_owner_admin_username_id"]) || trim($_POST["ldap_default_db_owner_admin_username_id"]) == "" || intval(trim($_POST["ldap_default_db_owner_admin_username_id"])) < 0)
        if(defined("SWM") && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90))
          $_POST["ldap_default_db_owner_admin_username_id"] = 0;
          else
          $errors[] = "ldap_default_db_owner_admin_username_id";

      if(defined("SWM") && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90)){
        $_POST["ldap_field_owner_admin"] = "";
        $_POST["ldap_default_db_owner_admin_username_dn"] = "";
        $_POST["ldap_field_email"] = "";
      }

      $_POST["dbUserPrivileges"] = array();
      foreach($_POST as $key => $_QltJO){
        if(!is_array($_QltJO) && strpos($key, "Privilege") !== false){
          $_POST["dbUserPrivileges"][] = $key;
          unset($_POST[$key]);
        }
      }
      $_POST["dbUserPrivileges"] = serialize($_POST["dbUserPrivileges"]);


      if(isset($_POST["ldap_default_db_owner_admin_username_id"]))
        $_POST["ldap_default_db_owner_admin_username_id"] = intval($_POST["ldap_default_db_owner_admin_username_id"]);

      if(isset($_POST["dbUserMailingListAccess"]) && is_string($_POST["dbUserMailingListAccess"]) && $_POST["dbUserMailingListAccess"] == "1")
        $_POST["dbUserMailingListAccess"] = $_8QJ8L;

      if( (!isset($_POST["ldap_field_owner_admin"]) || trim($_POST["ldap_field_owner_admin"]) == "") &&
          (!isset($_POST["ldap_default_db_owner_admin_username_dn"]) || trim($_POST["ldap_default_db_owner_admin_username_dn"]) == "")
          ){
           if( isset($_POST["mailinglists"]) )
             $_POST["dbUserMailingListAccess"] = $_POST["mailinglists"];
      }

      if(!isset($_POST["dbUserMailingListAccess"]))
        $_POST["dbUserMailingListAccess"] = array();

      if(!is_array($_POST["dbUserMailingListAccess"]))
        $_POST["dbUserMailingListAccess"] = array($_POST["dbUserMailingListAccess"]);

      $_POST["dbUserMailingListAccess"] = serialize($_POST["dbUserMailingListAccess"]);
      if(isset($_POST["mailinglists"]))
        unset($_POST["mailinglists"]);


    }

    if( count($errors) == 0 && _JO8B1()) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

      if($_POST["AuthType"] == "db2fa" && !empty($_POST["2FAForSuperAdmin"]))
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["2FAReloginAsSuperAdmin"];

    } else {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
    }
    $_QLO0f = $_POST;

  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["ChangeAuthSettings"], $_Itfj8, 'settings_authsettings', 'settings_authsettings_snipped.htm');

  $_QLO0f["ssl"] = "0";
  if(isset($_QLO0f["ldap_tls"]) && $_QLO0f["ldap_tls"])
    $_QLO0f["ssl"] = "1";
  if(isset($_QLO0f["ldap_ssl"]) && $_QLO0f["ldap_ssl"])
    $_QLO0f["ssl"] = "2";

  $_QLfol = "SELECT `id`, `Username` FROM `$_I18lo` WHERE `UserType`='Admin' AND `IsActive`=1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLoli = "";
  while($_IC1oC = mysql_fetch_assoc($_QL8i1)){
    $_QLoli .= '<option value="' . $_IC1oC["id"] . '">' . $_IC1oC["Username"] . '</option>' . "\r\n";
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<ldap_default_db_owner_admin_username>", "</ldap_default_db_owner_admin_username>", $_QLoli);

  if(isset($_QLO0f["dbUserPrivileges"])){
    $_QLO0f["dbUserPrivileges"] = @unserialize($_QLO0f["dbUserPrivileges"]);
    if($_QLO0f["dbUserPrivileges"] === false)
       $_QLO0f["dbUserPrivileges"] = array();
    if(!is_array($_QLO0f["dbUserPrivileges"]))
       $_QLO0f["dbUserPrivileges"] = array();
    foreach($_QLO0f["dbUserPrivileges"] as $key => $_QltJO){
      $_QLO0f[$_QltJO] = 1;
    }
  }


  if(isset($_QLO0f["dbUserMailingListAccess"])){
    $_QLO0f["dbUserMailingListAccess"] = @unserialize($_QLO0f["dbUserMailingListAccess"]);
    if($_QLO0f["dbUserMailingListAccess"] === false)
       $_QLO0f["dbUserMailingListAccess"] = array();
    if(!is_array($_QLO0f["dbUserMailingListAccess"]))
       $_QLO0f["dbUserMailingListAccess"] = array();
  }else
    $_QLO0f["dbUserMailingListAccess"] = array();

  $_QlIf1 = "";
  if( $_QLO0f["AuthType"] == "ldap" && trim($_QLO0f["ldap_field_owner_admin"]) == "" && trim($_QLO0f["ldap_default_db_owner_admin_username_dn"]) == "" && $_QLO0f["ldap_default_db_owner_admin_username_id"] > 0){
    $_QlI6f = "SELECT DISTINCT {} FROM $_QL88I";
    $_QlI6f .= " WHERE users_id=" . $_QLO0f["ldap_default_db_owner_admin_username_id"];
    $_QlI6f = str_replace("{}", "id, Name", $_QlI6f);
    $_QlI6f .= " ORDER BY Name ASC";

    // Mailinglisten Liste
    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    if($_QL8i1) {
      while($_I1OfI = mysql_fetch_assoc($_QL8i1)) {
         $_IOfJi = "";
         if(in_array($_I1OfI["id"], $_QLO0f["dbUserMailingListAccess"]))
           $_IOfJi = 'checked="checked"';
         $_QlIf1 .= sprintf('<span class="scrollboxSpan"><input type="checkbox" name="mailinglists[]" value="%d" id="mailinglistsId%d" ' . $_IOfJi . ' />&nbsp;<label for="mailinglistsId%d">%s</label><br /></span>', $_I1OfI["id"], $_I1OfI["id"], $_I1OfI["id"], $_I1OfI["Name"]);
      }
      mysql_free_result($_QL8i1);
    }

    $_QLO0f["LastAdminUserId"] = $_QLO0f["ldap_default_db_owner_admin_username_id"];
    if(count($_QLO0f["dbUserMailingListAccess"]) == 1 && $_QLO0f["dbUserMailingListAccess"][0] == $_8QJ8L){
        $_QLO0f["dbUserMailingListAccess"] = $_8QJ8L;
      }
      else
      unset($_QLO0f["dbUserMailingListAccess"]);
  }else{
    if(isset($_QLO0f["dbUserMailingListAccess"]) && count($_QLO0f["dbUserMailingListAccess"]) == 1 && $_QLO0f["dbUserMailingListAccess"][0] == $_8QJ8L)
      $_QLO0f["dbUserMailingListAccess"] = $_8QJ8L;
      else
      if(isset($_QLO0f["dbUserMailingListAccess"]))
        unset($_QLO0f["dbUserMailingListAccess"]);
  }

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>", $_QlIf1);

  $_QLJfI = _L8AOB($errors, $_QLO0f, $_QLJfI);

  if(defined("SML") && $OwnerOwnerUserId == 0x41){
    $_QLJfI = _L81BJ($_QLJfI, "<IS:NOT_SML_BASIC>", "</IS:NOT_SML_BASIC>", "");
  }else
    $_QLJfI = _L8OF8($_QLJfI, "<IS:NOT_SML_BASIC>");

  if(defined("SWM") && ($OwnerOwnerUserId <= 0x41 || $OwnerOwnerUserId == 0x5A)){
    $_QLJfI = _L80DF($_QLJfI, "<USER_RIGHTS>", "</USER_RIGHTS>");
    $_QLJfI = _L80DF($_QLJfI, "<IS:NOT_SWM_BASIC>", "</IS:NOT_SWM_BASIC>");
    }
    else{
      $_QLJfI = _L8OF8($_QLJfI, "<USER_RIGHTS>");
      $_QLJfI = _L8OF8($_QLJfI, "<IS:NOT_SWM_BASIC>");
    }

  if(function_exists("ldap_connect"))
    $_QLJfI = _L80DF($_QLJfI, "<NOT:LDAP>", "</NOT:LDAP>");
    else
      $_QLJfI = _L8OF8($_QLJfI, "<NOT:LDAP>");

  print _JJCCF($_QLJfI);

  function _JO8B1() {
    global $_JQ00O, $_QLttI;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM `$_JQ00O`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
    if (mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if($key == "Field") {
                 $_Iflj0[] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    $_ItIti = array();
    $_ItI0o = array("ldap_ssl", "ldap_tls", "2FAForSuperAdmin");
    $_I6tLJ = $_POST;

    $_QLfol = "UPDATE `$_JQ00O` SET ";
    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
          $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]) );
        }
      } else {
         if(in_array($key, $_ItI0o)) {
           $key = $_Iflj0[$_Qli6J];
           $_Io01j[] = "`$key`=0";
         } else {
           if(in_array($key, $_ItIti)) {
             $key = $_Iflj0[$_Qli6J];
             $_Io01j[] = "`$key`=0";
           }
         }
      }
    }

    $_QLfol .= join(", ", $_Io01j);
    $_QLfol .= " WHERE id=1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

   return true;
  }

?>
