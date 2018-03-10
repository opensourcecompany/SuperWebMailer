<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

  if ( ((!isset($_POST['Username'])) || ($_POST['Username'] == "") ) || ((!isset($_POST['Password'])) || ($_POST['Password'] == "")  ) ) {
    _OEJLD("000005", array('Username', 'Password'));
    exit;
  }

  $_QJlJ0 = "SELECT `$_Q8f1L`.*, `$_Q880O`.`Theme`, `$_Q880O`.`id` As ThemesId FROM `$_Q8f1L` LEFT JOIN `$_Q880O` ON `$_Q880O`.`id`=`$_Q8f1L`.`ThemesId` WHERE `Username`="._OPQLR($_POST["Username"])." AND ";
  $_QJlJ0 .= "IF(LENGTH(`Password`) < 80, `Password`=PASSWORD("._OPQLR($_POST["Password"]).")".", SUBSTRING(`Password`, 81)=PASSWORD("."CONCAT(SUBSTRING(`Password`, 1, 80), "._OPQLR($_POST["Password"]).") )".")";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

  if ( (!$_Q60l1) || (mysql_num_rows($_Q60l1) == 0) ) {
    _OEJLD("000006", array('Username', 'Password'));
    exit;
  }

  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  if(!$_Q6Q1C["IsActive"]){
    _OEJLD("AccountDisabled", array('Username', 'Password'));
    exit;
  }

  // is it a user than we need the owner_id
  $_QJlJ0 = "SELECT owner_id FROM $_QLtQO WHERE users_id=$_Q6Q1C[id]";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if ( ($_Q60l1) && (mysql_num_rows($_Q60l1) > 0) ) {
    $_QllO8 = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q6Q1C["OwnerUserId"] = $_QllO8[0];
  } else {
    $_Q6Q1C["OwnerUserId"] = 0;
  }

  # ignore errors if session.auto_start = 1
  session_cache_limiter('public');
  if(!ini_get("session.auto_start"))
    @session_start();

  #session_register("UserId", "OwnerUserId", "AOwnerOwnerUserId", "Username", "UserType", "AccountType", "Language", "Theme", "ThemesId", "SHOW_LOGGEDINUSER", "SHOW_SUPPORTLINKS", "SHOW_SHOWCOPYRIGHT", "SHOW_PRODUCTVERSION", "SHOW_TOOLTIPS", "ProductLogoURL", "_UserFilesPath", "_UserAbsoluteFilesPath");

  $_SESSION["UserId"] = $_Q6Q1C["id"];
  $_SESSION["OwnerUserId"] = $_Q6Q1C["OwnerUserId"];
  $_SESSION["Username"] = $_Q6Q1C["Username"];
  $_SESSION["UserType"] = $_Q6Q1C["UserType"];
  $_SESSION["AccountType"] = $_Q6Q1C["AccountType"];
  $_SESSION["Language"] = $_Q6Q1C["Language"];
  $_SESSION["Theme"] = $_Q6Q1C["Theme"];
  $_SESSION["ThemesId"] = $_Q6Q1C["ThemesId"];
  $_SESSION["SHOW_LOGGEDINUSER"] = $_Q6Q1C["SHOW_LOGGEDINUSER"];
  $_SESSION["SHOW_SUPPORTLINKS"] = $_Q6Q1C["SHOW_SUPPORTLINKS"];
  $_SESSION["SHOW_SHOWCOPYRIGHT"] = $_Q6Q1C["SHOW_SHOWCOPYRIGHT"];
  $_SESSION["SHOW_PRODUCTVERSION"] = $_Q6Q1C["SHOW_PRODUCTVERSION"];
  $_SESSION["SHOW_TOOLTIPS"] = $_Q6Q1C["SHOW_TOOLTIPS"];
  if(isset($_GET["DEBUG"])){
    $_SESSION["DEBUG"] = 1;
  }

  $_JOJQO = false;
  if($_Q6Q1C["OwnerUserId"] == 0 && $_Q6Q1C["ProductLogoURL"] != "") { // Admin
   $_SESSION["ProductLogoURL"] = $_Q6Q1C["ProductLogoURL"];
   $_JOJQO = true;
  } else
    if($_Q6Q1C["OwnerUserId"] != 0 ) {
      $_QJlJ0 = "SELECT ProductLogoURL FROM $_Q8f1L WHERE id=$_Q6Q1C[OwnerUserId]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_QllO8 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      if($_QllO8["ProductLogoURL"] != "") {
         $_SESSION["ProductLogoURL"] = $_QllO8["ProductLogoURL"];
         $_JOJQO = true;
      }
    }

  $_QJlJ0 = "SELECT * FROM $_Q88iO LIMIT 0,1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  if(!$_JOJQO) {
     $_SESSION["ProductLogoURL"] = $_Q6Q1C["ProductLogoURL"];
  }

  if(defined("DEMO"))
    $_SESSION["ProductLogoURL"] = "";
  if($_SESSION["ProductLogoURL"] != "")
     if(stripos($_SESSION["ProductLogoURL"], '"') !== false || stripos($_SESSION["ProductLogoURL"], "'") !== false || stripos($_SESSION["ProductLogoURL"], '<') !== false  || stripos($_SESSION["ProductLogoURL"], '>') !== false)
       $_SESSION["ProductLogoURL"] = "";

  $_Q8otJ = _LQEQR($_Q6Q1C);
  if(count($_Q8otJ) == 0 ||
     !isset($_Q8otJ["DashboardDate"]) ||
     $_Q8otJ["DashboardDate"] == "" ||
     !isset($_Q8otJ["DashboardUser"]) ||
     $_Q8otJ["DashboardUser"] == "" ||
     !isset($_Q8otJ["DashboardTag"]) ||
     $_Q8otJ["DashboardTag"] == "" ||
     (strlen($_Q8otJ["DashboardTag"]) != $_jJO6j - 108)

    ) {
      print "@XAOVERFLOW";
      exit;
    } else{
      $_SESSION["AOwnerOwnerUserId"] = ord( substr($_Q8otJ["DashboardTag"], strlen($_Q8otJ["DashboardTag"]) - 1, 1) );
      $_SESSION["_j6QI6"] = strrev(substr(substr($_Q8otJ["DashboardTag"], 0, 13), 7));
      if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
      } else {
        if(substr($_Q8otJ["DashboardTag"], 0, 1) == "E"){
          print "Installieren Sie die Vollversion, um diese Version ausf&uuml;hren zu k&ouml;nnen.";
          print "<br />";
          print "Install full version to use this version.";
          exit;
        }
      }
    }

  LoadUserSettings();
  // paths for filemanager
  $_SESSION["_UserAbsoluteFilesPath"] = $_jjC06;

  $_Qt6oI = $UserId;
  if($OwnerUserId != 0)
    $_Qt6oI = $OwnerUserId;

  $_SESSION["_UserFilesPath"] = BasePath."userfiles/".$_Qt6oI."/";

  // Options empty?
  _ORFOQ();
  _ORFOA();

  // Cronjob entries empty?
  _ORFEQ();

  // Pages empty?
  _O800C();
  // Messages empty?
  _O81AE();
  // MTAs empty?
  _O81BJ();
  // Functions empty?
  _O81DC();

  define("LoginDone", 1);
  if ( ($UserType == "SuperAdmin") )
  include_once("browseusers.php");
  else
  include_once("dashboard.inc.php");
?>
