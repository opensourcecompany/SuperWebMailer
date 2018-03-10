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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("cron_campaigns.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("splitteststools.inc.php");


  if(isset($_POST['SplitTestId']))
    $_Iloio = intval($_POST['SplitTestId']);
  else
    if(isset($_GET['SplitTestId']))
      $_Iloio = intval($_GET['SplitTestId']);
      else
      if ( isset($_POST['OneSplitTestListId']) )
         $_Iloio = intval($_POST['OneSplitTestListId']);

  // from campaignsendstatselect.inc.php
  if(isset($_POST['CampaignId']))
    $_Iloio = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_Iloio = intval($_GET['CampaignId']);
  //

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);

  if(!isset($_Iloio) && isset($_POST["ResponderId"]))
     $_Iloio = intval($_POST["ResponderId"]);
     else
     if(!isset($_Iloio) && isset($_GET["ResponderId"]))
         $_Iloio = intval($_GET["ResponderId"]);

  if(!isset($_Iloio)) {
    $_GET["ResponderType"] = "SplitTest";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_Iloio = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_splittestlog.php";
    $_GET["ResponderType"] = "SplitTest";
    $_I6lOO = $_Iloio;
    include_once("campaignsendstatselect.inc.php");
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  $_POST['SplitTestId'] = $_Iloio;

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_I0600 = "";

  ## Log actions

  $_I680t = !isset($_POST["LogsActions"]);
  if(!$_I680t) {
    if( isset($_POST["OneSplitTestLogAction"]) && $_POST["OneSplitTestLogAction"] != "" )
      $_I680t = true;
    if($_I680t) {
      if( !( isset($_POST["OneSplitTestLogId"]) && $_POST["OneSplitTestLogId"] != "")  )
         $_I680t = false;
    }
  }

  if(  !$_I680t && isset($_POST["LogsActions"]) ) {
     // nur hier die Listenaktionen machen

     if($_POST["LogsActions"] == "RemoveEntries" ) {
       $_6iOi1 = $_POST["LogIDs"];
       $_QtIiC = array();
       _LLR1B($_6iOi1, $_QtIiC);
       if(count($_QtIiC) != 0)
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000672"].join("<br />", $_QtIiC);
       else
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000671"];

     }

     if($_POST["LogsActions"] == "SendAgain" ) {
       $_6iOi1 = $_POST["LogIDs"];
       $_6io6C = array();
       _LLRJD($_Iloio, $SendStatId, $_6iOi1, $_6io6C);
       if(count($_6io6C) != 0)
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_6io6C);
       else
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
     }

  }

  if( isset($_POST["OneSplitTestLogAction"]) && isset($_POST["OneSplitTestLogId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneSplitTestLogAction"] == "DeleteLogEntry") {
        $_6iOi1 = array();
        $_6iOi1[] = $_POST["OneSplitTestLogId"];
        $_QtIiC = array();
        _LLR1B($_6iOi1, $_QtIiC);
        if(count($_QtIiC) != 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000672"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000671"];
      }
      if($_POST["OneSplitTestLogAction"] == "SendAgain") {
        $_6iOi1 = array();
        $_6iOi1[] = $_POST["OneSplitTestLogId"];
        $_6io6C = array();
        _LLRJD($_Iloio, $SendStatId, $_6iOi1, $_6io6C);
        if(count($_6io6C) != 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_6io6C);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
      }

  }
  ## Log actions end


  $_QJlJ0 = "SELECT $_IooOQ.*, $_Q60QL.MaillistTableName FROM $_IooOQ LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IooOQ.maillists_id WHERE $_IooOQ.id=$_Iloio";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_6lLOl = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_6llff = $_6lLOl["Name"];
  $_j0fti = $_6lLOl["CurrentSendTableName"];
  $_QlQC8 = $_6lLOl["MaillistTableName"];
  $_I0o0o = $_6lLOl["maillists_id"];
  $_6CoIO = $_6lLOl["CampaignsForSplitTestTableName"];
  $_6CoIt = $_6lLOl["SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName"];

  $_QJlJ0 = "SELECT `Campaigns_id` FROM `$_6CoIO`";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_6llOC = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_6llOC[] = array("Campaigns_id"=> $_Q6Q1C["Campaigns_id"], "CampaignsSendStat_id" => 0, "RecipientsCount" => 0);
  }
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_6CoIt` WHERE `SplitTestSendStat_id`=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    for($_Q6llo=0; $_Q6llo<count($_6llOC); $_Q6llo++)
      if ($_6llOC[$_Q6llo]["Campaigns_id"] == $_Q6Q1C["Campaigns_id"]) {
        $_6llOC[$_Q6llo]["CampaignsSendStat_id"] = $_Q6Q1C["CampaignsSendStat_id"];
        $_6llOC[$_Q6llo]["RecipientsCount"] = $_Q6Q1C["RecipientsCount"];
        break;
      }
  }
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS SentDateTime, DATE_FORMAT(StartSendDateTime, $_If0Ql) AS STARTDATE, ";
  $_QJlJ0 .= "DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, ";
  $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration ";
  $_QJlJ0 .= " FROM $_j0fti WHERE id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  // override it with old parameters because user can change it later
  $_6lLOl["WinnerType"] = $_Q6Q1C["WinnerType"];
  $_6lLOl["TestType"] = $_Q6Q1C["TestType"];
  $_6lLOl["ListPercentage"] = $_Q6Q1C["ListPercentage"];

  $_QtILf = $_Q6Q1C["SendState"] == 'Sending' || $_Q6Q1C["SendState"] == 'Waiting' || $_Q6Q1C["SendState"] == 'PreparingWinnerCampaign' || $_Q6Q1C["SendState"] == 'SendingWinnerCampaign';
  $_6L0Q0 = $_Q6Q1C["STARTDATE"];

  switch ($_Q6Q1C["SendState"]) {
    case 'Preparing': $_Q6Q1C["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001840"];
                 break;
    case 'Waiting': $_Q6Q1C["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001841"];
                 break;
    case 'PreparingWinnerCampaign': $_Q6Q1C["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001842"];
                 break;
    case 'SendingWinnerCampaign': $_Q6Q1C["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["001843"];
                 break;
    case 'Done': $_Q6Q1C["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["MailSendSent"];
                 break;
    default:
     $_Q6Q1C["SendStateText"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
  }


  // Sums and tablenames
  $_f006J = array();

  $_6L0f1 = array("CurrentSendTableName", "RStatisticsTableName", "ArchiveTableName", "TrackingOpeningsTableName", "TrackingOpeningsByRecipientTableName", "TrackingLinksTableName", "TrackingLinksByRecipientTableName", "TrackingUserAgentsTableName", "TrackingOSsTableName");

  $_f006J["RecipientsCount"] = 0;
  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    if($_QtILf)
      $_f006J["RecipientsCount"] += $_6llOC[$_Qf0Ct]["RecipientsCount"];
    $_QJlJ0 = "SELECT ".join(", ", $_6L0f1).", Name FROM $_Q6jOo WHERE id=".$_6llOC[$_Qf0Ct]["Campaigns_id"];
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    for($_Q6llo=0; $_Q6llo<count($_6L0f1); $_Q6llo++){
       $_6llOC[$_Qf0Ct][$_6L0f1[$_Q6llo]] = $_Q8OiJ[$_6L0f1[$_Q6llo]];
    }
    $_6llOC[$_Qf0Ct]["Name"] = $_Q8OiJ["Name"];

    mysql_free_result($_Q60l1);
  }

  if(!$_QtILf || $_Q6Q1C["SendState"] == 'SendingWinnerCampaign' && $_Q6Q1C["RecipientsCount"] > $_f006J["RecipientsCount"])
    $_f006J["RecipientsCount"] = $_Q6Q1C["RecipientsCount"];
    else
    if($_QtILf && $_6lLOl["TestType"] == "TestSendToListPercentage" && $_Q6Q1C["RecipientsCount"] > $_f006J["RecipientsCount"])
       $_f006J["RecipientsCount"] = $_Q6Q1C["RecipientsCount"];

  // Remove Send entry
  if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && !$_QtILf ) {
    // campaign itself
    for($_Q6llo=0; $_Q6llo<count($_6L0f1); $_Q6llo++){
      for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
        if($_6L0f1[$_Q6llo] == "CurrentSendTableName"){
          $_QJlJ0 = "DELETE FROM `".$_6llOC[$_Qf0Ct][$_6L0f1[$_Q6llo]]."` WHERE id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
          mysql_query($_QJlJ0);
          _OAL8F($_QJlJ0);
          continue;
        }
        $_QJlJ0 = "DELETE FROM `".$_6llOC[$_Qf0Ct][$_6L0f1[$_Q6llo]]."` WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
        mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
      }
    }
    // splittesttable
    $_QJlJ0 = "SELECT `MembersTableName`, `RandomMembersTableName` FROM `$_j0fti` WHERE id=$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "DROP TABLE `$_Q8OiJ[MembersTableName]`";
    mysql_query($_QJlJ0);

    $_QJlJ0 = "DROP TABLE `$_Q8OiJ[RandomMembersTableName]`";
    mysql_query($_QJlJ0);


    # don't send it again when there are no send entries
    $_QJlJ0 = "SELECT COUNT(*) FROM `$_j0fti` WHERE id<>$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if($_Q6Q1C[0] == 0 && $_6lLOl["SendScheduler"] != 'SaveOnly' ){
      $_QJlJ0 = "UPDATE `$_IooOQ` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_Iloio";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
    }
    mysql_free_result($_Q60l1);
    #

    $_QJlJ0 = "DELETE FROM `$_j0fti` WHERE id=$SendStatId";
    mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_QJlJ0 = "DELETE FROM `$_6lLOl[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$SendStatId";
    mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);


    include("browsesplittests.php");
    exit;
  }
  // Remove Send entry /

  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {

    # no send entry at this time
    if($_6llOC[$_Qf0Ct]["CampaignsSendStat_id"] == 0) {

      $_f008O = array("SentCountSucc", "SentCountFailed", "SentCountPossiblySent", "HardBouncesCount", "SoftBouncesCount", "UnsubscribesCount");
      reset($_f008O);
      foreach($_f008O as $key){
        $_f006J[$key] = 0;
      }
      continue;
    }

    $_QJlJ0 = "SELECT SentCountSucc, SentCountFailed, SentCountPossiblySent, HardBouncesCount, SoftBouncesCount, UnsubscribesCount FROM ".$_6llOC[$_Qf0Ct]["CurrentSendTableName"]." WHERE id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    foreach($_Q8OiJ as $key => $_Q6ClO){
      if(!isset($_f006J[$key]))
        $_f006J[$key] = 0;
      $_f006J[$key] += $_Q6ClO;
    }
    mysql_free_result($_Q60l1);
  }

  if($_QtILf) {
    $_f006J["SentCountSucc"] = 0;
    $_f006J["SentCountFailed"] = 0;
    $_f006J["SentCountPossiblySent"] = 0;

    for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
      # no send entry at this time
      if($_6llOC[$_Qf0Ct]["CampaignsSendStat_id"] == 0) continue;

      $_j08fl = $_6llOC[$_Qf0Ct]["RStatisticsTableName"];
      // Count things while sending
      $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." AND Send='Sent'";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_f006J["SentCountSucc"] += $_IO08Q[0];

      $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." AND Send='Failed'";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_f006J["SentCountFailed"] += $_IO08Q[0];

      $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." AND Send='PossiblySent'";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_f006J["SentCountPossiblySent"] += $_IO08Q[0];

    }
  }

  // Template
  $_6L16j = $_Q6Q1C["SentDateTime"];
  if($_QtILf)
    $_6L16j = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["000675"]."&quot;";
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001850"], $_6llff, $_6L16j), $_I0600, 'stat_splittestlog', 'browse_splittestslog_snipped.htm');
  $_QJCJi = str_replace('name="SplitTestId"', 'name="SplitTestId" value="'.$_POST['SplitTestId'].'"', $_QJCJi);

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_QtILf) {
    $_QJCJi = _OP6PQ($_QJCJi, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
  }

  $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_6llff );
  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_f006J["RecipientsCount"]);

  if($_f006J["RecipientsCount"] == 0)
    $_f006J["RecipientsCount"] = 0.01;

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_f006J["SentCountSucc"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_f006J["SentCountSucc"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_f006J["SentCountFailed"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_f006J["SentCountFailed"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_f006J["SentCountPossiblySent"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_f006J["SentCountPossiblySent"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCES>", "</HARDBOUNCES>", $_f006J["HardBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESPERCENT>", "</HARDBOUNCESPERCENT>", sprintf("%1.1f%%", $_f006J["HardBouncesCount"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCES>", "</SOFTBOUNCES>", $_f006J["SoftBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESPERCENT>", "</SOFTBOUNCESPERCENT>", sprintf("%1.1f%%", $_f006J["SoftBouncesCount"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBES>", "</UNSUBSCRIBES>", $_f006J["UnsubscribesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESPERCENT>", "</UNSUBSCRIBESPERCENT>", sprintf("%1.1f%%", $_f006J["UnsubscribesCount"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:START>", "</SENDING:START>", $_Q6Q1C["StartSendDateTimeFormated"]);
  if(!$_QtILf) {
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $_Q6Q1C["EndSendDateTimeFormated"]);
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $_Q6Q1C["SendDuration"]);
  } else {
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
  }

  if($_Q6Q1C["SendState"] == 'Sending' || $_Q6Q1C["SendState"] == 'Waiting')
    $_QJCJi = _OPR6L($_QJCJi, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
    else
    if ($_Q6Q1C["SendState"] == 'PreparingWinnerCampaign' || $_Q6Q1C["SendState"] == 'SendingWinnerCampaign' || $_Q6Q1C["SendState"] == 'Done') {

      # get winner campaign and clicks
      if( $_6lLOl["TestType"] == 'TestSendToAllMembers' ) {
        $_jOljL = 0;
        $_Q6Q1C["WinnerCampaigns_id"] = _LLLQF($_6lLOl, $_Q6Q1C, $_6llOC, false, $_jOljL);
        $_Q6Q1C["WinnerCampaignsMaxClicks"] = $_jOljL;
      }

      for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
        if($_6llOC[$_Qf0Ct]["Campaigns_id"] == $_Q6Q1C["WinnerCampaigns_id"]) {
          if($_6lLOl["WinnerType"] == 'WinnerOpens')
            $_QJCJi = _OPR6L($_QJCJi, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001851"], $_6llOC[$_Qf0Ct]["Name"], $_Q6Q1C["WinnerCampaignsMaxClicks"]));
          else
            $_QJCJi = _OPR6L($_QJCJi, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001852"], $_6llOC[$_Qf0Ct]["Name"], $_Q6Q1C["WinnerCampaignsMaxClicks"]));
        }
      }

    } else
      $_QJCJi = _OPR6L($_QJCJi, "<SPLITTESTWINNERCAMPAIGN>", "</SPLITTESTWINNERCAMPAIGN>", $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"]);

/*  if($_Q6Q1C["TwitterUpdate"] == "NotActivated" || empty($_Q6Q1C["TwitterUpdate"]))
     $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
  if($_Q6Q1C["TwitterUpdate"] == "Done")
     $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
  if($_Q6Q1C["TwitterUpdate"] == "Failed")
     if( $_Q6Q1C["TwitterUpdateErrorText"] == "TWITTER_UPDATE_POSTING_FAILED" )
        $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
        else
        if( $_Q6Q1C["TwitterUpdateErrorText"] == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
            $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);
*/

  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:SENDSTATETEXT>", "</SENDING:SENDSTATETEXT>", $_Q6Q1C["SendStateText"]);


  $_jC1lo = "";
  $_jCQ0I = "";

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QJlJ0 = "SELECT DATE_FORMAT(NOW(), $_If0Ql) AS ENDDATE ";
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_jCQ0I = $_Q6Q1C[0];
    $_jC1lo = $_6L0Q0;
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

  $_QJlJ0 = "SELECT {} FROM {CampaignsRStatisticsTableName} WHERE SendStat_id={CampaignsSentStatId} AND SendDateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I);

  if(isset($_POST["ShowItems"]) && ($_POST["ShowItems"] != "AllItems")) {
    if($_POST["ShowItems"] == "OnlySentItems")
       $_QJlJ0 .= "AND Send='Sent'";
       else
    if($_POST["ShowItems"] == "OnlyFailedItems")
       $_QJlJ0 .= "AND Send='Failed'";
       else
    if($_POST["ShowItems"] == "OnlyToSendItems")
       $_QJlJ0 .= "AND Send='Prepared'";
       else
    if($_POST["ShowItems"] == "OnlyPossiblySentItems")
       $_QJlJ0 .= "AND Send='PossiblySent'";
  }

  //$_QJlJ0 .= " ORDER BY SendDateTime";

  $_QJCJi = _LLDEE($_6llOC, $_QJlJ0, $_QJCJi);

  $_QJCJi = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QJCJi);

  if(!empty($_POST["ShowItems"])) {
    $_Q8otJ = array();
    $_Q8otJ["ShowItems"] = $_POST["ShowItems"];
    $_QJCJi = _OPFJA(array(), $_Q8otJ, $_QJCJi);
  }

  if($INTERFACE_LANGUAGE != "de")
    $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);


  if($_QtILf) {
    $_QJCJi = _LJ6B1($_QJCJi, "DeleteLogEntry");
    $_QJCJi = _LJRLJ($_QJCJi, "RemoveEntries");
    $_QJCJi = _LJ6B1($_QJCJi, "SendAgain");
    $_QJCJi = _LJRLJ($_QJCJi, "SendAgain");
  }

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeCampaignEdit"] || !$_QJojf["PrivilegeCampaignBrowse"] ) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteLogEntry");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveEntries");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }


  print $_QJCJi;

  function _LLDEE($_6llOC, $_QJlJ0, $_Q6ICj) {
    global $UserId, $OwnerUserId, $_Q6QQL, $INTERFACE_LANGUAGE, $resourcestrings, $_IooOQ, $_jC1lo, $_jCQ0I, $_Q6QiO, $_Iloio, $SendStatId;
    global $_I0o0o, $_QtILf, $_Q6fio;
    $_I61Cl = array();
    $_I61Cl["CampaignId"]=$_Iloio;
    $_I61Cl["SendStatId"]=$SendStatId;

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;

    $_I1l61 = array();
    for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
      $_QtjtL = $_QJlJ0;

      $_QtjtL = str_replace('{CampaignsRStatisticsTableName}', $_6llOC[$_Qf0Ct]["RStatisticsTableName"], $_QtjtL);
      $_QtjtL = str_replace('{CampaignsSentStatId}', $_6llOC[$_Qf0Ct]["CampaignsSendStat_id"], $_QtjtL);
      $_QtjtL = str_replace('{}', "COUNT(".$_6llOC[$_Qf0Ct]["RStatisticsTableName"].".id)", $_QtjtL);

      $_I1l61[] = "(".$_QtjtL.")";

    }

    # not union mysql merges rows not add rows therefore +
    $_QtjtL = join(" + ", $_I1l61);

    $_QtjtL = "SELECT ( ".$_QtjtL.")";

    $_Q60l1 = mysql_query($_QtjtL);
    _OAL8F($_QtjtL);
    $_Q6Q1C=mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];


    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneSplitTestLogId"] ) && ($_POST["OneSplitTestLogId"] == "End") )
       $_I6Q6O = $_I6IJ8;

    if ( ($_I6Q6O > $_I6IJ8) || ($_I6Q6O <= 0) )
       $_I6Q6O = 1;

    $_IJQQI = ($_I6Q6O - 1) * $_I6Q68;

    $_Q6i6i = "";
    for($_Q6llo=1; $_Q6llo<=$_I6IJ8; $_Q6llo++)
      if($_Q6llo != $_I6Q6O)
       $_Q6i6i .= "<option>$_Q6llo</option>";
       else
       $_Q6i6i .= '<option selected="selected">'.$_Q6llo.'</option>';

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:PAGES>", "</OPTION:PAGES>", $_Q6i6i);

    // Nav-Buttons
    $_I6ICC = "";
    if($_I6Q6O == 1) {
      $_I6ICC .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_I6Q6O == $_I6IJ8) || ($_I6Qfj == 0) ) {
      $_I6ICC .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_I6Qfj == 0)
      $_I6ICC .= "  DisableItem('PageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    $_I1l61 = array();
    for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {

      $_QtjtL = $_QJlJ0;

      $_QtjtL = str_replace('{CampaignsRStatisticsTableName}', $_6llOC[$_Qf0Ct]["RStatisticsTableName"], $_QtjtL);
      $_QtjtL = str_replace('{CampaignsSentStatId}', $_6llOC[$_Qf0Ct]["CampaignsSendStat_id"], $_QtjtL);

      $_Iijl0 = $_6llOC[$_Qf0Ct]["RStatisticsTableName"].".*, DATE_FORMAT(SendDateTime, $_Q6QiO) AS SentDateTime";
      $_Iijl0 .= ", ". $_6llOC[$_Qf0Ct]["Campaigns_id"]." As Campaigns_id";
      $_Iijl0 .= ", ". $_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." As CampaignsSendStat_id";
      $_QtjtL = str_replace('{}', $_Iijl0, $_QtjtL);

      $_I1l61[] = "(".$_QtjtL.")";
    }

    $_QJlJ0 = join(" UNION ", $_I1l61);

    $_QJlJ0 .= " ORDER BY SendDateTime";
    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      if($_Q6Q1C["Send"] == 'Prepared')
        $_6itQQ = '<img src="images/cross.gif" alt="" width="16" height="16" />&nbsp;';
        else
        if($_Q6Q1C["Send"] == 'Sent')
        $_6itQQ = '<img src="images/check16.gif" alt="" width="16" height="16" />&nbsp;';
        else
        if($_Q6Q1C["Send"] == 'PossiblySent')
        $_6itQQ = '<img src="images/minus16.gif" alt="" width="16" height="16" />&nbsp;';
        else
        $_6itQQ = '<img src="images/cross16.gif" alt="" width="16" height="16" />&nbsp;';
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:SENTDATE>", "</LIST:SENTDATE>", $_6itQQ.$_Q6Q1C["SentDateTime"]);

      if($_I0o0o != 0 && $_Q6Q1C["recipients_id"] != 0)
        $EMail = _LL6LE("u_EMail", $_I0o0o, $_Q6Q1C["recipients_id"]);
      else
        $EMail = $_Q6Q1C["EMail"];

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", $EMail);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:SUBJECT>", "</LIST:SUBJECT>", htmlspecialchars($_Q6Q1C["MailSubject"], ENT_COMPAT, $_Q6QQL));
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $resourcestrings[$INTERFACE_LANGUAGE]["MailSend".$_Q6Q1C["Send"]]);

      $_Q66jQ = str_replace("Result_id=", "Result_id=".$_Q6Q1C["id"], $_Q66jQ);
      $_Q66jQ = str_replace("CampaignId=", "CampaignId=".$_Q6Q1C["Campaigns_id"], $_Q66jQ);
      $_Q66jQ = str_replace("SendStatId=", "SendStatId=".$_Q6Q1C["CampaignsSendStat_id"], $_Q66jQ);

      # modify id
      $_Q6Q1C["id"] = $_Q6Q1C["Campaigns_id"]."-".$_Q6Q1C["CampaignsSendStat_id"]."-".$_Q6Q1C["id"];

      if( ($_Q6Q1C["Send"] == 'Failed' || $_Q6Q1C["Send"] == 'PossiblySent') && !$_QtILf && $EMail != "")
        $_Q66jQ = str_replace ('name="SendAgain"', 'name="SendAgain" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
        else
        $_Q66jQ = _LJ6B1($_Q66jQ, "SendAgain");
      $_Q66jQ = str_replace ('name="DeleteLogEntry"', 'name="DeleteLogEntry" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      $_Q66jQ = str_replace ('name="LogIDs[]"', 'name="LogIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      // not an admin, check rights for mailinglist
      if($OwnerUserId != 0) {
        if($_I0o0o != 0) {
          $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6fio WHERE maillists_id=$_I0o0o AND users_id=$UserId";
          $_Q8Oj8 = mysql_query($_QJlJ0);
          _OAL8F($_QJlJ0);
          $_I6JII = mysql_fetch_row($_Q8Oj8);
          if($_I6JII[0] == 0) {
              $_Q66jQ = _LJ6B1($_Q66jQ, "SendAgain");
          }
          mysql_free_result($_Q8Oj8);
        }
      }

      $_Q6tjl .= $_Q66jQ;
    } # while($_Q6Q1C=mysql_fetch_array($_Q60l1))
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    return $_Q6ICj;
  }

  function _LL6LE($_6ioLQ, $_I0o0o, $_jOfC1) {
   global $_Q60QL, $_Q61I1;
   $_Q6ClO = "";
   $_QJlJ0 = "SELECT MaillistTableName FROM $_Q60QL WHERE id=".intval($_I0o0o);
   $_Q60l1 = mysql_query($_QJlJ0);
   if(!$_Q60l1) return $_Q6ClO;
   $_Q6Q1C = mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QJlJ0 = "SELECT `$_6ioLQ` FROM $_Q6Q1C[MaillistTableName] WHERE id=".intval($_jOfC1);
   $_Q60l1 = mysql_query($_QJlJ0);
   if(!$_Q60l1) return $_Q6ClO;
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   return $_Q6Q1C[0];
  }

  function _LLR1B($_6iOi1, &$_QtIiC) {
    global $_Q6jOo;

    sort($_6iOi1);
    $_f00oJ = 0;
    for($_Q6llo=0; $_Q6llo<count($_6iOi1); $_Q6llo++) {
      $_I1L81 = explode("-", $_6iOi1[$_Q6llo]);
      $_f01jL = intval($_I1L81[0]);
      $_f01Ol = intval($_I1L81[1]);
      $_f0QIQ = intval($_I1L81[2]);

      if($_f00oJ != $_f01jL) {
        $_f00oJ = $_f01jL;
        $_QJlJ0 = "SELECT RStatisticsTableName FROM $_Q6jOo WHERE id=$_f00oJ";
        $_Q60l1 = mysql_query($_QJlJ0);
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
      }

      $_QJlJ0 = "DELETE FROM $_Q6Q1C[RStatisticsTableName] WHERE id=$_f0QIQ";
      mysql_query($_QJlJ0);
      if(mysql_error($_Q61I1) != "")
        $_QtIiC[] = mysql_error($_Q61I1);

    }
  }

 function _LLRJD($_Iloio, $SendStatId, $_6iOi1, &$_6io6C) {
  global $_Q6jOo, $_IooOQ, $_Q60QL, $_Q6fio, $UserId, $OwnerUserId, $_Q61I1;
  global $INTERFACE_LANGUAGE, $_Qofoi, $_QtjLI;


  sort($_6iOi1);

  $_QJlJ0 = "SELECT $_IooOQ.*, $_Q60QL.MaillistTableName, $_Q60QL.StatisticsTableName, $_Q60QL.LocalBlocklistTableName, $_IooOQ.maillists_id FROM $_IooOQ LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IooOQ.maillists_id WHERE $_IooOQ.id=$_Iloio";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_j0fti = $_Q8OiJ["CurrentSendTableName"];
    $_6CoIt = $_Q8OiJ["SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName"];
    $_6CoIO = $_Q8OiJ["CampaignsForSplitTestTableName"];
    $_QlQC8 = $_Q8OiJ["MaillistTableName"];
    $_I0o0o = $_Q8OiJ["maillists_id"];
    $_QlIf6 = $_Q8OiJ["StatisticsTableName"];
    $_ItCCo = $_Q8OiJ["LocalBlocklistTableName"];
    $FormId = $_Q8OiJ["forms_id"];
  } else{
    $_6io6C[] = "Can't find mailing list.";
    return false;
  }

  // not an admin, check rights for mailinglist
  if($OwnerUserId != 0) {
    if($_I0o0o != 0) {
      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6fio WHERE maillists_id=$_I0o0o AND users_id=$UserId";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_I6JII = mysql_fetch_row($_Q8Oj8);
      if($_I6JII[0] == 0) {
          $_6io6C[] = "You have no permissions for this mailing list.";
          return false;
      }
      mysql_free_result($_Q8Oj8);
    }
  }


  $_f00oJ = 0;

  $_jf8CQ = 0;

  $_Qt6oI = $UserId;
  if($OwnerUserId != 0)
    $_Qt6oI = $OwnerUserId;

  for($_Q6llo=0; $_Q6llo<count($_6iOi1); $_Q6llo++) {
    $_I1L81 = explode("-", $_6iOi1[$_Q6llo]);
    $_f01jL = intval($_I1L81[0]);
    $_f01Ol = intval($_I1L81[1]);
    $_f0QIQ = intval($_I1L81[2]);

    if($_f00oJ != $_f01jL) {
      $_f00oJ = $_f01jL;
      $_QJlJ0 = "SELECT * FROM $_Q6jOo WHERE id=$_f00oJ";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }


    // MTA
    $_QJlJ0 = "SELECT mtas_id FROM `$_Q8OiJ[MTAsTableName]` ORDER BY sortorder LIMIT 0, 1"; // only the first
    $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_j8Coj || mysql_num_rows($_j8Coj) == 0) {
      $_6io6C[] = $commonmsgHTMLMTANotFound;
      return false;
    } else {
      $_jIfO0 = mysql_fetch_assoc($_j8Coj);
      mysql_free_result($_j8Coj);
      $_Q8OiJ["mtas_id"] = $_jIfO0["mtas_id"];
    }

    // get group ids if specified for unsubscribe link
    if(!isset($_Q8OiJ["GroupIds"])) {
      $_IitLf = array();
      $_jIOjL = "SELECT * FROM $_Q8OiJ[GroupsTableName]";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IitLf[] = $_jIOio[0];
      }
      mysql_free_result($_jIOff);

      if(count($_IitLf) > 0) {
        // remove groups
        $_jIOjL = "SELECT * FROM $_Q8OiJ[NotInGroupsTableName]";
        $_jIOff = mysql_query($_jIOjL, $_Q61I1);
        while($_jIOio = mysql_fetch_row($_jIOff)) {
          $_IJQOL = array_search($_jIOio[0], $_IitLf);
          if($_IJQOL !== false)
             unset($_IitLf[$_IJQOL]);
        }
        mysql_free_result($_jIOff);
      }

      if(count($_IitLf) > 0)
        $_Q8OiJ["GroupIds"] = join(",", $_IitLf);
    }

    $_QLitI = 0;
    $MailingListId = $_I0o0o;

    $_QJlJ0 = "SELECT recipients_id, Send FROM `$_Q8OiJ[RStatisticsTableName]` WHERE id=$_f0QIQ";
    $_6iCJQ = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_6iCJQ) continue;
    $_6JLOL = mysql_fetch_array($_6iCJQ);
    mysql_free_result($_6iCJQ);
    if($_6JLOL["Send"] != "Failed" && $_6JLOL["Send"] != "PossiblySent") continue; // list operations, send failed only
    $_QLitI = $_6JLOL["recipients_id"];

    $_QJlJ0 = "SELECT u_EMail FROM $_QlQC8 WHERE id=$_QLitI";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_IJoII = $_Q6Q1C["u_EMail"];
    }else{
      $_6io6C[] = "member $_QLitI doesn't exists in mailinglist.";
      continue;
    }

    if(_L101P($_IJoII, $_I0o0o, $_ItCCo )) {
     $_6io6C[] = "member with email address '$_IJoII' is in local black list.";
     continue;
    }
    if(_L0FRD($_IJoII)) {
     $_6io6C[] = "member with email address '$_IJoII' is in global black list.";
     continue;
    }

    // old entry
    $_QJlJ0 = "DELETE FROM `$_Q8OiJ[RStatisticsTableName]` WHERE id=$_f0QIQ";
    mysql_query($_QJlJ0, $_Q61I1);
    //$_jf8CQ--; NOT HERE!!!

    // new entry
    $_QJlJ0 = "INSERT INTO `$_Q8OiJ[RStatisticsTableName]` SET `SendStat_id`=$_f01Ol, `MailSubject`="._OPQLR($_Q8OiJ["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QLitI, `Send`='Prepared'";
    mysql_query($_QJlJ0, $_Q61I1);

    $_jfiol = 0;
    if(mysql_affected_rows($_Q61I1) > 0) {
      $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_jfl1j=mysql_fetch_array($_jfLII);
      $_jfiol = $_jfl1j[0];
      mysql_free_result($_jfLII);
    } else {
      $_6io6C[] =  "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
      continue;
    }

    //
    $_QJlJ0 = "INSERT INTO $_QtjLI SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$_Qt6oI, `Source`='Campaign', `Source_id`=$_Q8OiJ[id], `Additional_id`=0, `SendId`=$_f01Ol, `maillists_id`=$_I0o0o, `recipients_id`=$_QLitI, `mtas_id`=$_Q8OiJ[mtas_id], `LastSending`=NOW() ";
    mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) != "") {
      $_6io6C[] = "MySQL error while adding to out queue: ".mysql_error($_Q61I1);
      continue;
    }

    $_jf8CQ++;

  } # for


  if($_jf8CQ > 0) {
    for($_Q6llo=0; $_Q6llo<count($_6iOi1); $_Q6llo++) {
      $_I1L81 = explode("-", $_6iOi1[$_Q6llo]);
      $_f01jL = intval($_I1L81[0]);
      $_f01Ol = intval($_I1L81[1]);
      $_f0QIQ = intval($_I1L81[2]);

      if($_f00oJ != $_f01jL) {
        $_f00oJ = $_f01jL;
        $_QJlJ0 = "SELECT * FROM $_Q6jOo WHERE id=$_f00oJ";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
      }

      // campaign
      $_QJlJ0 = "UPDATE `$_Q8OiJ[CurrentSendTableName]` SET `SendState`='ReSending' WHERE `id`=$_f01Ol";
      mysql_query($_QJlJ0, $_Q61I1);


    }

    // split test itself
    $_QJlJ0 = "UPDATE `$_j0fti` SET `SendState`='ReSending' WHERE `id`=$SendStatId";
    mysql_query($_QJlJ0, $_Q61I1);

  }

 }
?>
