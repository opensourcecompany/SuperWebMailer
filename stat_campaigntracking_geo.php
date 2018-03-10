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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("geolocation.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeViewCampaignTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_POST['CampaignId']))
    $_I6lOO = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_I6lOO = intval($_GET['CampaignId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $_I6lOO = intval($_POST['OneCampaignListId']);

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);

  if(isset($_POST['ResponderType']))
    $ResponderType = $_POST['ResponderType'];
  else
    if ( isset($_GET['ResponderType']) )
       $ResponderType = $_GET['ResponderType'];
  if(!isset($ResponderType))
    $ResponderType="Campaign";

  if(!isset($_I6lOO) && isset($_POST["ResponderId"]))
     $_I6lOO = intval($_POST["ResponderId"]);
     else
     if(!isset($_I6lOO) && isset($_GET["ResponderId"]))
         $_I6lOO = intval($_GET["ResponderId"]);

  if(!isset($_I6lOO)) {
    $_GET["ResponderType"] = $ResponderType;
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_I6lOO = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_campaigntracking.php";
    include_once("campaignsendstatselect.inc.php");
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  $_I0600 = "";


  if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"])) {
      $_QJlJ0 = "SELECT `TrackingOpeningsTableName` FROM `$_Q6jOo` WHERE `$_Q6jOo`.`id`=$_I6lOO";
      $_JlCl8 = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfOpener"], $_Q6QQL);
     }
     else {
      $_QJlJ0 = "SELECT `TrackingLinksTableName` FROM `$_Q6jOo` WHERE `$_Q6jOo`.id=$_I6lOO";
      $_JlCl8 = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfClick"], $_Q6QQL);
     }

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6J0Q = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);

  $_ICLO8 = $_Q6J0Q[0];

  // Prevent the browser from caching the result.
  // Date in the past
  @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
  // always modified
  @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
  // HTTP/1.1
  @header('Cache-Control: no-store, no-cache, must-revalidate') ;
  @header('Cache-Control: post-check=0, pre-check=0', false) ;
  // HTTP/1.0
  @header('Pragma: no-cache') ;

  // Set the response format.
  @header( 'Content-Type: text/plain; charset='.$_Q6QQL ) ;

  $_j6Qlo = new _OCB1C('./geoip/');

  if(!$_j6Qlo->Openable()) {
     print '[]'; // JSON empty array
     #$resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"];
     exit;
  }


  # *** JSON live output start

  # decimalseparator bug in some PHP versions
  @setlocale (LC_ALL, 'en_US');
  @setlocale (LC_TIME, 'en_US');
  if(function_exists("date_default_timezone_set"))
    @date_default_timezone_set("Europe/London");

  register_shutdown_function('CloseJSONArray');
  $_JlCQi = true;
  $_JlCjC = false;
  print '[';

  # own IP = red flag
  $_J6t8o = $_j6Qlo->lookupLocation(getOwnIP());
  if($_J6t8o != null){
     print _OCR88(array("latitude" => $_J6t8o->latitude, "longitude" => $_J6t8o->longitude, "image" => "red", "title" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoOwnStation"], $_Q6QQL)), JSON_NUMERIC_CHECK);
     $_JlCjC = true;
  }
  $_J6t8o = null;

  $_QJlJ0 = "SELECT DISTINCT `IP` FROM `$_ICLO8` WHERE `SendStat_id`=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  #_OAL8F($_QJlJ0);

  while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_J6t8o = $_j6Qlo->lookupLocation($_Q6Q1C["IP"]);
    if($_J6t8o == null ) {
      continue;
    }
    $_JliJl = $_J6t8o->city;
    if(empty($_JliJl))
      $_JliJl = $_JlCl8;

    print $_JlCjC ? "," : '' . _OCR88( array("latitude" => $_J6t8o->latitude, "longitude" => $_J6t8o->longitude, "image" => "blue", "title" => $_JliJl), JSON_NUMERIC_CHECK);

    if(!$_JlCjC){
      $_JlCjC = true;
    }
    $_J6t8o = null;
  }
  mysql_free_result($_Q60l1);

  print ']';
  $_JlCQi = false;
  # *** JSON live output end

  // shutdown
  function CloseJSONArray(){
    global $_JlCQi;
    if($_JlCQi){
      print ']';
      $_JlCQi = false;
    }
  }

?>
