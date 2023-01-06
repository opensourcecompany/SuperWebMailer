<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
 //error_reporting( E_ALL & ~ ( /*E_NOTICE | E_WARNING  |*/ E_DEPRECATED | E_STRICT ) );
 ini_set("display_errors", 0);

 define("SWM", 1);
 #define("SML", 1);
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
 include_once(InstallPath."emoji_helper.inc.php");
 
 define("LOCAL_PATH", dirname(__FILE__) . DIRECTORY_SEPARATOR);

 if(!function_exists("mysql_connect")) {
   // Include the definitions
   require_once(LOCAL_PATH . 'mysqli' . DIRECTORY_SEPARATOR . 'MySQL_Definitions.php');
   // Include the object
   require_once(LOCAL_PATH . 'mysqli' . DIRECTORY_SEPARATOR . 'MySQL.php');
   // Include the mysql_* functions
   require_once(LOCAL_PATH . 'mysqli' . DIRECTORY_SEPARATOR . 'MySQL_Functions.php');
 }

 # Csrf
 define("CKEDITOR_TOKEN_COOKIE_NAME", 'ckCsrfToken');
 define("SMLSWM_TOKEN_COOKIE_NAME", 'smlswmCsrfToken');
 define("SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME", 'smlswmFMCsrfToken');

 define("PEAR_PATH", LOCAL_PATH . "PEAR" . DIRECTORY_SEPARATOR);
 
 # default paths
 $_J18oI = InstallPath."userfiles/";
 $_jfOJj = ScriptBaseURL."userfiles/";
 @mkdir($_J18oI, 0777);
 $_ItL8f = $_J18oI."import/";
 $_J1t6J = $_J18oI."export/";
 $_IIlfi = $_J18oI."file/";
 $_IJi8f = $_J18oI."image/";
 $_J1tfC = $_J18oI."media/";
 $_QlLlJ = $_J18oI."templates/";

 # default DateFormat
 $ShortDateFormat = 'd.m.Y';
 $LongDateFormat = 'd.m.Y H:i:s';

 # default newsletter subscribtion/unsubscribtion Script
 $_J1tCf = "defaultnewsletter.php";
 $_J1OIO = ScriptBaseURL.$_J1tCf;
 $_J1OLl = "nl.php";
 $_J1oCI = "nlu.php";
 $_J1Cf8 = ScriptBaseURL.$_J1OLl;
 $_J1Clo = ScriptBaseURL.$_J1oCI;

 # default alt browser link
 $_J1i1C = "browser.php";
 $_jfilQ = ScriptBaseURL.$_J1i1C;

 # Tracking scripts
 $_J1L0I = "ostat.php";
 $_J1Lt8 = ScriptBaseURL.$_J1L0I;
 $_J1l0i = "link.php";
 $_J1l1J = ScriptBaseURL.$_J1l0i;

 # Default Tablenames
 $_J1lLC = TablePrefix."failed_logins";
 $_Ij8oL = TablePrefix."fieldnames";
 $_JQ00O = TablePrefix."auth_options";
 $_JQ0if = TablePrefix."2fa_blacklist";
 $_I1O0i = TablePrefix."options";
 $_JQ1I6 = TablePrefix."emailoptions";
 $_JQQI1 = TablePrefix."cronoptions";
 $_JQQoC = TablePrefix."cronlog";
 $_QL88I = TablePrefix."maillists";
 $_QlQot = TablePrefix."maillists_users";
 $_I1tQf = TablePrefix."themes";
 $_Ijf8l = TablePrefix."languages";
 $_I18lo = TablePrefix."users";
 $_IfOtC = TablePrefix."users_owner";
 $_jCJ6O = TablePrefix."users_login";
 $_IQQot = TablePrefix."outqueue";
 $_jJtt8 = TablePrefix."localusermessages";

 $_IjljI = "";
 $_Ijt0i = "";
 $_jfQtI = "";
 $_Ifi1J = "";
 $_I8tfQ = "";
 $_I8OoJ = "";
 $_jQ68I = "";
 $_QlfCL = "";
 $_Ql10t = "";
 $_Ql18I = "";
 $_jQf81 = "";

 $_ItfiJ = "";

 $_j6JfL = "";
 $_QLi60 = "";

 $_ICo0J = "";
 $_ICl0j = "";
 $_IoCo0 = "";
 $_ICIJo = "";
 $_I616t = "";

 $_j6Ql8 = "";
 $_j68Q0 = "";

 $_jJLQo = "";
 $_j68Co = "";

 $_JQQiJ = "";

 $_jJL88 = "";

 $_jJLLf = "";

 $_IjC0Q = "";
 $_IjCfJ = "";

 // global Campaigns tables
 $_jC80J = "";
 $_jC8Li = "";
 $_jCtCI = "";
 $_jCOO1 = "";
 $_jCOlI = "";
 $_jCo0Q = "";
 $_jCooQ = "";
 $_jCC16 = "";
 $_jCC1i = "";
 $_jCi01 = "";
 $_jCi8J = "";
 $_jCiL1 = "";
 $_jCLC8 = "";
 $_jClC0 = "";
 // global Campaigns tables /

# ControlTabs, we must remove it because firefox has problems
 $_JQI6L = array('<SHOWHIDE:ProductLogo>', '<ENABLE:SYSTEMMENU>', '<ISUSER:SUPERADMIN>', '<ISUSER:USER>', '<ISUSER:ADMINISTRATOR>', '<SHOWHIDE:LOGGEDINUSER>', '<ENABLE:MAINMENU>', '<SHOWHIDE:WARNHEADER>', '<SHOWHIDE:PAGETOPIC>', '<SHOWHIDE:HELPTOPIC>', '<SHOWHIDE:ERRORTOPIC>', '<CONTAINER:CONTENT>', '<SHOW:SUPPORTLINKS>', '<SHOW:SHOWCOPYRIGHT>', '<SHOW:PRODUCTVERSION>', '<SHOWHIDE:TOOLTIPS>', '<IS:SWM>', '<IS:SML>', '<SHOWHIDE:EVALUATIONHEADER>');
 $_QLl1Q = "\r\n";
 $_QLo06 = "utf-8";
 $_JQjQ6 = "target_groups";
 $_JQj6J = '<span style="mso-hide:all;display:none !important;font-size:0;max-height:0;line-height:0;visibility:hidden;overflow:hidden;opacity:0;color:transparent;height:0;width:0;">%s</span>';
 $_IC18i = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="format-detection" content="telephone=no"><title></title><meta http-equiv="X-UA-Compatible" content="IE=edge"></head><body>&nbsp;</body></html>';

 if(defined("SWM"))
   $_Il06C = "X-SWM-BOUNCE";
   else
   $_Il06C = "X-SML-BOUNCE";
 $_JQjt6 = -9999999; // MTA = SMSout

 # > 9000 check
 define("MonthlySendQuotaExceeded", 9990);
 define("SendQuotaExceeded", 9991);
 define("RecipientIsInECGList", 9992);

# Defaults for Login
 if(defined("SWM"))
   $AppName = "SuperWebMailer";
   else
   $AppName = "SuperMailingList";
 $_JQjlt = $AppName." ".$_Ij6Lj;
 $UserId = 0;
 $OwnerUserId = 0;
 $OwnerOwnerUserId = 0xA;
 $_I16ll = rand();
 $Username = "";
 $UserType = "none";
 $AccountType = "none";
 $INTERFACE_STYLE = "default";
 $INTERFACE_LANGUAGE = "de";
 $INTERFACE_THEMESID = 1;
 $_JQJjJ = false;
 $_JQJll = 0x90;
 $_JQ6CI = 0x99;
 $SHOW_LOGGEDINUSER = true;
 $SHOW_SUPPORTLINKS = true;
 $SHOW_SHOWCOPYRIGHT = true;
 $SHOW_PRODUCTVERSION = true;
 $SHOW_TOOLTIPS = true;
 if(!defined("PHP_VERSION"))
   define("PHP_VERSION", phpversion());

 define("rtBirthdayResponders", 1);
 define("rtEventResponders", 2);
 define("rtFUResponders", 3);
 define("rtCampaigns", 4);
 define("rtRSS2EMailResponders", 5);
 define("rtDistributionLists", 6);
 define("rtAutoResponders", 999);

 define("No_rtMailingLists", 10000);
 define("No_rtMailingListForms", 10001);

 define("FUResponderTypeTimeBased", 0);
 define("FUResponderTypeActionBased", 1);
 
 define("EMailSubjectVariantsSeparator", '|~&|');
 
########### MySQL Connection
 if(!defined("DefaultMySQLEncoding") && !defined("Install"))
    define("DefaultMySQLEncoding", 'utf8');
 $_QLttI = 0;
 if(MySQLServername != "") { # not on installation
   $_QLttI = mysql_connect (MySQLServername, MySQLUsername, MySQLPassword);

   if ($_QLttI == 0) {
      print ($resourcestrings[$INTERFACE_LANGUAGE]["000001"]."<br/>".mysql_error());
      exit;
   }

   // UTF-8 connection
   @mysql_query("SET NAMES '" . DefaultMySQLEncoding . "'", $_QLttI);
   @mysql_query("SET CHARACTER SET '" . DefaultMySQLEncoding . "'", $_QLttI);
   // not STRICT mode
   @mysql_query('SET SQL_MODE=""', $_QLttI);

   if (!mysql_select_db (MySQLDBName, $_QLttI)) {
     print ($resourcestrings[$INTERFACE_LANGUAGE]["000002"]." ".MySQLDBName."<br/>".mysql_error($_QLttI));
     mysql_close ($_QLttI);
     exit;
   }

   // UTF-8 connection
   @mysql_query("SET NAMES '" . DefaultMySQLEncoding . "'", $_QLttI);
   @mysql_query("SET CHARACTER SET '" . DefaultMySQLEncoding . "'", $_QLttI);
   // not STRICT mode
   @mysql_query('SET SQL_MODE=""', $_QLttI);

   // set to DefaultMySQLEncoding when possible
   @mysql_query("ALTER DATABASE ".MySQLDBName." CHARACTER SET " . DefaultMySQLEncoding . ";", $_QLttI);
   ClearLastError();
 }

##################################
# unquoting strings
 if ( (version_compare(PHP_VERSION, "5.4") < 0) && get_magic_quotes_gpc() ) {
    $_POST = stripslashes_deep($_POST);
    $_GET = stripslashes_deep($_GET);
    $_COOKIE = stripslashes_deep($_COOKIE);
 }
#################################

################################# change emojis on Startup
 if( !defined("Install") && !defined("Setup") ){
    $_JQttC = new emoji_helper_Class();
    $_JQttC = null;
 }
#################################

#################################
// special placeholders

$_Iol8t = array ('DateShort' => '[Date_short]',
                              'DateLong' => '[Date_long]',
                              'Time' => '[Time]',
                              'RecipientId' => '[RecipientId]',
                              'MailingListId' => '[MailingListId]',
                              'SubscriptionStatus' => '[SubscriptionStatus]',
                              'DateOfSubscription' => '[DateOfSubscription]',
                              'DateOfOptInConfirmation' => '[DateOfOptInConfirmation]',
                              'IPOnSubscription' => '[IPOnSubscription]',
                              'EMail_LocalPart' => '[EMail_LocalPart]',
                              'EMail_DomainPart' => '[EMail_DomainPart]'
                            );

$_IolCJ = array(
                                 'UnsubscribeLink' => '[UnsubscribeLink]',
                                 'EditLink' => '[EditLink]'
                                );

// on subscribe
$_JQtOo = array(
                                 'SubscribeRejectLink' => '[SubscribeRejectLink]',
                                 'SubscribeConfirmationLink' => '[SubscribeConfirmationLink]'
                                );

// on unsubscribe
$_JQOIC = array(
                                 'UnsubscribeRejectLink' => '[UnsubscribeRejectLink]',
                                 'UnsubscribeConfirmationLink' => '[UnsubscribeConfirmationLink]'
                                  );

// on edit
$_JQOtf = array(
                                 'EditRejectLink' => '[EditRejectLink]',
                                 'EditConfirmationLink' => '[EditConfirmationLink]'
                                  );

// on unsubscribe survey
$_JQofl = array(
                                           'ReasonsForUnsubscriptionSurvey' => '[ReasonsForUnsubscriptionSurvey]'
                                     );

// Alt browser link
$_ICiQ1 = array (
                                    'AltBrowserLink' => '[AltBrowserLink]'
                                   );

// Autoresponder
$_IC0fL = array(
                                 'OrgMailSubject' => '[OrgMailSubject]'
                                );

// Birthday responder
$_ICitL = array(
                                 'MembersAge' => '[MembersAge]',
                                 'Days_to_Birthday' => '[Days_to_Birthday]'
                                );

// FollowUp responder
$_JQoLt = array(
                                 );

// Campaigns
$_jlJ1o = array(
                              );

// RSS2EMail responder
$_JQol8 = array(
                                 );

// DistribListModifySubject
$_JQC0J = array(
                                 'OrgMailSubject' => '[OrgMailSubject]',
                                 'DistribListsName' => '[DistribListsName]',
                                 'DistribListsDescription' => '[DistribListsDescription]',
                                 'MailingListName' => '[MailingListName]',
                                 'DistribSenderEMailAddress' => '[DistribSenderEMailAddress]',
                                 'INBOXEMailAddress' => '[INBOXEMailAddress]'
                                );

// DistribListConfirmInfo, [CONFIRMATIONLINK] must the last one, don't put to $_jft6l
$_JQCjf = array(
                                 'DistribListsName' => '[DISTRIBLISTNAME]',
                                 'DistribListsSubject' => '[SUBJECT]',
                                 'DistribSenderEMailAddress' => '[FROMADDRESS]',
                                 'DistribListsConfirmationLink' => '[CONFIRMATIONLINK]'
                                );

// all placeholder names
$_jft6l = array_merge($_Iol8t, $_IolCJ, $_JQtOo, $_JQOIC, $_JQofl, $_ICiQ1, $_IC0fL, $_ICitL, $_JQoLt, $_jlJ1o, $_JQol8, $_JQC0J);
//$AllDefaultPlaceholdersNames = array("DefaultPlaceholders", "DefaultUnsubscribePlaceholders", "OnSubscribePlaceholders", "OnUnsubscribePlaceholders", "AltBrowserLinkPlaceholder", "AutoresponderPlaceholders", "BirthdayresponderPlaceholders", "FollowUpresponderPlaceholders", "CampaignsPlaceholders", "RSS2EMailresponderPlaceholders");

// invisible for user!
$_Ij08l = array(
                               'AltBrowserLink_SME' => '[AltBrowserLink_SME]',
                               'AltBrowserLink_SME_URLEncoded' => '[AltBrowserLink_SME_URLEncoded]',
                               'Mail_Subject_ISO88591' => '[Mail_Subject_ISO88591]',
                               'Mail_Subject_UTF8' => '[Mail_Subject_UTF8]',
                               'Mail_Subject_ISO88591_URLEncoded' => '[Mail_Subject_ISO88591_URLEncoded]',
                               'Mail_Subject_UTF8_URLEncoded' => '[Mail_Subject_UTF8_URLEncoded]'
                           );

#################################


################################# AltBrowserLink InfoBar

class TAltBrowserLinkInfoBarLinkType{
  var $abtSubscribe = 0;
  var $abtUnsubscribe = 1;
  var $abtFacebook = 2;
  var $abtTwitter = 3;
  var $abtArchieve = 4;
  var $abtRSS = 5;
  var $abtTranslate = 6;
  // Newsletter archive specific
  var $abtHome = 127;
  var $abtYears = 128;
  var $abtEntries = 129;
  var $abtAttachments = 130;
  
  function IsLinkType($_QltJO){
    return ($_QltJO >= $this->abtSubscribe && $_QltJO <= $this->abtTranslate) ||
           ($_QltJO >= $this->abtHome && $_QltJO <= $this->abtAttachments)
           ;
  }
}

class TAltBrowserLinkInfoBarLink{ 
   var $Checked = false;
   var $LinkType = 0; // => TAltBrowserLinkInfoBarLinkType
   var $internalCaption;
   var $URL = "";
   var $Title = "";
   var $Text = "";
}

# allowed import file upload extensions
$_ItLJj = array(".txt", ".csv");

#################################
$_Ijt8j = array(
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

# for PHP < 5.0 only
if(!$_JIQCl){
  _JQRLR($INTERFACE_LANGUAGE);
}

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
    $_I1OoI = explode(PATH_SEPARATOR, ini_get('include_path'));
    $_QLCt1 = false;
    for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++)
      if(strpos($_I1OoI[$_Qli6J], "./") !== false || strpos($_I1OoI[$_Qli6J], ".\\") !== false ) {
        $_QLCt1 = true;
        break;
      }
    if(!$_QLCt1)
      ini_set('include_path', './'.PATH_SEPARATOR.ini_get('include_path'));
  }

#################################

 function _LOC8P() {
   global $INTERFACE_STYLE, $INTERFACE_LANGUAGE;

   if(!defined("TemplatesPath"))
     define('TemplatesPath', 'templates');
   $_j8otC = TemplatesPath;
   if($_j8otC == ""){
     $_j8otC = 'templates';
   }

   $_QLJfI = DefaultPath.$_j8otC."/";

   if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
     $_QLJfI .= $INTERFACE_STYLE."/";
   if(isset($INTERFACE_LANGUAGE) && $INTERFACE_LANGUAGE != "")
     $_QLJfI .= $INTERFACE_LANGUAGE."/";
   return $_QLJfI;
 }

 function _LOCFC() {
   return InstallPath."sql/";
 }

 function LoadUserSettings() { // called from sessioncheck.inc.php, login.php and usersedit.php
   global $_SESSION;
   global $UserId, $OwnerUserId, $OwnerOwnerUserId, $_I16ll, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $_JQJjJ;
   global $SHOW_LOGGEDINUSER, $SHOW_SUPPORTLINKS, $SHOW_SHOWCOPYRIGHT, $SHOW_PRODUCTVERSION, $SHOW_TOOLTIPS;
   global $_Ijt8j, $resourcestrings, $_QLttI, $_I18lo;
   global $_J18oI, $_jfOJj, $_ItL8f, $_J1t6J, $_IIlfi, $_IJi8f, $_J1tfC, $_QlLlJ;

   if(isset($_SESSION["UserId"]))
     $UserId = intval($_SESSION["UserId"]);
     else
     $UserId = -1;
   $OwnerUserId = intval($_SESSION["OwnerUserId"]);
   $OwnerOwnerUserId = intval($_SESSION["AOwnerOwnerUserId"]);
   $_I16ll = sprintf("%06x", intval($_SESSION["AOwnerOwnerUserUniqueId"]));
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

   _LRPQ6($INTERFACE_LANGUAGE);

   _JQRLR($INTERFACE_LANGUAGE);

   reset($_Ijt8j);
   foreach($_Ijt8j as $key => $_QltJO) {
      $_Ijt8j[$key] = $resourcestrings[$INTERFACE_LANGUAGE][$key];
   }

   // paths
   if($OwnerUserId != 0)
     _LRRFJ($OwnerUserId);
     else
     _LRRFJ($UserId);

   _LPABA(_LPBCC($_J18oI), 0777);
   @chmod (_LPBCC($_J18oI), 0777);

   _LPABA(_LPBCC($_ItL8f), 0777);
   @chmod (_LPBCC($_ItL8f), 0777);

   _LPABA(_LPBCC($_J1t6J), 0777);
   @chmod (_LPBCC($_J1t6J), 0777);

   _LPABA(_LPBCC($_IIlfi), 0777);
   @chmod (_LPBCC($_IIlfi), 0777);

   _LPABA(_LPBCC($_IJi8f), 0777);
   @chmod (_LPBCC($_IJi8f), 0777);

   _LPABA(_LPBCC($_J1tfC), 0777);
   @chmod (_LPBCC($_J1tfC), 0777);

   _LPABA(_LPBCC($_QlLlJ), 0777);
   @chmod (_LPBCC($_QlLlJ), 0777);

   // flash not used
   _LPABA(_LPBCC($_J18oI."flash/"), 0777);
   @chmod (_LPBCC($_J18oI."flash/"), 0777);

   // Load User tables
   $_j6lIj = $OwnerUserId;
   if($_j6lIj == 0)
     $_j6lIj = $UserId;
   $_QLfol = "SELECT * FROM `$_I18lo` WHERE `id`=$_j6lIj";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) != ""){
     print "Load User Settings MySQL-ERROR ".mysql_error($_QLttI)."<br /><br />SQL: $_QLfol";
     exit;
   }
   if(!($_j661I = mysql_fetch_assoc($_QL8i1))){
     print "Load User Settings MySQL-ERROR "."No result for SQL query"."<br /><br />SQL: $_QLfol";
     exit;

   }
   mysql_free_result($_QL8i1);

   if(defined("SWM"))
     $_JQJjJ = $_j661I["NewsletterTemplatesImported"];

   _LR8AP($_j661I);
   //
 }



?>
