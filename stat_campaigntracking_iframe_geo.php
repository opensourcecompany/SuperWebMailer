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

  if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"]))
     $_QJlJ0 = "SELECT TrackingOpeningsTableName FROM $_Q6jOo WHERE $_Q6jOo.id=$_I6lOO";
     else
     $_QJlJ0 = "SELECT TrackingLinksTableName FROM $_Q6jOo WHERE $_Q6jOo.id=$_I6lOO";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6J0Q = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);

  $_ICLO8 = $_Q6J0Q[0];

  if(!isset($_GET["Card"]))
     if(!isset($_POST["Card"]))
        $_GET["Card"] = "world";
        else
        $_GET["Card"] = $_POST["Card"];


  $_JliOC = false;
 // earth
  if($_GET["Card"] == "europe") {
     $_JlL6L = imagecreatefromjpeg("./geoip/europe.jpg");
     $_JlLlC = 81.02;
     $_JllOJ = 71.881;
     $_600tI = 32.673;
     $_601ji = -26.365;
     }
     else
     if($_GET["Card"] == "germany"){
        $_JlL6L = imagecreatefromjpeg("./geoip/germany.jpg");
        $_JlLlC = 55.155;
        $_JllOJ = 15.96;
        $_600tI = 46.94;
        $_601ji = 5.43;
        }
        else {
          $_JlL6L = imagecreatefromjpeg("./geoip/earth.jpg");
          $_JliOC = true;
          $_JlLlC = 0;
          $_600tI = 180;
          $_JllOJ = 90;
          $_601ji = -90;
        }
  $_6016i = imagesx($_JlL6L);
  $_601il = imagesy($_JlL6L);
  $_60Qit = (($_601il/($_JlLlC-$_600tI)));
  $_60QL0 = (($_6016i/($_JllOJ-$_601ji)));

 // red flag
  $_60Ioi = imagecreatefrompng("./geoip/point_red.png");
  $_60j11 = imagesx($_60Ioi);
  $_60j18 = imagesy($_60Ioi);

 // blue flag
  $_60jLI = imagecreatefrompng("./geoip/point_blue.png");
  $_60J6J = imagesx($_60jLI);
  $_606j1 = imagesy($_60jLI);

  $_j6Qlo = new _OCB1C('./geoip/');

  if(!$_j6Qlo->Openable()) {
     print $resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"];
     exit;
  }

  $_60fQ6 = imagecolorallocate($_JlL6L, 0,0,0);
  $_60ffi = imagecolorallocate($_JlL6L, 255,255,255);
  $_60f8l = imagecolorallocate($_JlL6L, 255,0,0);
  $_60fLJ = imagecolorallocate($_JlL6L, 0,0,255);
  $_608ij = imagecolorallocate($_JlL6L, 255,255,0);


 # own IP = red flag
  $_J6t8o = $_j6Qlo->lookupLocation(getOwnIP());
  if($_J6t8o != null)
     if($_JliOC)
        $_60t6i = _OF8BO($_J6t8o->latitude, $_J6t8o->longitude, $_6016i, $_601il);
        else{
           $_60t6i["x"] = round((($_J6t8o->longitude - $_601ji) * $_60QL0));
           $_60t6i["y"] = round((($_JlLlC - $_J6t8o->latitude) * $_60Qit));
        }
     else {
       $_60t6i["x"] = $_60j11 * -1;
       $_60t6i["y"] = $_60j18 * -1;
     }
  $_J6t8o = null;

 // red flag with own IP
  $_60tt6 = imagecreatefrompng("./geoip/block_red.png");
  $_60tOi = imagesx($_60tt6);
  $_60OOo = imagesy($_60tt6);

  imagecopy($_JlL6L, $_60tt6, $_60t6i["x"] - round($_60tOi / 2), $_60t6i["y"] - ($_60OOo / 2), 0, 0, $_60tOi, $_60OOo);

  $_QJlJ0 = "SELECT DISTINCT IP FROM $_ICLO8 WHERE SendStat_id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  $_60Oll = imagecreatefrompng("./geoip/block_blue.png");
  $_60tOi = imagesx($_60Oll);
  $_60OOo = imagesy($_60Oll);

  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_J6t8o = $_j6Qlo->lookupLocation($_Q6Q1C["IP"]);
    if($_J6t8o == null ) {
      continue;
    }
    if($_JliOC)
      $_60t6i = _OF8BO($_J6t8o->latitude, $_J6t8o->longitude, $_6016i, $_601il);
      else{
        $_60t6i["x"] = round((($_J6t8o->longitude - $_601ji) * $_60QL0));
        $_60t6i["y"] = round((($_JlLlC - $_J6t8o->latitude) * $_60Qit));
      }
    $_J6t8o = null;
    // blue flag
    imagecopy($_JlL6L, $_60Oll, $_60t6i["x"] - round($_60tOi / 2), $_60t6i["y"] - ($_60OOo / 2), 0, 0, $_60tOi, $_60OOo);
  }
  mysql_free_result($_Q60l1);


  if($_JliOC) {
   // Legend left
   # imagestring($_JlL6L, 3, 12, $_601il - 18, unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoConfirmedSubscribtions"], "iso-8859-1"), $_60fLJ);

   // Legend "middle"
    $_QllO8 = $_6016i / 3;
    imagecopy($_JlL6L, $_60Ioi, $_QllO8 - round($_60j11 / 2), $_601il - 14, 0, 0, $_60j11, $_60j18);
    imagestring($_JlL6L, 3, $_QllO8 + $_60j11 + 2, $_601il - 18, unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoOwnStation"],"iso-8859-1" ), $_60fQ6);

   // Legend "right"
    $_QllO8 = ($_6016i / 3) * 2;
    imagecopy($_JlL6L, $_60jLI, $_QllO8 - round($_60J6J / 2), $_601il - 14, 0, 0, $_60J6J, $_606j1);

    if(isset($_GET["ShowOpenings"]) || isset($_POST["ShowOpenings"]))
       $_QJCJi = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfOpener"], "iso-8859-1");
       else
       $_QJCJi = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfClick"], "iso-8859-1");
    imagestring($_JlL6L, 3, $_QllO8 + $_60J6J + 2, $_601il - 18, $_QJCJi, $_60fQ6);
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

  imagejpeg($_JlL6L);




  function _OF8BO($_60oCo, $_60C06, $_JCilL, $_60CfC)
  {
    $_QllO8 = (($_60C06 + 180) * ($_JCilL / 360));
    $_Io0l8 = ((($_60oCo * -1) + 90) * ($_60CfC / 180));
    return array("x"=>round($_QllO8),"y"=>round($_Io0l8));
  }

?>
