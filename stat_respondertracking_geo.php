<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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

  if(isset($_POST['ResponderId']))
    $_6QJI0 = intval($_POST['ResponderId']);
    else
    if(isset($_GET['ResponderId']))
      $_6QJI0 = intval($_GET['ResponderId']);

  if(isset($_POST['ResponderType']))
    $ResponderType = $_POST['ResponderType'];
    else
    if(isset($_GET['ResponderType']))
      $ResponderType = $_GET['ResponderType'];

  if(isset($_POST['FUMailId']))
    $_6Q60L = intval($_POST['FUMailId']);
  else
    if ( isset($_GET['FUMailId']) )
       $_6Q60L = intval($_GET['FUMailId']);

  if(isset($_POST['DistribListEntryId']))
    $_88LLI = intval($_POST['DistribListEntryId']);
  else
    if ( isset($_GET['DistribListEntryId']) )
       $_88LLI = intval($_GET['DistribListEntryId']);

  if(isset($_POST['OneDLEId']))
    $_88LLI = intval($_POST['OneDLEId']);
  else
    if ( isset($_GET['OneDLEId']) )
       $_88LLI = intval($_GET['OneDLEId']);

  if(!isset($_6QJI0)) {
    $_GET["ResponderType"] = $ResponderType;
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_6QJI0 = intval($_POST["ResponderId"]);
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($ResponderType == "FollowUpResponder" && !$_QLJJ6["PrivilegeViewFUResponderTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($ResponderType == "BirthdayResponder" && !$_QLJJ6["PrivilegeViewBirthdayMailsTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($ResponderType == "EventResponder" && !$_QLJJ6["PrivilegeViewEventMailsTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($ResponderType == "RSS2EMailResponder" && !$_QLJJ6["PrivilegeViewRSS2EMailMailsTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($ResponderType == "DistributionList" && !$_QLJJ6["PrivilegeViewDistribListTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  $_fJtjj = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  if($ResponderType == "BirthdayResponder")
    $_6Ol6i = $_ICo0J;
  if($ResponderType == "EventResponder")
    $_6Ol6i = $_j6Ql8;
  if($ResponderType == "FollowUpResponder")
    $_6Ol6i = $_I616t;
  if($ResponderType == "Campaign") {
    include_once("stat_campaigntracking.php");
    exit;
  }
  if($ResponderType == "RSS2EMailResponder")
    $_6Ol6i = $_jJLQo;

  if($ResponderType == "DistributionList")
    $_6Ol6i = $_IjC0Q;

  if($ResponderType == "FollowUpResponder") {
    if(!isset($_6Q60L)) {
      include_once("fumailselect.inc.php");
      if(!isset($_POST["FUMailId"]))
         exit;
         else
         $_6Q60L = intval($_POST["FUMailId"]);
    }
  }

  if($ResponderType == "DistributionList") {
    if(!isset($_88LLI)) {
      include_once("distriblistentryselect.inc.php");
      if(!isset($_POST["OneDLEId"]))
         exit;
         else
         $_88LLI = intval($_POST["OneDLEId"]);
    }
  }

  $_QLfol = "SELECT $_6Ol6i.*, $_QL88I.MaillistTableName FROM $_6Ol6i LEFT JOIN $_QL88I ON $_6Ol6i.maillists_id=$_QL88I.id WHERE $_6Ol6i.id=$_6QJI0";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_8tILo = mysql_fetch_assoc($_QL8i1);
  $_I8I6o = $_8tILo["MaillistTableName"];
  mysql_free_result($_QL8i1);

  if($ResponderType == "FollowUpResponder") {
    $_QLfol = "SELECT Name AS FUMMailName, TrackingOpeningsTableName, TrackingLinksTableName FROM $_8tILo[FUMailsTableName] WHERE id=$_6Q60L";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_8tILo = array_merge($_8tILo, $_QLO0f);
    mysql_free_result($_QL8i1);
  }

  if($ResponderType == "DistributionList") {
    $_QLfol = "SELECT MailSubject, DATE_FORMAT(CreateDate, $_QLo60) AS MailCreateDate FROM $_IjCfJ WHERE id=$_88LLI";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_8tILo = array_merge($_8tILo, $_QLO0f);
    mysql_free_result($_QL8i1);
  }

  $_JoiCQ = "";
  $_JoL0L = "";
  if(isset($_GET["startdate"]))
    $_POST["startdate"] = $_GET["startdate"];
  if(isset($_GET["enddate"]))
    $_POST["enddate"] = $_GET["enddate"];

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ) AS ENDDATE, DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 10 DAY), $_j01CJ) AS STARTDATE ";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_JoL0L = $_QLO0f[0];
    $_JoiCQ = $_QLO0f[1];
    $_POST["startdate"] = $_JoiCQ;
    $_POST["enddate"] = $_JoL0L;
    mysql_free_result($_QL8i1);
  }

  if( isset($_POST["startdate"]) && isset($_POST["enddate"]) ) {
    if($INTERFACE_LANGUAGE != "de") {
      $_JoiCQ = $_POST["startdate"];
      $_JoL0L = $_POST["enddate"];
    } else {
      $_I1OoI = explode('.', $_POST["startdate"]);
      $_JoiCQ = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
      $_I1OoI = explode('.', $_POST["enddate"]);
      $_JoL0L = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
    }
  }


  if($OwnerOwnerUserId == 0x5A) exit;

  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";

  if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"])) {
    $_QLfol = "SELECT DISTINCT IP FROM $_8tILo[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
    $_f6j0L = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfOpener"], $_QLo06);
    }
  else {
    $_QLfol = "SELECT DISTINCT IP FROM $_8tILo[TrackingLinksTableName] WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
    $_f6j0L = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfClick"], $_QLo06);
  }

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

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  //_L8D88($_QLfol);

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
