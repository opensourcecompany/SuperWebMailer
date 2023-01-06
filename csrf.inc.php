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


 function _LJPOA(){
   if(!isset($_SESSION) || !isset($_SESSION["UserId"]) || defined("UserNewsletterPHP") || defined("DefaultNewsletterPHP") || defined("CRONS_PHP") || defined("API")) return "";
   $_Jofff = $_SESSION["UserId"] . $_SESSION["OwnerUserId"];
   $_Jo8Q0 = 'abcdefghijklmnopqrstuvwxyz0123456789';
   $_Jo8iI = 36;
   if( isset($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) )
      $token = $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME];
   if(!isset($token) || strlen($token) < $_Jo8iI || substr($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME], 0, strlen($_Jofff)) !== $_Jofff){
     mt_srand(time());
     $_JotjI = array();
     for($_Qli6J=0; $_Qli6J<$_Jo8iI; $_Qli6J++){
       $_JotjI[] = mt_rand() * 256;
     }

     $token = "";
     for($_Qli6J=0; $_Qli6J<count($_JotjI); $_Qli6J++){
       $_JottI = $_Jo8Q0[ abs($_JotjI[$_Qli6J] % strlen($_Jo8Q0)) ];
       $token .= (mt_rand(1, 100) / 100 > 0.5) ? strtoupper($_JottI) : $_JottI;
     }

     $token = $_Jofff . $token;

     setcookie(SMLSWM_TOKEN_COOKIE_NAME, $token);
     $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME] = $token;
   }

   return $token;
 }

 function DoubleSubmitCookieTokenValidator(){
    if(defined("NoCSRFProtection") && NoCSRFProtection == 1) return true;
    $_Jo8iI = 32;
    $_Jofff = $_SESSION["UserId"] . $_SESSION["OwnerUserId"];
    if(!isset($_SESSION) || defined("UserNewsletterPHP") || defined("DefaultNewsletterPHP")  || defined("CRONS_PHP") || defined("API")) return true;
    if($_SERVER['REQUEST_METHOD'] == 'GET' && (!isset($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) || strlen($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) < $_Jo8iI || substr($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME], 0, strlen($_Jofff)) !== $_Jofff) ) return false;

    if($_SERVER['REQUEST_METHOD'] == 'GET'){

      if(isset($_GET["IsFCKEditor"]) && $_GET["IsFCKEditor"] === true && !_LJC0F()){
        return false;
      }

      if((!isset($_GET["IsFCKEditor"]) || $_GET["IsFCKEditor"] === false) && !(_LJBJF() || _LJBLD()) ){
        return false;
      }
    }
    if($_SERVER['REQUEST_METHOD'] !== 'POST') return true;

    $_JotLj = isset($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) ? $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME] : "";
    $_JoO1l = isset($_POST[SMLSWM_TOKEN_COOKIE_NAME]) ? $_POST[SMLSWM_TOKEN_COOKIE_NAME] : "";

    if (strlen($_JoO1l) >= $_Jo8iI && strlen($_JotLj) >= $_Jo8iI) {
        return ($_JoO1l === $_JotLj) && (substr($_JoO1l, 0, strlen($_Jofff)) == $_Jofff);
    }

    return false;
 }

 function _LJA6C($_QLoli, $_JoOJL=false){
   $token = _LJPOA();
   if($token == "" || strpos($_QLoli, 'name="'.SMLSWM_TOKEN_COOKIE_NAME.'"') !== false) return $_QLoli;
   $_QLJfI = '<input type="hidden" name="' . SMLSWM_TOKEN_COOKIE_NAME . '" value="' . $token. '" />';

   if(!$_JoOJL)
     $_QLoli = preg_replace('/(<form(.*?)>)/is', '$0'.$_QLJfI, $_QLoli, -1, $_j1881);
     else
     $_QLoli = preg_replace('/(<form(.*?)>)/i', '$0'.$_QLJfI, $_QLoli, -1, $_j1881);
   //$_QLoli = str_ireplace('</form>', $_QLJfI.'</form>', $_QLoli, $_j1881);
   if(!$_j1881)
     //$_QLoli = str_ireplace('</body>', $_QLJfI.'</body>', $_QLoli);
     $_QLoli = preg_replace('/(<body(.*?)>)/is', '$0'.$_QLJfI, $_QLoli);

   return $_QLoli;
 }

 function _LJBLD(){
   if(defined("NoCSRFProtection") && NoCSRFProtection == 1) return true;
   if($_SERVER['REQUEST_METHOD'] != 'GET') return true;
   $_JoOiJ = "";
   if (function_exists('getallheaders')){
     $_I6C0o = getallheaders();
     if(isset($_I6C0o["X-".SMLSWM_TOKEN_COOKIE_NAME]))
       $_JoOiJ = $_I6C0o["X-".SMLSWM_TOKEN_COOKIE_NAME];
       else
        foreach ($_I6C0o as $_I6C8f => $_QltJO) {
          if( strtoupper($_I6C8f) == strtoupper("X-".SMLSWM_TOKEN_COOKIE_NAME)){
            $_JoOiJ = $_QltJO;
            break;
          }
        }
   }else{
     if( isset($_SERVER[strtoupper('HTTP_'."X_".SMLSWM_TOKEN_COOKIE_NAME)]) )
       $_JoOiJ = $_SERVER[strtoupper('HTTP_'."X_".SMLSWM_TOKEN_COOKIE_NAME)];
       else
        if( isset($_SERVER['HTTP_'."X_".SMLSWM_TOKEN_COOKIE_NAME]) )
          $_JoOiJ = $_SERVER['HTTP_'."X_".SMLSWM_TOKEN_COOKIE_NAME];
   }

   return $_JoOiJ == _LJPOA();
 }

 function _LJBJF(){
   if(defined("NoCSRFProtection") && NoCSRFProtection == 1) return true;
   if($_SERVER['REQUEST_METHOD'] != 'GET') return true;
   if(!empty($_GET[SMLSWM_TOKEN_COOKIE_NAME]))
      $token = $_GET[SMLSWM_TOKEN_COOKIE_NAME];
  if(!isset($token) || $token != $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME])
    return false;
  return true;
 }

 function _LJC0F(){
  if(defined("NoCSRFProtection") && NoCSRFProtection == 1) return true;
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!empty($_POST[CKEDITOR_TOKEN_COOKIE_NAME]))
      $token = $_POST[CKEDITOR_TOKEN_COOKIE_NAME];
  }
  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(!empty($_GET[CKEDITOR_TOKEN_COOKIE_NAME]))
      $token = $_GET[CKEDITOR_TOKEN_COOKIE_NAME];
  }
  if(!isset($token) || $token != $_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME])
    return false;
  return true;
 }

?>
