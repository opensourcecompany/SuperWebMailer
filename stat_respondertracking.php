<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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

  $_8fCCQ="http://www.superscripte.de/pub/img/";
  if(stripos(ScriptBaseURL, 'https:') !== false)
    $_8fCCQ="https://www.superscripte.de/pub/img/";

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

  if(isset($_POST["DistribListId"]))
   $_6QJI0 = intval($_POST['DistribListId']);

  if(isset($_POST["OneDistribListId"]))
   $_6QJI0 = intval($_POST['OneDistribListId']);

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
    $_GET["TrackingStatistics"] = "TrackingStatistics";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_6QJI0 = intval($_POST["ResponderId"]);
  }

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);
  if(isset($SendStatId) && empty($SendStatId))
    unset($SendStatId);

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

  $_Itfj8 = "";
  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  $_fJtjj = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  if($ResponderType == "Campaign") {
    include_once("stat_campaigntracking.php");
    exit;
  }

  $_8fiti=false;
  if(!empty($_GET["PrintVersion"]) || !empty($_POST["PrintVersion"]))
    $_8fiti = true;

  $_J0ifL = _LPO6C($ResponderType);
  if($_J0ifL)
    $_6Ol6i = _LPLBQ($_J0ifL);

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
      $_GET["action"] = "stat_respondertracking.php";
      include_once("distriblistentryselect.inc.php");
      if(!isset($_POST["OneDLEId"]))
         exit;
         else
         $_88LLI = intval($_POST["OneDLEId"]);
    }
  }

  if(empty($_6Ol6i)) return false;

  if($OwnerOwnerUserId == 0x5A) exit;

  $_QLfol = "SELECT $_6Ol6i.*, DATE_FORMAT($_6Ol6i.CreateDate, $_QLo60) AS CREATEDATE, $_QL88I.MaillistTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.Name AS MaillistName FROM $_6Ol6i LEFT JOIN $_QL88I ON $_6Ol6i.maillists_id=$_QL88I.id WHERE $_6Ol6i.id=$_6QJI0";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_8tILo = mysql_fetch_assoc($_QL8i1);
  $_I8I6o = $_8tILo["MaillistTableName"];
  $_jjj8f = $_8tILo["LocalBlocklistTableName"];
  mysql_free_result($_QL8i1);

  if($ResponderType == "FollowUpResponder") {
    $_QLfol = "SELECT Name AS FUMMailName, DATE_FORMAT(CreateDate, $_QLo60) AS FUMMailCreateDate, LinksTableName, TrackingOpeningsTableName, TrackingOpeningsByRecipientTableName, TrackingLinksTableName, TrackingLinksByRecipientTableName, TrackingUserAgentsTableName, TrackingOSsTableName FROM $_8tILo[FUMailsTableName] WHERE id=$_6Q60L";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_8tILo = array_merge($_8tILo, $_QLO0f);
    mysql_free_result($_QL8i1);
  }

  if($ResponderType == "DistributionList") {
    $_QLfol = "SELECT `$_IjC0Q`.`CurrentSendTableName`, `$_IjCfJ`.`MailSubject`, DATE_FORMAT(`$_IjCfJ`.`CreateDate`, $_QLo60) AS MailCreateDate FROM `$_IjC0Q` ";
    $_QLfol .= " LEFT JOIN `$_IjCfJ` ON `$_IjCfJ`.`DistribList_id`=`$_IjC0Q`.`id` WHERE `$_IjCfJ`.`id`=$_88LLI AND `$_IjC0Q`.`id`=$_6QJI0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_8tILo = array_merge($_8tILo, $_QLO0f);
    mysql_free_result($_QL8i1);

    if(!isset($SendStatId)){
      $_QLfol = "SELECT `id` FROM `$_8tILo[CurrentSendTableName]` WHERE `distriblistentry_id`=$_88LLI";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $SendStatId = $_QLO0f["id"];
    }

     $_QLfol = "SELECT `HardBouncesCount`, `SoftBouncesCount`, `UnsubscribesCount` FROM `$_8tILo[CurrentSendTableName]` WHERE `id`=$SendStatId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
     $_8tILo = array_merge($_8tILo, $_QLO0f);

  }

  // Remove Send entry
  if(isset($_POST["RemoveSendEntry_x"])) {

    $_8tj0j = true;
    if($ResponderType == "DistributionList") {
      $_QLfol = "SELECT `SendState` FROM `$_8tILo[CurrentSendTableName]` WHERE `distriblistentry_id`=$_88LLI";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
        if($_QLO0f["SendState"] == "Sending" || $_QLO0f["SendState"] == "ReSending"){
          $_8tj0j = false;
        }
      }
      mysql_free_result($_QL8i1);
    }

    if($_8tj0j){

      $_8ftCj = array("TrackingOpeningsTableName", "TrackingOpeningsByRecipientTableName", "TrackingLinksTableName", "TrackingLinksByRecipientTableName", "TrackingUserAgentsTableName", "TrackingOSsTableName");

      // Remove tracking links
      if($ResponderType == "RSS2EMailResponder"){
        $_8ftCj[] = "LinksTableName";
      }

      for($_Qli6J=0; $_Qli6J<count($_8ftCj); $_Qli6J++){
        if($ResponderType != "FollowUpResponder") {
             $_QLfol = "DELETE FROM `".$_8tILo[$_8ftCj[$_Qli6J]]."`";
             if(isset($SendStatId))
               $_QLfol .= " WHERE `SendStat_id`=$SendStatId";
           }
           else
           $_QLfol = "DELETE FROM `".$_8tILo[$_8ftCj[$_Qli6J]]."` WHERE SendStat_id=$_6Q60L";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }

      $_QLfol = "";
      if($ResponderType == "BirthdayResponder") {
          $_QLfol = "DELETE FROM ".$_ICl0j;
          mysql_query($_QLfol, $_QLttI);
        }
        else
        if($ResponderType == "EventResponder") {
          $_QLfol = "DELETE FROM ".$_j68Q0;
          mysql_query($_QLfol, $_QLttI);
        } else
          if($ResponderType == "FollowUpResponder") {
            $_QLfol = "DELETE FROM `$_8tILo[RStatisticsTableName]` WHERE fumails_id=$_6Q60L";
            mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
          } else
            if($ResponderType == "RSS2EMailResponder") {
              $_QLfol = "DELETE FROM ".$_j68Co;
              mysql_query($_QLfol, $_QLttI);
          } else
            if($ResponderType == "DistributionList") {

              # don't send it again
              $_QLfol = "UPDATE `$_IjCfJ` SET `SendScheduler`='Sent' WHERE id=$_88LLI AND `$_IjCfJ`.`SendScheduler`='SendImmediately'";
              mysql_query($_QLfol, $_QLttI);
              #
              $_QLfol = "DELETE FROM `$_8tILo[RStatisticsTableName]` WHERE `distriblistentry_id`=$_88LLI";
              mysql_query($_QLfol, $_QLttI);

              #
              $_QLfol = "DELETE FROM `$_8tILo[CurrentSendTableName]` WHERE `distriblistentry_id`=$_88LLI";
              mysql_query($_QLfol, $_QLttI);
              if(isset($SendStatId))
                unset($SendStatId);
            }

      $_QLfol = "UPDATE `$_6Ol6i` SET ";
      $_QLfol .= "EMailsSent=0, ";
      $_QLfol .= "HardBouncesCount=0, SoftBouncesCount=0, UnsubscribesCount=0 WHERE id=$_6QJI0";
      mysql_query($_QLfol, $_QLttI);

      $_8tILo["EMailsSent"] = 0;
      $_8tILo["HardBouncesCount"] = 0;
      $_8tILo["SoftBouncesCount"] = 0;
      $_8tILo["UnsubscribesCount"] = 0;
    } else
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002611"];
  }
  // Remove Send entry /

  $_jj8Ci = " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
  $_jj8Ci .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
  $_jjtQf = " `$_I8I6o`.IsActive=1 AND `$_I8I6o`.SubscriptionStatus<>'OptInConfirmationPending'".$_QLl1Q;
  $_jjtQf .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ".$_QLl1Q;

  $_QLfol = "SELECT COUNT($_I8I6o.id) FROM $_I8I6o $_jj8Ci WHERE $_jjtQf";
  if($ResponderType == "BirthdayResponder")
     $_QLfol .= " AND `$_I8I6o`.u_Birthday <> '0000-00-00' ";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);
  $_8tILo["RecipientsCount"] = $_QLO0f[0];

  // Template
  $_8tj6J = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000700"], $_8tILo["Name"]);
  if($ResponderType == "FollowUpResponder") {
    $_8tj6J = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000704"], $_8tILo["Name"], $_8tILo["FUMMailName"]);
  }
  if($ResponderType == "DistributionList") {
    $_8tj6J = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000705"], $_8tILo["Name"], $_8tILo["MailSubject"]);
  }


  if(!$_8fiti)
     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_8tj6J, $_Itfj8, 'stat_respondertracking', 'stat_respondertracking_snipped.htm');
     else
     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_8tj6J, $_Itfj8, 'stat_respondertracking', 'stat_respondertracking_printable.htm');

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);

  $_JIfIj = new _LBPJO('./geoip/');
  if($_JIfIj->Openable()){
    $_QLJfI = _L80DF($_QLJfI, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>");
  } else {
    $_QLJfI = _L81BJ($_QLJfI, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"]."</b>");
  }

  $_fJ8l0 = _JOLQE("GoogleDeveloperPublicKey", "");
  if(empty($_fJ8l0)){
     $_QLJfI = _L81BJ($_QLJfI, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GoogleDeveloperPublicKeyMissing"]."</b>");
     $_QLJfI = _L80DF($_QLJfI, "<GoogleMaps>", "</GoogleMaps>");
     $_QLJfI = str_replace("<NoGoogleMaps>", "", $_QLJfI);
     $_QLJfI = str_replace("</NoGoogleMaps>", "", $_QLJfI);
  } else{
    $_QLJfI = _L80DF($_QLJfI, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>");
    $_QLJfI = str_replace("<GoogleDeveloperPublicKey>", $_fJ8l0, $_QLJfI);
    $_QLJfI = str_replace("&lt;GoogleDeveloperPublicKey&gt;", $_fJ8l0, $_QLJfI);
    $_QLJfI = str_replace("<GoogleMaps>", "", $_QLJfI);
    $_QLJfI = str_replace("</GoogleMaps>", "", $_QLJfI);
    $_QLJfI = _L80DF($_QLJfI, "<NoGoogleMaps>", "</NoGoogleMaps>");
    if(stripos(ScriptBaseURL, 'https:') !== false)
      $_QLJfI = str_replace('http://maps.googleapis.com', 'https://maps.googleapis.com', $_QLJfI);
  }

  $_8tjOC = "";
  if($ResponderType == "BirthdayResponder")
    $_8tjOC = "./browsebirthdayresponders.php";
    else
    if($ResponderType == "FollowUpResponder")
       $_8tjOC = "./browsefuresponders.php";
       else
       if($ResponderType == "EventResponder")
         $_8tjOC = "./browseeventresponders.php";
         else
         if($ResponderType == "RSS2EMailResponder")
            $_8tjOC = "./browserss2emailresponders.php";
            else
            if($ResponderType == "DistributionList") {
               $_8tjOC = "./browsedistriblists.php";
               if(!empty($_POST["BackLink"])) {
                 $_8tjOC = "./".$_POST["BackLink"];
                 $_QLJfI = str_replace('name="BackLink"', 'name="BackLink" value="'.$_POST["BackLink"].'"', $_QLJfI);
               }
               if(!isset($SendStatId)){
                 include_once("./browsedistriblists.php");
                 return;
               }
            }

  $_QLJfI = str_replace('[BACKLINK]', $_8tjOC, $_QLJfI);

  $_QLJfI = str_replace('name="ResponderId"', 'name="ResponderId" value="'.$_6QJI0.'"', $_QLJfI);
  $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);

  $_QLJfI = str_replace('ResponderId=ResponderId', 'ResponderId='.$_6QJI0, $_QLJfI);
  $_QLJfI = str_replace('ResponderType=ResponderType', 'ResponderType="'.$ResponderType, $_QLJfI);

  if(isset($_6Q60L)) {
     $_QLJfI = str_replace('name="FUMailId"', 'name="FUMailId" value="'.$_6Q60L.'"', $_QLJfI);
     $_QLJfI = str_replace('FUMailId=FUMailId', 'FUMailId='.$_6Q60L, $_QLJfI);
  }
  if(isset($_88LLI)){
     $_QLJfI = str_replace('name="DistribListEntryId"', 'name="DistribListEntryId" value="'.$_88LLI.'"', $_QLJfI);
     $_QLJfI = str_replace('DistribListEntryId=DistribListEntryId', 'DistribListEntryId='.$_88LLI, $_QLJfI);
  }
  if(isset($SendStatId)){
     $_QLJfI = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QLJfI);
     $_QLJfI = str_replace('SendStatId=SendStatId', 'SendStatId='.$SendStatId, $_QLJfI);
  }

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:RESPONDERNAME>", "</SHOW:RESPONDERNAME>", $_8tILo["Name"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $_8tILo["CREATEDATE"]);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILLISTNAME>", "</MAILLISTNAME>", $_8tILo["MaillistName"]);
  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_8tILo["RecipientsCount"]);

  if($_8tILo["TrackingIPBlocking"] != 0)
     $_QLJfI = _L81BJ($_QLJfI, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
     else
     $_QLJfI = _L81BJ($_QLJfI, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EMAILSSENT>", "</SHOW:EMAILSSENT>", $_8tILo["EMailsSent"]);
  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $_8tILo["HardBouncesCount"]);

  if($ResponderType == "DistributionList"){
    // EMailsSent is here for all email entries in this list, so change it to current recipientscount for correct percent results
    $_8tILo["EMailsSent"] = $_8tILo["RecipientsCount"];
  }

  // prevent division by zero
  if($_8tILo["EMailsSent"] == 0)
     $_8tILo["EMailsSent"] = 0.01;

  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_8tILo["HardBouncesCount"] * 100 / $_8tILo["EMailsSent"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $_8tILo["SoftBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_8tILo["SoftBouncesCount"] * 100 / $_8tILo["EMailsSent"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $_8tILo["UnsubscribesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESCOUNTPERCENT>", "</UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.1f%%", $_8tILo["UnsubscribesCount"] * 100 / $_8tILo["EMailsSent"]) );

  if(isset($_8tILo["FUMMailName"])) {
      $_QLJfI = _L81BJ($_QLJfI, "<FUMMAILNAME>", "</FUMMAILNAME>", $_8tILo["FUMMailName"]);
      $_QLJfI = _L81BJ($_QLJfI, "<FUMMAILCREATEDATE>", "</FUMMAILCREATEDATE>", $_8tILo["FUMMailCreateDate"]);
      $_QLJfI = str_replace("<if:FUResponder>", "", $_QLJfI);
      $_QLJfI = str_replace("</if:FUResponder>", "", $_QLJfI);
    }
    else{
      $_QLJfI = _L80DF($_QLJfI, "<if:FUResponder>", "</if:FUResponder>");
    }

  if(isset($_8tILo["MailSubject"]) && $ResponderType == "DistributionList") {
      $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EMAILSPROCCESSED>", "</SHOW:EMAILSPROCCESSED>", $_8tILo["EMailsProcessed"]);

      $_QLJfI = _L81BJ($_QLJfI, "<MAILSUBJECT>", "</MAILSUBJECT>", $_8tILo["MailSubject"]);
      $_QLJfI = _L81BJ($_QLJfI, "<MAILCREATEDATE>", "</MAILCREATEDATE>", $_8tILo["MailCreateDate"]);

      $_QLJfI = str_replace("<if:DistribList>", "", $_QLJfI);
      $_QLJfI = str_replace("</if:DistribList>", "", $_QLJfI);

      $_QLJfI = _L80DF($_QLJfI, "<if:NotDistribList>", "</if:NotDistribList>");
    }
    else{
      $_QLJfI = _L80DF($_QLJfI, "<if:DistribList>", "</if:DistribList>");
      $_QLJfI = str_replace("<if:NotDistribList>", "", $_QLJfI);
      $_QLJfI = str_replace("</if:NotDistribList>", "", $_QLJfI);
    }

  if($_8tILo["TrackEMailOpeningsByRecipient"]) {
    $_QLJfI = str_replace("<IF:RECIPIENTOPENINGTRACKING>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:RECIPIENTOPENINGTRACKING>", "", $_QLJfI);
  } else {
    $_QLJfI = _L80DF($_QLJfI, "<IF:RECIPIENTOPENINGTRACKING>", "</IF:RECIPIENTOPENINGTRACKING>");
  }

  if($_8tILo["TrackLinksByRecipient"]) {
    $_QLJfI = str_replace("<IF:RECIPIENTLINKTRACKING>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:RECIPIENTLINKTRACKING>", "", $_QLJfI);
  } else {
    $_QLJfI = _L80DF($_QLJfI, "<IF:RECIPIENTLINKTRACKING>", "</IF:RECIPIENTLINKTRACKING>");
  }

  if($_8tILo["TrackingIPBlocking"] > 0)
    $_QLJfI = _L81BJ($_QLJfI, "<info:ipblocking>", "</info:ipblocking>", $resourcestrings[$INTERFACE_LANGUAGE]["TrackingIPBlocking"]);
   else
    $_QLJfI = _L81BJ($_QLJfI, "<info:ipblocking>", "</info:ipblocking>", "");

  $_JoiCQ = "";
  $_JoL0L = "";

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ) AS ENDDATE, DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 10 DAY), $_j01CJ) AS STARTDATE ";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
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


  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";


  $_QLJfI = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QLJfI);

  if($INTERFACE_LANGUAGE != "de")
    $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);


  $_88088 = -1;
  if($_8tILo["TrackEMailOpeningsByRecipient"] && !$_8tILo["TrackingIPBlocking"]){
    $_88088 = 0;
    $_QLfol = "SELECT `Member_id` FROM `$_8tILo[TrackingOpeningsByRecipientTableName]` WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
    if(isset($SendStatId))
       $_QLfol .= " AND `SendStat_id`=$SendStatId";
    $_QLfol .= " GROUP BY `Member_id`";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_88088 = mysql_num_rows($_QL8i1);
      if(!isset($_88088))
         $_88088 = 0;
      mysql_free_result($_QL8i1);
    }
  }

  $_QLfol = "SELECT SUM(Clicks) FROM $_8tILo[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";

  if(isset($SendStatId))
     $_QLfol .= " AND `SendStat_id`=$SendStatId";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if(!isset($_QLO0f[0]))
       $_QLO0f[0] = 0;
    mysql_free_result($_QL8i1);
  } else
    $_QLO0f[0] = 0;

  $_IilfC = $_QLO0f[0];
  if($_88088 > -1)
    $_IilfC .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueOpenings"], $_88088);
  $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSCOUNT>", "</OPENINGSCOUNT>", $_IilfC);

  //
  $_I0Clj = _L81DB($_QLJfI, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>");
  $_QLoli = "";
  $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_8tILo[TrackingUserAgentsTableName]` WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
  if(isset($SendStatId))
     $_QLfol .= " AND `SendStat_id`=$SendStatId";
  $_QLfol .= " GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT 0, 20";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Qli6J = 0;
  $_88j6Q = 0;
  $_ICQjo = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
    $_QLoli .= $_I0Clj;
    $_88jiQ = $_QLO0f["UserAgent"];
    if($_88jiQ == "")
      $_88jiQ = "name=".$resourcestrings[$INTERFACE_LANGUAGE]["UnknownEMailClient"].";icon=ua/unknown.gif";
    // name=IE 8.0;icon=msie.png
    $_I6C8f = substr($_88jiQ, 0, strpos($_88jiQ, ";"));
    $_I6C8f = substr($_I6C8f, 5);
    $_88JCo = substr($_88jiQ, strpos_reverse($_88jiQ, ";") + 1);
    $_88JCo = substr($_88JCo, 5);

    $_QLoli = _L81BJ($_QLoli, "<EMAILCLIENT_COUNT>", "</EMAILCLIENT_COUNT>", $_QLO0f["ClicksCount"]."&nbsp;(<EMAILCLIENT_COUNT_PERCENT$_Qli6J></EMAILCLIENT_COUNT_PERCENT$_Qli6J>)");
    $_ICQjo[] = $_QLO0f["ClicksCount"];
    $_88j6Q += $_QLO0f["ClicksCount"];
    $_Qli6J++;
    $_QLoli = _L81BJ($_QLoli, "<EMAILCLIENT_NAME>", "</EMAILCLIENT_NAME>", $_I6C8f);
    $_QLoli = _L81BJ($_QLoli, "&lt;EMAILCLIENT_NAME&gt;", "&lt;/EMAILCLIENT_NAME&gt;", $_I6C8f);
    if(strpos($_88JCo, "ua/") !== false)
       $_QLoli = str_replace("EMAILCLIENT_ICON", "images/$_88JCo", $_QLoli);
       else
       $_QLoli = str_replace("EMAILCLIENT_ICON", $_8fCCQ."ua/$_88JCo", $_QLoli);
  }
  mysql_free_result($_QL8i1);
  if($_88j6Q <= 0)
     $_88j6Q = 0.01;
  for($_Qli6J=0; $_Qli6J<count($_ICQjo); $_Qli6J++){
    $_QLoli = _L81BJ($_QLoli, "<EMAILCLIENT_COUNT_PERCENT$_Qli6J>", "</EMAILCLIENT_COUNT_PERCENT$_Qli6J>", sprintf("%1.1f%%", $_ICQjo[$_Qli6J] * 100 / $_88j6Q));
  }

  $_QLJfI = _L81BJ($_QLJfI, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>", $_QLoli);
  //
  $_I0Clj = _L81DB($_QLJfI, "<OS_STAT>", "</OS_STAT>");
  $_QLoli = "";
  $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_8tILo[TrackingOSsTableName]` WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
  if(isset($SendStatId))
     $_QLfol .= " AND `SendStat_id`=$SendStatId";
  $_QLfol .= " GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Qli6J = 0;
  $_8866O = 0;
  $_ICQjo = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
    $_QLoli .= $_I0Clj;
    $_88jiQ = $_QLO0f["OS"];
    if($_88jiQ == "")
      $_88jiQ = "name=".$resourcestrings[$INTERFACE_LANGUAGE]["UnknownOS"].";icon=ua/unknown.gif";
    // name=Windows 7;icon=windows7.png
    $_I6C8f = substr($_88jiQ, 0, strpos($_88jiQ, ";"));
    $_I6C8f = substr($_I6C8f, 5);
    $_88JCo = substr($_88jiQ, strpos_reverse($_88jiQ, ";") + 1);
    $_88JCo = substr($_88JCo, 5);

    $_QLoli = _L81BJ($_QLoli, "<OS_COUNT>", "</OS_COUNT>", $_QLO0f["ClicksCount"]."&nbsp;(<OS_COUNT_PERCENT$_Qli6J></OS_COUNT_PERCENT$_Qli6J>)");
    $_ICQjo[] = $_QLO0f["ClicksCount"];
    $_8866O += $_QLO0f["ClicksCount"];
    $_Qli6J++;
    $_QLoli = _L81BJ($_QLoli, "<OS_NAME>", "</OS_NAME>", $_I6C8f);
    $_QLoli = _L81BJ($_QLoli, "&lt;OS_NAME&gt;", "&lt;/OS_NAME&gt;", $_I6C8f);
    if(strpos($_88JCo, "ua/") !== false)
       $_QLoli = str_replace("OS_ICON", "images/$_88JCo", $_QLoli);
       else
       $_QLoli = str_replace("OS_ICON", $_8fCCQ."os/$_88JCo", $_QLoli);
  }
  mysql_free_result($_QL8i1);
  if($_8866O <= 0)
     $_8866O = 0.01;
  for($_Qli6J=0; $_Qli6J<count($_ICQjo); $_Qli6J++){
    $_QLoli = _L81BJ($_QLoli, "<OS_COUNT_PERCENT$_Qli6J>", "</OS_COUNT_PERCENT$_Qli6J>", sprintf("%1.1f%%", $_ICQjo[$_Qli6J] * 100 / $_8866O));
  }

  $_QLJfI = _L81BJ($_QLJfI, "<OS_STAT>", "</OS_STAT>", $_QLoli);
  //

  $_881Il = array();
  $_88Q0f = array();

  $_J1QfQ = array($_8tILo["TrackingOpeningsTableName"], $_8tILo["TrackingLinksTableName"]);
  for($_Qli6J = 0; $_Qli6J < count($_J1QfQ); $_Qli6J++) {
    $_QLfol = "SELECT `Country`, SUM( `Clicks` ) AS `ClicksCount` FROM `$_J1QfQ[$_Qli6J]` WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
    if(isset($SendStatId))
       $_QLfol .= " AND `SendStat_id`=$SendStatId";
    $_QLfol .= " GROUP BY `Country` ORDER BY `ClicksCount` DESC LIMIT 0, 10";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)){

      if($_QLO0f["Country"] == "")
         $_QLO0f["Country"] = "UNKNOWN_COUNTRY";

      if( $_QLO0f["Country"] == "UNKNOWN_COUNTRY" )
        $_Iiloo = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN_COUNTRY"];
        else
        $_Iiloo = $_QLO0f["Country"];

      if($_Qli6J == 0) {
        if(!isset($_881Il[$_Iiloo]))
          $_881Il[$_Iiloo] = $_QLO0f["ClicksCount"];
        else
          $_881Il[$_Iiloo] += $_QLO0f["ClicksCount"];
      } else{
        if(!isset($_88Q0f[$_Iiloo]))
          $_88Q0f[$_Iiloo] = $_QLO0f["ClicksCount"];
        else
          $_88Q0f[$_Iiloo] += $_QLO0f["ClicksCount"];
      }
    }
    mysql_free_result($_QL8i1);
  }

  asort($_881Il, SORT_NUMERIC);
  asort($_88Q0f, SORT_NUMERIC);

  $_I0Clj = _L81DB($_QLJfI, "<Openings:Line>", "</Openings:Line>");
  $_QLoli = "";
  $_QltJO = end($_881Il);
  $key = key($_881Il);
  for($_Qli6J=0; $key !== false && $_Qli6J<10;$_Qli6J++){
    $_QLoli .= $_I0Clj;
    $_QLoli = _L81BJ($_QLoli, "<Openings:CountryName>", "</Openings:CountryName>", $key);
    $_QLoli = _L81BJ($_QLoli, "<Openings:Count>", "</Openings:Count>", $_QltJO);
    $_QltJO = prev($_881Il);
    $key = key($_881Il);
    if($_QltJO === false) break;
  }
  $_QLJfI = _L81BJ($_QLJfI, "<Openings:Line>", "</Openings:Line>", $_QLoli);

  $_I0Clj = _L81DB($_QLJfI, "<Clicks:Line>", "</Clicks:Line>");
  $_QLoli = "";
  $_QltJO = end($_88Q0f);
  $key = key($_88Q0f);
  for($_Qli6J=0; $key !== false && $_Qli6J<10;$_Qli6J++){
    $_QLoli .= $_I0Clj;
    $_QLoli = _L81BJ($_QLoli, "<Clicks:CountryName>", "</Clicks:CountryName>", $key);
    $_QLoli = _L81BJ($_QLoli, "<Clicks:Count>", "</Clicks:Count>", $_QltJO);
    $_QltJO = prev($_88Q0f);
    $key = key($_88Q0f);
    if($_QltJO === false) break;
  }
  $_QLJfI = _L81BJ($_QLJfI, "<Clicks:Line>", "</Clicks:Line>", $_QLoli);
  //

  // Charts

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QLJfI = addCultureInfo($_QLJfI);
  // addCultureInfo /


  # Set chart attributes
  $_QLJfI = str_replace("OPENINGCHART_TIMETITLE", unhtmlentities(sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000701"], $_POST["startdate"], $_POST["enddate"] ), $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("OPENINGCHART_TIMEAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("OPENINGCHART_TIMEAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);

  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount, DATE_FORMAT(ADateTime, $_j01CJ) AS ADate, DATE_FORMAT(ADateTime, $_fJtjj) AS ActionDateOnlyDate FROM $_8tILo[TrackingOpeningsTableName] WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
  if(isset($SendStatId))
     $_QLfol .= " AND `SendStat_id`=$SendStatId";
  $_QLfol .= " GROUP BY ActionDateOnlyDate ORDER BY ADate ASC";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);

  $_JCQoQ = array();
  $_I0QjQ = array();
  $_JolCi = 0;

  if($_QL8i1) {
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {

      $_JC0jO = array("label" => $_QLO0f["ADate"], "y" => intval($_QLO0f["ClicksCount"]), "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_JCQoQ[] = $_JC0jO;

      if($_QLO0f["ClicksCount"] > $_JolCi)
        $_JolCi = $_QLO0f["ClicksCount"];

    }
    mysql_free_result($_QL8i1);
  }
  if(count($_JCQoQ) == 0){
      $_JC0jO = array("label" => "", "y" => 0);
      $_JCQoQ[] = $_JC0jO;
  }


  $entry = array("type" => "column", "name" => "", "showInLegend" => false, "dataPoints" => $_JCQoQ);
  $_I0QjQ[] = $entry;

  $_QLJfI = str_replace("/* OPENINGCHART_TIME_DATA */", _LAFFB($_I0QjQ, JSON_NUMERIC_CHECK), $_QLJfI);

  if($_JolCi < 10){
     // set interval 1
     $_QLJfI = str_replace("/*OPENINGCHART_TIME_INTERVAL", "", $_QLJfI);
     $_QLJfI = str_replace("OPENINGCHART_TIME_INTERVAL*/", "", $_QLJfI);
  }


  ////////////////////////////////////
  # Set chart attributes
  $_QLJfI = str_replace("LINKCHART_TOPXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Top10"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("LINKCHART_TOPXAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["LinkDescription"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("LINKCHART_TOPXAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Clicks"], $_QLo06), $_QLJfI);

  
                                                                                                              //possibly works with utf8mb4, but not with utf8_unicode: CONCAT(LEFT(@a, 20), '...')      
  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount, @a:=IF(Description<>'', Description, Link), IF( LENGTH(@a) > 20, @a,  @a ) AS LinkDescription, Link FROM $_8tILo[TrackingLinksTableName] LEFT JOIN $_8tILo[LinksTableName] ON $_8tILo[LinksTableName].id=$_8tILo[TrackingLinksTableName].Links_id WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
  if(isset($SendStatId))
     $_QLfol .= " AND $_8tILo[LinksTableName].`distriblistentry_id`=$_88LLI AND $_8tILo[TrackingLinksTableName].`SendStat_id`=$SendStatId";
  $_QLfol .= " GROUP BY $_8tILo[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link) LIMIT 0, 10";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);

  $_JCQoQ = array();

  if($_QL8i1) {
    while($_I1OfI = mysql_fetch_assoc($_QL8i1)) {
      $_JC0jO = array("label" => _L8L8D($_I1OfI["LinkDescription"], 20), "y" => $_I1OfI["ClicksCount"], "toolTipContent" => $_I1OfI["Link"].", {y} ", "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_JCQoQ[] = $_JC0jO;
    }
    mysql_free_result($_QL8i1);
  }

  if(count($_JCQoQ) == 0){
    $_JC0jO = array("label" => "", "y" => 0);
    $_JCQoQ[] = $_JC0jO;
  }
  $_QLJfI = str_replace("/* LINKCHART_TOPX_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);

  ////////////////////////////////////

  $_IC1C6 = _L81DB($_QLJfI, "<HYPERLINKLIST:ENTRY>", "</HYPERLINKLIST:ENTRY>");
  $_QLoli = "";
  $_QLfol = "SELECT Links_id, Link, SUM(Clicks) AS ClicksCount, IF(Description<>'', Description, Link) AS LinkDescription FROM $_8tILo[TrackingLinksTableName] LEFT JOIN $_8tILo[LinksTableName] ON $_8tILo[LinksTableName].id=$_8tILo[TrackingLinksTableName].Links_id WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
  if(isset($SendStatId))
     $_QLfol .= " AND $_8tILo[LinksTableName].`distriblistentry_id`=$_88LLI AND $_8tILo[TrackingLinksTableName].`SendStat_id`=$SendStatId";
  $_QLfol .= " GROUP BY $_8tILo[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link)";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_Ql0fO = $_IC1C6;
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["Links_id"]);
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LINK>", "</LIST:LINK>", '<a href="'.$_QLO0f["Link"].'" target="_blank">'.$_QLO0f["LinkDescription"].'</a>');

    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LINK_HREF>", "</LIST:LINK_HREF>", $_QLO0f["Link"]);
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LINK_DESC>", "</LIST:LINK_DESC>", $_QLO0f["LinkDescription"]);

    $_88I8t = -1;
    if($_8tILo["TrackLinksByRecipient"] && !$_8tILo["TrackingIPBlocking"]){
      $_88I8t = 0;
      $_QLfol = "SELECT `Member_id` FROM `$_8tILo[TrackingLinksByRecipientTableName]` WHERE (ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).")";
      if(isset($SendStatId))
         $_QLfol .= " AND $_8tILo[LinksTableName].`distriblistentry_id`=$_88LLI AND $_8tILo[TrackingLinksByRecipientTableName].`SendStat_id`=$SendStatId";
      $_QLfol .= " AND `Links_id`=$_QLO0f[Links_id] GROUP BY `Member_id`";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if($_I1O6j) {
        $_88I8t = mysql_num_rows($_I1O6j);
         if(!isset($_88I8t))
           $_88I8t = 0;
        mysql_free_result($_I1O6j);
      }
    }

    $_IilfC = $_QLO0f["ClicksCount"];
    if($_88I8t > -1)
      $_IilfC .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueClicks"], $_88I8t);
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:CLICKS>", "</LIST:CLICKS>", $_IilfC);

    $_Ql0fO = str_replace('name="WhoHasClickedBtn"', 'name="WhoHasClickedBtn" value="'.$_QLO0f["Links_id"].'"', $_Ql0fO);
    $_QLoli .= $_Ql0fO;
  }
  mysql_free_result($_QL8i1);

  $_QLJfI = _L81BJ($_QLJfI, "<HYPERLINKLIST:ENTRY>", "</HYPERLINKLIST:ENTRY>", $_QLoli);

  print $_QLJfI;

?>
