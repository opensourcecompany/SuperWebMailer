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

if ( !isset( $_SERVER ) ) {
    $_SERVER = $HTTP_SERVER_VARS;
}


if ( !isset( $_ENV ) ) {
    $_ENV = $HTTP_ENV_VARS;
}


if ( !isset( $_GET ) ) {
    $_GET = $HTTP_GET_VARS;
}

if ( !isset( $_POST ) ) {
    $_POST = $HTTP_POST_VARS;
}

if ( !isset( $_FILES ) ) {
    $_FILES = $HTTP_POST_FILES;
}

if ( !isset( $_SESSION ) && isset($HTTP_SESSION_VARS) ) {
    $_SESSION = $HTTP_SESSION_VARS;
}

if ( !defined( 'DIRECTORY_SEPARATOR' ) ) {
    define( 'DIRECTORY_SEPARATOR',
        strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? '\\' : '/'
    ) ;
}

// Fix for removed Session functions
// http://php.net/manual/de/function.session-register.php
if (!function_exists ('fix_session_register') ) {
  function fix_session_register(){
       function session_register(){
           $_6JJC6 = func_get_args();
           foreach ($_6JJC6 as $key){
               $_SESSION[$key]=$GLOBALS[$key];
           }
       }
       function session_is_registered($key){
           return isset($_SESSION[$key]);
       }
       function session_unregister($key){
           unset($_SESSION[$key]);
       }
  }
}
if (!function_exists('session_register')) fix_session_register();

if(!function_exists("is_a")) {
  function is_a($_6JJlJ, $_6J6oO) {
     return get_class($_6JJlJ) == strtolower($_6J6oO)
       or is_subclass_of($_6JJlJ, $_6J6oO);
  }
}

