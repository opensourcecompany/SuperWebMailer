<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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
 if(!defined("E_STRICT"))
   define("E_STRICT", 0);
 if(!defined("E_DEPRECATED"))
   define("E_DEPRECATED", 0);
 if(!defined("E_RECOVERABLE_ERROR"))
   define("E_RECOVERABLE_ERROR", 4096);

 error_reporting( 0 );
 ini_set("display_errors", 0);

 define("SWM", 1);
 include_once("config_paths.inc.php");
 include_once(InstallPath."userdefined.inc.php");

 if(defined("DEBUG")){
   error_reporting( E_ALL & ~ ( E_NOTICE /*| E_WARNING*/  | E_DEPRECATED | E_STRICT ) );
   ini_set("display_errors", 1);
 }

 include_once(InstallPath."version.inc.php");
 include_once(InstallPath."config_db.inc.php");
 include_once(InstallPath."phpcompat.php");
 include_once(InstallPath."ressources.inc.php");
 include_once(InstallPath."functions.inc.php");

 if(!function_exists("mysql_connect")) {
   // Include the definitions
   require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysqli' . DIRECTORY_SEPARATOR . 'MySQL_Definitions.php');
   // Include the object
   require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysqli' . DIRECTORY_SEPARATOR . 'MySQL.php');
   // Include the mysql_* functions
   require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysqli' . DIRECTORY_SEPARATOR . 'MySQL_Functions.php');
 }

 # default paths
 $_jjC06 = InstallPath."userfiles/";
 $_jjCtI = ScriptBaseURL."userfiles/";
 @mkdir($_jjC06, 0777);
 $_I0lQJ = $_jjC06."import/";
 $_jji0C = $_jjC06."export/";
 $_QOCJo = $_jjC06."file/";
 $_QCo6j = $_jjC06."image/";
 $_jji0i = $_jjC06."media/";

 # default newsletter subscribtion/unsubscribtion Script
 $_jjiCt = "defaultnewsletter.php";
 $_jjLO0 = ScriptBaseURL.$_jjiCt;
 $_jjlQ0 = "nl.php";
 $_jjlC6 = "nlu.php";
 $_jJ088 = ScriptBaseURL.$_jjlQ0;
 $_jJ1Il = ScriptBaseURL.$_jjlC6;

 # default alt browser link
 $_jJ1Li = "browser.php";
 $_jJQ66 = ScriptBaseURL.$_jJ1Li;

 # Tracking scripts
 $_jJIJ8 = "ostat.php";
 $_jJIol = ScriptBaseURL.$_jJIJ8;
 $_jJjo6 = "link.php";
 $_jJjCi = ScriptBaseURL.$_jJjo6;

 # Default Tablenames
 $_Qofjo = TablePrefix."fieldnames";
 $_Q88iO = TablePrefix."options";
 $_jJJjO = TablePrefix."emailoptions";
 $_jJJtf = TablePrefix."cronoptions";
 $_jJ6Qf = TablePrefix."cronlog";
 $_Q60QL = TablePrefix."maillists";
 $_Q6fio = TablePrefix."maillists_users";
 $_Q880O = TablePrefix."themes";
 $_Qo6Qo = TablePrefix."languages";
 $_Q8f1L = TablePrefix."users";
 $_QLtQO = TablePrefix."users_owner";
 $_QtjLI = TablePrefix."outqueue";
 $_Io680 = TablePrefix."localusermessages";

 $_QolLi = "";
 $_Qofoi = "";
 $_ICljl = "";
 $_QLo0Q = "";
 $_Ql8C0 = "";
 $_Qlt66 = "";
 $_I88i8 = "";
 $_Q6C0i = "";
 $_Q66li = "";
 $_Q6ftI = "";
 $_I8tjl = "";

 $_I0f8O = "";

 $_IC1lt = "";
 $_Q6jOo = "";
 $_IIl8O = "";
 $_IjQIf = "";
 $_IQL81 = "";
 $_II8J0 = "";
 $_QCLCI = "";

 $_IC0oQ = "";
 $_ICjQ6 = "";

 $_IoOLJ = "";
 $_ICjCO = "";

 $_jJ6f0 = "";

 $_IooOQ = "";

 $_IoCtL = "";

 $_QoOft = "";
 $_Qoo8o = "";

# ControlTabs, we must remove it because firefox has problems
 $_jJf6o = array('<SHOWHIDE:ProductLogo>', '<ENABLE:SYSTEMMENU>', '<ISUSER:SUPERADMIN>', '<ISUSER:USER>', '<ISUSER:ADMINISTRATOR>', '<SHOWHIDE:LOGGEDINUSER>', '<ENABLE:MAINMENU>', '<SHOWHIDE:WARNHEADER>', '<SHOWHIDE:PAGETOPIC>', '<SHOWHIDE:HELPTOPIC>', '<SHOWHIDE:ERRORTOPIC>', '<CONTAINER:CONTENT>', '<SHOW:SUPPORTLINKS>', '<SHOW:SHOWCOPYRIGHT>', '<SHOW:PRODUCTVERSION>', '<SHOWHIDE:TOOLTIPS>', '<IS:SWM>', '<IS:SML>', '<SHOWHIDE:EVALUATIONHEADER>');
 $_Q6JJJ = "\r\n";
 $_Q6QQL = "utf-8";
 $_jJfoI = "target_groups";
 $_jJ88O = '<span style="mso-hide:all;display:none !important;font-size:0;max-height:0;line-height:0;visibility:hidden;overflow:hidden;opacity:0;color:transparent;height:0;width:0;">%s</span>';

 $_jJtJt = "X-SWM-BOUNCE";
 $_jJt8t = -9999999; // MTA = SMSout

 # > 9000 check
 define("MonthlySendQuotaExceeded", 9990);
 define("SendQuotaExceeded", 9991);
 define("RecipientIsInECGList", 9992);

# Defaults for Login
 $AppName = "SuperWebMailer";
 $_jJtt0 = $AppName." ".$_QoJ8j;
 $UserId = 0;
 $OwnerUserId = 0;
 $OwnerOwnerUserId = 0xA;
 $_Q8J1j = rand();
 $Username = "";
 $UserType = "none";
 $AccountType = "none";
 $INTERFACE_STYLE = "default";
 $INTERFACE_LANGUAGE = "de";
 $INTERFACE_THEMESID = 1;
 $_jJO1j = false;
 $_jJO6j = 0x90;
 $_jJo1i = 0x99;
 $SHOW_LOGGEDINUSER = true;
 $SHOW_SUPPORTLINKS = true;
 $SHOW_SHOWCOPYRIGHT = true;
 $SHOW_PRODUCTVERSION = true;
 $SHOW_TOOLTIPS = true;
 if(!defined("PHP_VERSION"))
   define("PHP_VERSION", phpversion());

 define("FUResponderTypeTimeBased", 0);
 define("FUResponderTypeActionBased", 1);

########### MySQL Connection
 $_Q61I1 = 0;
 if(MySQLServername != "") { # not on installation
   $_Q61I1 = mysql_connect (MySQLServername, MySQLUsername, MySQLPassword);

   if ($_Q61I1 == 0) {
      print ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".mysql_error());
      exit;
   }

   // UTF-8 connection
   @mysql_query("SET NAMES 'utf8'", $_Q61I1);
   @mysql_query("SET CHARACTER SET 'utf8'", $_Q61I1);
   // not STRICT mode
   @mysql_query('SET SQL_MODE=""', $_Q61I1);

   if (!mysql_select_db (MySQLDBName, $_Q61I1)) {
     print ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".MySQLDBName."<br/>".mysql_error($_Q61I1));
     mysql_close ($_Q61I1);
     exit;
   }

   // UTF-8 connection
   @mysql_query("SET NAMES 'utf8'", $_Q61I1);
   @mysql_query("SET CHARACTER SET 'utf8'", $_Q61I1);
   // not STRICT mode
   @mysql_query('SET SQL_MODE=""', $_Q61I1);

   // set to utf8_general_ci when possible
   @mysql_query("ALTER DATABASE ".MySQLDBName." CHARACTER SET utf8;", $_Q61I1);
 }

##################################
# unquoting strings
 if ( (version_compare(PHP_VERSION, "5.4") < 0) && get_magic_quotes_gpc() ) {
    $_POST = stripslashes_deep($_POST);
    $_GET = stripslashes_deep($_GET);
    $_COOKIE = stripslashes_deep($_COOKIE);
 }
#################################

#################################
// special placeholders

$_IIQI8 = array ('DateShort' => '[Date_short]',
                              'DateLong' => '[Date_long]',
                              'Time' => '[Time]',
                              'RecipientId' => '[RecipientId]',
                              'MailingListId' => '[MailingListId]',
                              'SubscriptionStatus' => '[SubscriptionStatus]',
                              'DateOfSubscription' => '[DateOfSubscription]',
                              'DateOfOptInConfirmation' => '[DateOfOptInConfirmation]',
                              'IPOnSubscription' => '[IPOnSubscription]',
                            );

$_III0L = array(
                                 'UnsubscribeLink' => '[UnsubscribeLink]',
                                 'EditLink' => '[EditLink]'
                                );

// on subscribe
$_jJioL = array(
                                 'SubscribeRejectLink' => '[SubscribeRejectLink]',
                                 'SubscribeConfirmationLink' => '[SubscribeConfirmationLink]'
                                );

// on unsubscribe
$_jJLJ6 = array(
                                 'UnsubscribeRejectLink' => '[UnsubscribeRejectLink]',
                                 'UnsubscribeConfirmationLink' => '[UnsubscribeConfirmationLink]'
                                  );

// on edit
$_jJLoj = array(
                                 'EditRejectLink' => '[EditRejectLink]',
                                 'EditConfirmationLink' => '[EditConfirmationLink]'
                                  );

// on unsubscribe survey
$_jJl6I = array(
                                           'ReasonsForUnsubscriptionSurvey' => '[ReasonsForUnsubscriptionSurvey]'
                                     );

// Alt browser link
$_Ij18l = array (
                                    'AltBrowserLink' => '[AltBrowserLink]'
                                   );

// Autoresponder
$_III86 = array(
                                 'OrgMailSubject' => '[OrgMailSubject]'
                                );

// Birthday responder
$_IjQQ8 = array(
                                 'MembersAge' => '[MembersAge]',
                                 'Days_to_Birthday' => '[Days_to_Birthday]'
                                );

// FollowUp responder
$_j601Q = array(
                                 );

// Campaigns
$_jQt18 = array(
                              );

// RSS2EMail responder
$_j610t = array(
                                 );

// DistribListModifySubject
$_j616i = array(
                                 'OrgMailSubject' => '[OrgMailSubject]',
                                 'DistribListsName' => '[DistribListsName]',
                                 'DistribListsDescription' => '[DistribListsDescription]',
                                 'MailingListName' => '[MailingListName]',
                                 'DistribSenderEMailAddress' => '[DistribSenderEMailAddress]'
                                );

// DistribListConfirmInfo, [CONFIRMATIONLINK] must the last one, don't put to $_IifjO
$_j61l6 = array(
                                 'DistribListsName' => '[DISTRIBLISTNAME]',
                                 'DistribListsSubject' => '[SUBJECT]',
                                 'DistribSenderEMailAddress' => '[FROMADDRESS]',
                                 'DistribListsConfirmationLink' => '[CONFIRMATIONLINK]'
                                );

// all placeholder names
$_IifjO = array_merge($_IIQI8, $_III0L, $_jJioL, $_jJLJ6, $_jJl6I, $_Ij18l, $_III86, $_IjQQ8, $_j601Q, $_jQt18, $_j610t, $_j616i);
//$AllDefaultPlaceholdersNames = array("DefaultPlaceholders", "DefaultUnsubscribePlaceholders", "OnSubscribePlaceholders", "OnUnsubscribePlaceholders", "AltBrowserLinkPlaceholder", "AutoresponderPlaceholders", "BirthdayresponderPlaceholders", "FollowUpresponderPlaceholders", "CampaignsPlaceholders", "RSS2EMailresponderPlaceholders");

// invisible for user!
$_QOifL = array(
                               'AltBrowserLink_SME' => '[AltBrowserLink_SME]',
                               'AltBrowserLink_SME_URLEncoded' => '[AltBrowserLink_SME_URLEncoded]',
                               'Mail_Subject_ISO88591' => '[Mail_Subject_ISO88591]',
                               'Mail_Subject_UTF8' => '[Mail_Subject_UTF8]',
                               'Mail_Subject_ISO88591_URLEncoded' => '[Mail_Subject_ISO88591_URLEncoded]',
                               'Mail_Subject_UTF8_URLEncoded' => '[Mail_Subject_UTF8_URLEncoded]'
                           );

#################################

# allowed import file upload extensions
$_I0l08 = array(".txt", ".csv");

#################################
$_Qo8OO = array(
    'iso-8859-2' => '',
    'iso-8859-3' => '',
    'iso-8859-4' => '',
    'iso-8859-6' => '',
    'iso-8859-7' => '',
    'iso-8859-8' => '',
    'iso-8859-9' => '',
    'iso-8859-10' => '',

    'KOI8-R' => '',
    'KOI8-U' => '',
    'KOI8-RU' => '',

    'windows-1250' => '',
    'windows-1251' => '',
    'windows-1252' => '',
    'windows-1253' => '',
    'windows-1254' => '',
    'windows-1255' => '', // x
    'windows-1256' => '',
    'windows-1257' => '',
    'windows-1258' => ''
);


#################################

# Set timezone PHP 5.3+ required
@setlocale (LC_ALL, 'en_US');
if(function_exists("date_default_timezone_set"))
  @date_default_timezone_set("Europe/London");

################################# PATH PROBLEMS ON SOME SYSTEMS
  if ( ! defined( "PATH_SEPARATOR" ) ) {
    if ( strpos( $_ENV[ "OS" ], "Win" ) !== false )
      define( "PATH_SEPARATOR", ";" );
    else define( "PATH_SEPARATOR", ":" );
  }

  if(ini_get('include_path') == "") {
    ini_set('include_path', './');
  } else {
    $_Q8otJ = explode(PATH_SEPARATOR, ini_get('include_path'));
    $_Qo1oC = false;
    for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++)
      if(strpos($_Q8otJ[$_Q6llo], "./") !== false || strpos($_Q8otJ[$_Q6llo], ".\\") !== false ) {
        $_Qo1oC = true;
        break;
      }
    if(!$_Qo1oC)
      ini_set('include_path', './'.PATH_SEPARATOR.ini_get('include_path'));
  }

#################################

 function _O68QF() {
   global $INTERFACE_STYLE, $INTERFACE_LANGUAGE;
   $_QJCJi = DefaultPath.TemplatesPath."/";

   if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
     $_QJCJi .= $INTERFACE_STYLE."/";
   if(isset($INTERFACE_LANGUAGE) && $INTERFACE_LANGUAGE != "")
     $_QJCJi .= $INTERFACE_LANGUAGE."/";
   return $_QJCJi;
 }

 function _O68A8() {
   return InstallPath."sql/";
 }

 function LoadUserSettings() { // called from sessioncheck.inc.php, login.php and usersedit.php
   global $_SESSION;
   global $UserId, $OwnerUserId, $OwnerOwnerUserId, $_Q8J1j, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $_jJO1j;
   global $SHOW_LOGGEDINUSER, $SHOW_SUPPORTLINKS, $SHOW_SHOWCOPYRIGHT, $SHOW_PRODUCTVERSION, $SHOW_TOOLTIPS;
   global $_Qo8OO, $resourcestrings, $_Q61I1, $_Q8f1L;
   global $_jjC06, $_jjCtI, $_I0lQJ, $_jji0C, $_QOCJo, $_QCo6j, $_jji0i;

   if(isset($_SESSION["UserId"]))
     $UserId = intval($_SESSION["UserId"]);
     else
     $UserId = -1;
   $OwnerUserId = intval($_SESSION["OwnerUserId"]);
   $OwnerOwnerUserId = intval($_SESSION["AOwnerOwnerUserId"]);
   $_Q8J1j = sprintf("%06x", intval($_SESSION["_j6QI6"]));
   $Username = $_SESSION["Username"];
   $UserType = $_SESSION["UserType"];
   $AccountType = $_SESSION["AccountType"];
   $INTERFACE_STYLE = $_SESSION["Theme"];
   $INTERFACE_THEMESID = intval($_SESSION["ThemesId"]);
   $INTERFACE_LANGUAGE = $_SESSION["Language"];
   $SHOW_LOGGEDINUSER = $_SESSION["SHOW_LOGGEDINUSER"];
   $SHOW_SUPPORTLINKS = $_SESSION["SHOW_SUPPORTLINKS"];
   $SHOW_SHOWCOPYRIGHT = $_SESSION["SHOW_SHOWCOPYRIGHT"];
   $SHOW_PRODUCTVERSION = $_SESSION["SHOW_PRODUCTVERSION"];
   $SHOW_TOOLTIPS = $_SESSION["SHOW_TOOLTIPS"];

   if($INTERFACE_STYLE == "")
      $INTERFACE_STYLE = "default";
   if($INTERFACE_LANGUAGE == "")
     $INTERFACE_LANGUAGE = "de";

   _OP10J($INTERFACE_LANGUAGE);

   _LQLRQ($INTERFACE_LANGUAGE);

   reset($_Qo8OO);
   foreach($_Qo8OO as $key => $_Q6ClO) {
      $_Qo8OO[$key] = $resourcestrings[$INTERFACE_LANGUAGE][$key];
   }

   // paths
   if($OwnerUserId != 0)
     _OP0AF($OwnerUserId);
     else
     _OP0AF($UserId);

   _OBL6Q(_OBLCO($_jjC06), 0777);
   @chmod (_OBLCO($_jjC06), 0777);

   _OBL6Q(_OBLCO($_I0lQJ), 0777);
   @chmod (_OBLCO($_I0lQJ), 0777);

   _OBL6Q(_OBLCO($_jji0C), 0777);
   @chmod (_OBLCO($_jji0C), 0777);

   _OBL6Q(_OBLCO($_QOCJo), 0777);
   @chmod (_OBLCO($_QOCJo), 0777);

   _OBL6Q(_OBLCO($_QCo6j), 0777);
   @chmod (_OBLCO($_QCo6j), 0777);

   _OBL6Q(_OBLCO($_jji0i), 0777);
   @chmod (_OBLCO($_jji0i), 0777);

   // flash not used
   _OBL6Q(_OBLCO($_jjC06."flash/"), 0777);
   @chmod (_OBLCO($_jjC06."flash/"), 0777);

   // Load User tables
   $_ICoOt = $OwnerUserId;
   if($_ICoOt == 0)
     $_ICoOt = $UserId;
   $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `id`=$_ICoOt";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_error($_Q61I1) != ""){
     print "Load User Settings MySQL-ERROR ".mysql_error($_Q61I1)."<br /><br />SQL: $_QJlJ0";
     exit;
   }
   if(!($_ICQQo = mysql_fetch_assoc($_Q60l1))){
     print "Load User Settings MySQL-ERROR "."No result for SQL query"."<br /><br />SQL: $_QJlJ0";
     exit;

   }
   mysql_free_result($_Q60l1);

   $_jJO1j = $_ICQQo["NewsletterTemplatesImported"];

   _OP0D0($_ICQQo);
   //
 }



?>
