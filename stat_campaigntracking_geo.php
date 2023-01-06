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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("geolocation.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeViewCampaignTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_POST['CampaignId']))
    $_j01fj = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_j01fj = intval($_GET['CampaignId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $_j01fj = intval($_POST['OneCampaignListId']);

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

  if(!isset($_j01fj) && isset($_POST["ResponderId"]))
     $_j01fj = intval($_POST["ResponderId"]);
     else
     if(!isset($_j01fj) && isset($_GET["ResponderId"]))
         $_j01fj = intval($_GET["ResponderId"]);

  if(!isset($_j01fj)) {
    $_GET["ResponderType"] = $ResponderType;
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_j01fj = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_campaigntracking.php";
    include_once("campaignsendstatselect.inc.php");
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  $_Itfj8 = "";


  if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"])) {
      $_QLfol = "SELECT `TrackingOpeningsTableName` FROM `$_QLi60` WHERE `$_QLi60`.`id`=$_j01fj";
      $_f6j0L = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfOpener"], $_QLo06);
     }
     else {
      $_QLfol = "SELECT `TrackingLinksTableName` FROM `$_QLi60` WHERE `$_QLi60`.id=$_j01fj";
      $_f6j0L = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfClick"], $_QLo06);
     }

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLL16 = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);

  $_jf1IJ = $_QLL16[0];

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
  @header( 'Content-Type: text/plain; charset='.$_QLo06 ) ;

  $_JIfIj = new _LBPJO('./geoip/');

  if(!$_JIfIj->Openable()) {
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
  $_f6QLo = true;
  $_f6I8f = false;
  print '[';

  # own IP = red flag
  $_6tJfO = $_JIfIj->lookupLocation(getOwnIP(false));
  if($_6tJfO != null){
     print _LAFFB(array("latitude" => $_6tJfO->latitude, "longitude" => $_6tJfO->longitude, "image" => "red", "title" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoOwnStation"], $_QLo06)), JSON_NUMERIC_CHECK);
     $_f6I8f = true;
  }
  $_6tJfO = null;

  $_QLfol = "SELECT DISTINCT `IP` FROM `$_jf1IJ` WHERE `SendStat_id`=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  #_L8D88($_QLfol);

  while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_6tJfO = $_JIfIj->lookupLocation($_QLO0f["IP"]);
    if($_6tJfO == null ) {
      continue;
    }
    $_f6jQC = $_6tJfO->city;
    if(empty($_f6jQC))
      $_f6jQC = $_f6j0L;

    print ($_f6I8f ? "," : '') . _LAFFB( array("latitude" => $_6tJfO->latitude, "longitude" => $_6tJfO->longitude, "image" => "blue", "title" => $_f6jQC), JSON_NUMERIC_CHECK);

    if(!$_f6I8f){
      $_f6I8f = true;
    }
    $_6tJfO = null;
  }
  mysql_free_result($_QL8i1);

  print ']';
  $_f6QLo = false;
  # *** JSON live output end

  // shutdown
  function CloseJSONArray(){
    global $_f6QLo;
    if($_f6QLo){
      print ']';
      $_f6QLo = false;
    }
  }

?>
