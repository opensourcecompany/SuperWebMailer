<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
  include_once("php_register_globals_off.inc.php");
  include_once("templates.inc.php");
  include_once("defaulttexts.inc.php");
  include_once("login_page.inc.php");
  include_once("savedoptions.inc.php");
  include_once("ldap_auth.php");

  $MaxLoginAttempts = 5;
  if(defined("MaxLoginAttempts"))
    $MaxLoginAttempts = MaxLoginAttempts;

  $_6l6Jt = new FailedLogins();
  
  if($_6l6Jt->_LDCEA() > $MaxLoginAttempts){
    $_6l6Jt->_LDCBL();
    _LDB1C("000000", array('Username', 'Password'));
    die;
  }

  $_6lfIJ = false;
  if(isset($_POST["2fa"]) && !empty($_POST["SecurityToken"]) ){
    $_6l8I6 = new _2FA();
    $_6lfIJ = $_6l8I6->_LDFBQ();
    if(!$_6lfIJ){
      $_6l6Jt->_LDCBL();
      _LDB1C("000005", array('Username', 'Password'));
      die;
    }
  }
  
  if ( !$_6lfIJ && (empty($_POST['Username']) || empty($_POST['Password'])) ) {
    $_6l6Jt->_LDCBL();
    _LDB1C("000005", array('Username', 'Password'));
    die;
  }

  $_QLfol = "SELECT * FROM `$_JQ00O` LIMIT 0,1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
    if($_QL8i1)
      mysql_free_result($_QL8i1);
    mysql_query("INSERT INTO `$_JQ00O` SET `id`=1, `AuthType`='db'", $_QLttI);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
      _LDB1C("NoLoginAuthVariant", array('Username', 'Password'));
      die;
    }
    $_I1jfC = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    
  }else{
    $_I1jfC = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }

  // superadmin in local db
  if(!$_6lfIJ && strtolower($_POST['Username']) == "superadmin"){
    if($_I1jfC["AuthType"] != "db"){
      if($_I1jfC["AuthType"] == "db2fa" && !$_I1jfC["2FAForSuperAdmin"])
         $_I1jfC["AuthType"] = "db";
      if($_I1jfC["AuthType"] !== "db2fa") // LDAP => db  
         $_I1jfC["AuthType"] = "db";
    }  
  }

  $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;

  switch($_I1jfC["AuthType"]){
     
     case 'db':
     case 'db2fa':

         $_QLfol = "SELECT `$_I18lo`.*, `$_I1tQf`.`Theme`, `$_I1tQf`.`id` As ThemesId FROM `$_I18lo` LEFT JOIN `$_I1tQf` ON `$_I1tQf`.`id`=`$_I18lo`.`ThemesId` WHERE ";

         if(!$_It0IQ){
           if(!$_6lfIJ){
             $_QLfol .= "`Username`="._LRAFO($_POST["Username"])." AND ";
             $_QLfol .= "IF(LENGTH(`Password`) < 80, `Password`=PASSWORD("._LRAFO($_POST["Password"]).")".", SUBSTRING(`Password`, 81)=PASSWORD("."CONCAT(SUBSTRING(`Password`, 1, 80), "._LRAFO($_POST["Password"]).") )".")";
           }else{
             $_QLfol .= "`Username`="._LRAFO($_6lfIJ["Username"])." AND ";
             $_QLfol .= "`Password`="._LRAFO($_6lfIJ["Password"]);
             }
         }else{
           if(!$_6lfIJ){
             $_QLfol .= "`Username`="._LRAFO($_POST["Username"])." AND ";
             $_QLfol .= "IF(LENGTH(`Password`) < 80, `Password`=SHA2("._LRAFO($_POST["Password"]).", 224)".", SUBSTRING(`Password`, 81)=SHA2("."CONCAT(SUBSTRING(`Password`, 1, 80), "._LRAFO($_POST["Password"])."), 224 )".")";
           }else{
             $_QLfol .= "`Username`="._LRAFO($_6lfIJ["Username"])." AND ";
             $_QLfol .= "`Password`="._LRAFO($_6lfIJ["Password"]);
           }
         }

         $_QL8i1 = mysql_query($_QLfol, $_QLttI);

         if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
           $_6l6Jt->_LDCBL();
           _LDB1C("000006", array('Username', 'Password'));
           die;
         }

         $_QLO0f = mysql_fetch_assoc($_QL8i1);
         mysql_free_result($_QL8i1);
         if($_6lfIJ || $_I1jfC["AuthType"] == "db" || version_compare(PHP_VERSION, "7.1.0", "<"))
           break;
         if(!isset($_6l8I6))
           $_6l8I6 = new _2FA();  
         $_6l8I6->_LDF6Q($_I1jfC, $_QLO0f);
         die;  
     case 'ldap':
         $_6l8Io = new _LDR0F();
         if(!$_6l8Io->ldap_available){
           _LDB1C("NoLDAPExtension", array('Username', 'Password'));
           die;
         }

         $_6l8Io->ldap_address = $_I1jfC["ldap_address"];
         $_6l8Io->ldap_port = $_I1jfC["ldap_port"];
         $_6l8Io->ldap_use_ssl = (bool)$_I1jfC["ldap_ssl"];
         $_6l8Io->ldap_use_tls = (bool)$_I1jfC["ldap_tls"];

         $_6l8Io->ldap_base_dn = $_I1jfC["ldap_base_dn"];
         $_6l8Io->ldap_protocol_version = $_I1jfC["ldap_protocol_version"];
         $_6l8Io->ldap_external_auth_username = $_I1jfC["ldap_external_auth_username"];
         $_6l8Io->ldap_external_auth_password = $_I1jfC["ldap_external_auth_password"];
         $_6l8Io->ldap_field_uid = $_I1jfC["ldap_field_uid"];
         $_6l8Io->ldap_field_email = $_I1jfC["ldap_field_email"];
         $_6l8Io->ldap_field_owner_admin = $_I1jfC["ldap_field_owner_admin"];
         $_6l8Io->ldap_field_useraccountcontrol = $_I1jfC["ldap_field_useraccountcontrol"];
         $_6l8Io->ldap_useraccountcontrol_useractivevalue = $_I1jfC["ldap_useraccountcontrol_useractivevalue"];
         $_6l8Io->ldap_user_filter = $_I1jfC["ldap_user_filter"];
         $_6l8Io->ldap_default_owner_admin_username = $_I1jfC["ldap_default_db_owner_admin_username_dn"];

         if(!$_6l8Io->_LDRJJ(trim($_POST["Username"]), trim($_POST["Password"]), $_6LtL0, $_6LOfC, $_6Loff, $_6LoCL, $_6LCof)){
           $_6l6Jt->_LDCBL();
           _LDB1C("LDAPAuthFailed", array('Username', 'Password'), $_6l8Io->ldap_lastErrorText);
           die;
         }
         if(!$_6LCof){
           // deactivate user by username
           $_QLfol = "UPDATE `$_I18lo` SET `IsActive`=0 WHERE `Username`="._LRAFO($_6LtL0);
           mysql_query($_QLfol, $_QLttI);
           // leave switch here
           $_QLO0f["IsActive"] = false;
           break;
         }

         $_QLfol = "SELECT `$_I18lo`.*, `$_I1tQf`.`Theme`, `$_I1tQf`.`id` As ThemesId FROM `$_I18lo` LEFT JOIN `$_I1tQf` ON `$_I1tQf`.`id`=`$_I18lo`.`ThemesId` WHERE `Username`="._LRAFO($_6LtL0);
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
           // user not exists
           if($_QL8i1)
             mysql_free_result($_QL8i1);

           if($_6LoCL == "" && $_I1jfC["ldap_default_db_owner_admin_username_id"] > 0){
             $_QLfol = "SELECT `Username` FROM `$_I18lo` WHERE `UserType`='Admin' AND `id`=" . $_I1jfC["ldap_default_db_owner_admin_username_id"];
             $_QL8i1 = mysql_query($_QLfol, $_QLttI);
             if($_I1OfI = mysql_fetch_row($_QL8i1)){
               $_6LoCL = $_I1OfI[0];
             }
             if($_QL8i1)
               mysql_free_result($_QL8i1);
           }

           if($_6LoCL == "" || defined("SWM") && (_JO61Q() <= 0x41 || _JO61Q() == 0x5A)){
            _LDB1C("LDAPAuthNoDBAdminUser", array('Username', 'Password'));
            die;
           }

           // get ID of admin user
           $_QLfol = "SELECT `id`, `Language`, `ThemesId` FROM `$_I18lo` WHERE `IsActive`>0 AND `UserType`='Admin' AND `Username`=" . _LRAFO($_6LoCL);
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
            if($_QL8i1)
              mysql_free_result($_QL8i1);
            _LDB1C("LDAPAuthNoDBAdminUser", array('Username', 'Password'));
            die;
           }
           $_6l8tj = mysql_fetch_assoc($_QL8i1);
           mysql_free_result($_QL8i1);

           _L8FCO($_I18lo, $_ItI0o);
           $_I1jfC["dbUserPrivileges"] = unserialize($_I1jfC["dbUserPrivileges"]);
           $_Io01j = array();
           for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++){
             if(strpos($_ItI0o[$_Qli6J], "Privilege") !== false)
               if(in_array($_ItI0o[$_Qli6J], $_I1jfC["dbUserPrivileges"]))
                 $_Io01j[] = "`".$_ItI0o[$_Qli6J] . "`" . "=1";
                 else
                 $_Io01j[] = "`".$_ItI0o[$_Qli6J] . "`" . "=0";
           }

           $_I8li6 = _LAPE1();

           $_QLfol = "INSERT INTO `$_I18lo` SET `IsActive`=1, `UserType`='User', `Language`=" . _LRAFO($_6l8tj["Language"]) . ", `ThemesId`=" . _LRAFO($_6l8tj["ThemesId"]);
           $_QLfol .= ", `Username`=" . _LRAFO($_6LtL0) . ", `EMail`=" ._LRAFO($_6Loff);
           if(!$_It0IQ)
             $_QLfol .= ", `Password`=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.trim($_6LOfC) ).") )";
             else
             $_QLfol .= ", `Password`=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.trim($_6LOfC) ).", 224) )";
           if(count($_Io01j))
             $_QLfol .= ", " . join(", ", $_Io01j);
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if ( mysql_error($_QLttI) != "" || mysql_affected_rows($_QLttI) == 0 ) {
            _LDB1C("LDAPCantCreateNewUser", array('Username', 'Password'),  mysql_error($_QLttI));
            die;
           }
           $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
           $_QLO0f = mysql_fetch_row($_QL8i1);
           $_6l8Ot = $_QLO0f[0];
           mysql_free_result($_QL8i1);

           if($_6l8Ot == 0){
            _LDB1C("LDAPCantCreateNewUser", array('Username', 'Password'),  mysql_error($_QLttI));
            die;
           }

           // set owner of user
           $_QLfol = "INSERT INTO `$_IfOtC` SET `users_id`=$_6l8Ot, `owner_id`=$_6l8tj[id]";
           mysql_query($_QLfol, $_QLttI);
           if(mysql_error($_QLttI) != ""){
             _LDB1C("LDAPCantCreateNewUser", array('Username', 'Password'),  mysql_error($_QLttI));
             die;
           }

           // set MailingLists of user
           $_I1jfC["dbUserMailingListAccess"] = unserialize($_I1jfC["dbUserMailingListAccess"]);
           if(is_array($_I1jfC["dbUserMailingListAccess"]) && count($_I1jfC["dbUserMailingListAccess"])){
             $_6ltO6 = array();
             $_QLfol = "SELECT `id` FROM `$_QL88I` WHERE `users_id`=$_6l8tj[id]";
             $_QL8i1 = mysql_query($_QLfol, $_QLttI);
             while($_I1OfI = mysql_fetch_row($_QL8i1)){
               $_6ltO6[] = $_I1OfI[0];
             }
             mysql_free_result($_QL8i1);

             if(count($_I1jfC["dbUserMailingListAccess"]) == 1 && $_I1jfC["dbUserMailingListAccess"][0] == "@all_mailingLists#+~"){
               foreach($_6ltO6 as $key => $_QltJO){
                 $_QLfol = "INSERT INTO `$_QlQot` SET `users_id`=$_6l8Ot, `maillists_id`=$_QltJO";
                 mysql_query($_QLfol, $_QLttI);
               }
             }
             else{
               foreach($_I1jfC["dbUserMailingListAccess"] as $key => $_QltJO){
                 if(in_array($_QltJO, $_6ltO6)){
                   $_QLfol = "INSERT INTO `$_QlQot` SET `users_id`=$_6l8Ot, `maillists_id`=$_QltJO";
                   mysql_query($_QLfol, $_QLttI);
                 }
               }
             }
           }

           $_QLfol = "SELECT `$_I18lo`.*, `$_I1tQf`.`Theme`, `$_I1tQf`.`id` As ThemesId FROM `$_I18lo` LEFT JOIN `$_I1tQf` ON `$_I1tQf`.`id`=`$_I18lo`.`ThemesId` WHERE `$_I18lo`.`id`=$_6l8Ot";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if(mysql_num_rows($_QL8i1) == 0){
             // something wrong here, should never occur
            _LDB1C("LDAPCantCreateNewUser", array('Username', 'Password'),  mysql_error($_QLttI));
            die;
           }
         }

         if(!$_QLO0f = mysql_fetch_assoc($_QL8i1)){
           _LDB1C("LDAPCantCreateNewUser", array('Username', 'Password'),  mysql_error($_QLttI));
         }
         mysql_free_result($_QL8i1);

         break;
     default:
         _LDB1C("NoLoginAuthVariant", array('Username', 'Password'));
         die;
  }

  if(!$_QLO0f["IsActive"]){
    $_6l6Jt->_LDCBL();
    _LDB1C("AccountDisabled", array('Username', 'Password'));
    die;
  }

  // is it a user than we need the owner_id
  $_QLfol = "SELECT `owner_id` FROM `$_IfOtC` WHERE `users_id`=$_QLO0f[id]";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if ( ($_QL8i1) && (mysql_num_rows($_QL8i1) > 0) ) {
    $_I016j = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_QLO0f["OwnerUserId"] = $_I016j[0];
  } else {
    $_QLO0f["OwnerUserId"] = 0;
  }

  # ignore errors if session.auto_start = 1
  if(IsHTTPS())
    ini_set('session.cookie_secure', 'On');
  session_cache_limiter('public');
  session_set_cookie_params(0, "/", "");
  if(!ini_get("session.auto_start"))
    @session_start();

  $_SESSION["UserId"] = $_QLO0f["id"];
  $_SESSION["OwnerUserId"] = $_QLO0f["OwnerUserId"];
  $_SESSION["Username"] = $_QLO0f["Username"];
  $_SESSION["UserType"] = $_QLO0f["UserType"];
  $_SESSION["AccountType"] = $_QLO0f["AccountType"];
  $_SESSION["Language"] = $_QLO0f["Language"];
  $_SESSION["Theme"] = $_QLO0f["Theme"];
  $_SESSION["ThemesId"] = $_QLO0f["ThemesId"];
  $_SESSION["SHOW_LOGGEDINUSER"] = $_QLO0f["SHOW_LOGGEDINUSER"];
  $_SESSION["SHOW_SUPPORTLINKS"] = $_QLO0f["SHOW_SUPPORTLINKS"];
  $_SESSION["SHOW_SHOWCOPYRIGHT"] = $_QLO0f["SHOW_SHOWCOPYRIGHT"];
  $_SESSION["SHOW_PRODUCTVERSION"] = $_QLO0f["SHOW_PRODUCTVERSION"];
  $_SESSION["SHOW_TOOLTIPS"] = $_QLO0f["SHOW_TOOLTIPS"];
  if(isset($_GET["DEBUG"])){
    $_SESSION["DEBUG"] = 1;
  }

  $_6lO8o = false;
  if($_QLO0f["OwnerUserId"] == 0 && $_QLO0f["ProductLogoURL"] != "") { // Admin
   $_SESSION["ProductLogoURL"] = $_QLO0f["ProductLogoURL"];
   $_6lO8o = true;
  } else
    if($_QLO0f["OwnerUserId"] != 0 ) {
      $_QLfol = "SELECT ProductLogoURL FROM $_I18lo WHERE id=$_QLO0f[OwnerUserId]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_I016j = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      if($_I016j["ProductLogoURL"] != "") {
         $_SESSION["ProductLogoURL"] = $_I016j["ProductLogoURL"];
         $_6lO8o = true;
      }
    }

  $_QLfol = "SELECT * FROM $_I1O0i LIMIT 0,1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  if(!$_6lO8o) {
     $_SESSION["ProductLogoURL"] = $_QLO0f["ProductLogoURL"];
  }

  if(defined("DEMO"))
    $_SESSION["ProductLogoURL"] = "";
  if($_SESSION["ProductLogoURL"] != "")
     if(stripos($_SESSION["ProductLogoURL"], '"') !== false || stripos($_SESSION["ProductLogoURL"], "'") !== false || stripos($_SESSION["ProductLogoURL"], '<') !== false  || stripos($_SESSION["ProductLogoURL"], '>') !== false)
       $_SESSION["ProductLogoURL"] = "";

  $_I1OoI = _JOLOA($_QLO0f);
  if(!is_array($_I1OoI) || !count($_I1OoI) ||
     !isset($_I1OoI["DashboardDate"]) ||
     $_I1OoI["DashboardDate"] == "" ||
     !isset($_I1OoI["DashboardUser"]) ||
     $_I1OoI["DashboardUser"] == "" ||
     !isset($_I1OoI["DashboardTag"]) ||
     $_I1OoI["DashboardTag"] == "" ||
     (strlen($_I1OoI["DashboardTag"]) != $_JQJll - 108)

    ) {
      print "@XAOVERFLOW";
      exit;
    } else{
      $_SESSION["AOwnerOwnerUserId"] = ord( substr($_I1OoI["DashboardTag"], strlen($_I1OoI["DashboardTag"]) - 1, 1) );
      $_SESSION["AOwnerOwnerUserUniqueId"] = strrev(substr(substr($_I1OoI["DashboardTag"], 0, 13), 7));
      if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
      } else {
        if(substr($_I1OoI["DashboardTag"], 0, 1) == "E"){
          print "Installieren Sie die Vollversion, um diese Version ausf&uuml;hren zu k&ouml;nnen.";
          print "<br />";
          print "Install full version to use this version.";
          exit;
        }
      }
    }

  LoadUserSettings();
  // paths for filemanager
  $_SESSION["_UserAbsoluteFilesPath"] = $_J18oI;

  $_Qll8O = $UserId;
  if($OwnerUserId != 0)
    $_Qll8O = $OwnerUserId;

  $_SESSION["_UserFilesPath"] = BasePath."userfiles/".$_Qll8O."/";

  // login log
  $_QLfol = "INSERT INTO `$_jCJ6O` SET `users_id`=$UserId, `LastLogin`=NOW(), `IP`=" . _LRAFO( getOwnIP(false) );
  mysql_query($_QLfol, $_QLttI);
  $_QLfol = "SELECT LAST_INSERT_ID()";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  $_SESSION["UsersLoginId"] = $_QLO0f[0];
  mysql_free_result($_QL8i1);
  $_QLfol = "UPDATE `$_I18lo` SET `LastLogin`=NOW() WHERE `id`=$UserId";
  mysql_query($_QLfol, $_QLttI);
  //
    
  // Options empty?
  _LJDJB();
  _LJDBF();

  // Cronjob entries empty?
  _LJEJR();

  // Pages empty?
  _LJEDE();
  // Messages empty?
  _LJFP0();
  // MTAs empty?
  _L6066();
  // Functions empty?
  _L61QE();

  define("LoginDone", 1);
  $_POST[SMLSWM_TOKEN_COOKIE_NAME] = _LJPOA();

  if ( ($UserType == "SuperAdmin") )
  include_once("browseusers.php");
  else
  include_once("dashboard.inc.php");
  
?>
