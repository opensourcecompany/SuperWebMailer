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

  if(!defined("ISFROMCKFILEMANAGER")) {
    @include_once("config.inc.php");
    @include_once("ressources.inc.php");
    @require_once("templates.inc.php");
  }

  @session_cache_limiter('public');
  @session_set_cookie_params(600, "/", "");
  @ini_set("session.cookie_path", "/");

  # check session OK, ignore errors if session.auto_start = 1
  if(!ini_get("session.auto_start") && !defined("LoginDone") ) {
    @session_start();
  }

  @include_once("php_register_globals_off.inc.php");

  if(isset($_SESSION["DEBUG"])){
    error_reporting( E_ALL & ~ ( E_NOTICE /*| E_WARNING*/  | E_DEPRECATED | E_STRICT ) );
    ini_set("display_errors", 1);
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
      if(function_exists("GetMainTemplate") && !defined("ISFROMCKFILEMANAGER")){
           _LQLRQ($INTERFACE_LANGUAGE);
           print GetMainTemplate(False, False, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000003"], $resourcestrings[$INTERFACE_LANGUAGE]["000003"], 'DISABLED', 'session_error_snipped.htm');
         }
         else
         print "Session expired";
      if(!defined("ISFROMCKFILEMANAGER"))
        exit;
  } else {
     if(!defined("ISFROMCKFILEMANAGER") && function_exists("LoadUserSettings")) {
        LoadUserSettings();
     }
  }

?>
