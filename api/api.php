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

$api = ScriptBaseURL."api/api.php";
$_QfLff = $api.'?wsdl';

$apiserver = new nusoap_server();
$apiserver->soap_defencoding = 'UTF-8';
$apiserver->configureWSDL($AppName.'API', $api);
$apiserver->decode_utf8 = false; // set to false we wan't UTF-8!!!
$apiserver->setDebugLevel(0);

reset($_QfC8t->wsdlStruct);
foreach($_QfC8t->wsdlStruct as $key => $_QfjtQ){
  $classname = $key;

  foreach($_QfjtQ["method"] as $_QfJI8 => $_Q80Qf){
   $_Q80lL = array();
   $_Q8186 = array('result' => 'xsd:void');

   foreach($_Q80Qf["var"] as $_Q6llo => $_QffOf){
     if(isset($_QffOf["return"]))
       $_Q8186 = array("result" => $_QffOf["wsdltype"]);
       else{
        $_Q80lL = array_merge($_Q80lL, array( $_QffOf["name"] => $_QffOf["wsdltype"] ) );
       }
   }


   $apiserver->register($classname.".".$_QfJI8, $_Q80lL, $_Q8186, false, false, false, false, $_Q80Qf["description"]);
  }
}


if(function_exists("file_get_contents"))
  $_Q818O = file_get_contents("php://input");
  else
  $_Q818O = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

$apiserver->service($_Q818O);

?>
