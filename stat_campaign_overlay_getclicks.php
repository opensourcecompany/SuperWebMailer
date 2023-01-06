<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  if(empty($_GET["link_id"])){
    _JLEQF("MISSING PARAMETERS", 1);
    exit;
  }

  $_I1OoI = explode("_", $_GET["link_id"]);
  if(count($_I1OoI) < 5) {
    _JLEQF("INCORRECT PARAMETERS ".$_GET["link_id"], 1);
    exit;
  }
  $SendStatId = hexdec($_I1OoI[0]);
  $_jfIoi = hexdec($_I1OoI[1]);
  $ResponderType = hexdec($_I1OoI[2]);
  $ResponderId = hexdec($_I1OoI[3]);
  $_J10lj = hexdec($_I1OoI[4]);

  $_QLfol = "SELECT `CurrentSendTableName`, `LinksTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackingIPBlocking`, `TrackLinks`, `TrackLinksByRecipient` FROM `$_QLi60` WHERE `id`=$ResponderId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _JLEQF("EMAILING NOT FOUND ".$_GET["link_id"], 1);
    exit;
  } else {
    $_QLL16 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }

  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount FROM $_QLL16[TrackingLinksTableName] WHERE SendStat_id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  $_88iCj = $_QLO0f["ClicksCount"];
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount FROM $_QLL16[TrackingLinksTableName] WHERE SendStat_id=$SendStatId AND `Links_id`=$_J10lj";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1)
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
  else
   $_QLO0f["ClicksCount"] = 0;
  if(empty($_QLO0f["ClicksCount"]))
    $_QLO0f["ClicksCount"] = 0;
  if($_QL8i1)
    mysql_free_result($_QL8i1);

  SetHTMLHeaders($_QLo06);

  _JLEQF($_QLO0f["ClicksCount"], $_88iCj);

function _JLEQF($_QltJO, $_88L1i){
 global $resourcestrings, $INTERFACE_LANGUAGE;
 if(!$_88L1i) $_88L1i = 1;

print '

<div class="overlay_stat_frame">
  <div style="padding-left: 10px; font-family: Verdana, Arial, Helvetica, Sans-serif; color: #000; font-size: 16px; font-weight: bold; text-decoration: none">
    ' . $_QltJO . '
  </div>
  <div style="padding-left: 10px; font-family: Verdana, Arial, Helvetica, Sans-serif; color: #000; font-size: 10px; font-weight: normal; text-decoration: none">
    ' . sprintf("%1.2f%%", $_QltJO * 100 / $_88L1i)." ".$resourcestrings[$INTERFACE_LANGUAGE]["ClickRate"] . '
  </div>
</div>
';
}

?>
