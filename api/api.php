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

$api = ScriptBaseURL."api/api.php";
$_I101L = $api.'?wsdl';

$apiserver = new nusoap_server();
$apiserver->soap_defencoding = 'UTF-8';
$apiserver->configureWSDL($AppName.'API', $api);
$apiserver->decode_utf8 = false; // set to false we wan't UTF-8!!!
$apiserver->setDebugLevel(0);

reset($_I0lji->wsdlStruct);
foreach($_I0lji->wsdlStruct as $key => $_I060f){
  $classname = $key;

  foreach($_I060f["method"] as $_I06t6 => $_I1Q0I){
   $_I1QI0 = array();
   $_I1I0L = array('result' => 'xsd:void');

   foreach($_I1Q0I["var"] as $_Qli6J => $_I08CQ){
     if(isset($_I08CQ["return"]))
       $_I1I0L = array("result" => $_I08CQ["wsdltype"]);
       else{
        $_I1QI0 = array_merge($_I1QI0, array( $_I08CQ["name"] => $_I08CQ["wsdltype"] ) );
       }
   }


   $apiserver->register($classname.".".$_I06t6, $_I1QI0, $_I1I0L, false, false, false, false, $_I1Q0I["description"]);
  }
}


if(function_exists("file_get_contents"))
  $_I1Itl = file_get_contents("php://input");
  else
  $_I1Itl = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';


ClearLastError(); 
if (function_exists("error_get_last")) {
  register_shutdown_function('APIErrorHandler');
}
  
$apiserver->service($_I1Itl);

function APIErrorHandler() {
  if(defined("DEBUG"))
     return;
  
   $_I1Ilj = error_get_last();

   if(!$_I1Ilj)
     return;

   if( $_I1Ilj["type"] == E_ERROR || $_I1Ilj["type"] == E_USER_ERROR  ) {
   } else
     return;

   print sprintf("Fatal PHP ERROR type=%d; message=%s; file=%s; line=%d", $_I1Ilj["type"], $_I1Ilj["message"], $_I1Ilj["file"], $_I1Ilj["line"]);

   return;
}

?>
