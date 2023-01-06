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
           $_fCJQJ = func_get_args();
           foreach ($_fCJQJ as $key){
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
  function is_a($_fCJCQ, $_fC66J) {
     return get_class($_fCJCQ) == strtolower($_fC66J)
       or is_subclass_of($_fCJCQ, $_fC66J);
  }
}

# PHP 5 includes this functions
 if (!function_exists ('stripos') ) {
   function stripos ( $_jOjjt, $_jOjt8, $_jOJ1C=NULL ) {
   if (isset($_jOJ1C) && $_jOJ1C != NULL)
     return strpos( strtolower($_jOjjt), strtolower($_jOjt8), $_jOJ1C);
     else
     return strpos(strtolower($_jOjjt), strtolower($_jOjt8), $_jOjt8);
   }
 }

 if (!function_exists ('str_ireplace') ) {
   function str_ireplace($_jOJtO,$_jOJCl,$_ILi8o){
       $token = chr(1);
       $_jOjjt = strtolower($_ILi8o);
       $_jOjt8 = strtolower($_jOJtO);
       while (($_IOO6C=strpos($_jOjjt,$_jOjt8))!==FALSE){
         $_ILi8o = substr_replace($_ILi8o,$token,$_IOO6C,strlen($_jOJtO));
         $_jOjjt = substr_replace($_jOjjt,$token,$_IOO6C,strlen($_jOJtO));
       }
       $_ILi8o = str_replace($token,$_jOJCl,$_ILi8o);
       return $_ILi8o;
     }
 }

 if (!function_exists('file_get_contents')) {
      function file_get_contents($_JfIIf, $_fIoi1 = false, $_fICI0 = null)
      {
          if (false === $_fICjf = fopen($_JfIIf, 'rb', $_fIoi1)) {
              trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
              return false;
          }

          clearstatcache();
          if ($_fICLL = @filesize($_JfIIf)) {
              $_I0QjQ = fread($_fICjf, $_fICLL);
          } else {
              $_I0QjQ = '';
              while (!feof($_fICjf)) {
                  $_I0QjQ .= fread($_fICjf, 8192);
              }
          }

          fclose($_fICjf);
          return $_I0QjQ;
      }
  }
# PHP 5 includes this functions END

if(!function_exists("mb_str_split")){   // PHP >= 7.4.0
 function mb_str_split($_6I1QQ, $_6jQOQ = 1, $_fC6Oj = "UTF-8") { 
      $_fCfff = preg_split('~~u', $_6I1QQ, -1, PREG_SPLIT_NO_EMPTY);
      if ($_6jQOQ > 1) {
          $_fC86C = array_chunk($_fCfff, $_6jQOQ);
          foreach ($_fC86C as $_Qli6J => $_fCtjf) {
              $_fC86C[$_Qli6J] = join('', (array) $_fCtjf);
          }
          $_fCfff = $_fC86C;
      }
      return $_fCfff;
  }   
}

if(!function_exists("mb_ord") && function_exists("mb_convert_encoding")){ // PHP >= 7.2.0
 function mb_ord($_fCtji, $_fC6Oj = null){  
   if($_fC6Oj == null) $_fC6Oj = "UTF-8";
   $_fCO0Q = mb_convert_encoding($_fCtji, 'UCS-4BE', $_fC6Oj);
   $_fCOiJ = ord(substr($_fCO0Q, 0, 1));
   $_fCoIl = ord(substr($_fCO0Q, 1, 1));
   $_fCol0 = ord(substr($_fCO0Q, 2, 1));
   $_fCCOi = ord(substr($_fCO0Q, 3, 1));
   return ($_fCOiJ << 32) + ($_fCoIl << 16) + ($_fCol0 << 8) + $_fCCOi;
 }
}

if(!function_exists("strftime")){ // PHP >= 9
 function strftime($format, $_fCi6L = null){
   global $INTERFACE_LANGUAGE;

   $format = str_replace('%r', '%I:%M:%S %p', $format);
   $format = str_replace('%R', '%H:%M', $format);
   $format = str_replace('%T', '%H:%M:%S', $format);
   $format = str_replace('%X', '%H:%M:%S', $format);
   $format = str_replace('%D', '%m/%d/%y', $format);
   $format = str_replace('%F', '%Y-%m-%d', $format);
   
   
   //05.02.2009 00:45:10
   if($INTERFACE_LANGUAGE && $INTERFACE_LANGUAGE == "de")
     $_Ift08 = '%d.%m.%Y %H:%M:%S';
     else
     $_Ift08 = '%d-%m-%Y %H:%M:%S';
   $_I016j = '%d.%m.%Y';
   
   
   $_fCL6Q = array('%a' => 'D', '%A' => 'l', '%d' => 'd', '%e' => 'j', '%j' => 'z', '%u' => 'N', '%w' => 'w', '%U' => 'W', '%V' => 'W', '%W' => 'W', '%b' => 'M',
                         '%B' => 'F', '%h' => 'M', '%m' => 'm', '%C' => 'Y', '%g' => 'y', '%G' => 'Y', '%y' => 'y', '%Y' => 'Y', '%H' => 'H', '%k' => 'H', 
                         '%I' => 'h', '%l' => 'h', '%M' => 'i', '%p' => 'A', '%P' => 'a', '%S' => 's', '%z' => 'O', '%Z' => 'e', '%c' => $_Ift08, '%s' => 'U',
                         '%x' => $_I016j, '%n' => "\n", '%t' => "\t" , '%%' => '%'
                          );
   foreach($_fCL6Q as $key => $_QltJO)                     
     $format = str_replace($key, $_QltJO, $format);
   
   return date($format, $_fCi6L);
   
 }
}
