<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

# API mode
define("API", 1);

include_once('../config.inc.php');
require_once("./nusoap/lib/nusoap.php");

include_once('api.inc.php');

# set include path to find correct external script locations
$_I0L8J = ini_get('include_path');
if($_I0L8J == "")
  ini_set('include_path', InstallPath);
  else{
     if ( ! defined( "PATH_SEPARATOR" ) ) {
       if ( strpos( $_ENV[ "OS" ], "Win" ) !== false )
         define( "PATH_SEPARATOR", ";" );
       else define( "PATH_SEPARATOR", ":" );
     }
     ini_set('include_path', InstallPath.PATH_SEPARATOR.$_I0L8J);
  }

require_once('api_base.php');
require_once('api_common.php');
require_once('api_users.php');
require_once('api_mailinglists.php');
require_once('api_recipients.php');
require_once('api_files.php');
require_once('api_distriblists.php');

if(defined("SWM")){
  require_once('api_campaigns.php');
  require_once('api_furesponders.php');
}

$_I0lji = new GetFunctionsList();
$_I0lji->preventMethods = array("__construct", "__destruct", "api_base", "CheckAPIKey", "api_ShowSQLError", "api_Error", "_internal_refreshTracking", "_internalCheckFileExtension", "_internal_initRecipientsData");

$_I0lfQ = array("api_Common", "api_Mailinglists", "api_Recipients", "api_Users", "api_Files", "api_DistributionLists");

if(defined("SWM")){
  $_I0lfQ = array_merge($_I0lfQ, array("api_Campaigns", "api_FUResponders"));
}

arsort($_I0lfQ, SORT_STRING);

foreach($_I0lfQ as $key) {
  $_I0lji->classname = $key;
  $_I0lji->classMethodsIntoStruct();
}

@arsort($_I0lji->wsdlStruct, SORT_STRING);

@header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
// always modified
@header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
// HTTP/1.1
@header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0') ;
@header('Cache-Control: post-check=0, pre-check=0', false) ;
// HTTP/1.0
@header('Pragma: no-cache') ;

if(defined('JS_Access_Control_Allow_Origin'))
   @header("Access-Control-Allow-Origin: ".JS_Access_Control_Allow_Origin);
  else
   @header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  @header("Access-Control-Allow-Headers: apitoken");
  exit;
}

$api = null;
$_I6oQo = "APIToken";

if (!function_exists('apache_request_headers')) {
  $APIToken = !empty($_SERVER[strtoupper("HTTP_" . $_I6oQo)]) ? $_SERVER[strtoupper("HTTP_" . $_I6oQo)] : "";
  if(empty($APIToken))
     $APIToken = !empty($_SERVER["HTTP_" . $_I6oQo]) ? $_SERVER["HTTP_" . $_I6oQo] : "";
} else{
  $_I6C0o = apache_request_headers();
  if(!empty($_I6C0o[$_I6oQo]))
    $APIToken = $_I6C0o[$_I6oQo];
  else
    foreach ($_I6C0o as $_I6C8f => $_QltJO) {
      if( strtoupper($_I6C8f) == strtoupper($_I6oQo)){
        $APIToken = $_QltJO;
        break;
      }
    }
}


if(empty($APIToken)){
  _OF1RQ(500, "No valid APIToken.");
  exit;
}


$_I6CiJ = array();

reset($_I0lji->wsdlStruct);
foreach($_I0lji->wsdlStruct as $key => $_I060f){
  $classname = $key;

  foreach($_I060f["method"] as $_I06t6 => $_I1Q0I){
   $_I1QI0 = array();
   $_I1I0L = array('result' => 'xsd:void');

   foreach($_I1Q0I["var"] as $_Qli6J => $_I08CQ){
     if(isset($_I08CQ["return"]))
       $_I1I0L = array("result" => $_I08CQ["type"]);
       else{
        $_I1QI0 = array_merge($_I1QI0, array( $_I08CQ["name"] => $_I08CQ["type"] ) );
       }
   }
   $_I6CiJ[$classname.".".$_I06t6] = array("inparams" => $_I1QI0, "returnparams" => $_I1I0L);
  }
}

#print_r($_I6CiJ);

 #api_Recipients__api_createRecipient
 $_I0lji = "";
 $_I6i6I = "";
 foreach($_POST as $key => $_QltJO){
   if(strpos($key, "__") === false) continue;
   $_I0lji = str_replace("__", ".", $key);
   if(isset($_I6CiJ[$_I0lji])){
     $_I6i6I = $key;
     break;
     }
 }

 // find as substr *.<name> for too long var names on some plattforms
 if(empty($_I6i6I)){
   foreach($_POST as $key => $_QltJO){
     if(strpos($key, "__") !== false) continue;

     foreach($_I6CiJ as $_I6iot => $_Ql0fO){
      if(strpos($_I6iot, ".".$key) !== false){
       $_I0lji = $_I6iot;
       $_I6i6I = $key;
       break;
      }
     }

     if(!empty($_I6i6I)){
      break;
     }
   }
 }

 if(empty($_I6i6I)){
   _OF1RQ(501, "No valid method found.");
   exit;
 }

 $api = _LPC1C( defined("ScriptBaseJSONAlternateURL") && ScriptBaseJSONAlternateURL != "" ? ScriptBaseJSONAlternateURL : ScriptBaseURL )."api/api.php?wsdl";

 # DEBUG overwrite
 # $api = "http://localhost/swm/api/api.php";

 $_I6L8i = new nusoap_client($api);
 $_I6L8i->soap_defencoding = 'UTF-8';# use UTF-8!
 $_I6L8i->decode_utf8 = false; # don't decode UTF-8, json_encode NEEDS UTF-8!
 $_I6LlC = $_I6L8i->getError();
 if ($_I6LlC) {
  _OF1RQ(500, "SOAP constructor error, $_I6LlC");
  exit;
 }

 # set APIToken
 $_I6L8i->setHeaders(array('APIToken' => $APIToken));

 $_I08CQ = json_decode($_POST[$_I6i6I], true);  // assoc arrays no objects!
 $_QL8i1 = $_I6L8i->call($_I0lji, $_I08CQ, '', '', false, true);


 if ($_I6L8i->fault) {
   _OF1RQ( $_QL8i1["faultcode"], !empty($_QL8i1["faultstring"]) ? $_QL8i1["faultstring"] : $_QL8i1["detail"]  );
  } else {
   $_I6LlC = $_I6L8i->getError();
   if ($_I6LlC) {
     _OF1RQ(500, $_I6LlC);
   } else {
     header("Content-Type: application/json; charset=utf-8");
     print json_encode($_QL8i1);
   }
  }

 function _OF1RQ($_I6llO, $_If0JJ, $_If0iI = false){
   global $api;
   if($_If0iI)
      header($_SERVER["SERVER_PROTOCOL"]." $_I6llO $_If0JJ");
   if(count($_POST) > 0)
     @header("Content-Type: application/json; charset=utf-8");
     else
     @header("Content-Type: text/html; charset=utf-8");
   $_Ijj6Q = json_encode(array("xml_api_url" => $api, "error_code" => $_I6llO, "error" => "$_If0JJ"));
   print $_Ijj6Q;
 }

?>
