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

  if(!isset($_GET["Card"]))
     if(!isset($_POST["Card"]))
        $_GET["Card"] = "world";
        else
        $_GET["Card"] = $_POST["Card"];

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
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_8tILo = mysql_fetch_assoc($_QL8i1);
  $_I8I6o = $_8tILo["MaillistTableName"];
  mysql_free_result($_QL8i1);

  if($ResponderType == "FollowUpResponder") {
    $_QLfol = "SELECT Name AS FUMMailName, TrackingOpeningsTableName, TrackingLinksTableName FROM $_8tILo[FUMailsTableName] WHERE id=$_6Q60L";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_8tILo = array_merge($_8tILo, $_QLO0f);
    mysql_free_result($_QL8i1);
  }

  if($ResponderType == "DistributionList") {
    $_QLfol = "SELECT MailSubject, DATE_FORMAT(CreateDate, $_QLo60) AS MailCreateDate FROM $_IjCfJ WHERE id=$_88LLI";
    $_QL8i1 = mysql_query($_QLfol);
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
    $_QL8i1 = mysql_query($_QLfol);
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

  if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"]))
    $_QLfol = "SELECT DISTINCT IP FROM $_8tILo[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
  else
    $_QLfol = "SELECT DISTINCT IP FROM $_8tILo[TrackingLinksTableName] WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";


  $_f6jlt = false;
 // earth
  if($_GET["Card"] == "europe") {
     $_f6Jjf = imagecreatefromjpeg("./geoip/europe.jpg");
     $_f66QL = 81.02;
     $_f66ft = 71.881;
     $_f66iC = 32.673;
     $_f6fJ1 = -26.365;
     }
     else
     if($_GET["Card"] == "germany"){
        $_f6Jjf = imagecreatefromjpeg("./geoip/germany.jpg");
        $_f66QL = 55.155;
        $_f66ft = 15.96;
        $_f66iC = 46.94;
        $_f6fJ1 = 5.43;
        }
        else {
          $_f6Jjf = imagecreatefromjpeg("./geoip/earth.jpg");
          $_f6jlt = true;
          $_f66QL = 0;
          $_f66iC = 180;
          $_f66ft = 90;
          $_f6fJ1 = -90;
        }
  $_f680C = imagesx($_f6Jjf);
  $_f68I0 = imagesy($_f6Jjf);
  $_f686Q = (($_f68I0/($_f66QL-$_f66iC)));
  $_f6t0Q = (($_f680C/($_f66ft-$_f6fJ1)));

 // red flag
  $_f6tj8 = imagecreatefrompng("./geoip/point_red.png");
  $_f6t6O = imagesx($_f6tj8);
  $_f6tOt = imagesy($_f6tj8);

 // blue flag
  $_f6O6f = imagecreatefrompng("./geoip/point_blue.png");
  $_f6Oi1 = imagesx($_f6O6f);
  $_f6oIo = imagesy($_f6O6f);

  $_JIfIj = new _LBPJO('./geoip/');

  if(!$_JIfIj->Openable()) {
     print $resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"];
     exit;
  }

  $_f6oC8 = imagecolorallocate($_f6Jjf, 0,0,0);
  $_f6ol8 = imagecolorallocate($_f6Jjf, 255,255,255);
  $_f6C1j = imagecolorallocate($_f6Jjf, 255,0,0);
  $_f6ClL = imagecolorallocate($_f6Jjf, 0,0,255);
  $_f6ifj = imagecolorallocate($_f6Jjf, 255,255,0);


 # own IP = red flag
  $_6tJfO = $_JIfIj->lookupLocation(getOwnIP(false));
  if($_6tJfO != null)
     if($_f6jlt)
        $_f6LIJ = _LF6P1($_6tJfO->latitude, $_6tJfO->longitude, $_f680C, $_f68I0);
        else{
           $_f6LIJ["x"] = round((($_6tJfO->longitude - $_f6fJ1) * $_f6t0Q));
           $_f6LIJ["y"] = round((($_f66QL - $_6tJfO->latitude) * $_f686Q));
        }
     else {
       $_f6LIJ["x"] = $_f6t6O * -1;
       $_f6LIJ["y"] = $_f6tOt * -1;
     }
  $_6tJfO = null;

 // red flag with own IP
  $_f6LoO = imagecreatefrompng("./geoip/block_red.png");
  $_f6LCt = imagesx($_f6LoO);
  $_f6l8f = imagesy($_f6LoO);

  imagecopy($_f6Jjf, $_f6LoO, $_f6LIJ["x"] - round($_f6LCt / 2), $_f6LIJ["y"] - ($_f6l8f / 2), 0, 0, $_f6LCt, $_f6l8f);

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_ff0Qi = imagecreatefrompng("./geoip/block_blue.png");
  $_f6LCt = imagesx($_ff0Qi);
  $_f6l8f = imagesy($_ff0Qi);

  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_6tJfO = $_JIfIj->lookupLocation($_QLO0f["IP"]);
    if($_6tJfO == null ) {
      continue;
    }
    if($_f6jlt)
      $_f6LIJ = _LF6P1($_6tJfO->latitude, $_6tJfO->longitude, $_f680C, $_f68I0);
      else{
        $_f6LIJ["x"] = round((($_6tJfO->longitude - $_f6fJ1) * $_f6t0Q));
        $_f6LIJ["y"] = round((($_f66QL - $_6tJfO->latitude) * $_f686Q));
      }
    $_6tJfO = null;
    // blue flag
    imagecopy($_f6Jjf, $_ff0Qi, $_f6LIJ["x"] - round($_f6LCt / 2), $_f6LIJ["y"] - ($_f6l8f / 2), 0, 0, $_f6LCt, $_f6l8f);
  }
  mysql_free_result($_QL8i1);


  if($_f6jlt) {
   // Legend left
    #imagestring($_f6Jjf, 3, 12, $_f68I0 - 18, unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoConfirmedSubscribtions"], "iso-8859-1"), $_f6ClL);

   // Legend "middle"
    $_I016j = (int)($_f680C / 3);
    imagecopy($_f6Jjf, $_f6tj8, $_I016j - round($_f6t6O / 2), $_f68I0 - 14, 0, 0, $_f6t6O, $_f6tOt);
    imagestring($_f6Jjf, 3, $_I016j + $_f6t6O + 2, $_f68I0 - 18, unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoOwnStation"],"iso-8859-1" ), $_f6oC8);

   // Legend "right"
    $_I016j = (int)(($_f680C / 3) * 2);
    imagecopy($_f6Jjf, $_f6O6f, $_I016j - round($_f6Oi1 / 2), $_f68I0 - 14, 0, 0, $_f6Oi1, $_f6oIo);
    if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"]))
       $_QLJfI = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfOpener"], "iso-8859-1");
       else
       $_QLJfI = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfClick"], "iso-8859-1");
    imagestring($_f6Jjf, 3, $_I016j + $_f6Oi1 + 2, $_f68I0 - 18, $_QLJfI, $_f6oC8);
  }


  header("Content-Type: image/jpeg");
  // Prevent the browser from caching the result.
  // Date in the past
  @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
  // always modified
  @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
  // HTTP/1.1
  @header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0') ;
  @header('Cache-Control: post-check=0, pre-check=0', false) ;
  // HTTP/1.0
  @header('Pragma: no-cache') ;

  imagejpeg($_f6Jjf);




  function _LF6P1($_ff0jo, $_ff06i, $_fQlIo, $_ff0CC)
  {
    $_I016j = (($_ff06i + 180) * ($_fQlIo / 360));
    $_jJjQi = ((($_ff0jo * -1) + 90) * ($_ff0CC / 180));
    return array("x"=>(int)round($_I016j),"y"=>(int)round($_jJjQi));
  }

?>
