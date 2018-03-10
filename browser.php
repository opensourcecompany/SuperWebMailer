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
  include_once("mailcreate.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("rss2emailreplacements.inc.php");
  include_once("replacements.inc.php");

   //http://localhost/swm/browser.php?key=twitter-9-1&rid=1B_02_04_0C
  if(!isset($_GET["key"]) || !isset($_GET["rid"])) {
    _OJLLQ();
    exit;
  }

  if($_GET["rid"] == "smp") {
    _OJJOL();
    exit;
  }

  $_Q8otJ = explode("_", $_GET["rid"]);
  if(count($_Q8otJ) < 4) {
    _OJLLQ();
    exit;
  }
  $_ICltC = hexdec($_Q8otJ[0]);
  $_Ii016 = hexdec($_Q8otJ[1]);
  $ResponderType = hexdec($_Q8otJ[2]);
  $ResponderId = hexdec($_Q8otJ[3]);
  $_Ii0fC = 0;
  if(count($_Q8otJ) > 4){
     if($_Q8otJ[4]{0} == "x" && hexdec(substr($_Q8otJ[4], 1)))
       $_Ii0fC = hexdec(substr($_Q8otJ[4], 1));
      }
  $OwnerUserId = 0;
  $UserId = $_Ii016;

  if($_ICltC == 0 && $ResponderType == 6) { // DistributionList
    print '<p>Test-E-Mail, Text kann nicht angezeigt werden.</p>';
    print "<p>Test email, text can't be displayed.</p>";
    exit;
  }

  // load user

  $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$UserId";
  $_Ii16i = mysql_query($_QJlJ0, $_Q61I1);
  if($_Ii16i && mysql_num_rows($_Ii16i) > 0) {
    $_ICQQo = mysql_fetch_array($_Ii16i);

    $UserId = $_ICQQo["id"];
    $OwnerUserId = 0;
    $Username = $_ICQQo["Username"];
    $UserType = $_ICQQo["UserType"];
    $INTERFACE_THEMESID = $_ICQQo["ThemesId"];
    $INTERFACE_LANGUAGE = $_ICQQo["Language"];

    $_QJlJ0 = "SELECT Theme FROM $_Q880O WHERE id=$INTERFACE_THEMESID";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
    $INTERFACE_STYLE = $_Q8OiJ[0];
    mysql_free_result($_Q8Oj8);

    _OP0D0($_ICQQo);

    _OP0AF($UserId);

    _OP10J($INTERFACE_LANGUAGE);

    _LQLRQ($INTERFACE_LANGUAGE);

    mysql_free_result($_Ii16i);
  }

  //


  $_QLitI = 0;
  $MailingListId = 0;
  $FormId = 0;

  _OA8LE($_GET["key"], $_QLitI, $MailingListId, $FormId);

  // twitter starts with twitter
  if(strpos($_GET["key"], "twitter") !== false && strpos($_GET["key"], "twitter") == 0){
    $_QLitI = -1;
  }

  // social media
  if(strpos($_GET["key"], "sme") !== false && strpos($_GET["key"], "sme") == 0){
    $_QLitI = -1;
  }

  if($_QLitI == 0 || $MailingListId == 0 || $FormId == 0) {
    _OJLLQ();
    exit;
  }

  if($_Ii0fC == 0)
    $_Ii0fC = $FormId;


  $_IiQl1 = _OAAP1($ResponderType);

  if($_IiQl1 == "") {
   _OJLLQ();
   exit;
  }

  // Responder Type Name
  $ResponderType = _OAPCO($ResponderType);

  $_QJlJ0 = "SELECT $_IiQl1 FROM $_Q8f1L WHERE id=$_Ii016";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
    _OJLLQ();
    exit;
  } else {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_IiQl1 = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);
  }

  if($ResponderType == "Campaign") {
    if($_ICltC != 0) # no test mail
      $_QJlJ0 = "SELECT ArchiveTableName, LinksTableName, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackEMailOpeningsImageURL, TrackEMailOpeningsByRecipientImageURL, PersonalizeEMails FROM $_IiQl1 WHERE id=$ResponderId";
      else
      $_QJlJ0 = "SELECT * FROM $_IiQl1 WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
      _OJLLQ();
      exit;
    } else {
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      $_IiI8C = $_Q6Q1C["ArchiveTableName"];
      unset($_Q6Q1C["ArchiveTableName"]);
      $_IiICC = $_Q6Q1C;
      mysql_free_result($_Q60l1);
    }

    if($_ICltC != 0) { # no test mail
      $_QJlJ0 = "SELECT * FROM $_IiI8C WHERE SendStat_id=$_ICltC";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
        _OJLLQ();
        exit;
      } else {
        $_IiICC = array_merge(mysql_fetch_assoc($_Q60l1), $_IiICC);
        mysql_free_result($_Q60l1);
      }
    }
  }
   else if($ResponderType == "FollowUpResponder") {
     $_QJlJ0 = "SELECT `FUMailsTableName`, PersonalizeEMails, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackingIPBlocking FROM $_IiQl1 WHERE id=$ResponderId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
       _OJLLQ();
       exit;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       $_ItJIf = $_Q6Q1C["FUMailsTableName"];
       unset($_Q6Q1C["FUMailsTableName"]);
       $_IiICC = $_Q6Q1C;
       mysql_free_result($_Q60l1);
     }

     $_QJlJ0 = "SELECT * FROM $_ItJIf WHERE id=$_ICltC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
       _OJLLQ();
       exit;
     } else {
       $_IiICC = array_merge(mysql_fetch_assoc($_Q60l1), $_IiICC);
       mysql_free_result($_Q60l1);
     }

   } else if($ResponderType == "BirthdayResponder") {
     $_QJlJ0 = "SELECT * FROM $_IiQl1 WHERE id=$ResponderId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
       _OJLLQ();
       exit;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       $_IiICC = $_Q6Q1C;
       mysql_free_result($_Q60l1);
     }
   } else if($ResponderType == "RSS2EMailResponder") {
     $_QJlJ0 = "SELECT * FROM $_IiQl1 WHERE id=$ResponderId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
       _OJLLQ();
       exit;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       $_IiICC = $_Q6Q1C;
       mysql_free_result($_Q60l1);
     }
   } else
  if($ResponderType == "DistributionList") {
    if($_ICltC != 0) # no test mail
      $_QJlJ0 = "SELECT `CurrentSendTableName`, LinksTableName, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackEMailOpeningsImageURL, TrackEMailOpeningsByRecipientImageURL, PersonalizeEMails FROM $_IiQl1 WHERE id=$ResponderId";
      else
      $_QJlJ0 = "SELECT * FROM $_IiQl1 WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
      _OJLLQ();
      exit;
    } else {
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      $_IiICC = $_Q6Q1C;
      mysql_free_result($_Q60l1);
    }

    if($_ICltC != 0) { # no test mail
      $_QJlJ0 = "SELECT `$_Qoo8o`.* FROM `$_Qoo8o` LEFT JOIN `$_IiICC[CurrentSendTableName]` ON `$_IiICC[CurrentSendTableName]`.`distriblistentry_id`=`$_Qoo8o`.`id` WHERE `$_IiICC[CurrentSendTableName]`.`id`=$_ICltC";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
        _OJLLQ();
        exit;
      } else {
        $_Iij08 = mysql_fetch_assoc($_Q60l1);

        if(substr($_Iij08["MailPlainText"], 0, 4) == "xb64"){
           $_Iij08["MailPlainText"] = base64_decode( substr($_Iij08["MailPlainText"], 4) );
        }

        if(substr($_Iij08["MailHTMLText"], 0, 4) == "xb64"){
           $_Iij08["MailHTMLText"] = base64_decode( substr($_Iij08["MailHTMLText"], 4) );
        }

        $_IiICC["UseInternalText"] = $_Iij08["UseInternalText"];
        $_IiICC["ExternalTextURL"] = $_Iij08["ExternalTextURL"];

        $_IiICC["MailFormat"] = $_Iij08["MailFormat"];
        $_IiICC["MailPriority"] = $_Iij08["MailPriority"];
        $_IiICC["MailEncoding"] = $_Iij08["MailEncoding"];
        $_IiICC["MailSubject"] = $_Iij08["MailSubject"];
        $_IiICC["MailPlainText"] = $_Iij08["MailPlainText"];
        $_IiICC["MailHTMLText"] = $_Iij08["MailHTMLText"];
        $_IiICC["Attachments"] = $_Iij08["Attachments"];

        mysql_free_result($_Q60l1);
        unset($_IiICC["CurrentSendTableName"]);
      }
    }
  }


  $_QJlJ0 = "SELECT MaillistTableName, FormsTableName FROM $_Q60QL WHERE id=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    _OJLLQ();
    exit;
  } else {
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
  }
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  $_IiICC = array_merge($_Q6Q1C, $_IiICC);

  $_QJlJ0 = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_Q6Q1C[FormsTableName]` WHERE id=$_Ii0fC";
  $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_IOOt1) {
    $_QlftL = mysql_fetch_assoc($_IOOt1);
    $_Iijft = $_QlftL["OverrideSubUnsubURL"];
    $_IijO6 = $_QlftL["OverrideTrackingURL"];
    mysql_free_result($_IOOt1);
  } else{
    $_Iijft = "";
    $_IijO6 = "";
  }

  $_QJlJ0 = "";
  $_IOQf6 = "`$_QlQC8`.`id`=$_QLitI AND `$_QlQC8`.`IdentString`="._OPQLR($_GET["key"]);
  switch ($ResponderType) {
    case "BirthdayResponder":
      $_Iijl0 =
          'TO_DAYS(

              DATE_ADD(
             `u_Birthday`,
                  INTERVAL
                    (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                      IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
             )
                    YEAR)
             )
             -
             TO_DAYS( CURRENT_DATE() )
             AS `Days_to_Birthday`';

      $_QJlJ0 = "SELECT `$_QlQC8`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge, $_Iijl0 FROM `$_QlQC8` WHERE $_IOQf6";
    break;
    case "Campaign":
      $_QJlJ0 = "SELECT `$_QlQC8`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge FROM `$_QlQC8` WHERE $_IOQf6";
    break;
    case "DistributionList":
      $_QJlJ0 = "SELECT `$_QlQC8`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge FROM `$_QlQC8` WHERE $_IOQf6";
    break;
    default:
      $_QJlJ0 = "SELECT `$_QlQC8`.* FROM `$_QlQC8` WHERE $_IOQf6";
  }

  if($_QJlJ0 == "") {
    _OJLLQ();
    exit;
  }

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    // recipient removed
    $_QLLjo = array();
    _OA60P($_QlQC8, $_QLLjo, array());
    reset($_QLLjo);
    foreach($_QLLjo as $key => $_Q6ClO) {
      $_IiJo8[$key] = "";
    }
    $_IiJo8["u_Gender"] = "undefined";
    $_IiJo8["MembersAge"] = "";

  } else {
    $_IiJo8 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }

  // mail class
  $_IiJit = new _OF0EE(mtAltBrowserLinkMail);
  $errors = array();
  $_Ql1O8 = array();
  $_Ii6QI = "";
  $_Ii6lO = "";
  $_IiICC["AltBrowserLink"] = 1;
  $_IiICC["CurrentSendId"] = $_ICltC;
  $_IiICC["SenderFromAddress"] = "";
  $_IiICC["SenderFromName"] = "";
  $_IiICC["ReplyToEMailAddress"] = "";
  $_IiICC["ReturnPathEMailAddress"] = "";
  $_IiICC["CcEMailAddresses"] = "";
  $_IiICC["BCcEMailAddresses"] = "";
  $_IiICC["ReturnReceipt"] = 0;
  $_IiICC["OverrideSubUnsubURL"] = $_Iijft;
  $_IiICC["OverrideTrackingURL"] = $_IijO6;
  // recipients groups for unsubscribe link
  if(!empty($_GET["RG"]))
     $_IiICC["GroupIds"] = $_GET["RG"];
  // overlay
  if(!empty($_GET["Overlay"])){
    // overlay ever utf-8
    $_IiICC["MailEncoding"] = "utf-8";
    // don't count openings
    $_IiICC["TrackEMailOpenings"] = 0;
    $_IiICC["TrackEMailOpeningsByRecipient"] = 0;
    // replace all internal placeholders
    reset($_IifjO);
    foreach($_IifjO as $key){
      $_IiICC["MailSubject"] = str_replace("[$key]", "", $_IiICC["MailSubject"]);
      $_IiICC["MailPlainText"] = str_replace("[$key]", "", $_IiICC["MailPlainText"]);
      $_IiICC["MailHTMLText"] = str_replace("[$key]", "", $_IiICC["MailHTMLText"]);
    }
  }

  // GoogleAnalytics
  if(!empty($_GET["utm_source"])) {
    $_IiICC["GoogleAnalyticsActive"] = 1;
    $_IiICC["GoogleAnalytics_utm_source"] = !empty($_GET["utm_source"]) ? $_GET["utm_source"] : "";
    $_IiICC["GoogleAnalytics_utm_medium"] = !empty($_GET["utm_medium"]) ? $_GET["utm_medium"] : "";
    $_IiICC["GoogleAnalytics_utm_term"] = !empty($_GET["utm_term"]) ? $_GET["utm_term"] : "";
    $_IiICC["GoogleAnalytics_utm_content"] = !empty($_GET["utm_content"]) ? $_GET["utm_content"] : "";
    $_IiICC["GoogleAnalytics_utm_campaign"] = !empty($_GET["utm_campaign"]) ? $_GET["utm_campaign"] : "";
  }

  ###################
  // special for RSS2EMailResponder we must inserting RSS feed entries and refreshing tracking links
  if($ResponderType == "RSS2EMailResponder") {
      $_QJlJ0 = "SELECT `New_RSS_Entries` FROM `$_IiICC[ML_RM_RefTableName]` WHERE `Member_id`=$_QLitI";
      $_Ii80i = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Ii80i) == 0){
        _OJLLQ();
        exit;
      }
      $_Ii816 = mysql_fetch_assoc($_Ii80i);
      mysql_free_result($_Ii80i);
      # other entries?
      $_Ii8Cl = true;
      $_IitJj = true;

      if($_IitJj || $_Ii8Cl) {    # from cron_sendengine.inc.php
         $_Ii816["New_RSS_Entries"] = @unserialize($_Ii816["New_RSS_Entries"]);
         if($_Ii816["New_RSS_Entries"] === false)
            $_Ii816["New_RSS_Entries"] = array();
         if(!is_array($_IiICC["LastRSSFeedContent"])) {
            $_IiICC["LastRSSFeedContent"] = @unserialize(base64_decode($_IiICC["LastRSSFeedContent"]));
            if($_IiICC["LastRSSFeedContent"] === false) {
               $_IiICC["LastRSSFeedContent"] = array();
               $_IiICC["LastRSSFeedContent"]["ITEMS"] = array();
            }
         }
         _LQRFC($_IiICC["MailHTMLText"], $_IiICC["MailSubject"], $_Ii816["New_RSS_Entries"], $_IiICC["LastRSSFeedContent"], $_IiICC["EverSendLastRSSFeedMaxEntries"]);

         // auto create plaintext part
         $_IiICC["MailPlainText"] = _ODQAB ( $_IiICC["MailHTMLText"], $_Q6QQL );

      }
  }
  ###################

  if(_OED01($_IiJit, $_Ii6QI, $_Ii6lO, true, $_IiICC, $_IiJo8, $MailingListId, $FormId, $_Ii0fC, $errors, $_Ql1O8, array(), $ResponderId, $ResponderType)) {
    $_QJCJi = $_Ii6lO;
    $_QJCJi = str_replace(InstallPath, ScriptBaseURL, $_QJCJi);

    // overlay
    if(!empty($_GET["Overlay"])){
      // insert javascript and CSS for overlay
      $_QJCJi = str_replace("</head>", '<script type="text/javascript" src="js/jquery-latest.min.js"></script><script type="text/javascript" src="js/stats_overlay.js"></script><link type="text/css" rel="stylesheet" href="css/stats_overlay.css" /></head>', $_QJCJi);
    }

    SetHTMLHeaders($_IiICC["MailEncoding"]);
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

    $_QJCJi = _OP6PQ($_QJCJi, "[AltBrowserLink_begin]", "[AltBrowserLink_end]");
    $_QJCJi = _OP6PQ($_QJCJi, "<!--AltBrowserLink_begin//-->", "<!--AltBrowserLink_end//-->");
    print $_QJCJi;
    exit;
  }

  function _OJLLQ() {
    print '<p>Text nicht gefunden!</p>';
    print '<p>Text not found!</p>';
    exit;
  }

  function _OJJOL() {
    print '<p>Text in Serien-E-Mail-Vorschau nicht verf&uuml;gbar.</p>';
    print '<p>Text in serial email preview not available.</p>';
    exit;
  }
?>
