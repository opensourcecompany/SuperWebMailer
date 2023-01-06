<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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
  include_once("splitteststools.inc.php");

  $_8fCCQ="http://www.superscripte.de/pub/img/";
  if(stripos(ScriptBaseURL, 'https:') !== false)
    $_8fCCQ="https://www.superscripte.de/pub/img/";

  if(isset($_POST['SplitTestId']))
    $_joQIl = intval($_POST['SplitTestId']);
  else
    if(isset($_GET['SplitTestId']))
      $_joQIl = intval($_GET['SplitTestId']);
      else
      if ( isset($_POST['OneSplitTestListId']) )
         $_joQIl = intval($_POST['OneSplitTestListId']);

  // from campaignsendstatselect.inc.php
  if(isset($_POST['CampaignId']))
    $_joQIl = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_joQIl = intval($_GET['CampaignId']);
  //

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);

  if(!isset($_joQIl) && isset($_POST["ResponderId"]))
     $_joQIl = intval($_POST["ResponderId"]);
     else
     if(!isset($_joQIl) && isset($_GET["ResponderId"]))
         $_joQIl = intval($_GET["ResponderId"]);

  if(!isset($_joQIl)) {
    $_GET["ResponderType"] = "SplitTest";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_joQIl = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_splittesttracking.php";
    $_GET["ResponderType"] = "SplitTest";
    $_j01fj = $_joQIl;
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

  $_Itfj8 = "";

  $_QLfol = "SELECT $_jJL88.*, DATE_FORMAT($_jJL88.CreateDate, $_QLo60) AS CreateDateFormated, $_QL88I.MaillistTableName FROM $_jJL88 LEFT JOIN $_QL88I ON $_QL88I.id=$_jJL88.maillists_id WHERE $_jJL88.id=$_joQIl";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_8tO6L = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_8to1f = $_8tO6L["Name"];
  $_jClC1 = $_8tO6L["CurrentSendTableName"];
  $_I8I6o = $_8tO6L["MaillistTableName"];
  $_IttOL = $_8tO6L["maillists_id"];
  $_86j8Q = $_8tO6L["CampaignsForSplitTestTableName"];
  $_86j0I = $_8tO6L["SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName"];

  $_QLfol = "SELECT `Campaigns_id` FROM `$_86j8Q`";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_8totC = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_8totC[] = array("Campaigns_id"=> $_QLO0f["Campaigns_id"], "CampaignsSendStat_id" => 0, "RecipientsCount" => 0);
  }
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_86j0I` WHERE `SplitTestSendStat_id`=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    for($_Qli6J=0; $_Qli6J<count($_8totC); $_Qli6J++)
      if ($_8totC[$_Qli6J]["Campaigns_id"] == $_QLO0f["Campaigns_id"]) {
        $_8totC[$_Qli6J]["CampaignsSendStat_id"] = $_QLO0f["CampaignsSendStat_id"];
        $_8totC[$_Qli6J]["RecipientsCount"] = $_QLO0f["RecipientsCount"];
        break;
      }
  }
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS SentDateTime, DATE_FORMAT(StartSendDateTime, $_j01CJ) AS STARTDATE, ";
  $_QLfol .= "DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
  $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDurationFormated ";
  $_QLfol .= " FROM $_jClC1 WHERE id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  // override it with old parameters because user can change it later
  $_8tO6L["WinnerType"] = $_QLO0f["WinnerType"];
  $_8tO6L["TestType"] = $_QLO0f["TestType"];
  $_8tO6L["ListPercentage"] = $_QLO0f["ListPercentage"];

  $_IQ1ji = $_QLO0f["SendState"] == 'Sending' || $_QLO0f["SendState"] == 'Waiting' || $_QLO0f["SendState"] == 'PreparingWinnerCampaign' || $_QLO0f["SendState"] == 'SendingWinnerCampaign';
  $_8ft1J = $_QLO0f["STARTDATE"];

  switch ($_QLO0f["SendState"]) {
    case 'Preparing': $_QLO0f["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001840"];
                 break;
    case 'Waiting': $_QLO0f["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001841"];
                 break;
    case 'PreparingWinnerCampaign': $_QLO0f["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001842"];
                 break;
    case 'SendingWinnerCampaign': $_QLO0f["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001843"];
                 break;
    case 'Done': $_QLO0f["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["MailSendSent"];
                 break;
    default:
     $_QLO0f["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
  }

  // Sums and tablenames
  $_8toif = array();

  $_8ftCj = array("CurrentSendTableName", "RStatisticsTableName", "ArchiveTableName", "TrackingOpeningsTableName", "TrackingOpeningsByRecipientTableName", "TrackingLinksTableName", "TrackingLinksByRecipientTableName", "TrackingUserAgentsTableName", "TrackingOSsTableName");

  $_8toif["RecipientsCount"] = 0;
  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    if($_IQ1ji)
      $_8toif["RecipientsCount"] += $_8totC[$_QliOt]["RecipientsCount"];
      else {
        if($_8tO6L["TestType"] == 'TestSendToListPercentage' && $_8totC[$_QliOt]["Campaigns_id"] == $_QLO0f["WinnerCampaigns_id"]) {
           $_8totC[$_QliOt]["RecipientsCount"] += $_QLO0f["WinnerRecipientsCount"]; # add Winner recipients
        }
        $_8toif["RecipientsCount"] += $_8totC[$_QliOt]["RecipientsCount"];
      }

    $_QLfol = "SELECT ".join(", ", $_8ftCj).", Name, TrackingIPBlocking, TrackEMailOpeningsByRecipient, TrackLinksByRecipient FROM $_QLi60 WHERE id=".$_8totC[$_QliOt]["Campaigns_id"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    for($_Qli6J=0; $_Qli6J<count($_8ftCj); $_Qli6J++){
       $_8totC[$_QliOt][$_8ftCj[$_Qli6J]] = $_I1OfI[$_8ftCj[$_Qli6J]];
    }
    $_8totC[$_QliOt]["Name"] = $_I1OfI["Name"];
    $_8totC[$_QliOt]["TrackEMailOpeningsByRecipient"] = $_I1OfI["TrackEMailOpeningsByRecipient"];
    $_8totC[$_QliOt]["TrackLinksByRecipient"] = $_I1OfI["TrackLinksByRecipient"];
    # we need this later
    $_8tO6L["TrackingIPBlocking"] = $_I1OfI["TrackingIPBlocking"];

    mysql_free_result($_QL8i1);
  }

  if(!$_IQ1ji || $_QLO0f["SendState"] == 'SendingWinnerCampaign' && $_QLO0f["RecipientsCount"] > $_8toif["RecipientsCount"])
    $_8toif["RecipientsCount"] = $_QLO0f["RecipientsCount"];
    else
    if($_IQ1ji && $_8tO6L["TestType"] == "TestSendToListPercentage" && $_QLO0f["RecipientsCount"] > $_8toif["RecipientsCount"])
       $_8toif["RecipientsCount"] = $_QLO0f["RecipientsCount"];


  // Remove Send entry
  if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && !$_IQ1ji ) {
    // campaign itself
    for($_Qli6J=0; $_Qli6J<count($_8ftCj); $_Qli6J++){
      for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
        if($_8ftCj[$_Qli6J] == "CurrentSendTableName"){
          $_QLfol = "DELETE FROM `".$_8totC[$_QliOt][$_8ftCj[$_Qli6J]]."` WHERE id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
          mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          continue;
        }
        $_QLfol = "DELETE FROM `".$_8totC[$_QliOt][$_8ftCj[$_Qli6J]]."` WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
    }
    // splittesttable
    $_QLfol = "SELECT `MembersTableName`, `RandomMembersTableName` FROM `$_jClC1` WHERE id=$SendStatId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "DROP TABLE `$_I1OfI[MembersTableName]`";
    mysql_query($_QLfol, $_QLttI);

    $_QLfol = "DROP TABLE `$_I1OfI[RandomMembersTableName]`";
    mysql_query($_QLfol, $_QLttI);

    # don't send it again when there are no send entries
    $_QLfol = "SELECT COUNT(*) FROM `$_jClC1` WHERE id<>$SendStatId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if($_QLO0f[0] == 0 && $_8tO6L["SendScheduler"] != 'SaveOnly' ){
      $_QLfol = "UPDATE `$_jJL88` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_joQIl";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }
    mysql_free_result($_QL8i1);
    #

    $_QLfol = "DELETE FROM `$_jClC1` WHERE id=$SendStatId";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QLfol = "DELETE FROM `$_8tO6L[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$SendStatId";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);


    include("browsesplittests.php");
    exit;
  }
  // Remove Send entry /

  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {

    # no send entry at this time
    if($_8totC[$_QliOt]["CampaignsSendStat_id"] == 0) {
      $_8tC0C = array("SentCountSucc", "SentCountFailed", "SentCountPossiblySent", "HardBouncesCount", "SoftBouncesCount", "UnsubscribesCount");
      reset($_8tC0C);
      foreach($_8tC0C as $key){
        $_8toif[$key] = 0;
        $_8totC[$_QliOt][$key] = 0;
      }
      continue;
    }

    $_QLfol = "SELECT SentCountSucc, SentCountFailed, SentCountPossiblySent, HardBouncesCount, SoftBouncesCount, UnsubscribesCount FROM ".$_8totC[$_QliOt]["CurrentSendTableName"]." WHERE id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    foreach($_I1OfI as $key => $_QltJO){
      $_8totC[$_QliOt][$key] = $_QltJO;
      if(!isset($_8toif[$key]))
        $_8toif[$key] = 0;
      if($_QltJO != -9999) // canceled
        $_8toif[$key] += $_QltJO;
        else
        $_8toif[$key] = "Canceled";
    }
    mysql_free_result($_QL8i1);
  }

  if($_IQ1ji) {
    $_8toif["SentCountSucc"] = 0;
    $_8toif["SentCountFailed"] = 0;
    for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
      $_ji080 = $_8totC[$_QliOt]["RStatisticsTableName"];
      // Count things while sending
      $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." AND Send='Sent'";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_8toif["SentCountSucc"] += $_jj6L6[0];
      $_8totC[$_QliOt]["SentCountSucc"] += $_jj6L6[0];

      $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." AND Send='Failed'";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_8toif["SentCountFailed"] += $_jj6L6[0];
      $_8totC[$_QliOt]["SentCountFailed"] += $_jj6L6[0];

      $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." AND Send='PossiblySent'";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_8toif["SentCountPossiblySent"] += $_jj6L6[0];
      $_8totC[$_QliOt]["SentCountPossiblySent"] += $_jj6L6[0];
    }
  }

  // Template
  $_8fOOf = $_QLO0f["SentDateTime"];
  if($_IQ1ji)
    $_8fOOf = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["000675"]."&quot;";
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001860"], $_8tO6L["Name"], $_8fOOf ), $_Itfj8, 'stat_splittesttracking', 'stat_splittesttracking_snipped.htm');

  $_QLJfI = str_replace('name="SplitTestId"', 'name="SplitTestId" value="'.$_joQIl.'"', $_QLJfI);
  $_QLJfI = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QLJfI);

  $ResponderType = "SplitTest";
  $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);

  if($_8tO6L["TrackingIPBlocking"] > 0)
    $_QLJfI = _L81BJ($_QLJfI, "<info:ipblocking>", "</info:ipblocking>", $resourcestrings[$INTERFACE_LANGUAGE]["TrackingIPBlocking"]);
   else
    $_QLJfI = _L81BJ($_QLJfI, "<info:ipblocking>", "</info:ipblocking>", "");

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<SplitTestId>", "</SplitTestId>", $_8tO6L["id"]);
  $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_8tO6L["Name"]);
  $_QLJfI = _L81BJ($_QLJfI, "<CREATEDATE>", "</CREATEDATE>", $_8tO6L["CreateDateFormated"]);

  $_QLJfI = _L81BJ($_QLJfI, "<WinnerType>", "</WinnerType>", $resourcestrings[$INTERFACE_LANGUAGE]["WinnerType".$_8tO6L["WinnerType"]]);
  if($_8tO6L["TestType"] == 'TestSendToAllMembers')
    $_86C0i = $resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_8tO6L["TestType"]];
    else {
      $_86C0i = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_8tO6L["TestType"]], $_8tO6L["ListPercentage"], $_8tO6L["SendAfterInterval"], $resourcestrings[$INTERFACE_LANGUAGE][$_8tO6L["SendAfterIntervalType"]."s"]);
    }
  $_QLJfI = _L81BJ($_QLJfI, "<TestType>", "</TestType>", $_86C0i);


  if($_8tO6L["TrackingIPBlocking"] != 0)
     $_QLJfI = _L81BJ($_QLJfI, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
     else
     $_QLJfI = _L81BJ($_QLJfI, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QLJfI = _L81BJ($_QLJfI, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_IQ1ji) {
    $_QLJfI = _L80DF($_QLJfI, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
    $_8flQ8["EndSendDateTimeFormated"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
  }
  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_8toif["RecipientsCount"]);

  // prevent division by zero
  if($_8toif["RecipientsCount"] == 0)
     $_8toif["RecipientsCount"] = 0.01;

  if($_8toif["SentCountSucc"] != "Canceled"){
    $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_8toif["SentCountSucc"]);
    $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_8toif["SentCountSucc"] * 100 / $_8toif["RecipientsCount"]) );
  }else{
    $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $resourcestrings[$INTERFACE_LANGUAGE]["000623"]);
    $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", "" );
  }  

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_8toif["SentCountFailed"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_8toif["SentCountFailed"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_8toif["SentCountPossiblySent"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_8toif["SentCountPossiblySent"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $_8toif["HardBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_8toif["HardBouncesCount"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $_8toif["SoftBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_8toif["SoftBouncesCount"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $_8toif["UnsubscribesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESCOUNTPERCENT>", "</UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.1f%%", $_8toif["UnsubscribesCount"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:START>", "</SENDING:START>", $_QLO0f["StartSendDateTimeFormated"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $_QLO0f["EndSendDateTimeFormated"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $_QLO0f["SendDurationFormated"]."&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE]["Hours"]);

  if($_QLO0f["SendState"] == 'Sending' || $_QLO0f["SendState"] == 'Waiting')
    $_QLJfI = _L81BJ($_QLJfI, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
    else
    if ($_QLO0f["SendState"] == 'PreparingWinnerCampaign' || $_QLO0f["SendState"] == 'SendingWinnerCampaign' || $_QLO0f["SendState"] == 'Done') {

      # get winner campaign and clicks
      if( $_8tO6L["TestType"] == 'TestSendToAllMembers' ) {
        $_JO616 = 0;
        $_QLO0f["WinnerCampaigns_id"] = _JL8L0($_8tO6L, $_QLO0f, $_8totC, false, $_JO616);
        $_QLO0f["WinnerCampaignsMaxClicks"] = $_JO616;
      }

      for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
        if($_8totC[$_QliOt]["Campaigns_id"] == $_QLO0f["WinnerCampaigns_id"]) {
          if($_8tO6L["WinnerType"] == 'WinnerOpens')
            $_QLJfI = _L81BJ($_QLJfI, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001851"], $_8totC[$_QliOt]["Name"], $_QLO0f["WinnerCampaignsMaxClicks"]));
          else
            $_QLJfI = _L81BJ($_QLJfI, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001852"], $_8totC[$_QliOt]["Name"], $_QLO0f["WinnerCampaignsMaxClicks"]));
        }
      }

    } else
      $_QLJfI = _L81BJ($_QLJfI, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"]);


  $_8tlIo = 0;
  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    $_QLfol = "SELECT SUM(Clicks) AS ClicksCount FROM `".$_8totC[$_QliOt]["TrackingLinksTableName"]."` WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_I1OfI = mysql_fetch_row($_QL8i1);
      if(!isset($_I1OfI[0]))
         $_I1OfI[0] = 0;
      mysql_free_result($_QL8i1);
      $_8tlIo += $_I1OfI[0];
      if(!isset($_8totC[$_QliOt]["LinksClicks"]))
         $_8totC[$_QliOt]["LinksClicks"] = 0;
      $_8totC[$_QliOt]["LinksClicks"] += $_I1OfI[0];
    }
  }

  $_QLJfI = _L81BJ($_QLJfI, "<SUMLINKCLICKS>", "</SUMLINKCLICKS>", $_8tlIo);

  $_8tlIo = 0;
  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    $_QLfol = "SELECT SUM(Clicks) AS ClicksCount FROM `".$_8totC[$_QliOt]["TrackingOpeningsTableName"]."` WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_I1OfI = mysql_fetch_row($_QL8i1);
      if(!isset($_I1OfI[0]))
         $_I1OfI[0] = 0;
      mysql_free_result($_QL8i1);
      $_8tlIo += $_I1OfI[0];
      if(!isset($_8totC[$_QliOt]["Openings"]))
         $_8totC[$_QliOt]["Openings"] = 0;
      $_8totC[$_QliOt]["Openings"] += $_I1OfI[0];
    }
  }

  $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSCOUNT>", "</OPENINGSCOUNT>", $_8tlIo);
  if($_8tO6L["TrackingIPBlocking"] != 0)
    $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSRATE>", "</OPENINGSRATE>", sprintf("%1.2f%%", $_8tlIo * 100 / $_8toif["RecipientsCount"]) );
    else
    $_QLJfI = _L81BJ($_QLJfI, "<OPENINGSRATE>", "</OPENINGSRATE>",  $resourcestrings[$INTERFACE_LANGUAGE]["NA"] );

  //
  // sum useragents
  $_8O0Qo = array();
  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `".$_8totC[$_QliOt]["TrackingUserAgentsTableName"]."` WHERE `SendStat_id`=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
      $_88jiQ = $_I1OfI["UserAgent"];
      if($_88jiQ == "")
        $_88jiQ = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownEMailClient];icon=ua/unknown.gif";
      // name=IE 8.0;icon=msie.png
      $_I6C8f = substr($_88jiQ, 0, strpos($_88jiQ, ";"));
      $_I6C8f = substr($_I6C8f, 5);
      $_88JCo = substr($_88jiQ, strpos_reverse($_88jiQ, ";") + 1);
      $_88JCo = substr($_88JCo, 5);

      if(!isset($_8O0Qo[$_I6C8f]))
        $_8O0Qo[$_I6C8f] = array("icon" => $_88JCo, "ClicksCount" => $_I1OfI["ClicksCount"]);
        else {
          $_8O0Qo[$_I6C8f]["ClicksCount"] += $_I1OfI["ClicksCount"];
        }
    }
    mysql_free_result($_I1O6j);
  }

  // sum OSs
  $_8O0Of = array();
  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `".$_8totC[$_QliOt]["TrackingOSsTableName"]."` WHERE `SendStat_id`=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
      $_88jiQ = $_I1OfI["OS"];
      if($_88jiQ == "")
        $_88jiQ = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownOS];icon=ua/unknown.gif";
      // name=Windows 7;icon=windows7.png
      $_I6C8f = substr($_88jiQ, 0, strpos($_88jiQ, ";"));
      $_I6C8f = substr($_I6C8f, 5);
      $_88JCo = substr($_88jiQ, strpos_reverse($_88jiQ, ";") + 1);
      $_88JCo = substr($_88JCo, 5);

      if(!isset($_8O0Of[$_I6C8f]))
        $_8O0Of[$_I6C8f] = array("icon" => $_88JCo, "ClicksCount" => $_I1OfI["ClicksCount"]);
        else {
          $_8O0Of[$_I6C8f]["ClicksCount"] += $_I1OfI["ClicksCount"];
        }
    }
    mysql_free_result($_I1O6j);
  }

  $_I0Clj = _L81DB($_QLJfI, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>");
  $_QLoli = "";
  $_Qli6J = 0;
  $_88j6Q = 0;
  $_ICQjo = array();
  reset($_8O0Qo);
  foreach($_8O0Qo as $_88jiQ => $_QltJO){
    $_QLoli .= $_I0Clj;

    $_QLoli = _L81BJ($_QLoli, "<EMAILCLIENT_COUNT>", "</EMAILCLIENT_COUNT>", $_QltJO["ClicksCount"]."&nbsp;(<EMAILCLIENT_COUNT_PERCENT$_Qli6J></EMAILCLIENT_COUNT_PERCENT$_Qli6J>)");
    $_ICQjo[] = $_QltJO["ClicksCount"];
    $_88j6Q += $_QltJO["ClicksCount"];
    $_Qli6J++;
    $_QLoli = _L81BJ($_QLoli, "<EMAILCLIENT_NAME>", "</EMAILCLIENT_NAME>", $_88jiQ);
    $_QLoli = _L81BJ($_QLoli, "&lt;EMAILCLIENT_NAME&gt;", "&lt;/EMAILCLIENT_NAME&gt;", $_88jiQ);
    if(strpos($_QltJO["icon"], "ua/") !== false)
       $_QLoli = str_replace("EMAILCLIENT_ICON", "images/".$_QltJO["icon"], $_QLoli);
       else
       $_QLoli = str_replace("EMAILCLIENT_ICON", $_8fCCQ."ua/".$_QltJO["icon"], $_QLoli);
  }
  if($_88j6Q <= 0)
     $_88j6Q = 0.01;
  for($_Qli6J=0; $_Qli6J<count($_ICQjo); $_Qli6J++){
    $_QLoli = _L81BJ($_QLoli, "<EMAILCLIENT_COUNT_PERCENT$_Qli6J>", "</EMAILCLIENT_COUNT_PERCENT$_Qli6J>", sprintf("%1.1f%%", $_ICQjo[$_Qli6J] * 100 / $_88j6Q));
  }

  $_QLJfI = _L81BJ($_QLJfI, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>", $_QLoli);
  //
  $_I0Clj = _L81DB($_QLJfI, "<OS_STAT>", "</OS_STAT>");
  $_QLoli = "";
  $_Qli6J = 0;
  $_8866O = 0;
  $_ICQjo = array();
  reset($_8O0Of);
  foreach($_8O0Of as $_jOQf8 => $_QltJO){
    $_QLoli .= $_I0Clj;

    $_QLoli = _L81BJ($_QLoli, "<OS_COUNT>", "</OS_COUNT>", $_QltJO["ClicksCount"]."&nbsp;(<OS_COUNT_PERCENT$_Qli6J></OS_COUNT_PERCENT$_Qli6J>)");
    $_ICQjo[] = $_QltJO["ClicksCount"];
    $_8866O += $_QltJO["ClicksCount"];
    $_Qli6J++;
    $_QLoli = _L81BJ($_QLoli, "<OS_NAME>", "</OS_NAME>", $_jOQf8);
    $_QLoli = _L81BJ($_QLoli, "&lt;OS_NAME&gt;", "&lt;/OS_NAME&gt;", $_jOQf8);
    if(strpos($_QltJO["icon"], "ua/") !== false)
       $_QLoli = str_replace("OS_ICON", "images/".$_QltJO["icon"], $_QLoli);
       else
       $_QLoli = str_replace("OS_ICON", $_8fCCQ."os/".$_QltJO["icon"], $_QLoli);
  }
  if($_8866O <= 0)
     $_8866O = 0.01;
  for($_Qli6J=0; $_Qli6J<count($_ICQjo); $_Qli6J++){
    $_QLoli = _L81BJ($_QLoli, "<OS_COUNT_PERCENT$_Qli6J>", "</OS_COUNT_PERCENT$_Qli6J>", sprintf("%1.1f%%", $_ICQjo[$_Qli6J] * 100 / $_8866O));
  }

  $_QLJfI = _L81BJ($_QLJfI, "<OS_STAT>", "</OS_STAT>", $_QLoli);
  //

  $_I0Clj = _L81DB($_QLJfI, "<OPENINGS_LINE>", "</OPENINGS_LINE>");
  $_QLoli = "";

  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    $_QLoli .= $_I0Clj;

    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_CAMPAIGNNAME>", "</OPENINGS_CAMPAIGNNAME>", $_8totC[$_QliOt]["Name"]);
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_RECIPIENTSCOUNT>", "</OPENINGS_RECIPIENTSCOUNT>", $_8totC[$_QliOt]["RecipientsCount"]);
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_OPENINGSCOUNT>", "</OPENINGS_OPENINGSCOUNT>", $_8totC[$_QliOt]["Openings"]);

    if($_8totC[$_QliOt]["RecipientsCount"] == 0)
       $_8totC[$_QliOt]["RecipientsCount"] = 0.01;

    if($_8tO6L["TrackingIPBlocking"] != 0)
      $_QLoli = _L81BJ($_QLoli, "<OPENINGS_OPENINGSRATE>", "</OPENINGS_OPENINGSRATE>", sprintf("%1.2f%%", $_8totC[$_QliOt]["Openings"] * 100 / $_8totC[$_QliOt]["RecipientsCount"]));
      else
      $_QLoli = _L81BJ($_QLoli, "<OPENINGS_OPENINGSRATE>", "</OPENINGS_OPENINGSRATE>", $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);

    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_UNSUBSCRIBESCOUNT>", "</OPENINGS_UNSUBSCRIBESCOUNT>", $_8totC[$_QliOt]["UnsubscribesCount"]);
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_SOFTBOUNCESCOUNT>", "</OPENINGS_SOFTBOUNCESCOUNT>", $_8totC[$_QliOt]["SoftBouncesCount"]);
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_HARDBOUNCESCOUNT>", "</OPENINGS_HARDBOUNCESCOUNT>", $_8totC[$_QliOt]["HardBouncesCount"]);
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_FAILEDCOUNT>", "</OPENINGS_FAILEDCOUNT>", $_8totC[$_QliOt]["SentCountFailed"]);
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_SUCCCOUNT>", "</OPENINGS_SUCCCOUNT>", $_8totC[$_QliOt]["SentCountSucc"]);

    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_UNSUBSCRIBESCOUNTPERCENT>", "</OPENINGS_UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.2f%%", $_8totC[$_QliOt]["UnsubscribesCount"] / $_8totC[$_QliOt]["RecipientsCount"]));
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_SOFTBOUNCESCOUNTPERCENT>", "</OPENINGS_SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.2f%%", $_8totC[$_QliOt]["SoftBouncesCount"] / $_8totC[$_QliOt]["RecipientsCount"]));
    $_QLoli = _L81BJ($_QLoli, "<OPENINGS_HARDBOUNCESCOUNTPERCENT>", "</OPENINGS_HARDBOUNCESCOUNTPERCENT>", sprintf("%1.2f%%", $_8totC[$_QliOt]["HardBouncesCount"] / $_8totC[$_QliOt]["RecipientsCount"]));

    $_8O1j1 = $_8totC[$_QliOt]["Campaigns_id"]."-".$_8totC[$_QliOt]["CampaignsSendStat_id"];
    $_QLoli = str_replace('name="WhoHasOpenedBtn" value="-1"', 'name="WhoHasOpenedBtn" value="'.$_8O1j1.'"', $_QLoli);

    $_QLoli = str_replace("stat_campaigntracking.php?CampaignId=&SendStatId=", "stat_campaigntracking.php?CampaignId=".$_8totC[$_QliOt]["Campaigns_id"]."&SendStatId=".$_8totC[$_QliOt]["CampaignsSendStat_id"], $_QLoli);

    if(!$_8totC[$_QliOt]["TrackEMailOpeningsByRecipient"])
       $_QLoli = _L80DF($_QLoli, "<IF:RECIPIENTOPENINGTRACKING>", "</IF:RECIPIENTOPENINGTRACKING>");
       else {
         $_QLoli = str_replace("<IF:RECIPIENTOPENINGTRACKING>", "", $_QLoli);
         $_QLoli = str_replace("</IF:RECIPIENTOPENINGTRACKING>", "", $_QLoli);
       }

  }

  $_QLJfI = _L81BJ($_QLJfI, "<OPENINGS_LINE>", "</OPENINGS_LINE>", $_QLoli);
  //

  $_I0Clj = _L81DB($_QLJfI, "<CLICKS_LINE>", "</CLICKS_LINE>");
  $_QLoli = "";

  for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
    $_QLoli .= $_I0Clj;

    $_QLoli = _L81BJ($_QLoli, "<LINKS_CAMPAIGNNAME>", "</LINKS_CAMPAIGNNAME>", $_8totC[$_QliOt]["Name"]);
    $_QLoli = _L81BJ($_QLoli, "<LINKS_RECIPIENTSCOUNT>", "</LINKS_RECIPIENTSCOUNT>", $_8totC[$_QliOt]["RecipientsCount"]);
    $_QLoli = _L81BJ($_QLoli, "<LINKS_CLICKSCOUNT>", "</LINKS_CLICKSCOUNT>", $_8totC[$_QliOt]["LinksClicks"]);

    if($_8totC[$_QliOt]["RecipientsCount"] == 0)
       $_8totC[$_QliOt]["RecipientsCount"] = 0.01;


    if($_8tO6L["TrackingIPBlocking"] != 0)
      $_QLoli = _L81BJ($_QLoli, "<LINKS_CLICKRATE>", "</LINKS_CLICKRATE>", sprintf("%1.2f%%", $_8totC[$_QliOt]["LinksClicks"] * 100 / $_8totC[$_QliOt]["RecipientsCount"]));
      else
      $_QLoli = _L81BJ($_QLoli, "<LINKS_CLICKRATE>", "</LINKS_CLICKRATE>", $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);

    $_8O1j1 = $_8totC[$_QliOt]["Campaigns_id"]."-".$_8totC[$_QliOt]["CampaignsSendStat_id"];

    $_QLoli = str_replace("stat_campaigntracking.php?CampaignId=&SendStatId=", "stat_campaigntracking.php?CampaignId=".$_8totC[$_QliOt]["Campaigns_id"]."&SendStatId=".$_8totC[$_QliOt]["CampaignsSendStat_id"], $_QLoli);

  }

  $_QLJfI = _L81BJ($_QLJfI, "<CLICKS_LINE>", "</CLICKS_LINE>", $_QLoli);


  print $_QLJfI;
?>
