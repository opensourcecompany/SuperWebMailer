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
  include_once("templates.inc.php");
  include_once("campaignstools.inc.php");
  include_once("geolocation.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeViewCampaignTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_8fCCQ="http://www.superscripte.de/pub/img/";
  if(stripos(ScriptBaseURL, 'https:') !== false)
    $_8fCCQ="https://www.superscripte.de/pub/img/";

  if(isset($_POST['CampaignId']))
    $_j01fj = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_j01fj = intval($_GET['CampaignId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $_j01fj = intval($_POST['OneCampaignListId']);

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

  if(!isset($_j01fj) && isset($_POST["ResponderId"]))
     $_j01fj = intval($_POST["ResponderId"]);
     else
     if(!isset($_j01fj) && isset($_GET["ResponderId"]))
         $_j01fj = intval($_GET["ResponderId"]);

  if(!isset($_j01fj)) {
    $_GET["ResponderType"] = $ResponderType;
    $_GET["TrackingStatistics"] = "TrackingStatistics";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_j01fj = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_campaigntracking.php";
    include_once("campaignsendstatselect.inc.php");
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_8fiti = false;
  if(!empty($_GET["PrintVersion"]) || !empty($_POST["PrintVersion"]))
    $_8fiti = true;

  $_Itfj8 = "";

  $_QLfol = "SELECT $_QLi60.*, DATE_FORMAT($_QLi60.CreateDate, $_QLo60) AS CreateDateFormated, $_QLi60.MailFormat, $_QLi60.MailSubject, $_QLi60.CurrentSendTableName, $_QLi60.RStatisticsTableName FROM $_QLi60 WHERE $_QLi60.id=$_j01fj";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLL16 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_jClC1 = $_QLL16["CurrentSendTableName"];
  $_ji080 = $_QLL16["RStatisticsTableName"];

  // MailSubject and Attachments of selected SentEntry
  $_QLfol = "SELECT `MailSubject`, `Attachments` FROM `$_QLL16[ArchiveTableName]` WHERE `SendStat_id`=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1){
    $_8fLtQ = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_QLL16["MailSubject"] = $_8fLtQ["MailSubject"];
    $_QLL16["Attachments"] = $_8fLtQ["Attachments"];
  }

  // Attachments
  if(!empty($_QLL16["Attachments"])) {
     $_QLL16["Attachments"] = unserialize($_QLL16["Attachments"]);
     $_QLL16["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_QLL16["Attachments"]);
  }
   else
     $_QLL16["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];

  // sent email row
  $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDurationFormated FROM $_jClC1 WHERE id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_8flQ8 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_IQ1ji = $_8flQ8["SendState"] == 'Sending';

  // Remove Send entry
  if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && !$_IQ1ji) {
    $_8ftCj = array("CurrentUsedMTAsTableName", "RStatisticsTableName", "ArchiveTableName", "TrackingOpeningsTableName", "TrackingOpeningsByRecipientTableName", "TrackingLinksTableName", "TrackingLinksByRecipientTableName", "TrackingUserAgentsTableName", "TrackingOSsTableName");
    for($_Qli6J=0; $_Qli6J<count($_8ftCj); $_Qli6J++){
      $_QLfol = "DELETE FROM `".$_QLL16[$_8ftCj[$_Qli6J]]."` WHERE SendStat_id=$SendStatId";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    # don't send it again when there are no send entries
    $_QLfol = "SELECT COUNT(*) FROM `$_jClC1` WHERE `Campaigns_id`=$_j01fj AND id<>$SendStatId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if($_QLO0f[0] == 0 && $_QLL16["SendScheduler"] != 'SaveOnly' && $_QLL16["SendScheduler"] != 'SendManually' ){
      $_QLfol = "UPDATE `$_QLi60` SET `ReSendFlag`=0, `SetupLevel`=0, `LastSentDateTime`='0000-00-00 00:00:00' WHERE id=$_j01fj";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }
    mysql_free_result($_QL8i1);
    #

    $_QLfol = "DELETE FROM `$_jClC1` WHERE id=$SendStatId";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QLfol = "SELECT `EndSendDateTime` FROM `$_jClC1` WHERE `Campaigns_id`=$_j01fj AND (`SendState`='Done' OR `SendState`='ReSending') DESC LIMIT 0,1";
    $_QL8i1 = mysql_query($_QLfol);
    if($_QL8i1 && mysql_num_rows($_QL8i1)){
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      $_QLfol = "UPDATE `$_QLi60` SET `LastSentDateTime`='$_QLO0f[EndSendDateTime]' WHERE `id`=$_j01fj";
      mysql_query($_QLfol);
    }
    if($_QL8i1)
      mysql_free_result($_QL8i1);

    include("browsecampaigns.php");
    exit;
  } else
    if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && $_IQ1ji) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002611"];
    }
  // Remove Send entry /

  // Template
  $_8fOOf = $_8flQ8["StartSendDateTimeFormated"];
  if($_IQ1ji)
    $_8fOOf = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["000675"]."&quot;";

  if(!$_8fiti)
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000680"], $_QLL16["Name"], $_8fOOf ), $_Itfj8, 'stat_campaigntracking', 'stat_campaigntracking_snipped.htm');
    else {
       $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000680"], $_QLL16["Name"], $_8fOOf ), $_Itfj8, 'stat_campaigntracking', 'stat_campaigntracking_printable.htm');
    }

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

  $_QLJfI = str_replace('name="CampaignId"', 'name="CampaignId" value="'.$_j01fj.'"', $_QLJfI);
  $_QLJfI = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QLJfI);
  $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);

  $_QLJfI = str_replace('CampaignId=CampaignId', 'CampaignId='.$_j01fj, $_QLJfI);
  $_QLJfI = str_replace('SendStatId=SendStatId', 'SendStatId='.$SendStatId, $_QLJfI);
  $_QLJfI = str_replace('ResponderType=ResponderType', 'ResponderType='.$ResponderType, $_QLJfI);

  $nocache = urlencode(date("u") );
  $_QLJfI = str_replace('stat_campaign_overlay.php', 'stat_campaign_overlay.php?CampaignId='.$_j01fj.'&SendStatId='.$SendStatId.'&ResponderType='.$ResponderType.'&MailingListId='.$_QLL16["maillists_id"].'&nocache='.$nocache, $_QLJfI);

  if($_QLL16["TrackEMailOpeningsByRecipient"]) {
    $_QLJfI = str_replace("<IF:RECIPIENTOPENINGTRACKING>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:RECIPIENTOPENINGTRACKING>", "", $_QLJfI);
  } else {
    $_QLJfI = _L80DF($_QLJfI, "<IF:RECIPIENTOPENINGTRACKING>", "</IF:RECIPIENTOPENINGTRACKING>");
  }

  if($_QLL16["TrackLinksByRecipient"]) {
    $_QLJfI = str_replace("<IF:RECIPIENTLINKTRACKING>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:RECIPIENTLINKTRACKING>", "", $_QLJfI);
  } else {
    $_QLJfI = _L80DF($_QLJfI, "<IF:RECIPIENTLINKTRACKING>", "</IF:RECIPIENTLINKTRACKING>");
  }

  if($_QLL16["TrackingIPBlocking"] > 0)
    $_QLJfI = _L81BJ($_QLJfI, "<info:ipblocking>", "</info:ipblocking>", $resourcestrings[$INTERFACE_LANGUAGE]["TrackingIPBlocking"]);
   else
    $_QLJfI = _L81BJ($_QLJfI, "<info:ipblocking>", "</info:ipblocking>", "");

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGNID>", "</CAMPAIGNID>", $_QLL16["id"]);
  $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_QLL16["Name"]);
  $_QLJfI = _L81BJ($_QLJfI, "<CREATEDATE>", "</CREATEDATE>", $_QLL16["CreateDateFormated"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTDATE>", "</SENTDATE>", $_8fOOf);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILFORMAT>", "</MAILFORMAT>", $resourcestrings[$INTERFACE_LANGUAGE]["MailFormat".$_QLL16["MailFormat"]]);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILSUBJECT>", "</MAILSUBJECT>", htmlspecialchars(_LCRC8($_QLL16["MailSubject"]), ENT_COMPAT, $_QLo06) );
  $_QLJfI = _L81BJ($_QLJfI, "<ATTACHMENTS>", "</ATTACHMENTS>", $_QLL16["Attachments"]);
  if($_QLL16["TrackingIPBlocking"] != 0)
     $_QLJfI = _L81BJ($_QLJfI, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
     else
     $_QLJfI = _L81BJ($_QLJfI, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QLJfI = _L81BJ($_QLJfI, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_IQ1ji) {
     $_QLJfI = _L80DF($_QLJfI, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
#    $_8flQ8["SentCountSucc"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_8flQ8["SentCountFailed"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_8flQ8["HardBouncesCount"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_8flQ8["SoftBouncesCount"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
    $_8flQ8["EndSendDateTimeFormated"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_QLJfI = _L80DF($_QLJfI, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>");
#    $_QLJfI = _L80DF($_QLJfI, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>");
#    $_QLJfI = _L80DF($_QLJfI, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>");
#    $_QLJfI = _L80DF($_QLJfI, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>");
  }
  if( _LO8EB($_j01fj) )
     $_QLJfI = _L80DF($_QLJfI, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_8flQ8["RecipientsCount"]);

  // prevent division by zero
  if($_8flQ8["RecipientsCount"] == 0)
     $_8flQ8["RecipientsCount"] = 0.01;

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_8flQ8["SentCountSucc"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_8flQ8["SentCountSucc"] * 100 / $_8flQ8["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_8flQ8["SentCountFailed"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_8flQ8["SentCountFailed"] * 100 / $_8flQ8["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_8flQ8["SentCountPossiblySent"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_8flQ8["SentCountPossiblySent"] * 100 / $_8flQ8["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $_8flQ8["HardBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_8flQ8["HardBouncesCount"] * 100 / $_8flQ8["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $_8flQ8["SoftBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_8flQ8["SoftBouncesCount"] * 100 / $_8flQ8["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $_8flQ8["UnsubscribesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESCOUNTPERCENT>", "</UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.1f%%", $_8flQ8["UnsubscribesCount"] * 100 / $_8flQ8["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:START>", "</SENDING:START>", $_8flQ8["StartSendDateTimeFormated"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $_8flQ8["EndSendDateTimeFormated"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $_8flQ8["SendDurationFormated"]."&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE]["Hours"]);

  $_8flCl = -1;
  if($_QLL16["TrackLinksByRecipient"] && !$_QLL16["TrackingIPBlocking"]){
    $_8flCl = 0;
    $_QLfol = "SELECT `Member_id` FROM `$_QLL16[TrackingLinksByRecipientTableName]` WHERE SendStat_id=$SendStatId GROUP BY `Member_id`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_8flCl = mysql_num_rows($_QL8i1);
      if(!isset($_8flCl))
         $_8flCl = 0;
      mysql_free_result($_QL8i1);
    }
  }

  $_QLO0f[0] = 0;

  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount FROM `$_QLL16[TrackingLinksTableName]` WHERE SendStat_id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if(!isset($_QLO0f[0]))
       $_QLO0f[0] = 0;
    mysql_free_result($_QL8i1);
  }

  $_IilfC = $_QLO0f[0];
  if($_8flCl > -1)
    $_IilfC .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueClicks"], $_8flCl);

  $_QLJfI = _L81BJ($_QLJfI, "<SUMLINKCLICKS>", "</SUMLINKCLICKS>", $_IilfC);


  $_88088 = -1;
  if($_QLL16["TrackEMailOpeningsByRecipient"] && !$_QLL16["TrackingIPBlocking"]){
    $_88088 = 0;
    $_QLfol = "SELECT `Member_id` FROM `$_QLL16[TrackingOpeningsByRecipientTableName]` WHERE SendStat_id=$SendStatId GROUP BY `Member_id`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_88088 = mysql_num_rows($_QL8i1);
      if(!isset($_88088))
         $_88088 = 0;
      mysql_free_result($_QL8i1);
    }
  }


  $_QLO0f[0] = 0;

  $_QLfol = "SELECT SUM(Clicks) FROM `$_QLL16[TrackingOpeningsTableName]` WHERE SendStat_id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if(!isset($_QLO0f[0]))
       $_QLO0f[0] = 0;
    mysql_free_result($_QL8i1);
  }

  $_IilfC = $_QLO0f[0];
  if($_88088 > -1)
    $_IilfC .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueOpenings"], $_88088);

  $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSCOUNT>", "</OPENINGSCOUNT>", $_IilfC);
  if($_QLL16["TrackingIPBlocking"])
    $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSRATE>", "</OPENINGSRATE>", sprintf("%1.2f%%", $_QLO0f[0] * 100 / $_8flQ8["RecipientsCount"]) );
    else{
      if($_88088 > 0)
        $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSRATE>", "</OPENINGSRATE>",  sprintf("%1.2f%%", $_88088 * 100 / $_8flQ8["RecipientsCount"]) );
      else
        $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSRATE>", "</OPENINGSRATE>",  $resourcestrings[$INTERFACE_LANGUAGE]["NA"] );
    }

  //

  // Country Top 10
  $_881Il = array();
  $_88Q0f = array();
  $_J1QfQ = array($_QLL16["TrackingOpeningsTableName"], $_QLL16["TrackingLinksTableName"]);
  for($_Qli6J = 0; $_Qli6J < count($_J1QfQ); $_Qli6J++) {
    $_QLfol = "SELECT `Country`, SUM( `Clicks` ) AS `ClicksCount` FROM `$_J1QfQ[$_Qli6J]` WHERE `SendStat_id`=$SendStatId GROUP BY `Country` ORDER BY `ClicksCount` DESC LIMIT 0, 10";

    $_88QfJ = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_88QO1 = mysql_fetch_assoc($_88QfJ)){

      if($_88QO1["Country"] == "")
         $_88QO1["Country"] = "UNKNOWN_COUNTRY";

      if( $_88QO1["Country"] == "UNKNOWN_COUNTRY" )
        $_Iiloo = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN_COUNTRY"];
        else
        $_Iiloo = $_88QO1["Country"];

      if($_Qli6J == 0) {
        if(!isset($_881Il[$_Iiloo]))
          $_881Il[$_Iiloo] = $_88QO1["ClicksCount"];
        else
          $_881Il[$_Iiloo] += $_88QO1["ClicksCount"];
      } else{
        if(!isset($_88Q0f[$_Iiloo]))
          $_88Q0f[$_Iiloo] = $_88QO1["ClicksCount"];
        else
          $_88Q0f[$_Iiloo] += $_88QO1["ClicksCount"];
      }
    }
    mysql_free_result($_88QfJ);
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
  // Country Top 10 /

  //
  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QLJfI = addCultureInfo($_QLJfI);
  // addCultureInfo /

  # Set chart attributes
  $_QLJfI = str_replace("OPENINGCHART_SUMMARYTITLE", unhtmlentities("", $_QLo06), $_QLJfI);

  $_JCQoQ = array();
  $_JC0jO = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000681"].", {y}", "y" => $_QLO0f[0]);
  $_JCQoQ[] = $_JC0jO;
  if($_8flQ8["HardBouncesCount"]) {
    $_JC0jO = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000683"].", {y}", "y" => $_8flQ8["HardBouncesCount"]);
    $_JCQoQ[] = $_JC0jO;
  }
  if($_8flQ8["SoftBouncesCount"]){
    $_JC0jO = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000682"].", {y}", "y" => $_8flQ8["SoftBouncesCount"]);
    $_JCQoQ[] = $_JC0jO;
  }

  $_QLJfI = str_replace("/* OPENINGCHART_SUMMARY_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);

  ////////////////////////////////////



  # Set chart attributes
  $_QLJfI = str_replace("OPENINGCHART_TIMETITLE", unhtmlentities("", $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("OPENINGCHART_TIMEAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000685"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("OPENINGCHART_TIMEAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);


  $_88Ij8 = 0;
  for($_Qli6J=0;$_Qli6J<160;$_Qli6J+=6) {
    $_QLfol = "SELECT NOW() - DATE_ADD('$_8flQ8[StartSendDateTime]', INTERVAL ".($_Qli6J+6)." HOUR)";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if($_QLO0f[0] >= 0)
      $_88Ij8 = $_Qli6J;
      else
      if(abs($_QLO0f[0]) <= 86400)
         $_88Ij8 = $_Qli6J;
    mysql_free_result($_QL8i1);
  }
  if($_88Ij8 == 0)
     $_88Ij8 = 6;
  $_88Ij8 += 6; # +6 for now()

  $_JCQoQ = array();
  $_I0QjQ = array();
  $_JolCi = 0;

  for($_Qli6J=0;$_Qli6J<$_88Ij8;$_Qli6J+=6) {
    $_QLfol = "SELECT SUM(Clicks), DATE_ADD('$_8flQ8[StartSendDateTime]', INTERVAL ".($_Qli6J+6)." HOUR) FROM $_QLL16[TrackingOpeningsTableName] WHERE SendStat_id=$SendStatId AND `ADateTime` <= DATE_ADD('$_8flQ8[StartSendDateTime]', INTERVAL ".($_Qli6J+6)." HOUR) ";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);

      $_JC0jO = array("label" => unhtmlentities(sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000684"], 0, $_Qli6J+6), $_QLo06), "y" => intval($_QLO0f[0]));
      $_JCQoQ[] = $_JC0jO;

      if($_QLO0f[0] > $_JolCi)
        $_JolCi = $_QLO0f[0];
    }
  }

  if(count($_JCQoQ) == 0){
      $_JC0jO = array("label" => unhtmlentities(sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000684"], 0, 0+6), $_QLo06), "y" => 0);
      $_JCQoQ[] = $_JC0jO;
  }

  $entry = array("type" => "area", "name" => "", "showInLegend" => false, "dataPoints" => $_JCQoQ);
  $_I0QjQ[] = $entry;

  $_QLJfI = str_replace("/* OPENINGCHART_TIME_DATA */", _LAFFB($_I0QjQ, JSON_NUMERIC_CHECK), $_QLJfI);

  if($_JolCi < 10){
     // set interval 1
     $_QLJfI = str_replace("/*OPENINGCHART_TIME_INTERVAL", "", $_QLJfI);
     $_QLJfI = str_replace("OPENINGCHART_TIME_INTERVAL*/", "", $_QLJfI);
  }


  ////////////////////////////////////


  $_JCQoQ = array();
  $_I0QjQ = array();
  $_JolCi = 0;

  $_fJtjj = "'%Y-%m-%d'";
  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount, DATE_FORMAT(ADateTime, $_j01CJ) AS ADate, DATE_FORMAT(ADateTime, $_fJtjj) AS ActionDateOnlyDate FROM $_QLL16[TrackingOpeningsTableName] WHERE SendStat_id=$SendStatId GROUP BY ActionDateOnlyDate ORDER BY ActionDateOnlyDate ASC LIMIT 0,31";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $startdate = "";
  $enddate = "";
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    if($startdate == "")
       $startdate = $_QLO0f["ADate"];
       else
       $enddate = $_QLO0f["ADate"];

    $_JC0jO = array("label" => unhtmlentities($_QLO0f["ADate"], $_QLo06), "y" => intval($_QLO0f["ClicksCount"]), "indexLabelFontSize" => "16", "indexLabel" => "{y}");
    $_JCQoQ[] = $_JC0jO;

    if($_QLO0f["ClicksCount"] > $_JolCi)
      $_JolCi = $_QLO0f["ClicksCount"];

  }
  mysql_free_result($_QL8i1);

  if(count($_JCQoQ) == 0){
      $_JC0jO = array("label" => unhtmlentities($enddate, $_QLo06), "y" => 0);
      $_JCQoQ[] = $_JC0jO;
  }

  # Set chart attributes
  $_QLJfI = str_replace("OPENINGCHART_DATETITLE", unhtmlentities("$startdate - $enddate", $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("OPENINGCHART_DATEAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("OPENINGCHART_DATEAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);

  $entry = array("type" => "column", "name" => "", "showInLegend" => false, "dataPoints" => $_JCQoQ);
  $_I0QjQ[] = $entry;

  $_QLJfI = str_replace("/* OPENINGCHART_DATE_DATA */", _LAFFB($_I0QjQ, JSON_NUMERIC_CHECK), $_QLJfI);

  if($_JolCi < 10){
     // set interval 1
     $_QLJfI = str_replace("/*OPENINGCHART_DATE_INTERVAL", "", $_QLJfI);
     $_QLJfI = str_replace("OPENINGCHART_DATE_INTERVAL*/", "", $_QLJfI);
  }


  ////////////////////////////////////



  # Set chart attributes
  $_QLJfI = str_replace("LINKCHART_TOPXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Top10"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("LINKCHART_TOPXAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["LinkDescription"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("LINKCHART_TOPXAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Clicks"], $_QLo06), $_QLJfI);

  $_JCQoQ = array();                                                                                    //possibly works with utf8mb4, but not with utf8_unicode: CONCAT(LEFT(@a, 20), '...')      
  $_QLfol = "SELECT SUM(Clicks) AS ClicksCount, @a:=IF(Description<>'', Description, Link), IF( LENGTH(@a) > 20, @a,  @a ) AS LinkDescription, Link FROM $_QLL16[TrackingLinksTableName] LEFT JOIN $_QLL16[LinksTableName] ON $_QLL16[LinksTableName].id=$_QLL16[TrackingLinksTableName].Links_id WHERE SendStat_id=$SendStatId GROUP BY $_QLL16[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link) LIMIT 0, 10";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    while($_I1OfI = mysql_fetch_assoc($_QL8i1)) {
      $_JC0jO = array("label" => _L8L8D( $_I1OfI["LinkDescription"], 20 ), "y" => $_I1OfI["ClicksCount"], "toolTipContent" => $_I1OfI["Link"].", {y} ", "indexLabelFontSize" => "16", "indexLabel" => "{y}");
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
  $_QLfol = "SELECT Links_id, Link, SUM(Clicks) AS ClicksCount, IF(Description<>'', Description, Link) AS LinkDescription FROM $_QLL16[TrackingLinksTableName] LEFT JOIN $_QLL16[LinksTableName] ON $_QLL16[LinksTableName].id=$_QLL16[TrackingLinksTableName].Links_id WHERE SendStat_id=$SendStatId GROUP BY $_QLL16[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link)";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["Links_id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LINK>", "</LIST:LINK>", '<a href="'.$_QLO0f["Link"].'" target="_blank">'. _LCRC8($_QLO0f["LinkDescription"]) . '</a>');

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LINK_HREF>", "</LIST:LINK_HREF>", $_QLO0f["Link"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LINK_DESC>", "</LIST:LINK_DESC>", $_QLO0f["LinkDescription"] );

      $_88I8t = -1;
      if($_QLL16["TrackLinksByRecipient"] && !$_QLL16["TrackingIPBlocking"]){
        $_88I8t = 0;
        $_QLfol = "SELECT `Member_id` FROM `$_QLL16[TrackingLinksByRecipientTableName]` WHERE SendStat_id=$SendStatId AND `Links_id`=$_QLO0f[Links_id] GROUP BY `Member_id`";
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
  }

  $_QLJfI = _L81BJ($_QLJfI, "<HYPERLINKLIST:ENTRY>", "</HYPERLINKLIST:ENTRY>", $_QLoli);

  //
  $_I0Clj = _L81DB($_QLJfI, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>");
  $_QLoli = "";
  $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_QLL16[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$SendStatId GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Qli6J = 0;
  $_88j6Q = 0;
  $_ICQjo = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
    $_QLoli .= $_I0Clj;
    $_88jiQ = $_QLO0f["UserAgent"];
    if($_88jiQ == "")
      $_88jiQ = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownEMailClient];icon=ua/unknown.gif";
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
  $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_QLL16[TrackingOSsTableName]` WHERE `SendStat_id`=$SendStatId GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Qli6J = 0;
  $_8866O = 0;
  $_ICQjo = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
    $_QLoli .= $_I0Clj;
    $_88jiQ = $_QLO0f["OS"];
    if($_88jiQ == "")
      $_88jiQ = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownOS];icon=ua/unknown.gif";
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


  print $_QLJfI;
?>
