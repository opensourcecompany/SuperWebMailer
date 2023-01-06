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

// https://stackoverflow.com/questions/15944824/search-a-php-array-for-partial-string-match   
function array_find($_jOjt8, array $_jOjjt, $_JLilI = null) {

    $_JLLoO = array();

    if(is_array($_jOjjt[0]) === true) { // for multidimentional array

        foreach (array_column($_jOjjt, $_JLilI) as $key => $_QltJO) {
            if (strpos($_QltJO, $_jOjt8) !== false) {
                $_JLLoO[] = $key;

            }
        }

    } else {
        foreach ($_jOjjt as $key => $_QltJO) { // for normal array
            if (strpos($_QltJO, $_jOjt8) !== false) {
                $_JLLoO[] = $key;
            }
        }
    }

    if(empty($_JLLoO)) {
        return false;
    }

    return $_JLLoO;
}

function _L6AJP($EMail){
   $_J0fIj = array();
   if(_L6AF6($EMail, $_J0fIj, $_J08Q1) === false)
      return count($_J0fIj) > 0;
      else
      return $_J08Q1;
}
   
function _L6AF6($_J06Ji, &$_J0fIj, &$_J08Q1){
   // array of email=>email-address, id=>id
   global $_QLo06;

   $_J0fIj = array();

   if(!is_array($_J06Ji)){
     $_I1OoI = array();
     $_I1OoI[] = array("email" => $_J06Ji, "id" => 1);
     $_J06Ji = $_I1OoI;
   }

   if(count($_J06Ji) == 0)
    return true;
   
   if(version_compare(PHP_VERSION, "5.5") < 0){
     $_J08Q1 = "function requires PHP 5.5 and newer";
     return false;
   }

   if(!defined("ECG_APIKEY") || ECG_APIKEY == ""){
     $_J08Q1 = "ECG List API key in file userdefined.inc.php missing";
     return false;
   }

   $_J08Q1 = "";
   
   $url = 'https://ecg.rtr.at/dev/api/v1/emails/check/batch';
     
   $_JLlto = array();
   foreach($_J06Ji as $key => $_QltJO){
     $_JLlto[] = strtolower($_QltJO["email"]);
     $_JLloo = substr($_QltJO["email"], strpos($_QltJO["email"], "@") + 1);
     if(!in_array($_JLloo, $_JLlto))
       $_JLlto[] = $_JLloo;
   }  
   
   $_I0QjQ["emails"] = $_JLlto;
   $_I0QjQ["contained"] = (bool)true;
   
   $_IO6iJ = array(
      'http' => array(
        'method'  => 'POST',
        'content' => json_encode( $_I0QjQ, JSON_UNESCAPED_UNICODE ),
        'header'=>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n" .
                    "X-API-KEY: " . ECG_APIKEY . "\r\n"
        )
   );

   $_Jl00o  = stream_context_create( $_IO6iJ );
   $_QL8i1 = file_get_contents( $url, false, $_Jl00o );
   if($_QL8i1 === false) {
     $_J08Q1 = htmlspecialchars(str_replace("\r\n", "", join("", $http_response_header)), ENT_COMPAT, $_QLo06, false);
     return false;
   }  

   $response = json_decode( $_QL8i1, true );
   
   if(!$response || !isset($response["emails"])){
     return false; // or true, request failed or json errors?
   }

   if(!count($response["emails"])){
     
     // debug
     /*$key = array_search('info@mirkoboeer.de', array_column($_J06Ji, 'email'));
     if($key !== false)
       $_J0fIj[] = $_J06Ji[$key]; */
     // debug /
     return true;
   }  
   
   // 1 email
   if(count($_JLlto) == 2 && count($response["emails"])){
     $_J0fIj[] = $_J06Ji[0];
     return true;
   }  
    
     
   for($_Qli6J=0; $_Qli6J<count($response["emails"]); $_Qli6J++){
     
     // find email address
     $key = array_search($response["emails"][$_Qli6J], array_column($_J06Ji, 'email'));
     if($key !== false){
       if(array_search($_J06Ji[$key]['email'], array_column($_J0fIj, 'email')) === false)
          $_J0fIj[] = $_J06Ji[$key];
       continue;
     }
     // find domain  
     $_Jl0CO = array_find("@" . $response["emails"][$_Qli6J], $_J06Ji, 'email');
     for($key=0; $_Jl0CO !== false && $key<count($_Jl0CO); $key++){
       if(array_search($_J06Ji[$key]['email'], array_column($_J0fIj, 'email')) === false)
          $_J0fIj[] = $_J06Ji[$key];
     }  
   }  
     
   return count($_J0fIj) > 0;
 }

 function _L6BPA($EMail){
   $EMail = strtolower($EMail);
   $_JLloo = substr($EMail, strpos($EMail, "@"));
   $_I60fo = fopen(InstallPath."ECG/ecg-liste.hash", "rb");
   if($_I60fo === false)
     return false;
   if(version_compare(phpversion(), "5.0.0", "<") && !function_exists("sha1_str2blks_SHA1") ) { //sha1_str2blks_SHA1 is defined in sha1.php
     $EMail = sha1($EMail);
     $_JLloo = sha1($_JLloo);
   } else {
     $EMail = sha1($EMail, true);
     $_JLloo = sha1($_JLloo, true);
   }
   if(version_compare(phpversion(), "5.0.0", "<") && !function_exists("sha1_str2blks_SHA1") ) { //sha1_str2blks_SHA1 is defined in sha1.php
     $_Ql0fO = "";
     for($_Qli6J=0; $_Qli6J<strlen($EMail); $_Qli6J=$_Qli6J+2)
        $_Ql0fO .= chr(hexdec(substr($EMail, $_Qli6J, 2)));
     $EMail = $_Ql0fO;
     $_Ql0fO = "";
     for($_Qli6J=0; $_Qli6J<strlen($_JLloo); $_Qli6J=$_Qli6J+2)
        $_Ql0fO .= chr(hexdec(substr($_JLloo, $_Qli6J, 2)));
     $_JLloo = $_Ql0fO;
   }
   while(!feof($_I60fo)) {
     $_I0Clj = fread($_I60fo, 20);
     if($EMail == $_I0Clj || $_JLloo == $_I0Clj){
       fclose($_I60fo);
       return true;
     }
   }
   fclose($_I60fo);
   return false;
 }

?>
