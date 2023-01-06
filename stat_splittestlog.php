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
  include_once("cron_campaigns.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("splitteststools.inc.php");


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
    $_GET["action"] = "stat_splittestlog.php";
    $_GET["ResponderType"] = "SplitTest";
    $_j01fj = $_joQIl;
    include_once("campaignsendstatselect.inc.php");
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  $_POST['SplitTestId'] = $_joQIl;

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_Itfj8 = "";

  ## Log actions

  $_Ilt8t = !isset($_POST["LogsActions"]);
  if(!$_Ilt8t) {
    if( isset($_POST["OneSplitTestLogAction"]) && $_POST["OneSplitTestLogAction"] != "" )
      $_Ilt8t = true;
    if($_Ilt8t) {
      if( !( isset($_POST["OneSplitTestLogId"]) && $_POST["OneSplitTestLogId"] != "")  )
         $_Ilt8t = false;
    }
  }

  if(  !$_Ilt8t && isset($_POST["LogsActions"]) ) {
     // nur hier die Listenaktionen machen

     if($_POST["LogsActions"] == "RemoveEntries" ) {
       $_8fIJO = $_POST["LogIDs"];
       $_IQ0Cj = array();
       _JLAAL($_8fIJO, $_IQ0Cj);
       if(count($_IQ0Cj) != 0)
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000672"].join("<br />", $_IQ0Cj);
       else
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000671"];

     }

     if($_POST["LogsActions"] == "SendAgain" ) {
       $_8fIJO = $_POST["LogIDs"];
       $_foL0Q = array();
       _JLBPF($_joQIl, $SendStatId, $_8fIJO, $_foL0Q);
       if(count($_foL0Q) != 0)
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_foL0Q);
       else
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
     }

  }

  if( isset($_POST["OneSplitTestLogAction"]) && isset($_POST["OneSplitTestLogId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneSplitTestLogAction"] == "DeleteLogEntry") {
        $_8fIJO = array();
        $_8fIJO[] = $_POST["OneSplitTestLogId"];
        $_IQ0Cj = array();
        _JLAAL($_8fIJO, $_IQ0Cj);
        if(count($_IQ0Cj) != 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000672"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000671"];
      }
      if($_POST["OneSplitTestLogAction"] == "SendAgain") {
        $_8fIJO = array();
        $_8fIJO[] = $_POST["OneSplitTestLogId"];
        $_foL0Q = array();
        _JLBPF($_joQIl, $SendStatId, $_8fIJO, $_foL0Q);
        if(count($_foL0Q) != 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_foL0Q);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
      }

  }
  ## Log actions end


  $_QLfol = "SELECT $_jJL88.*, $_QL88I.MaillistTableName FROM $_jJL88 LEFT JOIN $_QL88I ON $_QL88I.id=$_jJL88.maillists_id WHERE $_jJL88.id=$_joQIl";
  $_QL8i1 = mysql_query($_QLfol);
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
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_8totC = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_8totC[] = array("Campaigns_id"=> $_QLO0f["Campaigns_id"], "CampaignsSendStat_id" => 0, "RecipientsCount" => 0);
  }
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_86j0I` WHERE `SplitTestSendStat_id`=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol);
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
  $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration ";
  $_QLfol .= " FROM $_jClC1 WHERE id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol);
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
    $_QLfol = "SELECT ".join(", ", $_8ftCj).", Name FROM $_QLi60 WHERE id=".$_8totC[$_QliOt]["Campaigns_id"];
    $_QL8i1 = mysql_query($_QLfol);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    for($_Qli6J=0; $_Qli6J<count($_8ftCj); $_Qli6J++){
       $_8totC[$_QliOt][$_8ftCj[$_Qli6J]] = $_I1OfI[$_8ftCj[$_Qli6J]];
    }
    $_8totC[$_QliOt]["Name"] = $_I1OfI["Name"];

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
          mysql_query($_QLfol);
          _L8D88($_QLfol);
          continue;
        }
        $_QLfol = "DELETE FROM `".$_8totC[$_QliOt][$_8ftCj[$_Qli6J]]."` WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
        mysql_query($_QLfol);
        _L8D88($_QLfol);
      }
    }
    // splittesttable
    $_QLfol = "SELECT `MembersTableName`, `RandomMembersTableName` FROM `$_jClC1` WHERE id=$SendStatId";
    $_QL8i1 = mysql_query($_QLfol);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "DROP TABLE `$_I1OfI[MembersTableName]`";
    mysql_query($_QLfol);

    $_QLfol = "DROP TABLE `$_I1OfI[RandomMembersTableName]`";
    mysql_query($_QLfol);


    # don't send it again when there are no send entries
    $_QLfol = "SELECT COUNT(*) FROM `$_jClC1` WHERE id<>$SendStatId";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if($_QLO0f[0] == 0 && $_8tO6L["SendScheduler"] != 'SaveOnly' ){
      $_QLfol = "UPDATE `$_jJL88` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_joQIl";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
    }
    mysql_free_result($_QL8i1);
    #

    $_QLfol = "DELETE FROM `$_jClC1` WHERE id=$SendStatId";
    mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_QLfol = "DELETE FROM `$_8tO6L[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$SendStatId";
    mysql_query($_QLfol);
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
      }
      continue;
    }

    $_QLfol = "SELECT SentCountSucc, SentCountFailed, SentCountPossiblySent, HardBouncesCount, SoftBouncesCount, UnsubscribesCount FROM ".$_8totC[$_QliOt]["CurrentSendTableName"]." WHERE id=".$_8totC[$_QliOt]["CampaignsSendStat_id"];
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    foreach($_I1OfI as $key => $_QltJO){
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
    $_8toif["SentCountPossiblySent"] = 0;

    for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
      # no send entry at this time
      if($_8totC[$_QliOt]["CampaignsSendStat_id"] == 0) continue;

      $_ji080 = $_8totC[$_QliOt]["RStatisticsTableName"];
      // Count things while sending
      $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." AND Send='Sent'";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_8toif["SentCountSucc"] += $_jj6L6[0];

      $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." AND Send='Failed'";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_8toif["SentCountFailed"] += $_jj6L6[0];

      $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=".$_8totC[$_QliOt]["CampaignsSendStat_id"]." AND Send='PossiblySent'";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_8toif["SentCountPossiblySent"] += $_jj6L6[0];

    }
  }

  // Template
  $_8fOOf = $_QLO0f["SentDateTime"];
  if($_IQ1ji)
    $_8fOOf = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["000675"]."&quot;";
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001850"], $_8to1f, $_8fOOf), $_Itfj8, 'stat_splittestlog', 'browse_splittestslog_snipped.htm');
  $_QLJfI = str_replace('name="SplitTestId"', 'name="SplitTestId" value="'.$_POST['SplitTestId'].'"', $_QLJfI);

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_IQ1ji) {
    $_QLJfI = _L80DF($_QLJfI, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
  }

  $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_8to1f );
  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_8toif["RecipientsCount"]);

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

  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCES>", "</HARDBOUNCES>", $_8toif["HardBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<HARDBOUNCESPERCENT>", "</HARDBOUNCESPERCENT>", sprintf("%1.1f%%", $_8toif["HardBouncesCount"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCES>", "</SOFTBOUNCES>", $_8toif["SoftBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SOFTBOUNCESPERCENT>", "</SOFTBOUNCESPERCENT>", sprintf("%1.1f%%", $_8toif["SoftBouncesCount"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBES>", "</UNSUBSCRIBES>", $_8toif["UnsubscribesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<UNSUBSCRIBESPERCENT>", "</UNSUBSCRIBESPERCENT>", sprintf("%1.1f%%", $_8toif["UnsubscribesCount"] * 100 / $_8toif["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:START>", "</SENDING:START>", $_QLO0f["StartSendDateTimeFormated"]);
  if(!$_IQ1ji) {
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $_QLO0f["EndSendDateTimeFormated"]);
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $_QLO0f["SendDuration"]);
  } else {
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
  }

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

/*  if($_QLO0f["TwitterUpdate"] == "NotActivated" || empty($_QLO0f["TwitterUpdate"]))
     $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
  if($_QLO0f["TwitterUpdate"] == "Done")
     $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
  if($_QLO0f["TwitterUpdate"] == "Failed")
     if( $_QLO0f["TwitterUpdateErrorText"] == "TWITTER_UPDATE_POSTING_FAILED" )
        $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
        else
        if( $_QLO0f["TwitterUpdateErrorText"] == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
            $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);
*/

  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:SENDSTATETEXT>", "</SENDING:SENDSTATETEXT>", $_QLO0f["SendStateText"]);


  $_JoiCQ = "";
  $_JoL0L = "";

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ) AS ENDDATE ";
    $_QL8i1 = mysql_query($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_JoL0L = $_QLO0f[0];
    $_JoiCQ = $_8ft1J;
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

  $_QLfol = "SELECT {} FROM {CampaignsRStatisticsTableName} WHERE SendStat_id={CampaignsSentStatId} AND SendDateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L);

  if(isset($_POST["ShowItems"]) && ($_POST["ShowItems"] != "AllItems")) {
    if($_POST["ShowItems"] == "OnlySentItems")
       $_QLfol .= "AND Send='Sent'";
       else
    if($_POST["ShowItems"] == "OnlyFailedItems")
       $_QLfol .= "AND Send='Failed'";
       else
    if($_POST["ShowItems"] == "OnlyToSendItems")
       $_QLfol .= "AND Send='Prepared'";
       else
    if($_POST["ShowItems"] == "OnlyPossiblySentItems")
       $_QLfol .= "AND Send='PossiblySent'";
       else
       if($_POST["ShowItems"] == "OnlyHardbouncedItems")
       $_QLfol .= "AND Send='Hardbounced'";
  }

  //$_QLfol .= " ORDER BY SendDateTime";

  $_QLJfI = _JJ1E1($_8totC, $_QLfol, $_QLJfI);

  $_QLJfI = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QLJfI);

  if(!empty($_POST["ShowItems"])) {
    $_I1OoI = array();
    $_I1OoI["ShowItems"] = $_POST["ShowItems"];
    $_QLJfI = _L8AOB(array(), $_I1OoI, $_QLJfI);
  }

  if($INTERFACE_LANGUAGE != "de")
    $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);


  if($_IQ1ji) {
    $_QLJfI = _JJC1E($_QLJfI, "DeleteLogEntry");
    $_QLJfI = _JJCRD($_QLJfI, "RemoveEntries");
    $_QLJfI = _JJC1E($_QLJfI, "SendAgain");
    $_QLJfI = _JJCRD($_QLJfI, "SendAgain");
  }

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeCampaignEdit"] || !$_QLJJ6["PrivilegeCampaignBrowse"] ) {
      $_QLoli = _JJC1E($_QLoli, "DeleteLogEntry");
      $_QLoli = _JJCRD($_QLoli, "RemoveEntries");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }


  print $_QLJfI;

  function _JJ1E1($_8totC, $_QLfol, $_QLoli) {
    global $UserId, $OwnerUserId, $_QLo06, $INTERFACE_LANGUAGE, $resourcestrings, $_jJL88, $_JoiCQ, $_JoL0L, $_QLo60, $_joQIl, $SendStatId;
    global $_IttOL, $_IQ1ji, $_QlQot;
    $_Il0o6 = array();
    $_Il0o6["CampaignId"]=$_joQIl;
    $_Il0o6["SendStatId"]=$SendStatId;

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;

    $_Io01j = array();
    for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {
      $_QLlO6 = $_QLfol;

      $_QLlO6 = str_replace('{CampaignsRStatisticsTableName}', $_8totC[$_QliOt]["RStatisticsTableName"], $_QLlO6);
      $_QLlO6 = str_replace('{CampaignsSentStatId}', $_8totC[$_QliOt]["CampaignsSendStat_id"], $_QLlO6);
      $_QLlO6 = str_replace('{}', "COUNT(".$_8totC[$_QliOt]["RStatisticsTableName"].".id)", $_QLlO6);

      $_Io01j[] = "(".$_QLlO6.")";

    }

    # not union mysql merges rows not add rows therefore +
    $_QLlO6 = join(" + ", $_Io01j);

    $_QLlO6 = "SELECT ( ".$_QLlO6.")";

    $_QL8i1 = mysql_query($_QLlO6);
    _L8D88($_QLlO6);
    $_QLO0f=mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];


    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "End") )
       $_IlQQ6 = $_IlILC;

    if ( ($_IlQQ6 > $_IlILC) || ($_IlQQ6 <= 0) )
       $_IlQQ6 = 1;

    $_Iil6i = ($_IlQQ6 - 1) * $_Il1jO;

    $_QlOjt = "";
    for($_Qli6J=1; $_Qli6J<=$_IlILC; $_Qli6J++)
      if($_Qli6J != $_IlQQ6)
       $_QlOjt .= "<option>$_Qli6J</option>";
       else
       $_QlOjt .= '<option selected="selected">'.$_Qli6J.'</option>';

    $_QLoli = _L81BJ($_QLoli, "<OPTION:PAGES>", "</OPTION:PAGES>", $_QlOjt);

    // Nav-Buttons
    $_Iljoj = "";
    if($_IlQQ6 == 1) {
      $_Iljoj .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_IlQQ6 == $_IlILC) || ($_IlQll == 0) ) {
      $_Iljoj .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_IlQll == 0)
      $_Iljoj .= "  DisableItem('PageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    $_Io01j = array();
    for($_QliOt=0; $_QliOt<count($_8totC); $_QliOt++) {

      $_QLlO6 = $_QLfol;

      $_QLlO6 = str_replace('{CampaignsRStatisticsTableName}', $_8totC[$_QliOt]["RStatisticsTableName"], $_QLlO6);
      $_QLlO6 = str_replace('{CampaignsSentStatId}', $_8totC[$_QliOt]["CampaignsSendStat_id"], $_QLlO6);

      $_jf8JI = $_8totC[$_QliOt]["RStatisticsTableName"].".*, DATE_FORMAT(SendDateTime, $_QLo60) AS SentDateTime";
      $_jf8JI .= ", ". $_8totC[$_QliOt]["Campaigns_id"]." As Campaigns_id";
      $_jf8JI .= ", ". $_8totC[$_QliOt]["CampaignsSendStat_id"]." As CampaignsSendStat_id";
      $_QLlO6 = str_replace('{}', $_jf8JI, $_QLlO6);

      $_Io01j[] = "(".$_QLlO6.")";
    }

    $_QLfol = join(" UNION ", $_Io01j);

    $_QLfol .= " ORDER BY SendDateTime";
    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      if($_QLO0f["Send"] == 'Prepared')
        $_8fQ0J = '<img src="images/cross.gif" alt="" width="16" height="16" />&nbsp;';
        else
        if($_QLO0f["Send"] == 'Sent')
        $_8fQ0J = '<img src="images/check16.gif" alt="" width="16" height="16" />&nbsp;';
        else
        if($_QLO0f["Send"] == 'PossiblySent')
        $_8fQ0J = '<img src="images/minus16.gif" alt="" width="16" height="16" />&nbsp;';
        else
        if($_QLO0f["Send"] == 'Hardbounced')
        $_8fQ0J = '<img src="images/user_bounced.gif" alt="" width="16" height="16" />&nbsp;';
        else
        $_8fQ0J = '<img src="images/cross16.gif" alt="" width="16" height="16" />&nbsp;';
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:SENTDATE>", "</LIST:SENTDATE>", $_8fQ0J.$_QLO0f["SentDateTime"]);

      if($_IttOL != 0 && $_QLO0f["recipients_id"] != 0)
        $EMail = _JLAOB("u_EMail", $_IttOL, $_QLO0f["recipients_id"]);
      else
        $EMail = $_QLO0f["EMail"];

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $EMail);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:SUBJECT>", "</LIST:SUBJECT>", htmlspecialchars(_LCRC8($_QLO0f["MailSubject"]), ENT_COMPAT, $_QLo06));
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $resourcestrings[$INTERFACE_LANGUAGE]["MailSend".$_QLO0f["Send"]]);

      $_Ql0fO = str_replace("Result_id=", "Result_id=".$_QLO0f["id"], $_Ql0fO);
      $_Ql0fO = str_replace("CampaignId=", "CampaignId=".$_QLO0f["Campaigns_id"], $_Ql0fO);
      $_Ql0fO = str_replace("SendStatId=", "SendStatId=".$_QLO0f["CampaignsSendStat_id"], $_Ql0fO);

      # modify id
      $_QLO0f["id"] = $_QLO0f["Campaigns_id"]."-".$_QLO0f["CampaignsSendStat_id"]."-".$_QLO0f["id"];

      if( ($_QLO0f["Send"] == 'Failed' || $_QLO0f["Send"] == 'PossiblySent') && !$_IQ1ji && $EMail != "")
        $_Ql0fO = str_replace ('name="SendAgain"', 'name="SendAgain" value="'.$_QLO0f["id"].'"', $_Ql0fO);
        else
        $_Ql0fO = _JJC1E($_Ql0fO, "SendAgain");
      $_Ql0fO = str_replace ('name="DeleteLogEntry"', 'name="DeleteLogEntry" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      $_Ql0fO = str_replace ('name="LogIDs[]"', 'name="LogIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      // not an admin, check rights for mailinglist
      if($OwnerUserId != 0) {
        if($_IttOL != 0) {
          $_QLfol = "SELECT COUNT(*) FROM $_QlQot WHERE maillists_id=$_IttOL AND users_id=$UserId";
          $_I1O6j = mysql_query($_QLfol);
          _L8D88($_QLfol);
          $_Il6l0 = mysql_fetch_row($_I1O6j);
          if($_Il6l0[0] == 0) {
              $_Ql0fO = _JJC1E($_Ql0fO, "SendAgain");
          }
          mysql_free_result($_I1O6j);
        }
      }

      $_QlIf1 .= $_Ql0fO;
    } # while($_QLO0f=mysql_fetch_array($_QL8i1))
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    return $_QLoli;
  }

  function _JLAOB($_8fjoC, $_IttOL, $_Jt0lt) {
   global $_QL88I, $_QLttI;
   $_QltJO = "";
   $_QLfol = "SELECT MaillistTableName FROM $_QL88I WHERE id=".intval($_IttOL);
   $_QL8i1 = mysql_query($_QLfol);
   if(!$_QL8i1) return $_QltJO;
   $_QLO0f = mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_QLfol = "SELECT `$_8fjoC` FROM $_QLO0f[MaillistTableName] WHERE id=".intval($_Jt0lt);
   $_QL8i1 = mysql_query($_QLfol);
   if(!$_QL8i1) return $_QltJO;
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   return $_QLO0f[0];
  }

  function _JLAAL($_8fIJO, &$_IQ0Cj) {
    global $_QLi60;

    sort($_8fIJO);
    $_8tC8o = 0;
    for($_Qli6J=0; $_Qli6J<count($_8fIJO); $_Qli6J++) {
      $_IOCjL = explode("-", $_8fIJO[$_Qli6J]);
      $_8tiJl = intval($_IOCjL[0]);
      $_8tLjf = intval($_IOCjL[1]);
      $_8tlQ1 = intval($_IOCjL[2]);

      if($_8tC8o != $_8tiJl) {
        $_8tC8o = $_8tiJl;
        $_QLfol = "SELECT RStatisticsTableName FROM $_QLi60 WHERE id=$_8tC8o";
        $_QL8i1 = mysql_query($_QLfol);
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
      }

      $_QLfol = "DELETE FROM $_QLO0f[RStatisticsTableName] WHERE id=$_8tlQ1";
      mysql_query($_QLfol);
      if(mysql_error($_QLttI) != "")
        $_IQ0Cj[] = mysql_error($_QLttI);

    }
  }

 function _JLBPF($_joQIl, $SendStatId, $_8fIJO, &$_foL0Q) {
  global $_QLi60, $_jJL88, $_QL88I, $_QlQot, $UserId, $OwnerUserId, $_QLttI;
  global $INTERFACE_LANGUAGE, $_Ijt0i, $_IQQot, $_QLo06;


  sort($_8fIJO);

  $_QLfol = "SELECT $_jJL88.*, $_QL88I.MaillistTableName, $_QL88I.StatisticsTableName, $_QL88I.LocalBlocklistTableName, $_jJL88.maillists_id FROM $_jJL88 LEFT JOIN $_QL88I ON $_QL88I.id=$_jJL88.maillists_id WHERE $_jJL88.id=$_joQIl";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_jClC1 = $_I1OfI["CurrentSendTableName"];
    $_86j0I = $_I1OfI["SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName"];
    $_86j8Q = $_I1OfI["CampaignsForSplitTestTableName"];
    $_I8I6o = $_I1OfI["MaillistTableName"];
    $_IttOL = $_I1OfI["maillists_id"];
    $_I8jjj = $_I1OfI["StatisticsTableName"];
    $_jjj8f = $_I1OfI["LocalBlocklistTableName"];
    $FormId = $_I1OfI["forms_id"];
  } else{
    $_foL0Q[] = "Can't find mailing list.";
    return false;
  }

  // not an admin, check rights for mailinglist
  if($OwnerUserId != 0) {
    if($_IttOL != 0) {
      $_QLfol = "SELECT COUNT(*) FROM $_QlQot WHERE maillists_id=$_IttOL AND users_id=$UserId";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_Il6l0 = mysql_fetch_row($_I1O6j);
      if($_Il6l0[0] == 0) {
          $_foL0Q[] = "You have no permissions for this mailing list.";
          return false;
      }
      mysql_free_result($_I1O6j);
    }
  }

  $_JjiJl = 0;

  $_Qll8O = $UserId;
  if($OwnerUserId != 0)
    $_Qll8O = $OwnerUserId;


  // ECG
  $_jlt10 = _JOLQE("ECGListCheck");
  if($_jlt10){
    $_8tC8o = 0;
    $_J06Ji = array();                        
    for($_Qli6J=0; $_Qli6J<count($_8fIJO); $_Qli6J++) {
      $_IOCjL = explode("-", $_8fIJO[$_Qli6J]);
      $_8tiJl = intval($_IOCjL[0]);
      $_8tLjf = intval($_IOCjL[1]);
      $_8tlQ1 = intval($_IOCjL[2]);

      if($_8tC8o != $_8tiJl) {
        $_8tC8o = $_8tiJl;
        $_QLfol = "SELECT * FROM $_QLi60 WHERE id=$_8tC8o";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_I1OfI = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
      }

      $_QLfol = "SELECT recipients_id, Send FROM `$_I1OfI[RStatisticsTableName]` WHERE id=$_8tlQ1";
      $_8fJ6L = mysql_query($_QLfol, $_QLttI);
      if(!$_8fJ6L) continue;
      $_fifQL = mysql_fetch_array($_8fJ6L);
      mysql_free_result($_8fJ6L);
      if($_fifQL["Send"] != "Failed" && $_fifQL["Send"] != "PossiblySent") continue; // list operations, send failed only
      $_IfLJj = $_fifQL["recipients_id"];

      $_QLfol = "SELECT `u_EMail` FROM `$_I8I6o` WHERE `id`=$_IfLJj";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
        $_J06Ji[] = array("email" => $_QLO0f["u_EMail"]/*, "id" => $_QLO0f["id"]*/);
      }else{
        continue;
      }
    }  
    $_J0fIj = array();
    $_J08Q1 = "";
    $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1);    
    if(!$_J0t0L) // request failed, is ever in ECG-liste
       $_J0fIj = $_J06Ji;
    unset($_J06Ji); 
  }
  // ECG /  

  $_8tC8o = 0;

  for($_Qli6J=0; $_Qli6J<count($_8fIJO); $_Qli6J++) {
    $_IOCjL = explode("-", $_8fIJO[$_Qli6J]);
    $_8tiJl = intval($_IOCjL[0]);
    $_8tLjf = intval($_IOCjL[1]);
    $_8tlQ1 = intval($_IOCjL[2]);

    if($_8tC8o != $_8tiJl) {
      $_8tC8o = $_8tiJl;
      $_QLfol = "SELECT * FROM $_QLi60 WHERE id=$_8tC8o";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }


    // MTA
    $_QLfol = "SELECT mtas_id FROM `$_I1OfI[MTAsTableName]` WHERE `Campaigns_id`=$_8tiJl ORDER BY sortorder LIMIT 0, 1"; // only the first
    $_J6j0L = mysql_query($_QLfol, $_QLttI);
    if(!$_J6j0L || mysql_num_rows($_J6j0L) == 0) {
      $_foL0Q[] = $commonmsgHTMLMTANotFound;
      return false;
    } else {
      $_J00C0 = mysql_fetch_assoc($_J6j0L);
      mysql_free_result($_J6j0L);
      $_I1OfI["mtas_id"] = $_J00C0["mtas_id"];
    }

    // get group ids if specified for unsubscribe link
    if(!isset($_I1OfI["GroupIds"])) {
      $_jt0QC = array();
      $_J0ILt = "SELECT `ml_groups_id` FROM $_I1OfI[GroupsTableName] WHERE `Campaigns_id`=$_8tiJl ";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt[0];
      }
      mysql_free_result($_J0jIQ);

      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT `ml_groups_id` FROM $_I1OfI[NotInGroupsTableName] WHERE `Campaigns_id`=$_8tiJl ";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }

      if(count($_jt0QC) > 0)
        $_I1OfI["GroupIds"] = join(",", $_jt0QC);
    }

    $_IfLJj = 0;
    $MailingListId = $_IttOL;

    $_QLfol = "SELECT recipients_id, Send, `MailSubject` FROM `$_I1OfI[RStatisticsTableName]` WHERE id=$_8tlQ1";
    $_8fJ6L = mysql_query($_QLfol, $_QLttI);
    if(!$_8fJ6L) continue;
    $_fifQL = mysql_fetch_assoc($_8fJ6L);
    mysql_free_result($_8fJ6L);
    if($_fifQL["Send"] != "Failed" && $_fifQL["Send"] != "PossiblySent") continue; // list operations, send failed only
    $_IfLJj = $_fifQL["recipients_id"];
    $_I1OfI["MailSubject"] = $_fifQL["MailSubject"]; // override subject, it can be a random variant

    $_QLfol = "SELECT u_EMail FROM $_I8I6o WHERE id=$_IfLJj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_IL8oI = $_QLO0f["u_EMail"];
    }else{
      $_foL0Q[] = "member $_IfLJj doesn't exists in mailinglist.";
      continue;
    }

    //ECGList
    if($_jlt10){
      $_J0olI = array_search($_IL8oI, array_column($_J0fIj, 'email')) !== false;
      if($_J0olI){
        $_foL0Q[] = "member with email address '$_IL8oI' is in ECG-Liste.";
        continue;
      }
    }

    if(_J18FQ($_IL8oI, $_IttOL, $_jjj8f )) {
     $_foL0Q[] = "member with email address '$_IL8oI' is in local black list.";
     continue;
    }
    if(_J18DA($_IL8oI)) {
     $_foL0Q[] = "member with email address '$_IL8oI' is in global black list.";
     continue;
    }

    // old entry
    $_QLfol = "DELETE FROM `$_I1OfI[RStatisticsTableName]` WHERE id=$_8tlQ1";
    mysql_query($_QLfol, $_QLttI);
    //$_JjiJl--; NOT HERE!!!

    // new entry
    $_QLfol = "INSERT INTO `$_I1OfI[RStatisticsTableName]` SET `SendStat_id`=$_8tLjf, `MailSubject`="._LRAFO(unhtmlentities($_I1OfI["MailSubject"], $_QLo06, false)).", `SendDateTime`=NOW(), `recipients_id`=$_IfLJj, `Send`='Prepared'";
    mysql_query($_QLfol, $_QLttI);

    $_JJQ6I = 0;
    if(mysql_affected_rows($_QLttI) > 0) {
      $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_JJIl0=mysql_fetch_array($_JJQlj);
      $_JJQ6I = $_JJIl0[0];
      mysql_free_result($_JJQlj);
    } else {
      $_foL0Q[] =  "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
      continue;
    }

    //
    $_QLfol = "INSERT INTO $_IQQot SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$_Qll8O, `Source`='Campaign', `Source_id`=$_I1OfI[id], `Additional_id`=0, `SendId`=$_8tLjf, `maillists_id`=$_IttOL, `recipients_id`=$_IfLJj, `mtas_id`=$_I1OfI[mtas_id], `LastSending`=NOW(), `MailSubject`="._LRAFO(unhtmlentities($_I1OfI["MailSubject"], $_QLo06, false));
    mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) != "") {
      $_foL0Q[] = "MySQL error while adding to out queue: ".mysql_error($_QLttI);
      continue;
    }

    $_JjiJl++;

  } # for


  if($_JjiJl > 0) {
    for($_Qli6J=0; $_Qli6J<count($_8fIJO); $_Qli6J++) {
      $_IOCjL = explode("-", $_8fIJO[$_Qli6J]);
      $_8tiJl = intval($_IOCjL[0]);
      $_8tLjf = intval($_IOCjL[1]);
      $_8tlQ1 = intval($_IOCjL[2]);

      if($_8tC8o != $_8tiJl) {
        $_8tC8o = $_8tiJl;
        $_QLfol = "SELECT * FROM $_QLi60 WHERE id=$_8tC8o";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_I1OfI = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
      }

      // campaign
      $_QLfol = "UPDATE `$_I1OfI[CurrentSendTableName]` SET `SendState`='ReSending' WHERE `id`=$_8tLjf";
      mysql_query($_QLfol, $_QLttI);


    }

    // split test itself
    $_QLfol = "UPDATE `$_jClC1` SET `SendState`='ReSending' WHERE `id`=$SendStatId";
    mysql_query($_QLfol, $_QLttI);

  }

 }
?>
