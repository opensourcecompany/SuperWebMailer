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

  include_once("config.inc.php");
  include_once("browserdetect.inc.php");
  include_once("countrydetect.inc.php");

  if(!isset($_GET["link"])) {
    _LQJ1R();
    exit;
  }

  $_I1OoI = explode("_", $_GET["link"]);
  if(count($_I1OoI) < 5) {
    _LQJ1R();
    exit;
  }
  $_jfQLo = hexdec($_I1OoI[0]);
  $_jfIoi = hexdec($_I1OoI[1]);
  $ResponderType = hexdec($_I1OoI[2]);
  $ResponderId = hexdec($_I1OoI[3]);
  $_jfj1I = 0;

  if($_I1OoI[4][0] == "x" && hexdec(substr($_I1OoI[4], 1))) {
     $_jfj1I = hexdec(substr($_I1OoI[4], 1));
     $_Qli6J=5;
    }
    else
     $_Qli6J=4; // without x<form_id>

  $_J10lj = hexdec($_I1OoI[$_Qli6J]);
  $_Qli6J++;

  $_6l1fl = 0;
  if (count($_I1OoI) > $_Qli6J) {
    $_I016j=explode("-", $_I1OoI[$_Qli6J]);
    $_6l1fl = hexdec($_I016j[0]);
  }

  $REMOTE_ADDR = getOwnIP();
  $_6lQQi = getOwnIP(false);
  $_6lQL8 = false;

  $_jfJJ0 = _LPOD8($ResponderType);

  if($_jfJJ0 == "") {
   _LQJ1R();
   exit;
  }

  $_QLfol = "SELECT `$_jfJJ0`, `Language` FROM `$_I18lo` WHERE `id`=$_jfIoi";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _LQJ1R();
    exit;
  } else {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_jfJJ0 = $_QLO0f[0];
    mysql_free_result($_QL8i1);
    _LRPQ6($_QLO0f[1]);
    _JQRLR($_QLO0f[1]);
  }

  if($ResponderType == 3) { // FollowUpMailsTableName

    if(isset($_6lIIJ))
      unset($_6lIIJ);

    $_QLfol = "SELECT `FUMailsTableName`, `TrackingIPBlocking`, `TrackLinks`, `TrackLinksByRecipient` FROM `$_jfJJ0` WHERE `id`=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
      _LQJ1R();
      exit;
    } else {
      $_6lIIJ = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }

    $_QLfol = "SELECT `LinksTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_6lIIJ[FUMailsTableName]` WHERE `id`=$_jfQLo";
  }
  else
    $_QLfol = "SELECT `LinksTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackingIPBlocking`, `TrackLinks`, `TrackLinksByRecipient` FROM `$_jfJJ0` WHERE `id`=$ResponderId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _LQJ1R();
    exit;
  } else {
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if(isset($_6lIIJ))
      $_QLO0f = array_merge($_QLO0f, $_6lIIJ);
  }

  if($_SERVER['REQUEST_METHOD'] != "HEAD" && $_jfQLo != 0) { # 0 = test email

    _LQ6C1($_JQjlt, $_6ljQ1);

    // Useragent
    $_QLfol = "SELECT `SendStat_id` FROM `$_QLO0f[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$_jfQLo AND `IP`="._LRAFO($REMOTE_ADDR)." AND `UserAgent`="._LRAFO($_JQjlt)." LIMIT 0, 1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) == 0) {
         $_QLfol = "INSERT INTO `$_QLO0f[TrackingUserAgentsTableName]` SET `SendStat_id`=$_jfQLo, `ADateTime`=NOW(), `UserAgent`="._LRAFO($_JQjlt).", `IP`="._LRAFO($REMOTE_ADDR);
         mysql_query($_QLfol, $_QLttI);
    } else{
      // we use IP blocking
    }
    mysql_free_result($_QL8i1);

    // OS
    $_QLfol = "SELECT `SendStat_id` FROM `$_QLO0f[TrackingOSsTableName]` WHERE `SendStat_id`=$_jfQLo AND `IP`="._LRAFO($REMOTE_ADDR)." AND `OS`="._LRAFO($_6ljQ1)." LIMIT 0, 1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) == 0) {
         $_QLfol = "INSERT INTO `$_QLO0f[TrackingOSsTableName]` SET `SendStat_id`=$_jfQLo, `ADateTime`=NOW(), `OS`="._LRAFO($_6ljQ1).", `IP`="._LRAFO($REMOTE_ADDR);
         mysql_query($_QLfol, $_QLttI);
    } else{
      // we use IP blocking
    }
    mysql_free_result($_QL8i1);

    if($_QLO0f["TrackLinks"]) {
      $_6lQL8 = $_QLO0f["TrackingIPBlocking"];
      if(!$_QLO0f["TrackingIPBlocking"]) { # no IP blocking
         $_QLfol = "INSERT INTO `$_QLO0f[TrackingLinksTableName]` SET `SendStat_id`=$_jfQLo, `Links_id`=$_J10lj, `ADateTime`=NOW(), `IP`="._LRAFO($REMOTE_ADDR).", `Country`="._LRAFO(GetCountryFromIP($_6lQQi));
         mysql_query($_QLfol, $_QLttI);
      } else{
         $_QLfol = "SELECT `SendStat_id` FROM `$_QLO0f[TrackingLinksTableName]` WHERE `SendStat_id`=$_jfQLo AND `Links_id`=$_J10lj AND `IP`="._LRAFO($REMOTE_ADDR)." LIMIT 0, 1";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_QL8i1) == 0) {
           $_QLfol = "INSERT INTO `$_QLO0f[TrackingLinksTableName]` SET `SendStat_id`=$_jfQLo, `Links_id`=$_J10lj, `ADateTime`=NOW(), `IP`="._LRAFO($REMOTE_ADDR).", `Country`="._LRAFO(GetCountryFromIP($_6lQQi));
           mysql_query($_QLfol, $_QLttI);
         }
         mysql_free_result($_QL8i1);
      }
    }


    if($_QLO0f["TrackLinksByRecipient"] && $_6l1fl) {
       $_QLfol = "UPDATE `$_QLO0f[TrackingLinksByRecipientTableName]` SET `Clicks`=`Clicks` + 1, `ADateTime`=NOW() WHERE `SendStat_id`=$_jfQLo AND `Links_id`=$_J10lj AND `Member_id`=$_6l1fl";
       mysql_query($_QLfol, $_QLttI);
       if(mysql_affected_rows($_QLttI) == 0) {
          $_QLfol = "INSERT INTO `$_QLO0f[TrackingLinksByRecipientTableName]` SET `SendStat_id`=$_jfQLo, `Links_id`=$_J10lj, `ADateTime`=NOW(), `Member_id`=$_6l1fl";
          mysql_query($_QLfol, $_QLttI);
       }
    }
  }

  $_QLfol = "SELECT `Link` FROM `$_QLO0f[LinksTableName]` WHERE `id`=$_J10lj";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _LQJ1R();
    exit;
  }

  $_QLO0f = mysql_fetch_row($_QL8i1);
  $link = unhtmlentities($_QLO0f[0], $_QLo06);

  $_I08CQ = "";
  if(strpos($link, "?") !== false)
    $_I08CQ = substr($link, strpos($link, "?") + 1);
  if($_I08CQ != ""){
    $_QlOjt = explode("&", $_I08CQ);
    reset($_GET);
    foreach($_GET as $key => $_QltJO){
       if($key == "link") continue;
       for($_Qli6J=0; $_Qli6J<count($_QlOjt); $_Qli6J++){
          if(strpos($_QlOjt[$_Qli6J], $key."=") === false) continue;
          $_I016j = explode("=", $_QlOjt[$_Qli6J]);
          if(count($_I016j) < 2) continue;
          if( strpos($_I016j[1], "[") !== false && strpos($_I016j[1], "]") !== false ) {
             $link = str_replace($_QlOjt[$_Qli6J], "$key=$_QltJO", $link);
          }
       }
    }
  }

  // GoogleAnalytics?
  if(!empty($_GET["utm_source"])) {
    $_I08CQ = array();
    if( !empty($_GET["utm_source"]) )
       $_I08CQ[] = "utm_source=".$_GET["utm_source"];
    if( !empty($_GET["utm_medium"]) )
       $_I08CQ[] = "utm_medium=".$_GET["utm_medium"];
    if( !empty($_GET["utm_term"]) )
       $_I08CQ[] = "utm_term=".$_GET["utm_term"];
    if( !empty($_GET["utm_content"]) )
       $_I08CQ[] = "utm_content=".$_GET["utm_content"];
    if( !empty($_GET["utm_campaign"]) )
       $_I08CQ[] = "utm_campaign=".$_GET["utm_campaign"];
    if(count($_I08CQ) > 0) {
      # anchor in link
      $_6ljfL = "";
      if(strpos($link, "#") !== false){
        $_6ljfL = substr($link, strpos($link, "#"));
        if(strpos($_6ljfL, "?") === false && strpos($_6ljfL, "&") === false)
           $link = substr($link, 0, strpos($link, "#") );
           else
           $_6ljfL = "";
      }

      if(strpos($link, "?") === false)
        $link .= "?".join("&", $_I08CQ).$_6ljfL;
        else
        $link .= "&".join("&", $_I08CQ).$_6ljfL;
    }
  }

  header ("Location: ".str_replace('&amp;', '&', $link));
  print '<p>Das angeforderte Dokument befindet sich nun hier: <a href="'.str_replace('&amp;', '&', $link).'">'.$link.'</a></p>';
  print '<p>The requested document has moved to: <a href="'.str_replace('&amp;', '&', $link).'">'.$link.'</a></p>';

  # track openings => image blocking
  if($_6lQL8) {
    define("LinkStat_PHP", "1");
    include_once("ostat.php");
  }

  function _LQJ1R() {
    print '<p>Link nicht gefunden!</p>';
    print '<p>Link not found!</p>';
    exit;
  }

?>
