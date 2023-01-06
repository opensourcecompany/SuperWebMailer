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

  if(!defined("ISFROMCKFILEMANAGER")) {
    @include_once("config.inc.php");
    @include_once("ressources.inc.php");
    @require_once("templates.inc.php");
  }

  if(isset($_SESSION["DEBUG"])){
    error_reporting( E_ALL & ~ ( E_NOTICE /*| E_WARNING*/  | E_DEPRECATED | E_STRICT ) );
    ini_set("display_errors", 1);
  }

  if( function_exists("IsHTTPS") && IsHTTPS() )
    @ini_set('session.cookie_secure', 'On');

  @session_cache_limiter('public');
  @session_set_cookie_params(0, "/", "");
  @ini_set("session.cookie_path", "/");

  # check session OK, ignore errors if session.auto_start = 1
  if(!ini_get("session.auto_start") && !defined("LoginDone") ) {
    @session_start();
  }

  @include_once("php_register_globals_off.inc.php");

  if(!function_exists("SetHTTPResponseCode")) { // can be declared in functions.inc.php
   function SetHTTPResponseCode($_6QiQi, $_jfO0t){
     if($_6QiQi > 0){
         $_6QioQ    = substr(php_sapi_name(), 0, 3);
         if ($_6QioQ == 'cgi' || $_6QioQ == 'fpm') {
            @header('Status: '.$_6QiQi.' '.$_jfO0t);
         } else {
            $_6QiLO = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            @header($_6QiLO.' '.$_6QiQi.' '.$_jfO0t);
         }
     }
   }
  }

  if (

     ( !isset($_SESSION["UserId"]) ) Or
     ( !isset($_SESSION["OwnerUserId"]) ) Or
     ( !isset($_SESSION["Username"]) ) Or
     ( !isset($_SESSION["UserType"]) ) Or
     ( !isset($_SESSION["AccountType"]) ) Or
     ( !isset($_SESSION["Language"]) ) Or
     ( !isset($_SESSION["Theme"]) ) Or
     ( !isset($_SESSION["SHOW_LOGGEDINUSER"]) ) Or
     ( !isset($_SESSION["SHOW_SUPPORTLINKS"]) ) Or
     ( !isset($_SESSION["SHOW_SHOWCOPYRIGHT"]) ) Or
     ( !isset($_SESSION["SHOW_PRODUCTVERSION"]) ) Or
     ( !isset($_SESSION["SHOW_TOOLTIPS"]) )
     ) {
      if(defined("JAVASCRIPT_LOCALIZATION")){
        SetHTMLHeaders($_QLo06, true, "text/javascript");
        die;
      }  
      SetHTTPResponseCode(405, "Session expired");
      if(function_exists("GetMainTemplate") && !defined("ISFROMCKFILEMANAGER")){
           _JQRLR($INTERFACE_LANGUAGE);
           print GetMainTemplate(False, False, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000003"], $resourcestrings[$INTERFACE_LANGUAGE]["000003"], 'DISABLED', 'session_error_snipped.htm');
         }
         else
         print "Session expired";
      if(!defined("ISFROMCKFILEMANAGER"))
        die;
  } else {
     if(!defined("ISFROMCKFILEMANAGER") && function_exists("LoadUserSettings")) {
        LoadUserSettings();
     }
  }

  if(!defined("ISFROMCKFILEMANAGER")  && !defined("CRONS_PHP") && !defined("API") && !defined("JAVASCRIPT_LOCALIZATION") && !DoubleSubmitCookieTokenValidator()) {
      SetHTTPResponseCode(405, "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed");
      if(function_exists("GetMainTemplate") && !defined("ISFROMCKFILEMANAGER")){
           _JQRLR($INTERFACE_LANGUAGE);
           $_QLJfI = GetMainTemplate(False, False, '', False, "Cross-Site Request Forgery (CSRF)", "DoubleSubmitCookieTokenValidator() failed", 'DISABLED', 'common_error_page.htm');
           $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed");
           print $_QLJfI;
         }
         else{
           print "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed";
         }
      die;
  }

  if(isset($_GET["IsFCKEditor"]) && $_GET["IsFCKEditor"] === true && !defined("JAVASCRIPT_LOCALIZATION") && !_LJC0F()){
    $_QLJfI = GetMainTemplate(False, False, '', False, "Cross-Site Request Forgery (CSRF)", "DoubleSubmitCookieTokenValidator() failed", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed");
    SetHTTPResponseCode(405, "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed");
    print $_QLJfI;
    die;
  }

  if(!defined("ISFROMCKFILEMANAGER") && !defined("CRONS_PHP") && !defined("API") && !defined("JAVASCRIPT_LOCALIZATION")) {
    $_6CCQ1 = new _JO0ED();
    $_6CCQ1 = null;
  }

?>
