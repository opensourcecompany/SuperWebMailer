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
  include_once("templates.inc.php");
  include_once("campaignstools.inc.php");
  include_once("geolocation.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeViewCampaignTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_6LQ08="http://www.superscripte.de/pub/img/";
  if(stripos(ScriptBaseURL, 'https:') !== false)
    $_6LQ08="https://www.superscripte.de/pub/img/";

  if(isset($_POST['CampaignId']))
    $_I6lOO = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_I6lOO = intval($_GET['CampaignId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $_I6lOO = intval($_POST['OneCampaignListId']);

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

  if(!isset($_I6lOO) && isset($_POST["ResponderId"]))
     $_I6lOO = intval($_POST["ResponderId"]);
     else
     if(!isset($_I6lOO) && isset($_GET["ResponderId"]))
         $_I6lOO = intval($_GET["ResponderId"]);

  if(!isset($_I6lOO)) {
    $_GET["ResponderType"] = $ResponderType;
    $_GET["TrackingStatistics"] = "TrackingStatistics";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_I6lOO = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_campaigntracking.php";
    include_once("campaignsendstatselect.inc.php");
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_6LQI1 = false;
  if(!empty($_GET["PrintVersion"]) || !empty($_POST["PrintVersion"]))
    $_6LQI1 = true;

  $_I0600 = "";

  $_QJlJ0 = "SELECT $_Q6jOo.*, DATE_FORMAT($_Q6jOo.CreateDate, $_Q6QiO) AS CreateDateFormated, $_Q6jOo.MailFormat, $_Q6jOo.MailSubject, $_Q6jOo.CurrentSendTableName, $_Q6jOo.RStatisticsTableName FROM $_Q6jOo WHERE $_Q6jOo.id=$_I6lOO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_j0fti = $_Q6J0Q["CurrentSendTableName"];
  $_j08fl = $_Q6J0Q["RStatisticsTableName"];

  // MailSubject and Attachments of selected SentEntry
  $_QJlJ0 = "SELECT `MailSubject`, `Attachments` FROM `$_Q6J0Q[ArchiveTableName]` WHERE `SendStat_id`=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1){
    $_6LI1o = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q6J0Q["MailSubject"] = $_6LI1o["MailSubject"];
    $_Q6J0Q["Attachments"] = $_6LI1o["Attachments"];
  }

  // Attachments
  if(!empty($_Q6J0Q["Attachments"])) {
     $_Q6J0Q["Attachments"] = unserialize($_Q6J0Q["Attachments"]);
     $_Q6J0Q["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_Q6J0Q["Attachments"]);
  }
   else
     $_Q6J0Q["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];

  // sent email row
  $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDurationFormated FROM $_j0fti WHERE id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_6LItL = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QtILf = $_6LItL["SendState"] == 'Sending';

  // Remove Send entry
  if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && !$_QtILf) {
    $_6L0f1 = array("RStatisticsTableName", "ArchiveTableName", "TrackingOpeningsTableName", "TrackingOpeningsByRecipientTableName", "TrackingLinksTableName", "TrackingLinksByRecipientTableName", "TrackingUserAgentsTableName", "TrackingOSsTableName");
    for($_Q6llo=0; $_Q6llo<count($_6L0f1); $_Q6llo++){
      $_QJlJ0 = "DELETE FROM `".$_Q6J0Q[$_6L0f1[$_Q6llo]]."` WHERE SendStat_id=$SendStatId";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    # don't send it again when there are no send entries
    $_QJlJ0 = "SELECT COUNT(*) FROM `$_j0fti` WHERE id<>$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if($_Q6Q1C[0] == 0 && $_Q6J0Q["SendScheduler"] != 'SaveOnly' && $_Q6J0Q["SendScheduler"] != 'SendManually' ){
      $_QJlJ0 = "UPDATE `$_Q6jOo` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_I6lOO";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }
    mysql_free_result($_Q60l1);
    #

    $_QJlJ0 = "DELETE FROM `$_j0fti` WHERE id=$SendStatId";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    include("browsecampaigns.php");
    exit;
  } else
    if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && $_QtILf) {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002611"];
    }
  // Remove Send entry /

  // Template
  $_6L16j = $_6LItL["StartSendDateTimeFormated"];
  if($_QtILf)
    $_6L16j = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["000675"]."&quot;";

  if(!$_6LQI1)
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000680"], $_Q6J0Q["Name"], $_6L16j ), $_I0600, 'stat_campaigntracking', 'stat_campaigntracking_snipped.htm');
    else {
       $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000680"], $_Q6J0Q["Name"], $_6L16j ), $_I0600, 'stat_campaigntracking', 'stat_campaigntracking_printable.htm');
    }

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

  $_QJCJi = str_replace('name="CampaignId"', 'name="CampaignId" value="'.$_I6lOO.'"', $_QJCJi);
  $_QJCJi = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QJCJi);
  $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);

  $_QJCJi = str_replace('CampaignId=CampaignId', 'CampaignId='.$_I6lOO, $_QJCJi);
  $_QJCJi = str_replace('SendStatId=SendStatId', 'SendStatId='.$SendStatId, $_QJCJi);
  $_QJCJi = str_replace('ResponderType=ResponderType', 'ResponderType='.$ResponderType, $_QJCJi);

  $nocache = urlencode(strftime("%c") );
  $_QJCJi = str_replace('"stat_campaign_overlay.php"', '"stat_campaign_overlay.php?CampaignId='.$_I6lOO.'&SendStatId='.$SendStatId.'&ResponderType='.$ResponderType.'&MailingListId='.$_Q6J0Q["maillists_id"].'&nocache='.$nocache.'"', $_QJCJi);

  if($_Q6J0Q["TrackEMailOpeningsByRecipient"]) {
    $_QJCJi = str_replace("<IF:RECIPIENTOPENINGTRACKING>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:RECIPIENTOPENINGTRACKING>", "", $_QJCJi);
  } else {
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:RECIPIENTOPENINGTRACKING>", "</IF:RECIPIENTOPENINGTRACKING>");
  }

  if($_Q6J0Q["TrackLinksByRecipient"]) {
    $_QJCJi = str_replace("<IF:RECIPIENTLINKTRACKING>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:RECIPIENTLINKTRACKING>", "", $_QJCJi);
  } else {
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:RECIPIENTLINKTRACKING>", "</IF:RECIPIENTLINKTRACKING>");
  }

  if($_Q6J0Q["TrackingIPBlocking"] > 0)
    $_QJCJi = _OPR6L($_QJCJi, "<info:ipblocking>", "</info:ipblocking>", $resourcestrings[$INTERFACE_LANGUAGE]["TrackingIPBlocking"]);
   else
    $_QJCJi = _OPR6L($_QJCJi, "<info:ipblocking>", "</info:ipblocking>", "");

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGNID>", "</CAMPAIGNID>", $_Q6J0Q["id"]);
  $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_Q6J0Q["Name"]);
  $_QJCJi = _OPR6L($_QJCJi, "<CREATEDATE>", "</CREATEDATE>", $_Q6J0Q["CreateDateFormated"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTDATE>", "</SENTDATE>", $_6L16j);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILFORMAT>", "</MAILFORMAT>", $resourcestrings[$INTERFACE_LANGUAGE]["MailFormat".$_Q6J0Q["MailFormat"]]);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILSUBJECT>", "</MAILSUBJECT>", htmlspecialchars($_Q6J0Q["MailSubject"], ENT_COMPAT, $_Q6QQL) );
  $_QJCJi = _OPR6L($_QJCJi, "<ATTACHMENTS>", "</ATTACHMENTS>", $_Q6J0Q["Attachments"]);
  if($_Q6J0Q["TrackingIPBlocking"] != 0)
     $_QJCJi = _OPR6L($_QJCJi, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
     else
     $_QJCJi = _OPR6L($_QJCJi, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QJCJi = _OPR6L($_QJCJi, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_QtILf) {
     $_QJCJi = _OP6PQ($_QJCJi, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
#    $_6LItL["SentCountSucc"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_6LItL["SentCountFailed"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_6LItL["HardBouncesCount"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_6LItL["SoftBouncesCount"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
    $_6LItL["EndSendDateTimeFormated"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
#    $_QJCJi = _OP6PQ($_QJCJi, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>");
#    $_QJCJi = _OP6PQ($_QJCJi, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>");
#    $_QJCJi = _OP6PQ($_QJCJi, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>");
#    $_QJCJi = _OP6PQ($_QJCJi, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>");
  }
  if( _O6LPE($_I6lOO) )
     $_QJCJi = _OP6PQ($_QJCJi, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_6LItL["RecipientsCount"]);

  // prevent division by zero
  if($_6LItL["RecipientsCount"] == 0)
     $_6LItL["RecipientsCount"] = 0.01;

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_6LItL["SentCountSucc"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_6LItL["SentCountSucc"] * 100 / $_6LItL["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_6LItL["SentCountFailed"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_6LItL["SentCountFailed"] * 100 / $_6LItL["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_6LItL["SentCountPossiblySent"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_6LItL["SentCountPossiblySent"] * 100 / $_6LItL["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $_6LItL["HardBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_6LItL["HardBouncesCount"] * 100 / $_6LItL["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $_6LItL["SoftBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_6LItL["SoftBouncesCount"] * 100 / $_6LItL["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $_6LItL["UnsubscribesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNTPERCENT>", "</UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.1f%%", $_6LItL["UnsubscribesCount"] * 100 / $_6LItL["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:START>", "</SENDING:START>", $_6LItL["StartSendDateTimeFormated"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $_6LItL["EndSendDateTimeFormated"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $_6LItL["SendDurationFormated"]."&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE]["Hours"]);

  $_6LjIC = -1;
  if($_Q6J0Q["TrackLinksByRecipient"] && !$_Q6J0Q["TrackingIPBlocking"]){
    $_6LjIC = 0;
    $_QJlJ0 = "SELECT `Member_id` FROM `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE SendStat_id=$SendStatId GROUP BY `Member_id`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_6LjIC = mysql_num_rows($_Q60l1);
      if(!isset($_6LjIC))
         $_6LjIC = 0;
      mysql_free_result($_Q60l1);
    }
  }

  $_Q6Q1C[0] = 0;

  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount FROM `$_Q6J0Q[TrackingLinksTableName]` WHERE SendStat_id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if(!isset($_Q6Q1C[0]))
       $_Q6Q1C[0] = 0;
    mysql_free_result($_Q60l1);
  }

  $_IJQJ8 = $_Q6Q1C[0];
  if($_6LjIC > -1)
    $_IJQJ8 .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueClicks"], $_6LjIC);

  $_QJCJi = _OPR6L($_QJCJi, "<SUMLINKCLICKS>", "</SUMLINKCLICKS>", $_IJQJ8);


  $_6Lj61 = -1;
  if($_Q6J0Q["TrackEMailOpeningsByRecipient"] && !$_Q6J0Q["TrackingIPBlocking"]){
    $_6Lj61 = 0;
    $_QJlJ0 = "SELECT `Member_id` FROM `$_Q6J0Q[TrackingOpeningsByRecipientTableName]` WHERE SendStat_id=$SendStatId GROUP BY `Member_id`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_6Lj61 = mysql_num_rows($_Q60l1);
      if(!isset($_6Lj61))
         $_6Lj61 = 0;
      mysql_free_result($_Q60l1);
    }
  }


  $_Q6Q1C[0] = 0;

  $_QJlJ0 = "SELECT SUM(Clicks) FROM `$_Q6J0Q[TrackingOpeningsTableName]` WHERE SendStat_id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if(!isset($_Q6Q1C[0]))
       $_Q6Q1C[0] = 0;
    mysql_free_result($_Q60l1);
  }

  $_IJQJ8 = $_Q6Q1C[0];
  if($_6Lj61 > -1)
    $_IJQJ8 .= ", " . sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UniqueOpenings"], $_6Lj61);

  $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSCOUNT>", "</OPENINGSCOUNT>", $_IJQJ8);
  if($_Q6J0Q["TrackingIPBlocking"])
    $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSRATE>", "</OPENINGSRATE>", sprintf("%1.2f%%", $_Q6Q1C[0] * 100 / $_6LItL["RecipientsCount"]) );
    else{
      if($_6Lj61 > 0)
        $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSRATE>", "</OPENINGSRATE>",  sprintf("%1.2f%%", $_6Lj61 * 100 / $_6LItL["RecipientsCount"]) );
      else
        $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSRATE>", "</OPENINGSRATE>",  $resourcestrings[$INTERFACE_LANGUAGE]["NA"] );
    }

  //

  // Country Top 10
  $_6LJ1C = array();
  $_6LJlI = array();
  $_jj6l0 = array($_Q6J0Q["TrackingOpeningsTableName"], $_Q6J0Q["TrackingLinksTableName"]);
  for($_Q6llo = 0; $_Q6llo < count($_jj6l0); $_Q6llo++) {
    $_QJlJ0 = "SELECT `Country`, SUM( `Clicks` ) AS `ClicksCount` FROM `$_jj6l0[$_Q6llo]` WHERE `SendStat_id`=$SendStatId GROUP BY `Country` ORDER BY `ClicksCount` DESC LIMIT 0, 10";

    $_6L6Qi = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_6L68O = mysql_fetch_assoc($_6L6Qi)){

      if($_6L68O["Country"] == "")
         $_6L68O["Country"] = "UNKNOWN_COUNTRY";

      if( $_6L68O["Country"] == "UNKNOWN_COUNTRY" )
        $_IJQOL = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN_COUNTRY"];
        else
        $_IJQOL = $_6L68O["Country"];

      if($_Q6llo == 0) {
        if(!isset($_6LJ1C[$_IJQOL]))
          $_6LJ1C[$_IJQOL] = $_6L68O["ClicksCount"];
        else
          $_6LJ1C[$_IJQOL] += $_6L68O["ClicksCount"];
      } else{
        if(!isset($_6LJlI[$_IJQOL]))
          $_6LJlI[$_IJQOL] = $_6L68O["ClicksCount"];
        else
          $_6LJlI[$_IJQOL] += $_6L68O["ClicksCount"];
      }
    }
    mysql_free_result($_6L6Qi);
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
  // Country Top 10 /

  //
  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QJCJi = addCultureInfo($_QJCJi);
  // addCultureInfo /

  # Set chart attributes
  $_QJCJi = str_replace("OPENINGCHART_SUMMARYTITLE", unhtmlentities("", $_Q6QQL), $_QJCJi);

  $_jCfit = array();
  $_jC6IQ = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000681"].", {y}", "y" => $_Q6Q1C[0]);
  $_jCfit[] = $_jC6IQ;
  if($_6LItL["HardBouncesCount"]) {
    $_jC6IQ = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000683"].", {y}", "y" => $_6LItL["HardBouncesCount"]);
    $_jCfit[] = $_jC6IQ;
  }
  if($_6LItL["SoftBouncesCount"]){
    $_jC6IQ = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000682"].", {y}", "y" => $_6LItL["SoftBouncesCount"]);
    $_jCfit[] = $_jC6IQ;
  }

  $_QJCJi = str_replace("/* OPENINGCHART_SUMMARY_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);

  ////////////////////////////////////



  # Set chart attributes
  $_QJCJi = str_replace("OPENINGCHART_TIMETITLE", unhtmlentities("", $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("OPENINGCHART_TIMEAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000685"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("OPENINGCHART_TIMEAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);


  $_6Lf61 = 0;
  for($_Q6llo=0;$_Q6llo<160;$_Q6llo+=6) {
    $_QJlJ0 = "SELECT NOW() - DATE_ADD('$_6LItL[StartSendDateTime]', INTERVAL ".($_Q6llo+6)." HOUR)";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if($_Q6Q1C[0] >= 0)
      $_6Lf61 = $_Q6llo;
      else
      if(abs($_Q6Q1C[0]) <= 86400)
         $_6Lf61 = $_Q6llo;
    mysql_free_result($_Q60l1);
  }
  if($_6Lf61 == 0)
     $_6Lf61 = 6;
  $_6Lf61 += 6; # +6 for now()

  $_jCfit = array();
  $_Qf1i1 = array();
  $_jC6QL = 0;

  for($_Q6llo=0;$_Q6llo<$_6Lf61;$_Q6llo+=6) {
    $_QJlJ0 = "SELECT SUM(Clicks), DATE_ADD('$_6LItL[StartSendDateTime]', INTERVAL ".($_Q6llo+6)." HOUR) FROM $_Q6J0Q[TrackingOpeningsTableName] WHERE SendStat_id=$SendStatId AND `ADateTime` <= DATE_ADD('$_6LItL[StartSendDateTime]', INTERVAL ".($_Q6llo+6)." HOUR) ";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);

      $_jC6IQ = array("label" => unhtmlentities(sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000684"], 0, $_Q6llo+6), $_Q6QQL), "y" => intval($_Q6Q1C[0]));
      $_jCfit[] = $_jC6IQ;

      if($_Q6Q1C[0] > $_jC6QL)
        $_jC6QL = $_Q6Q1C[0];
    }
  }

  if(count($_jCfit) == 0){
      $_jC6IQ = array("label" => unhtmlentities(sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000684"], 0, 0+6), $_Q6QQL), "y" => 0);
      $_jCfit[] = $_jC6IQ;
  }

  $entry = array("type" => "area", "name" => "", "showInLegend" => false, "dataPoints" => $_jCfit);
  $_Qf1i1[] = $entry;

  $_QJCJi = str_replace("/* OPENINGCHART_TIME_DATA */", _OCR88($_Qf1i1, JSON_NUMERIC_CHECK), $_QJCJi);

  if($_jC6QL < 10){
     // set interval 1
     $_QJCJi = str_replace("/*OPENINGCHART_TIME_INTERVAL", "", $_QJCJi);
     $_QJCJi = str_replace("OPENINGCHART_TIME_INTERVAL*/", "", $_QJCJi);
  }


  ////////////////////////////////////


  $_jCfit = array();
  $_Qf1i1 = array();
  $_jC6QL = 0;

  $_Jlj0J = "'%Y-%m-%d'";
  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount, DATE_FORMAT(ADateTime, $_If0Ql) AS ADate, DATE_FORMAT(ADateTime, $_Jlj0J) AS ActionDateOnlyDate FROM $_Q6J0Q[TrackingOpeningsTableName] WHERE SendStat_id=$SendStatId GROUP BY ActionDateOnlyDate ORDER BY ActionDateOnlyDate ASC LIMIT 0,31";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $startdate = "";
  $enddate = "";
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    if($startdate == "")
       $startdate = $_Q6Q1C["ADate"];
       else
       $enddate = $_Q6Q1C["ADate"];

    $_jC6IQ = array("label" => unhtmlentities($_Q6Q1C["ADate"], $_Q6QQL), "y" => intval($_Q6Q1C["ClicksCount"]), "indexLabelFontSize" => "16", "indexLabel" => "{y}");
    $_jCfit[] = $_jC6IQ;

    if($_Q6Q1C["ClicksCount"] > $_jC6QL)
      $_jC6QL = $_Q6Q1C["ClicksCount"];

  }
  mysql_free_result($_Q60l1);

  if(count($_jCfit) == 0){
      $_jC6IQ = array("label" => unhtmlentities($enddate, $_Q6QQL), "y" => 0);
      $_jCfit[] = $_jC6IQ;
  }

  # Set chart attributes
  $_QJCJi = str_replace("OPENINGCHART_DATETITLE", unhtmlentities("$startdate - $enddate", $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("OPENINGCHART_DATEAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("OPENINGCHART_DATEAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);

  $entry = array("type" => "column", "name" => "", "showInLegend" => false, "dataPoints" => $_jCfit);
  $_Qf1i1[] = $entry;

  $_QJCJi = str_replace("/* OPENINGCHART_DATE_DATA */", _OCR88($_Qf1i1, JSON_NUMERIC_CHECK), $_QJCJi);

  if($_jC6QL < 10){
     // set interval 1
     $_QJCJi = str_replace("/*OPENINGCHART_DATE_INTERVAL", "", $_QJCJi);
     $_QJCJi = str_replace("OPENINGCHART_DATE_INTERVAL*/", "", $_QJCJi);
  }


  ////////////////////////////////////



  # Set chart attributes
  $_QJCJi = str_replace("LINKCHART_TOPXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Top10"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("LINKCHART_TOPXAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["LinkDescription"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("LINKCHART_TOPXAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Clicks"], $_Q6QQL), $_QJCJi);

  $_jCfit = array();
  $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount, @a:=IF(Description<>'', Description, Link), IF( LENGTH(@a) > 20, CONCAT(LEFT(@a, 20), '...'),  @a ) AS LinkDescription, Link FROM $_Q6J0Q[TrackingLinksTableName] LEFT JOIN $_Q6J0Q[LinksTableName] ON $_Q6J0Q[LinksTableName].id=$_Q6J0Q[TrackingLinksTableName].Links_id WHERE SendStat_id=$SendStatId GROUP BY $_Q6J0Q[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link) LIMIT 0, 10";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
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
  $_QJlJ0 = "SELECT Links_id, Link, SUM(Clicks) AS ClicksCount, IF(Description<>'', Description, Link) AS LinkDescription FROM $_Q6J0Q[TrackingLinksTableName] LEFT JOIN $_Q6J0Q[LinksTableName] ON $_Q6J0Q[LinksTableName].id=$_Q6J0Q[TrackingLinksTableName].Links_id WHERE SendStat_id=$SendStatId GROUP BY $_Q6J0Q[TrackingLinksTableName].Links_id ORDER BY ClicksCount DESC, IF(Description<>'', Description, Link)";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["Links_id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LINK>", "</LIST:LINK>", '<a href="'.$_Q6Q1C["Link"].'" target="_blank">'.$_Q6Q1C["LinkDescription"].'</a>');

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LINK_HREF>", "</LIST:LINK_HREF>", $_Q6Q1C["Link"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LINK_DESC>", "</LIST:LINK_DESC>", $_Q6Q1C["LinkDescription"]);

      $_6LfO6 = -1;
      if($_Q6J0Q["TrackLinksByRecipient"] && !$_Q6J0Q["TrackingIPBlocking"]){
        $_6LfO6 = 0;
        $_QJlJ0 = "SELECT `Member_id` FROM `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE SendStat_id=$SendStatId AND `Links_id`=$_Q6Q1C[Links_id] GROUP BY `Member_id`";
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
  }

  $_QJCJi = _OPR6L($_QJCJi, "<HYPERLINKLIST:ENTRY>", "</HYPERLINKLIST:ENTRY>", $_Q6ICj);

  //
  $_QfoQo = _OP81D($_QJCJi, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>");
  $_Q6ICj = "";
  $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_Q6J0Q[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$SendStatId GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6llo = 0;
  $_6L86f = 0;
  $_II6ft = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    $_Q6ICj .= $_QfoQo;
    $_6L8i0 = $_Q6Q1C["UserAgent"];
    if($_6L8i0 == "")
      $_6L8i0 = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownEMailClient];icon=ua/unknown.gif";
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
  $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_Q6J0Q[TrackingOSsTableName]` WHERE `SendStat_id`=$SendStatId GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6llo = 0;
  $_6Ltfo = 0;
  $_II6ft = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    $_Q6ICj .= $_QfoQo;
    $_6L8i0 = $_Q6Q1C["OS"];
    if($_6L8i0 == "")
      $_6L8i0 = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownOS];icon=ua/unknown.gif";
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


  print $_QJCJi;
?>
