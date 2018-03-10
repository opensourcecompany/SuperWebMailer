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
    _L0AJC();
    exit;
  }

  $_Q8otJ = explode("_", $_GET["link"]);
  if(count($_Q8otJ) < 4) {
    _L0AJC();
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

  $_JO01o = 0;
  if(!defined("LinkStat_PHP")) {
    if (count($_Q8otJ) > $_Q6llo) {
      $_QllO8=explode("-", $_Q8otJ[$_Q6llo]);
      $_JO01o = hexdec($_QllO8[0]);
    }
  } else{ // in link
    $_Q6llo++;
    if (count($_Q8otJ) > $_Q6llo) {
      $_QllO8=explode("-", $_Q8otJ[$_Q6llo]);
      $_JO01o = hexdec($_QllO8[0]);
    }
  }

  $REMOTE_ADDR = getOwnIP();

  $_IiQl1 = _OAAP1($ResponderType);

  if($_IiQl1 == "") {
   _L0AJC();
   exit;
  }

  $_QJlJ0 = "SELECT `$_IiQl1`, `Language` FROM `$_Q8f1L` WHERE `id`=$_Ii016";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _L0AJC();
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

    $_QJlJ0 = "SELECT `FUMailsTableName`, `TrackingIPBlocking`, `TrackEMailOpenings`, `TrackEMailOpeningsByRecipient` FROM `$_IiQl1` WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
      _OJLLQ();
      exit;
    } else {
      $_JO1o8 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }

    $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackEMailOpeningsImageURL`, `TrackEMailOpeningsByRecipientImageURL` FROM `$_JO1o8[FUMailsTableName]` WHERE id=$_ICltC";
  }
  else
    $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName`, `TrackingIPBlocking`, `TrackEMailOpenings`, `TrackEMailOpeningsByRecipient`, `TrackEMailOpeningsImageURL`, `TrackEMailOpeningsByRecipientImageURL` FROM `$_IiQl1` WHERE id=$ResponderId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _L0AJC();
    exit;
  } else {
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if(isset($_JO1o8))
      $_Q6Q1C = array_merge($_Q6Q1C, $_JO1o8);
  }

  $_IOOit = "";

  if ( $_SERVER['REQUEST_METHOD'] != "HEAD" && $_ICltC != 0 ) { # 0 = test email

     if(!defined("LinkStat_PHP")) {
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

    }

    if($_Q6Q1C["TrackEMailOpenings"]) {
      $_IOOit = $_Q6Q1C["TrackEMailOpeningsImageURL"];
      if(!$_Q6Q1C["TrackingIPBlocking"]) { # no IP blocking
         $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingOpeningsTableName]` SET `SendStat_id`=$_ICltC, `ADateTime`=NOW(), `IP`="._OPQLR($REMOTE_ADDR).", `Country`="._OPQLR(GetCountryFromIP($REMOTE_ADDR));
         mysql_query($_QJlJ0, $_Q61I1);
      } else{
         $_QJlJ0 = "SELECT `SendStat_id` FROM `$_Q6Q1C[TrackingOpeningsTableName]` WHERE `SendStat_id`=$_ICltC AND `IP`="._OPQLR($REMOTE_ADDR)." LIMIT 0, 1";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q60l1) == 0) {
           $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingOpeningsTableName]` SET `SendStat_id`=$_ICltC, `ADateTime`=NOW(), `IP`="._OPQLR($REMOTE_ADDR).", `Country`="._OPQLR(GetCountryFromIP($REMOTE_ADDR));
           mysql_query($_QJlJ0, $_Q61I1);
         }
         mysql_free_result($_Q60l1);
      }
    }

    if($_Q6Q1C["TrackEMailOpeningsByRecipient"] && $_JO01o) {
       if($_Q6Q1C["TrackEMailOpeningsByRecipientImageURL"] != "")
          $_IOOit = $_Q6Q1C["TrackEMailOpeningsByRecipientImageURL"];
       $_QJlJ0 = "INSERT INTO `$_Q6Q1C[TrackingOpeningsByRecipientTableName]` SET `SendStat_id`=$_ICltC, `ADateTime`=NOW(), `Member_id`=$_JO01o";
       mysql_query($_QJlJ0, $_Q61I1);
    }

  }

  // simulate image for test emails
  if ( $_SERVER['REQUEST_METHOD'] != "HEAD" && $_ICltC == 0 ) { # 0 = test email
    if($_Q6Q1C["TrackEMailOpenings"]) {
      $_IOOit = $_Q6Q1C["TrackEMailOpeningsImageURL"];
    }
    if($_Q6Q1C["TrackEMailOpeningsByRecipient"] && $_JO01o) {
       if($_Q6Q1C["TrackEMailOpeningsByRecipientImageURL"] != "")
          $_IOOit = $_Q6Q1C["TrackEMailOpeningsByRecipientImageURL"];
    }
  }

  if(!defined("LinkStat_PHP")) { # comes from link.php
    if($_IOOit == "")
     _L0AJC();
     else
     header ("Location: $_IOOit");
  }

  function _L0AJC() {
    if(!defined("LinkStat_PHP")) { # comes from link.php
       if(!ini_get("allow_url_fopen"))
          header ("Location: ".ScriptBaseURL."images/blind.gif");
          else{
            $_QCioi = fopen("./images/blind.gif", "rb");
            if($_QCioi) {
              $_6JQI6 = fread($_QCioi, filesize("./images/blind.gif"));
              fclose($_QCioi);

              header("Content-type: image/gif");
              header('Expires: 0');
              header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
              header('Pragma: no-cache');
              print $_6JQI6;

            } else
               header ("Location: ".ScriptBaseURL."images/blind.gif");
          }
    }
  }
?>
