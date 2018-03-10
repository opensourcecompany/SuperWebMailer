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
  include_once("functions.inc.php");
  include_once("templates.inc.php");
  include_once("geolocation.inc.php");

  $_6LQ08="http://www.superscripte.de/pub/img/";
  if(stripos(ScriptBaseURL, 'https:') !== false)
    $_6LQ08="https://www.superscripte.de/pub/img/";

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
    $_GET["TrackingStatistics"] = "TrackingStatistics";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_J16QO = intval($_POST["ResponderId"]);
  }

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);
  if(isset($SendStatId) && empty($SendStatId))
    unset($SendStatId);

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

  if($ResponderType == "Campaign") {
    include_once("stat_campaigntracking.php");
    exit;
  }

  $_6LQI1=false;
  if(!empty($_GET["PrintVersion"]) || !empty($_POST["PrintVersion"]))
    $_6LQI1 = true;

  $_jj1tl = _OAP0L($ResponderType);
  if($_jj1tl)
    $_Jfi0i = _OABJE($_jj1tl);

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
      $_GET["action"] = "stat_respondertracking.php";
      include_once("distriblistentryselect.inc.php");
      if(!isset($_POST["OneDLEId"]))
         exit;
         else
         $_6lQ61 = intval($_POST["OneDLEId"]);
    }
  }

  if(empty($_Jfi0i)) return false;

  if($OwnerOwnerUserId == 0x5A) exit;

  $_QJlJ0 = "SELECT $_Jfi0i.*, DATE_FORMAT($_Jfi0i.CreateDate, $_Q6QiO) AS CREATEDATE, $_Q60QL.MaillistTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.Name AS MaillistName FROM $_Jfi0i LEFT JOIN $_Q60QL ON $_Jfi0i.maillists_id=$_Q60QL.id WHERE $_Jfi0i.id=$_J16QO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_6lfif = mysql_fetch_assoc($_Q60l1);
  $_QlQC8 = $_6lfif["MaillistTableName"];
  $_ItCCo = $_6lfif["LocalBlocklistTableName"];
  mysql_free_result($_Q60l1);

  if($ResponderType == "FollowUpResponder") {
    $_QJlJ0 = "SELECT Name AS FUMMailName, DATE_FORMAT(CreateDate, $_Q6QiO) AS FUMMailCreateDate, LinksTableName, TrackingOpeningsTableName, TrackingOpeningsByRecipientTableName, TrackingLinksTableName, TrackingLinksByRecipientTableName, TrackingUserAgentsTableName, TrackingOSsTableName FROM $_6lfif[FUMailsTableName] WHERE id=$_J16Jf";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_6lfif = array_merge($_6lfif, $_Q6Q1C);
    mysql_free_result($_Q60l1);
  }

  if($ResponderType == "DistributionList") {
    $_QJlJ0 = "SELECT `$_QoOft`.`CurrentSendTableName`, `$_Qoo8o`.`MailSubject`, DATE_FORMAT(`$_Qoo8o`.`CreateDate`, $_Q6QiO) AS MailCreateDate FROM `$_QoOft` ";
    $_QJlJ0 .= " LEFT JOIN `$_Qoo8o` ON `$_Qoo8o`.`DistribList_id`=`$_QoOft`.`id` WHERE `$_Qoo8o`.`id`=$_6lQ61 AND `$_QoOft`.`id`=$_J16QO";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_6lfif = array_merge($_6lfif, $_Q6Q1C);
    mysql_free_result($_Q60l1);

    if(!isset($SendStatId)){
      $_QJlJ0 = "SELECT `id` FROM `$_6lfif[CurrentSendTableName]` WHERE `distriblistentry_id`=$_6lQ61";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $SendStatId = $_Q6Q1C["id"];
    }

     $_QJlJ0 = "SELECT `HardBouncesCount`, `SoftBouncesCount`, `UnsubscribesCount` FROM `$_6lfif[CurrentSendTableName]` WHERE `id`=$SendStatId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     $_6lfif = array_merge($_6lfif, $_Q6Q1C);

  }

  // Remove Send entry
  if(isset($_POST["RemoveSendEntry_x"])) {

    $_6l86f = true;
    if($ResponderType == "DistributionList") {
      $_QJlJ0 = "SELECT `SendState` FROM `$_6lfif[CurrentSendTableName]` WHERE `distriblistentry_id`=$_6lQ61";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
        if($_Q6Q1C["SendState"] == "Sending" || $_Q6Q1C["SendState"] == "ReSending"){
          $_6l86f = false;
        }
      }
      mysql_free_result($_Q60l1);
    }

    if($_6l86f){

      $_6L0f1 = array("TrackingOpeningsTableName", "TrackingOpeningsByRecipientTableName", "TrackingLinksTableName", "TrackingLinksByRecipientTableName", "TrackingUserAgentsTableName", "TrackingOSsTableName");

      // Remove tracking links
      if($ResponderType == "RSS2EMailResponder"){
        $_6L0f1[] = "LinksTableName";
      }

      for($_Q6llo=0; $_Q6llo<count($_6L0f1); $_Q6llo++){
        if($ResponderType != "FollowUpResponder") {
             $_QJlJ0 = "DELETE FROM `".$_6lfif[$_6L0f1[$_Q6llo]]."`";
             if(isset($SendStatId))
               $_QJlJ0 .= " WHERE `SendStat_id`=$SendStatId";
           }
           else
           $_QJlJ0 = "DELETE FROM `".$_6lfif[$_6L0f1[$_Q6llo]]."` WHERE SendStat_id=$_J16Jf";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }

      $_QJlJ0 = "";
      if($ResponderType == "BirthdayResponder") {
          $_QJlJ0 = "DELETE FROM ".$_IjQIf;
          mysql_query($_QJlJ0, $_Q61I1);
        }
        else
        if($ResponderType == "EventResponder") {
          $_QJlJ0 = "DELETE FROM ".$_ICjQ6;
          mysql_query($_QJlJ0, $_Q61I1);
        } else
          if($ResponderType == "FollowUpResponder") {
            $_QJlJ0 = "DELETE FROM `$_6lfif[RStatisticsTableName]` WHERE fumails_id=$_J16Jf";
            mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
          } else
            if($ResponderType == "RSS2EMailResponder") {
              $_QJlJ0 = "DELETE FROM ".$_ICjCO;
              mysql_query($_QJlJ0, $_Q61I1);
          } else
            if($ResponderType == "DistributionList") {

              # don't send it again
              $_QJlJ0 = "UPDATE `$_Qoo8o` SET `SendScheduler`='Sent' WHERE id=$_6lQ61 AND `$_Qoo8o`.`SendScheduler`='SendImmediately'";
              mysql_query($_QJlJ0, $_Q61I1);
              #
              $_QJlJ0 = "DELETE FROM `$_6lfif[RStatisticsTableName]` WHERE `distriblistentry_id`=$_6lQ61";
              mysql_query($_QJlJ0, $_Q61I1);

              #
              $_QJlJ0 = "DELETE FROM `$_6lfif[CurrentSendTableName]` WHERE `distriblistentry_id`=$_6lQ61";
              mysql_query($_QJlJ0, $_Q61I1);
              if(isset($SendStatId))
                unset($SendStatId);
            }

      $_QJlJ0 = "UPDATE `$_Jfi0i` SET ";
      $_QJlJ0 .= "EMailsSent=0, ";
      $_QJlJ0 .= "HardBouncesCount=0, SoftBouncesCount=0, UnsubscribesCount=0 WHERE id=$_J16QO";
      mysql_query($_QJlJ0, $_Q61I1);

      $_6lfif["EMailsSent"] = 0;
      $_6lfif["HardBouncesCount"] = 0;
      $_6lfif["SoftBouncesCount"] = 0;
      $_6lfif["UnsubscribesCount"] = 0;
    } else
     $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002611"];
  }
  // Remove Send entry /

  $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
  $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
  $_IOQf6 = " `$_QlQC8`.IsActive=1 AND `$_QlQC8`.SubscriptionStatus<>'OptInConfirmationPending'".$_Q6JJJ;
  $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

  $_QJlJ0 = "SELECT COUNT($_QlQC8.id) FROM $_QlQC8 $_IO1Oj WHERE $_IOQf6";
  if($ResponderType == "BirthdayResponder")
     $_QJlJ0 .= " AND `$_QlQC8`.u_Birthday <> '0000-00-00' ";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);
  $_6lfif["RecipientsCount"] = $_Q6Q1C[0];

  // Template
  $_6lt0i = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000700"], $_6lfif["Name"]);
  if($ResponderType == "FollowUpResponder") {
    $_6lt0i = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000704"], $_6lfif["Name"], $_6lfif["FUMMailName"]);
  }
  if($ResponderType == "DistributionList") {
    $_6lt0i = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000705"], $_6lfif["Name"], htmlspecialchars($_6lfif["MailSubject"], ENT_COMPAT, $_Q6QQL));
  }


  if(!$_6LQI1)
     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_6lt0i, $_I0600, 'stat_respondertracking', 'stat_respondertracking_snipped.htm');
     else
     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_6lt0i, $_I0600, 'stat_respondertracking', 'stat_respondertracking_printable.htm');

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);

  $_j6Qlo = new _OCB1C('./geoip/');
  if($_j6Qlo->Openable()){
    $_QJCJi = _OP6PQ($_QJCJi, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>");
  } else {
    $_QJCJi = _OPR6L($_QJCJi, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"]."</b>");
  }

  $_JlIfj = _LQDLR("GoogleDeveloperPublicKey", "");
  if(empty($_JlIfj)){
     $_QJCJi = _OPR6L($_QJCJi, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GoogleDeveloperPublicKeyMissing"]."</b>");
     $_QJCJi = _OP6PQ($_QJCJi, "<GoogleMaps>", "</GoogleMaps>");
     $_QJCJi = str_replace("<NoGoogleMaps>", "", $_QJCJi);
     $_QJCJi = str_replace("</NoGoogleMaps>", "", $_QJCJi);
  } else{
    $_QJCJi = _OP6PQ($_QJCJi, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>");
    $_QJCJi = str_replace("<GoogleDeveloperPublicKey>", $_JlIfj, $_QJCJi);
    $_QJCJi = str_replace("&lt;GoogleDeveloperPublicKey&gt;", $_JlIfj, $_QJCJi);
    $_QJCJi = str_replace("<GoogleMaps>", "", $_QJCJi);
    $_QJCJi = str_replace("</GoogleMaps>", "", $_QJCJi);
    $_QJCJi = _OP6PQ($_QJCJi, "<NoGoogleMaps>", "</NoGoogleMaps>");
    if(stripos(ScriptBaseURL, 'https:') !== false)
      $_QJCJi = str_replace('http://maps.googleapis.com', 'https://maps.googleapis.com', $_QJCJi);
  }

  $_6ltoo = "";
  if($ResponderType == "BirthdayResponder")
    $_6ltoo = "./browsebirthdayresponders.php";
    else
    if($ResponderType == "FollowUpResponder")
       $_6ltoo = "./browsefuresponders.php";
       else
       if($ResponderType == "EventResponder")
         $_6ltoo = "./browseeventresponders.php";
         else
         if($ResponderType == "RSS2EMailResponder")
            $_6ltoo = "./browserss2emailresponders.php";
            else
            if($ResponderType == "DistributionList") {
               $_6ltoo = "./browsedistriblists.php";
               if(!empty($_POST["BackLink"])) {
                 $_6ltoo = "./".$_POST["BackLink"];
                 $_QJCJi = str_replace('name="BackLink"', 'name="BackLink" value="'.$_POST["BackLink"].'"', $_QJCJi);
               }
               if(!isset($SendStatId)){
                 include_once("./browsedistriblists.php");
                 return;
               }
            }

  $_QJCJi = str_replace('[BACKLINK]', $_6ltoo, $_QJCJi);

  $_QJCJi = str_replace('name="ResponderId"', 'name="ResponderId" value="'.$_J16QO.'"', $_QJCJi);
  $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);

  $_QJCJi = str_replace('ResponderId=ResponderId', 'ResponderId='.$_J16QO, $_QJCJi);
  $_QJCJi = str_replace('ResponderType=ResponderType', 'ResponderType="'.$ResponderType, $_QJCJi);

  if(isset($_J16Jf)) {
     $_QJCJi = str_replace('name="FUMailId"', 'name="FUMailId" value="'.$_J16Jf.'"', $_QJCJi);
     $_QJCJi = str_replace('FUMailId=FUMailId', 'FUMailId='.$_J16Jf, $_QJCJi);
  }
  if(isset($_6lQ61)){
     $_QJCJi = str_replace('name="DistribListEntryId"', 'name="DistribListEntryId" value="'.$_6lQ61.'"', $_QJCJi);
     $_QJCJi = str_replace('DistribListEntryId=DistribListEntryId', 'DistribListEntryId='.$_6lQ61, $_QJCJi);
  }
  if(isset($SendStatId)){
     $_QJCJi = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QJCJi);
     $_QJCJi = str_replace('SendStatId=SendStatId', 'SendStatId='.$SendStatId, $_QJCJi);
  }

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:RESPONDERNAME>", "</SHOW:RESPONDERNAME>", $_6lfif["Name"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $_6lfif["CREATEDATE"]);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILLISTNAME>", "</MAILLISTNAME>", $_6lfif["MaillistName"]);
  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_6lfif["RecipientsCount"]);

  if($_6lfif["TrackingIPBlocking"] != 0)
     $_QJCJi = _OPR6L($_QJCJi, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
     else
     $_QJCJi = _OPR6L($_QJCJi, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EMAILSSENT>", "</SHOW:EMAILSSENT>", $_6lfif["EMailsSent"]);
  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $_6lfif["HardBouncesCount"]);

  if($ResponderType == "DistributionList"){
    // EMailsSent is here for all email entries in this list, so change it to current recipientscount for correct percent results
    $_6lfif["EMailsSent"] = $_6lfif["RecipientsCount"];
  }

  // prevent division by zero
  if($_6lfif["EMailsSent"] == 0)
     $_6lfif["EMailsSent"] = 0.01;

  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_6lfif["HardBouncesCount"] * 100 / $_6lfif["EMailsSent"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $_6lfif["SoftBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_6lfif["SoftBouncesCount"] * 100 / $_6lfif["EMailsSent"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $_6lfif["UnsubscribesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNTPERCENT>", "</UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.1f%%", $_6lfif["UnsubscribesCount"] * 100 / $_6lfif["EMailsSent"]) );

  if(isset($_6lfif["FUMMailName"])) {
      $_QJCJi = _OPR6L($_QJCJi, "<FUMMAILNAME>", "</FUMMAILNAME>", $_6lfif["FUMMailName"]);
      $_QJCJi = _OPR6L($_QJCJi, "<FUMMAILCREATEDATE>", "</FUMMAILCREATEDATE>", $_6lfif["FUMMailCreateDate"]);
      $_QJCJi = str_replace("<if:FUResponder>", "", $_QJCJi);
      $_QJCJi = str_replace("</if:FUResponder>", "", $_QJCJi);
    }
    else{
      $_QJCJi = _OP6PQ($_QJCJi, "<if:FUResponder>", "</if:FUResponder>");
    }

  if(isset($_6lfif["MailSubject"]) && $ResponderType == "DistributionList") {
      $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EMAILSPROCCESSED>", "</SHOW:EMAILSPROCCESSED>", $_6lfif["EMailsProcessed"]);

      $_QJCJi = _OPR6L($_QJCJi, "<MAILSUBJECT>", "</MAILSUBJECT>", htmlspecialchars($_6lfif["MailSubject"], ENT_COMPAT, $_Q6QQL));
      $_QJCJi = _OPR6L($_QJCJi, "<MAILCREATEDATE>", "</MAILCREATEDATE>", $_6lfif["MailCreateDate"]);

      $_QJCJi = str_replace("<if:DistribList>", "", $_QJCJi);
      $_QJCJi = str_replace("</if:DistribList>", "", $_QJCJi);

      $_QJCJi = _OP6PQ($_QJCJi, "<if:NotDistribList>", "</if:NotDistribList>");
    }
    else{
      $_QJCJi = _OP6PQ($_QJCJi, "<if:DistribList>", "</if:DistribList>");
      $_QJCJi = str_replace("<if:NotDistribList>", "", $_QJCJi);
      $_QJCJi = str_replace("</if:NotDistribList>", "", $_QJCJi);
    }

  if($_6lfif["TrackEMailOpeningsByRecipient"]) {
    $_QJCJi = str_replace("<IF:RECIPIENTOPENINGTRACKING>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:RECIPIENTOPENINGTRACKING>", "", $_QJCJi);
  } else {
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:RECIPIENTOPENINGTRACKING>", "</IF:RECIPIENTOPENINGTRACKING>");
  }

  if($_6lfif["TrackLinksByRecipient"]) {
    $_QJCJi = str_replace("<IF:RECIPIENTLINKTRACKING>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:RECIPIENTLINKTRACKING>", "", $_QJCJi);
  } else {
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:RECIPIENTLINKTRACKING>", "</IF:RECIPIENTLINKTRACKING>");
  }

  if($_6lfif["TrackingIPBlocking"] > 0)
    $_QJCJi = _OPR6L($_QJCJi, "<info:ipblocking>", "</info:ipblocking>", $resourcestrings[$INTERFACE_LANGUAGE]["TrackingIPBlocking"]);
   else
    $_QJCJi = _OPR6L($_QJCJi, "<info:ipblocking>", "</info:ipblocking>", "");

  $_jC1lo = "";
  $_jCQ0I = "";

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


  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";


  $_QJCJi = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QJCJi);

  if($INTERFACE_LANGUAGE != "de")
    $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);


  $_6Lj61 = -1;
  if($_6lfif["TrackEMailOpeningsByRecipient"] && !$_6lfif["TrackingIPBlocking"]){
    $_6Lj61 = 0;
    $_QJlJ0 = "SELECT `Member_id` FROM `$_6lfif[TrackingOpeningsByRecipientTableName]` WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
    if(isset($SendStatId))
       $_QJlJ0 .= " AND `SendStat_id`=$SendStatId";
    $_QJlJ0 .= " GROUP BY `Member_id`";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_6Lj61 = mysql_num_rows($_Q60l1);
      if(!isset($_6Lj61))
         $_6Lj61 = 0;
      mysql_free_result($_Q60l1);
    }
  }

  $_QJlJ0 = "SELECT SUM(Clicks) FROM $_6lfif[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";

  if(isset($SendStatId))
     $_QJlJ0 .= " AND `SendStat_id`=$SendStatId";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if(!isset($_Q6Q1C[0]))
       $_Q6Q1C[0] = 0;
    mysql_free_result($_Q60l1);
  } else
    $_Q6Q1C[0] = 0;

  $_IJQJ8 = $_Q6Q1C[0];
  if($_6Lj61 > -1)
    $_IJQJ8 .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueOpenings"], $_6Lj61);
  $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSCOUNT>", "</OPENINGSCOUNT>", $_IJQJ8);

  //
  $_QfoQo = _OP81D($_QJCJi, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>");
  $_Q6ICj = "";
  $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_6lfif[TrackingUserAgentsTableName]` WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
  if(isset($SendStatId))
     $_QJlJ0 .= " AND `SendStat_id`=$SendStatId";
  $_QJlJ0 .= " GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT 0, 20";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6llo = 0;
  $_6L86f = 0;
  $_II6ft = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    $_Q6ICj .= $_QfoQo;
    $_6L8i0 = $_Q6Q1C["UserAgent"];
    if($_6L8i0 == "")
      $_6L8i0 = "name=".$resourcestrings[$INTERFACE_LANGUAGE]["UnknownEMailClient"].";icon=ua/unknown.gif";
    // name=IE 8.0;icon=msie.png
    $_IJLt1 = substr($_6L8i0, 0, strpos($_6L8i0, ";"));
    $_IJLt1 = substr($_IJLt1, 5);
    $_6L8it = substr($_6L8i0, strpos_reverse($_6L8i0, ";") + 1);
    $_6L8it = substr($_6L8it, 5);

    $_Q6ICj = _OPR6L($_Q6ICj, "<EMAILCLIENT_COUNT>", "</EMAILCLIENT_COUNT>", $_Q6Q1C["ClicksCount"]."&nbsp;(<EMAILCLIENT_COUNT_PERCENT$_Q6llo></EMAILCLIENT_COUNT_PERCENT$_Q6llo>)");
    $_II6ft[] = $_Q6Q1C["ClicksCount"];
    $_6L86f += $_Q6Q1C["ClicksCount"];
    $_Q6llo++;
    $_Q6ICj = _OPR6L($_Q6ICj, "<EMAILCLIENT_NAME>", "</EMAILCLIENT_NAME>", $_IJLt1);
    $_Q6ICj = _OPR6L($_Q6ICj, "&lt;EMAILCLIENT_NAME&gt;", "&lt;/EMAILCLIENT_NAME&gt;", $_IJLt1);
    if(strpos($_6L8it, "ua/") !== false)
       $_Q6ICj = str_replace("EMAILCLIENT_ICON", "images/$_6L8it", $_Q6ICj);
       else
       $_Q6ICj = str_replace("EMAILCLIENT_ICON", $_6LQ08."ua/$_6L8it", $_Q6ICj);
  }
  mysql_free_result($_Q60l1);
  if($_6L86f <= 0)
     $_6L86f = 0.01;
  for($_Q6llo=0; $_Q6llo<count($_II6ft); $_Q6llo++){
    $_Q6ICj = _OPR6L($_Q6ICj, "<EMAILCLIENT_COUNT_PERCENT$_Q6llo>", "</EMAILCLIENT_COUNT_PERCENT$_Q6llo>", sprintf("%1.1f%%", $_II6ft[$_Q6llo] * 100 / $_6L86f));
  }

  $_QJCJi = _OPR6L($_QJCJi, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>", $_Q6ICj);
  //
  $_QfoQo = _OP81D($_QJCJi, "<OS_STAT>", "</OS_STAT>");
  $_Q6ICj = "";
  $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_6lfif[TrackingOSsTableName]` WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
  if(isset($SendStatId))
     $_QJlJ0 .= " AND `SendStat_id`=$SendStatId";
  $_QJlJ0 .= " GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6llo = 0;
  $_6Ltfo = 0;
  $_II6ft = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    $_Q6ICj .= $_QfoQo;
    $_6L8i0 = $_Q6Q1C["OS"];
    if($_6L8i0 == "")
      $_6L8i0 = "name=".$resourcestrings[$INTERFACE_LANGUAGE]["UnknownOS"].";icon=ua/unknown.gif";
    // name=Windows 7;icon=windows7.png
    $_IJLt1 = substr($_6L8i0, 0, strpos($_6L8i0, ";"));
    $_IJLt1 = substr($_IJLt1, 5);
    $_6L8it = substr($_6L8i0, strpos_reverse($_6L8i0, ";") + 1);
    $_6L8it = substr($_6L8it, 5);

    $_Q6ICj = _OPR6L($_Q6ICj, "<OS_COUNT>", "</OS_COUNT>", $_Q6Q1C["ClicksCount"]."&nbsp;(<OS_COUNT_PERCENT$_Q6llo></OS_COUNT_PERCENT$_Q6llo>)");
    $_II6ft[] = $_Q6Q1C["ClicksCount"];
    $_6Ltfo += $_Q6Q1C["ClicksCount"];
    $_Q6llo++;
    $_Q6ICj = _OPR6L($_Q6ICj, "<OS_NAME>", "</OS_NAME>", $_IJLt1);
    $_Q6ICj = _OPR6L($_Q6ICj, "&lt;OS_NAME&gt;", "&lt;/OS_NAME&gt;", $_IJLt1);
    if(strpos($_6L8it, "ua/") !== false)
       $_Q6ICj = str_replace("OS_ICON", "images/$_6L8it", $_Q6ICj);
       else
       $_Q6ICj = str_replace("OS_ICON", $_6LQ08."os/$_6L8it", $_Q6ICj);
  }
  mysql_free_result($_Q60l1);
  if($_6Ltfo <= 0)
     $_6Ltfo = 0.01;
  for($_Q6llo=0; $_Q6llo<count($_II6ft); $_Q6llo++){
    $_Q6ICj = _OPR6L($_Q6ICj, "<OS_COUNT_PERCENT$_Q6llo>", "</OS_COUNT_PERCENT$_Q6llo>", sprintf("%1.1f%%", $_II6ft[$_Q6llo] * 100 / $_6Ltfo));
  }

  $_QJCJi = _OPR6L($_QJCJi, "<OS_STAT>", "</OS_STAT>", $_Q6ICj);
  //

  $_6LJ1C = array();
  $_6LJlI = array();

  $_jj6l0 = array($_6lfif["TrackingOpeningsTableName"], $_6lfif["TrackingLinksTableName"]);
  for($_Q6llo = 0; $_Q6llo < count($_jj6l0); $_Q6llo++) {
    $_QJlJ0 = "SELECT `Country`, SUM( `Clicks` ) AS `ClicksCount` FROM `$_jj6l0[$_Q6llo]` WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
    if(isset($SendStatId))
       $_QJlJ0 .= " AND `SendStat_id`=$SendStatId";
    $_QJlJ0 .= " GROUP BY `Country` ORDER BY `ClicksCount` DESC LIMIT 0, 10";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){

      if($_Q6Q1C["Country"] == "")
         $_Q6Q1C["Country"] = "UNKNOWN_COUNTRY";

      if( $_Q6Q1C["Country"] == "UNKNOWN_COUNTRY" )
        $_IJQOL = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN_COUNTRY"];
        else
        $_IJQOL = $_Q6Q1C["Country"];

      if($_Q6llo == 0) {
        if(!isset($_6LJ1C[$_IJQOL]))
          $_6LJ1C[$_IJQOL] = $_Q6Q1C["ClicksCount"];
        else
          $_6LJ1C[$_IJQOL] += $_Q6Q1C["ClicksCount"];
      } else{
        if(!isset($_6LJlI[$_IJQOL]))
          $_6LJlI[$_IJQOL] = $_Q6Q1C["ClicksCount"];
        else
          $_6LJlI[$_IJQOL] += $_Q6Q1C["ClicksCount"];
      }
    }
    mysql_free_result($_Q60l1);
  }

  asort($_6LJ1C, SORT_NUMERIC);
  asort($_6LJlI, SORT_NUMERIC);

  $_QfoQo = _OP81D($_QJCJi, "<Openings:Line>", "</Openings:Line>");
  $_Q6ICj = "";
  $_Q6ClO = end($_6LJ1C);
  $key = key($_6LJ1C);
  for($_Q6llo=0; $key !== false && $_Q6llo<10;$_Q6llo++){
    $_Q6ICj .= $_QfoQo;
    $_Q6ICj = _OPR6L($_Q6ICj, "<Openings:CountryName>", "</Openings:CountryName>", $key);
    $_Q6ICj = _OPR6L($_Q6ICj, "<Openings:Count>", "</Openings:Count>", $_Q6ClO);
    $_Q6ClO = prev($_6LJ1C);
    $key = key($_6LJ1C);
    if($_Q6ClO === false) break;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<Openings:Line>", "</Openings:Line>", $_Q6ICj);

  $_QfoQo = _OP81D($_QJCJi, "<Clicks:Line>", "</Clicks:Line>");
  $_Q6ICj = "";
  $_Q6ClO = end($_6LJlI);
  $key = key($_6LJlI);
  for($_Q6llo=0; $key !== false && $_Q6llo<10;$_Q6llo++){
    $_Q6ICj .= $_QfoQo;
    $_Q6ICj = _OPR6L($_Q6ICj, "<Clicks:CountryName>", "</Clicks:CountryName>", $key);
    $_Q6ICj = _OPR6L($_Q6ICj, "<Clicks:Count>", "</Clicks:Count>", $_Q6ClO);
    $_Q6ClO = prev($_6LJlI);
    $key = key($_6LJlI);
    if($_Q6ClO === false) break;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<Clicks:Line>", "</Clicks:Line>", $_Q6ICj);
  //

  // Charts

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QJCJi = addCultureInfo($_QJCJi);
  // addCultureInfo /


  # Set chart attributes
  $_QJCJi = str_replace("OPENINGCHART_TIMETITLE", unhtmlentities(sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000701"], $_POST["startdate"], $_POST["enddate"] ), $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("OPENINGCHART_TIMEAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("OPENINGCHART_TIMEAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);

  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount, DATE_FORMAT(ADateTime, $_If0Ql) AS ADate, DATE_FORMAT(ADateTime, $_Jlj0J) AS ActionDateOnlyDate FROM $_6lfif[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
  if(isset($SendStatId))
     $_QJlJ0 .= " AND `SendStat_id`=$SendStatId";
  $_QJlJ0 .= " GROUP BY ActionDateOnlyDate ORDER BY ADate ASC";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

  $_jCfit = array();
  $_Qf1i1 = array();
  $_jC6QL = 0;

  if($_Q60l1) {
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {

      $_jC6IQ = array("label" => $_Q6Q1C["ADate"], "y" => intval($_Q6Q1C["ClicksCount"]), "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_jCfit[] = $_jC6IQ;

      if($_Q6Q1C["ClicksCount"] > $_jC6QL)
        $_jC6QL = $_Q6Q1C["ClicksCount"];

    }
    mysql_free_result($_Q60l1);
  }
  if(count($_jCfit) == 0){
      $_jC6IQ = array("label" => "", "y" => 0);
      $_jCfit[] = $_jC6IQ;
  }


  $entry = array("type" => "column", "name" => "", "showInLegend" => false, "dataPoints" => $_jCfit);
  $_Qf1i1[] = $entry;

  $_QJCJi = str_replace("/* OPENINGCHART_TIME_DATA */", _OCR88($_Qf1i1, JSON_NUMERIC_CHECK), $_QJCJi);

  if($_jC6QL < 10){
     // set interval 1
     $_QJCJi = str_replace("/*OPENINGCHART_TIME_INTERVAL", "", $_QJCJi);
     $_QJCJi = str_replace("OPENINGCHART_TIME_INTERVAL*/", "", $_QJCJi);
  }


  ////////////////////////////////////
  # Set chart attributes
  $_QJCJi = str_replace("LINKCHART_TOPXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Top10"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("LINKCHART_TOPXAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["LinkDescription"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("LINKCHART_TOPXAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Clicks"], $_Q6QQL), $_QJCJi);

  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount, @a:=IF(Description<>'', Description, Link), IF( LENGTH(@a) > 20, CONCAT(LEFT(@a, 20), '...'),  @a ) AS LinkDescription, Link FROM $_6lfif[TrackingLinksTableName] LEFT JOIN $_6lfif[LinksTableName] ON $_6lfif[LinksTableName].id=$_6lfif[TrackingLinksTableName].Links_id WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
  if(isset($SendStatId))
     $_QJlJ0 .= " AND $_6lfif[LinksTableName].`distriblistentry_id`=$_6lQ61 AND $_6lfif[TrackingLinksTableName].`SendStat_id`=$SendStatId";
  $_QJlJ0 .= " GROUP BY $_6lfif[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link) LIMIT 0, 10";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

  $_jCfit = array();

  if($_Q60l1) {
    while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)) {
      $_jC6IQ = array("label" => $_Q8OiJ["LinkDescription"], "y" => $_Q8OiJ["ClicksCount"], "toolTipContent" => $_Q8OiJ["Link"].", {y} ", "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_jCfit[] = $_jC6IQ;
    }
    mysql_free_result($_Q60l1);
  }

  if(count($_jCfit) == 0){
    $_jC6IQ = array("label" => "", "y" => 0);
    $_jCfit[] = $_jC6IQ;
  }
  $_QJCJi = str_replace("/* LINKCHART_TOPX_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);

  ////////////////////////////////////

  $_IIJi1 = _OP81D($_QJCJi, "<HYPERLINKLIST:ENTRY>", "</HYPERLINKLIST:ENTRY>");
  $_Q6ICj = "";
  $_QJlJ0 = "SELECT Links_id, Link, SUM(Clicks) AS ClicksCount, IF(Description<>'', Description, Link) AS LinkDescription FROM $_6lfif[TrackingLinksTableName] LEFT JOIN $_6lfif[LinksTableName] ON $_6lfif[LinksTableName].id=$_6lfif[TrackingLinksTableName].Links_id WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
  if(isset($SendStatId))
     $_QJlJ0 .= " AND $_6lfif[LinksTableName].`distriblistentry_id`=$_6lQ61 AND $_6lfif[TrackingLinksTableName].`SendStat_id`=$SendStatId";
  $_QJlJ0 .= " GROUP BY $_6lfif[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link)";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_Q66jQ = $_IIJi1;
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["Links_id"]);
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LINK>", "</LIST:LINK>", '<a href="'.$_Q6Q1C["Link"].'" target="_blank">'.$_Q6Q1C["LinkDescription"].'</a>');

    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LINK_HREF>", "</LIST:LINK_HREF>", $_Q6Q1C["Link"]);
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LINK_DESC>", "</LIST:LINK_DESC>", $_Q6Q1C["LinkDescription"]);

    $_6LfO6 = -1;
    if($_6lfif["TrackLinksByRecipient"] && !$_6lfif["TrackingIPBlocking"]){
      $_6LfO6 = 0;
      $_QJlJ0 = "SELECT `Member_id` FROM `$_6lfif[TrackingLinksByRecipientTableName]` WHERE (ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).")";
      if(isset($SendStatId))
         $_QJlJ0 .= " AND $_6lfif[LinksTableName].`distriblistentry_id`=$_6lQ61 AND $_6lfif[TrackingLinksByRecipientTableName].`SendStat_id`=$SendStatId";
      $_QJlJ0 .= " AND `Links_id`=$_Q6Q1C[Links_id] GROUP BY `Member_id`";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q8Oj8) {
        $_6LfO6 = mysql_num_rows($_Q8Oj8);
         if(!isset($_6LfO6))
           $_6LfO6 = 0;
        mysql_free_result($_Q8Oj8);
      }
    }

    $_IJQJ8 = $_Q6Q1C["ClicksCount"];
    if($_6LfO6 > -1)
      $_IJQJ8 .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueClicks"], $_6LfO6);
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:CLICKS>", "</LIST:CLICKS>", $_IJQJ8);

    $_Q66jQ = str_replace('name="WhoHasClickedBtn"', 'name="WhoHasClickedBtn" value="'.$_Q6Q1C["Links_id"].'"', $_Q66jQ);
    $_Q6ICj .= $_Q66jQ;
  }
  mysql_free_result($_Q60l1);

  $_QJCJi = _OPR6L($_QJCJi, "<HYPERLINKLIST:ENTRY>", "</HYPERLINKLIST:ENTRY>", $_Q6ICj);

  print $_QJCJi;

?>
