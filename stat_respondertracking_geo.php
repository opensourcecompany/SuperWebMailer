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

  if(isset($_POST['ResponderId']))
    $_J16QO = intval($_POST['ResponderId']);
    else
    if(isset($_GET['ResponderId']))
      $_J16QO = intval($_GET['ResponderId']);

  if(isset($_POST['ResponderType']))
    $ResponderType = $_POST['ResponderType'];
    else
    if(isset($_GET['ResponderType']))
      $ResponderType = $_GET['ResponderType'];

  if(isset($_POST['FUMailId']))
    $_J16Jf = intval($_POST['FUMailId']);
  else
    if ( isset($_GET['FUMailId']) )
       $_J16Jf = intval($_GET['FUMailId']);

  if(isset($_POST['DistribListEntryId']))
    $_6lQ61 = intval($_POST['DistribListEntryId']);
  else
    if ( isset($_GET['DistribListEntryId']) )
       $_6lQ61 = intval($_GET['DistribListEntryId']);

  if(isset($_POST['OneDLEId']))
    $_6lQ61 = intval($_POST['OneDLEId']);
  else
    if ( isset($_GET['OneDLEId']) )
       $_6lQ61 = intval($_GET['OneDLEId']);

  if(!isset($_J16QO)) {
    $_GET["ResponderType"] = $ResponderType;
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_J16QO = intval($_POST["ResponderId"]);
  }

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($ResponderType == "FollowUpResponder" && !$_QJojf["PrivilegeViewFUResponderTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($ResponderType == "BirthdayResponder" && !$_QJojf["PrivilegeViewBirthdayMailsTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($ResponderType == "EventResponder" && !$_QJojf["PrivilegeViewEventMailsTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($ResponderType == "RSS2EMailResponder" && !$_QJojf["PrivilegeViewRSS2EMailMailsTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($ResponderType == "DistributionList" && !$_QJojf["PrivilegeViewDistribListTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  $_Jlj0J = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  if($ResponderType == "BirthdayResponder")
    $_Jfi0i = $_IIl8O;
  if($ResponderType == "EventResponder")
    $_Jfi0i = $_IC0oQ;
  if($ResponderType == "FollowUpResponder")
    $_Jfi0i = $_QCLCI;
  if($ResponderType == "Campaign") {
    include_once("stat_campaigntracking.php");
    exit;
  }
  if($ResponderType == "RSS2EMailResponder")
    $_Jfi0i = $_IoOLJ;

  if($ResponderType == "DistributionList")
    $_Jfi0i = $_QoOft;

  if($ResponderType == "FollowUpResponder") {
    if(!isset($_J16Jf)) {
      include_once("fumailselect.inc.php");
      if(!isset($_POST["FUMailId"]))
         exit;
         else
         $_J16Jf = intval($_POST["FUMailId"]);
    }
  }

  if($ResponderType == "DistributionList") {
    if(!isset($_6lQ61)) {
      include_once("distriblistentryselect.inc.php");
      if(!isset($_POST["OneDLEId"]))
         exit;
         else
         $_6lQ61 = intval($_POST["OneDLEId"]);
    }
  }

  $_QJlJ0 = "SELECT $_Jfi0i.*, $_Q60QL.MaillistTableName FROM $_Jfi0i LEFT JOIN $_Q60QL ON $_Jfi0i.maillists_id=$_Q60QL.id WHERE $_Jfi0i.id=$_J16QO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_6lfif = mysql_fetch_assoc($_Q60l1);
  $_QlQC8 = $_6lfif["MaillistTableName"];
  mysql_free_result($_Q60l1);

  if($ResponderType == "FollowUpResponder") {
    $_QJlJ0 = "SELECT Name AS FUMMailName, TrackingOpeningsTableName, TrackingLinksTableName FROM $_6lfif[FUMailsTableName] WHERE id=$_J16Jf";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_6lfif = array_merge($_6lfif, $_Q6Q1C);
    mysql_free_result($_Q60l1);
  }

  if($ResponderType == "DistributionList") {
    $_QJlJ0 = "SELECT MailSubject, DATE_FORMAT(CreateDate, $_Q6QiO) AS MailCreateDate FROM $_Qoo8o WHERE id=$_6lQ61";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_6lfif = array_merge($_6lfif, $_Q6Q1C);
    mysql_free_result($_Q60l1);
  }

  $_jC1lo = "";
  $_jCQ0I = "";
  if(isset($_GET["startdate"]))
    $_POST["startdate"] = $_GET["startdate"];
  if(isset($_GET["enddate"]))
    $_POST["enddate"] = $_GET["enddate"];

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QJlJ0 = "SELECT DATE_FORMAT(NOW(), $_If0Ql) AS ENDDATE, DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 10 DAY), $_If0Ql) AS STARTDATE ";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_jCQ0I = $_Q6Q1C[0];
    $_jC1lo = $_Q6Q1C[1];
    $_POST["startdate"] = $_jC1lo;
    $_POST["enddate"] = $_jCQ0I;
    mysql_free_result($_Q60l1);
  }

  if( isset($_POST["startdate"]) && isset($_POST["enddate"]) ) {
    if($INTERFACE_LANGUAGE != "de") {
      $_jC1lo = $_POST["startdate"];
      $_jCQ0I = $_POST["enddate"];
    } else {
      $_Q8otJ = explode('.', $_POST["startdate"]);
      $_jC1lo = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
      $_Q8otJ = explode('.', $_POST["enddate"]);
      $_jCQ0I = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
    }
  }


  if($OwnerOwnerUserId == 0x5A) exit;

  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";

  if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"])) {
    $_QJlJ0 = "SELECT DISTINCT IP FROM $_6lfif[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
    $_JlCl8 = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfOpener"], $_Q6QQL);
    }
  else {
    $_QJlJ0 = "SELECT DISTINCT IP FROM $_6lfif[TrackingLinksTableName] WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
    $_JlCl8 = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfClick"], $_Q6QQL);
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

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  //_OAL8F($_QJlJ0);

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
