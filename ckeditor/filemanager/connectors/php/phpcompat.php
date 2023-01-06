<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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

if(!defined("PHP_VERSION"))
  define("PHP_VERSION", phpversion());

if( isset($_SERVER['REQUEST_METHOD']))
  $_SERVER['REQUEST_METHOD'] = strtoupper($_SERVER['REQUEST_METHOD']);

// Fix for removed Session functions
// http://php.net/manual/de/function.session-register.php
if ( !function_exists('session_register') && !function_exists ('fix_session_register') ) {
  function fix_session_register(){
       function session_register(){
           $args = func_get_args();
           foreach ($args as $key){
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
  function is_a($anObject, $aClass) {
     return get_class($anObject) == strtolower($aClass)
       or is_subclass_of($anObject, $aClass);
  }
}

# PHP 5 includes this functions
 if (!function_exists ('stripos') ) {
   function stripos ( $haystack, $needle, $offset=NULL ) {
   if (isset($offset) && $offset != NULL)
     return strpos( strtolower($haystack), strtolower($needle), $offset);
     else
     return strpos(strtolower($haystack), strtolower($needle), $needle);
   }
 }

 if (!function_exists ('str_ireplace') ) {
   function str_ireplace($search,$replace,$subject){
       $token = chr(1);
       $haystack = strtolower($subject);
       $needle = strtolower($search);
       while (($pos=strpos($haystack,$needle))!==FALSE){
         $subject = substr_replace($subject,$token,$pos,strlen($search));
         $haystack = substr_replace($haystack,$token,$pos,strlen($search));
       }
       $subject = str_replace($token,$replace,$subject);
       return $subject;
     }
 }

 if (!function_exists('file_get_contents')) {
      function file_get_contents($filename, $incpath = false, $resource_context = null)
      {
          if (false === $fh = fopen($filename, 'rb', $incpath)) {
              trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
              return false;
          }

          clearstatcache();
          if ($fsize = @filesize($filename)) {
              $data = fread($fh, $fsize);
          } else {
              $data = '';
              while (!feof($fh)) {
                  $data .= fread($fh, 8192);
              }
          }

          fclose($fh);
          return $data;
      }
  }
# PHP 5 includes this functions END


