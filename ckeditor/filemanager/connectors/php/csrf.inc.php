<?php
/**
 *      Filemanager extension
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
define("CKEDITOR_TOKEN_COOKIE_NAME", 'ckCsrfToken');
define("SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME", 'smlswmFMCsrfToken');
define("SMLSWM_TOKEN_COOKIE_NAME", 'smlswmCsrfToken');

 function fmGetCsrfToken(){
   $tokenCharset = 'abcdefghijklmnopqrstuvwxyz0123456789';
   $minTokenLength = 36;
   if( isset($_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]) )
      $token = $_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME];
   if(!isset($token) || strlen($token) < $minTokenLength){
     mt_srand(time());
     $randValues = array();
     for($i=0; $i<$minTokenLength; $i++){
       $randValues[] = mt_rand() * 256;
     }

     $token = "";
     for($i=0; $i<count($randValues); $i++){
       $character = $tokenCharset[ abs($randValues[$i] % strlen($tokenCharset)) ];
       $token .= (mt_rand(1, 100) / 100 > 0.5) ? strtoupper($character) : $character;
     }

     setcookie(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME, $token, 0, '/');
     $_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] = $token;
   }

   return $token;
 }

 function fmCheckForCorrectCsrfTokenInHeader(){
   if(defined("NoCSRFProtection") && NoCSRFProtection == 1) return true;
   if($_SERVER['REQUEST_METHOD'] != 'GET') return true;
   $headertoken = "";
   if (function_exists('getallheaders')){
     $headers = getallheaders();
     if(isset($headers["X-".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]))
       $headertoken = $headers["X-".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME];
       else
        foreach ($headers as $name => $value) {
          if( strtoupper($name) == strtoupper("X-".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)){
            $headertoken = $value;
            break;
          }
        }
   }else{
     if( isset($_SERVER[strtoupper('HTTP_'."X_".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)]) )
       $headertoken = $_SERVER[strtoupper('HTTP_'."X_".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME)];
       else
        if( isset($_SERVER['HTTP_'."X_".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]) )
            $headertoken = $_SERVER['HTTP_'."X_".SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME];
   }

   return $headertoken == fmGetCsrfToken();
 }

?>