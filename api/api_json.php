<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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
$_Qfoof = ini_get('include_path');
if($_Qfoof == "")
  ini_set('include_path', InstallPath);
  else{
     if ( ! defined( "PATH_SEPARATOR" ) ) {
       if ( strpos( $_ENV[ "OS" ], "Win" ) !== false )
         define( "PATH_SEPARATOR", ";" );
       else define( "PATH_SEPARATOR", ":" );
     }
     ini_set('include_path', InstallPath.PATH_SEPARATOR.$_Qfoof);
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

$_QfC8t = new GetFunctionsList();
$_QfC8t->preventMethods = array("__construct", "__destruct", "api_base", "CheckAPIKey", "api_ShowSQLError", "api_Error", "_internal_refreshTracking", "_internalCheckFileExtension");

$_QfCl0 = array("api_Common", "api_Mailinglists", "api_Recipients", "api_Users", "api_Files", "api_DistributionLists");

if(defined("SWM")){
  $_QfCl0 = array_merge($_QfCl0, array("api_Campaigns", "api_FUResponders"));
}

arsort($_QfCl0, SORT_STRING);

foreach($_QfCl0 as $key) {
  $_QfC8t->classname = $key;
  $_QfC8t->classMethodsIntoStruct();
}

@arsort($_QfC8t->wsdlStruct, SORT_STRING);

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

if (!function_exists('apache_request_headers')) {
  $APIToken = !empty($_SERVER["HTTP_APITOKEN"]) ? $_SERVER["HTTP_APITOKEN"] : "";
} else{
  $_QiOo1 = apache_request_headers();
  if(!empty($_QiOo1["APIToken"]))
    $APIToken = $_QiOo1["APIToken"];
}


if(empty($APIToken)){
  _OQ6RR(500, "No valid APIToken.");
  exit;
}


$_Qiojj = array();

reset($_QfC8t->wsdlStruct);
foreach($_QfC8t->wsdlStruct as $key => $_QfjtQ){
  $classname = $key;

  foreach($_QfjtQ["method"] as $_QfJI8 => $_Q80Qf){
   $_Q80lL = array();
   $_Q8186 = array('result' => 'xsd:void');

   foreach($_Q80Qf["var"] as $_Q6llo => $_QffOf){
     if(isset($_QffOf["return"]))
       $_Q8186 = array("result" => $_QffOf["type"]);
       else{
        $_Q80lL = array_merge($_Q80lL, array( $_QffOf["name"] => $_QffOf["type"] ) );
       }
   }
   $_Qiojj[$classname.".".$_QfJI8] = array("inparams" => $_Q80lL, "returnparams" => $_Q8186);
  }
}

#print_r($_Qiojj);

 #api_Recipients__api_createRecipient
 $_QfC8t = "";
 $_QioiC = "";
 foreach($_POST as $key => $_Q6ClO){
   if(strpos($key, "__") === false) continue;
   $_QfC8t = str_replace("__", ".", $key);
   if(isset($_Qiojj[$_QfC8t])){
     $_QioiC = $key;
     break;
     }
 }

 // find as substr *.<name> for too long var names on some plattforms
 if(empty($_QioiC)){
   foreach($_POST as $key => $_Q6ClO){
     if(strpos($key, "__") !== false) continue;

     foreach($_Qiojj as $_QiCJJ => $_Q66jQ){
      if(strpos($_QiCJJ, ".".$key) !== false){
       $_QfC8t = $_QiCJJ;
       $_QioiC = $key;
       break;
      }
     }

     if(!empty($_QioiC)){
      break;
     }
   }
 }

 if(empty($_QioiC)){
   _OQ6RR(501, "No valid method found.");
   exit;
 }

 $api = _OBLDR(ScriptBaseURL)."api/api.php?wsdl";

 # DEBUG overwrite
 # $api = "http://localhost/swm/api/api.php";

 $_QiCoJ = new nusoap_client($api);
 $_QiCoJ->soap_defencoding = 'UTF-8';# use UTF-8!
 $_QiCoJ->decode_utf8 = false; # don't decode UTF-8, json_encode NEEDS UTF-8!
 $_Qiift = $_QiCoJ->getError();
 if ($_Qiift) {
  _OQ6RR(500, "SOAP constructor error, $_Qiift");
  exit;
 }

 # set APIToken
 $_QiCoJ->setHeaders(array('APIToken' => $APIToken));

 $_QffOf = json_decode($_POST[$_QioiC], true);  // assoc arrays no objects!
 $_Q60l1 = $_QiCoJ->call($_QfC8t, $_QffOf, '', '', false, true);


 if ($_QiCoJ->fault) {
   _OQ6RR( $_Q60l1["faultcode"], !empty($_Q60l1["faultstring"]) ? $_Q60l1["faultstring"] : $_Q60l1["detail"]  );
  } else {
   $_Qiift = $_QiCoJ->getError();
   if ($_Qiift) {
     _OQ6RR(500, $_Qiift);
   } else {
     header("Content-Type: application/json; charset=utf-8");
     print json_encode($_Q60l1);
   }
  }

 function _OQ6RR($_QiLI8, $_QiLCl, $_QilCi = false){
   global $api;
   if($_QilCi)
      header($_SERVER["SERVER_PROTOCOL"]." $_QiLI8 $_QiLCl");
   if(count($_POST) > 0)
     @header("Content-Type: application/json; charset=utf-8");
     else
     @header("Content-Type: text/html; charset=utf-8");
   $_QoQOL = json_encode(array("xml_api_url" => $api, "error_code" => $_QiLI8, "error" => "$_QiLCl"));
   print $_QoQOL;
 }

?>
