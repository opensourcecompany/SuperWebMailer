<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
    _LLA1Q("MISSING PARAMETERS", 1);
    exit;
  }

  $_Q8otJ = explode("_", $_GET["link_id"]);
  if(count($_Q8otJ) < 5) {
    _LLA1Q("INCORRECT PARAMETERS ".$_GET["link_id"], 1);
    exit;
  }
  $SendStatId = hexdec($_Q8otJ[0]);
  $_Ii016 = hexdec($_Q8otJ[1]);
  $ResponderType = hexdec($_Q8otJ[2]);
  $ResponderId = hexdec($_Q8otJ[3]);
  $_jjJoO = hexdec($_Q8otJ[4]);

  $_QJlJ0 = "SELECT `CurrentSendTableName`, `LinksTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackingIPBlocking`, `TrackLinks`, `TrackLinksByRecipient` FROM `$_Q6jOo` WHERE `id`=$ResponderId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _LLA1Q("EMAILING NOT FOUND ".$_GET["link_id"], 1);
    exit;
  } else {
    $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }

  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount FROM $_Q6J0Q[TrackingLinksTableName] WHERE SendStat_id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  $_6l100 = $_Q6Q1C["ClicksCount"];
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount FROM $_Q6J0Q[TrackingLinksTableName] WHERE SendStat_id=$SendStatId AND `Links_id`=$_jjJoO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1)
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  else
   $_Q6Q1C["ClicksCount"] = 0;
  if(empty($_Q6Q1C["ClicksCount"]))
    $_Q6Q1C["ClicksCount"] = 0;
  if($_Q60l1)
    mysql_free_result($_Q60l1);

  SetHTMLHeaders($_Q6QQL);

  _LLA1Q($_Q6Q1C["ClicksCount"], $_6l100);

function _LLA1Q($_Q6ClO, $_6l1l1){
 global $resourcestrings, $INTERFACE_LANGUAGE;
 if(!$_6l1l1) $_6l1l1 = 1;

print '

<div class="overlay_stat_frame">
  <div style="padding-left: 10px; font-family: Verdana, Arial, Helvetica, Sans-serif; color: #000; font-size: 16px; font-weight: bold; text-decoration: none">
    ' . $_Q6ClO . '
  </div>
  <div style="padding-left: 10px; font-family: Verdana, Arial, Helvetica, Sans-serif; color: #000; font-size: 10px; font-weight: normal; text-decoration: none">
    ' . sprintf("%1.2f%%", $_Q6ClO * 100 / $_6l1l1)." ".$resourcestrings[$INTERFACE_LANGUAGE]["ClickRate"] . '
  </div>
</div>
';
}

?>
