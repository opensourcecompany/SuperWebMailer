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
    _J0AAE();
    exit;
  }

  $_I1OoI = explode("_", $_GET["link"]);
  if(count($_I1OoI) < 4) {
    _J0AAE();
    exit;
  }
  $_jfQLo = hexdec($_I1OoI[0]);
  $_jfIoi = hexdec($_I1OoI[1]);
  $ResponderType = hexdec($_I1OoI[2]);
  $ResponderId = hexdec($_I1OoI[3]);

  $_jfj1I = 0;

  if(count($_I1OoI) > 4 && $_I1OoI[4][0] == "x" && hexdec(substr($_I1OoI[4], 1))) {
     $_jfj1I = hexdec(substr($_I1OoI[4], 1));
     $_Qli6J=5;
    }
    else
     $_Qli6J=4; // without x<form_id>

  $_6l1fl = 0;
  if(!defined("LinkStat_PHP")) {
    if (count($_I1OoI) > $_Qli6J) {
      $_I016j=explode("-", $_I1OoI[$_Qli6J]);
      $_6l1fl = hexdec($_I016j[0]);
    }
  } else{ // in link
    $_Qli6J++;
    if (count($_I1OoI) > $_Qli6J) {
      $_I016j=explode("-", $_I1OoI[$_Qli6J]);
      $_6l1fl = hexdec($_I016j[0]);
    }
  }

  $REMOTE_ADDR = getOwnIP();
  $_6lQQi = getOwnIP(false);

  $_jfJJ0 = _LPOD8($ResponderType);

  if($_jfJJ0 == "") {
   _J0AAE();
   exit;
  }

  $_QLfol = "SELECT `$_jfJJ0`, `Language` FROM `$_I18lo` WHERE `id`=$_jfIoi";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _J0AAE();
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

    $_QLfol = "SELECT `FUMailsTableName`, `TrackingIPBlocking`, `TrackEMailOpenings`, `TrackEMailOpeningsByRecipient` FROM `$_jfJJ0` WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
      _LQJ1R();
      exit;
    } else {
      $_6lIIJ = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }

    $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackEMailOpeningsImageURL`, `TrackEMailOpeningsByRecipientImageURL` FROM `$_6lIIJ[FUMailsTableName]` WHERE id=$_jfQLo";
  }
  else
    $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackingIPBlocking`, `TrackEMailOpenings`, `TrackEMailOpeningsByRecipient`, `TrackEMailOpeningsImageURL`, `TrackEMailOpeningsByRecipientImageURL` FROM `$_jfJJ0` WHERE id=$ResponderId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _J0AAE();
    exit;
  } else {
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if(isset($_6lIIJ))
      $_QLO0f = array_merge($_QLO0f, $_6lIIJ);
  }

  $_jjllL = "";

  if ( $_SERVER['REQUEST_METHOD'] != "HEAD" && $_jfQLo != 0 ) { # 0 = test email

     if(!defined("LinkStat_PHP")) {
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

    }

    if($_QLO0f["TrackEMailOpenings"]) {
      $_jjllL = $_QLO0f["TrackEMailOpeningsImageURL"];
      if(!$_QLO0f["TrackingIPBlocking"]) { # no IP blocking
         $_QLfol = "INSERT INTO `$_QLO0f[TrackingOpeningsTableName]` SET `SendStat_id`=$_jfQLo, `ADateTime`=NOW(), `IP`="._LRAFO($REMOTE_ADDR).", `Country`="._LRAFO(GetCountryFromIP($_6lQQi));
         mysql_query($_QLfol, $_QLttI);
      } else{
         $_QLfol = "SELECT `SendStat_id` FROM `$_QLO0f[TrackingOpeningsTableName]` WHERE `SendStat_id`=$_jfQLo AND `IP`="._LRAFO($REMOTE_ADDR)." LIMIT 0, 1";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_QL8i1) == 0) {
           $_QLfol = "INSERT INTO `$_QLO0f[TrackingOpeningsTableName]` SET `SendStat_id`=$_jfQLo, `ADateTime`=NOW(), `IP`="._LRAFO($REMOTE_ADDR).", `Country`="._LRAFO(GetCountryFromIP($_6lQQi));
           mysql_query($_QLfol, $_QLttI);
         }
         mysql_free_result($_QL8i1);
      }
    }

    if($_QLO0f["TrackEMailOpeningsByRecipient"] && $_6l1fl) {
       if($_QLO0f["TrackEMailOpeningsByRecipientImageURL"] != "")
          $_jjllL = $_QLO0f["TrackEMailOpeningsByRecipientImageURL"];
       $_QLfol = "INSERT INTO `$_QLO0f[TrackingOpeningsByRecipientTableName]` SET `SendStat_id`=$_jfQLo, `ADateTime`=NOW(), `Member_id`=$_6l1fl";
       mysql_query($_QLfol, $_QLttI);
    }

  }

  // simulate image for test emails
  if ( $_SERVER['REQUEST_METHOD'] != "HEAD" && $_jfQLo == 0 ) { # 0 = test email
    if($_QLO0f["TrackEMailOpenings"]) {
      $_jjllL = $_QLO0f["TrackEMailOpeningsImageURL"];
    }
    if($_QLO0f["TrackEMailOpeningsByRecipient"] && $_6l1fl) {
       if($_QLO0f["TrackEMailOpeningsByRecipientImageURL"] != "")
          $_jjllL = $_QLO0f["TrackEMailOpeningsByRecipientImageURL"];
    }
  }

  if(!defined("LinkStat_PHP")) { # comes from link.php
    if($_jjllL == "")
     _J0AAE();
     else
     header ("Location: $_jjllL");
  }

  function _J0AAE() {
    if(!defined("LinkStat_PHP")) { # comes from link.php
       if(!ini_get("allow_url_fopen"))
          header ("Location: ".ScriptBaseURL."images/blind.gif");
          else{
            $_I60fo = fopen("./images/blind.gif", "rb");
            if($_I60fo) {
              $_folit = fread($_I60fo, filesize("./images/blind.gif"));
              fclose($_I60fo);

              header("Content-type: image/gif");
              header('Expires: 0');
              header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
              header('Pragma: no-cache');
              print $_folit;

            } else
               header ("Location: ".ScriptBaseURL."images/blind.gif");
          }
    }
  }
?>
