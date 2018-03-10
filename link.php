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
  include_once("browserdetect.inc.php");
  include_once("countrydetect.inc.php");

  if(!isset($_GET["link"])) {
    _OJLLQ();
    exit;
  }

  $_Q8otJ = explode("_", $_GET["link"]);
  if(count($_Q8otJ) < 5) {
    _OJLLQ();
    exit;
  }
  $_ICltC = hexdec($_Q8otJ[0]);
  $_Ii016 = hexdec($_Q8otJ[1]);
  $ResponderType = hexdec($_Q8otJ[2]);
  $ResponderId = hexdec($_Q8otJ[3]);
  $_Ii0fC = 0;

  if($_Q8otJ[4]{0} == "x" && hexdec(substr($_Q8otJ[4], 1))) {
     $_Ii0fC = hexdec(substr($_Q8otJ[4], 1));
     $_Q6llo=5;
    }
    else
     $_Q6llo=4; // without x<form_id>

  $_jjJoO = hexdec($_Q8otJ[$_Q6llo]);
  $_Q6llo++;

  $_JO01o = 0;
  if (count($_Q8otJ) > $_Q6llo) {
    $_QllO8=explode("-", $_Q8otJ[$_Q6llo]);
    $_JO01o = hexdec($_QllO8[0]);
  }

  $REMOTE_ADDR = getOwnIP();
  $_JO0io = false;

  $_IiQl1 = _OAAP1($ResponderType);

  if($_IiQl1 == "") {
   _OJLLQ();
   exit;
  }

  $_QJlJ0 = "SELECT `$_IiQl1`, `Language` FROM `$_Q8f1L` WHERE `id`=$_Ii016";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _OJLLQ();
    exit;
  } else {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_IiQl1 = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);
    _OP10J($_Q6Q1C[1]);
    _LQLRQ($_Q6Q1C[1]);
  }

  if($ResponderType == 3) { // FollowUpMailsTableName

    if(isset($_JO1o8))
      unset($_JO1o8);

    $_QJlJ0 = "SELECT `FUMailsTableName`, `TrackingIPBlocking`, `TrackLinks`, `TrackLinksByRecipient` FROM `$_IiQl1` WHERE `id`=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
      _OJLLQ();
      exit;
    } else {
      $_JO1o8 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }

    $_QJlJ0 = "SELECT `LinksTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_JO1o8[FUMailsTableName]` WHERE `id`=$_ICltC";
  }
  else
    $_QJlJ0 = "SELECT `LinksTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackingIPBlocking`, `TrackLinks`, `TrackLinksByRecipient` FROM `$_IiQl1` WHERE `id`=$ResponderId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _OJLLQ();
    exit;
  } else {
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if(isset($_JO1o8))
      $_Q6Q1C = array_merge($_Q6Q1C, $_JO1o8);
  }

  if($_SERVER['REQUEST_METHOD'] != "HEAD" && $_ICltC != 0) { # 0 = test email

    _OJJJE($_jJtt0, $_JOQJ0);

    // Useragent
    $_QJlJ0 = "SELECT `SendStat_id` FROM `$_Q6Q1C[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$_ICltC AND `IP`="._OPQLR($REMOTE_ADDR)." AND `UserAgent`="._OPQLR($_jJtt0)." LIMIT 0, 1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) == 0) {
         $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingUserAgentsTableName]` SET `SendStat_id`=$_ICltC, `ADateTime`=NOW(), `UserAgent`="._OPQLR($_jJtt0).", `IP`="._OPQLR($REMOTE_ADDR);
         mysql_query($_QJlJ0, $_Q61I1);
    } else{
      // we use IP blocking
    }
    mysql_free_result($_Q60l1);

    // OS
    $_QJlJ0 = "SELECT `SendStat_id` FROM `$_Q6Q1C[TrackingOSsTableName]` WHERE `SendStat_id`=$_ICltC AND `IP`="._OPQLR($REMOTE_ADDR)." AND `OS`="._OPQLR($_JOQJ0)." LIMIT 0, 1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) == 0) {
         $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingOSsTableName]` SET `SendStat_id`=$_ICltC, `ADateTime`=NOW(), `OS`="._OPQLR($_JOQJ0).", `IP`="._OPQLR($REMOTE_ADDR);
         mysql_query($_QJlJ0, $_Q61I1);
    } else{
      // we use IP blocking
    }
    mysql_free_result($_Q60l1);

    if($_Q6Q1C["TrackLinks"]) {
      $_JO0io = $_Q6Q1C["TrackingIPBlocking"];
      if(!$_Q6Q1C["TrackingIPBlocking"]) { # no IP blocking
         $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingLinksTableName]` SET `SendStat_id`=$_ICltC, `Links_id`=$_jjJoO, `ADateTime`=NOW(), `IP`="._OPQLR($REMOTE_ADDR).", `Country`="._OPQLR(GetCountryFromIP($REMOTE_ADDR));
         mysql_query($_QJlJ0, $_Q61I1);
      } else{
         $_QJlJ0 = "SELECT `SendStat_id` FROM `$_Q6Q1C[TrackingLinksTableName]` WHERE `SendStat_id`=$_ICltC AND `Links_id`=$_jjJoO AND `IP`="._OPQLR($REMOTE_ADDR)." LIMIT 0, 1";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q60l1) == 0) {
           $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingLinksTableName]` SET `SendStat_id`=$_ICltC, `Links_id`=$_jjJoO, `ADateTime`=NOW(), `IP`="._OPQLR($REMOTE_ADDR).", `Country`="._OPQLR(GetCountryFromIP($REMOTE_ADDR));
           mysql_query($_QJlJ0, $_Q61I1);
         }
         mysql_free_result($_Q60l1);
      }
    }


    if($_Q6Q1C["TrackLinksByRecipient"] && $_JO01o) {
       $_QJlJ0 = "UPDATE `$_Q6Q1C[TrackingLinksByRecipientTableName]` SET `Clicks`=`Clicks` + 1, `ADateTime`=NOW() WHERE `SendStat_id`=$_ICltC AND `Links_id`=$_jjJoO AND `Member_id`=$_JO01o";
       mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_affected_rows($_Q61I1) == 0) {
          $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingLinksByRecipientTableName]` SET `SendStat_id`=$_ICltC, `Links_id`=$_jjJoO, `ADateTime`=NOW(), `Member_id`=$_JO01o";
          mysql_query($_QJlJ0, $_Q61I1);
       }
    }
  }

  $_QJlJ0 = "SELECT `Link` FROM `$_Q6Q1C[LinksTableName]` WHERE `id`=$_jjJoO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _OJLLQ();
    exit;
  }

  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  $link = unhtmlentities($_Q6Q1C[0], $_Q6QQL);

  $_QffOf = "";
  if(strpos($link, "?") !== false)
    $_QffOf = substr($link, strpos($link, "?") + 1);
  if($_QffOf != ""){
    $_Q6i6i = explode("&", $_QffOf);
    reset($_GET);
    foreach($_GET as $key => $_Q6ClO){
       if($key == "link") continue;
       for($_Q6llo=0; $_Q6llo<count($_Q6i6i); $_Q6llo++){
          if(strpos($_Q6i6i[$_Q6llo], $key."=") === false) continue;
          $_QllO8 = explode("=", $_Q6i6i[$_Q6llo]);
          if(count($_QllO8) < 2) continue;
          if( strpos($_QllO8[1], "[") !== false && strpos($_QllO8[1], "]") !== false ) {
             $link = str_replace($_Q6i6i[$_Q6llo], "$key=$_Q6ClO", $link);
          }
       }
    }
  }

  // GoogleAnalytics?
  if(!empty($_GET["utm_source"])) {
    $_QffOf = array();
    if( !empty($_GET["utm_source"]) )
       $_QffOf[] = "utm_source=".$_GET["utm_source"];
    if( !empty($_GET["utm_medium"]) )
       $_QffOf[] = "utm_medium=".$_GET["utm_medium"];
    if( !empty($_GET["utm_term"]) )
       $_QffOf[] = "utm_term=".$_GET["utm_term"];
    if( !empty($_GET["utm_content"]) )
       $_QffOf[] = "utm_content=".$_GET["utm_content"];
    if( !empty($_GET["utm_campaign"]) )
       $_QffOf[] = "utm_campaign=".$_GET["utm_campaign"];
    if(count($_QffOf) > 0) {
      # anchor in link
      $_JOQ8L = "";
      if(strpos($link, "#") !== false){
        $_JOQ8L = substr($link, strpos($link, "#"));
        if(strpos($_JOQ8L, "?") === false && strpos($_JOQ8L, "&") === false)
           $link = substr($link, 0, strpos($link, "#") );
           else
           $_JOQ8L = "";
      }

      if(strpos($link, "?") === false)
        $link .= "?".join("&", $_QffOf).$_JOQ8L;
        else
        $link .= "&".join("&", $_QffOf).$_JOQ8L;
    }
  }

  header ("Location: ".str_replace('&amp;', '&', $link));
  print '<p>Das angeforderte Dokument befindet sich nun hier: <a href="'.str_replace('&amp;', '&', $link).'">'.$link.'</a></p>';
  print '<p>The requested document has moved to: <a href="'.str_replace('&amp;', '&', $link).'">'.$link.'</a></p>';

  # track openings => image blocking
  if($_JO0io) {
    define("LinkStat_PHP", "1");
    include_once("ostat.php");
  }

  function _OJLLQ() {
    print '<p>Link nicht gefunden!</p>';
    print '<p>Link not found!</p>';
    exit;
  }

?>
