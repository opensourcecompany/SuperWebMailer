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
  include_once("splitteststools.inc.php");

  $_6LQ08="http://www.superscripte.de/pub/img/";
  if(stripos(ScriptBaseURL, 'https:') !== false)
    $_6LQ08="https://www.superscripte.de/pub/img/";

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
    $_GET["action"] = "stat_splittesttracking.php";
    $_GET["ResponderType"] = "SplitTest";
    $_I6lOO = $_Iloio;
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

  $_I0600 = "";

  $_QJlJ0 = "SELECT $_IooOQ.*, DATE_FORMAT($_IooOQ.CreateDate, $_Q6QiO) AS CreateDateFormated, $_Q60QL.MaillistTableName FROM $_IooOQ LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IooOQ.maillists_id WHERE $_IooOQ.id=$_Iloio";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
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
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_6llOC = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_6llOC[] = array("Campaigns_id"=> $_Q6Q1C["Campaigns_id"], "CampaignsSendStat_id" => 0, "RecipientsCount" => 0);
  }
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_6CoIt` WHERE `SplitTestSendStat_id`=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
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
  $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDurationFormated ";
  $_QJlJ0 .= " FROM $_j0fti WHERE id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
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
      else {
        if($_6lLOl["TestType"] == 'TestSendToListPercentage' && $_6llOC[$_Qf0Ct]["Campaigns_id"] == $_Q6Q1C["WinnerCampaigns_id"]) {
           $_6llOC[$_Qf0Ct]["RecipientsCount"] += $_Q6Q1C["WinnerRecipientsCount"]; # add Winner recipients
        }
        $_f006J["RecipientsCount"] += $_6llOC[$_Qf0Ct]["RecipientsCount"];
      }

    $_QJlJ0 = "SELECT ".join(", ", $_6L0f1).", Name, TrackingIPBlocking, TrackEMailOpeningsByRecipient, TrackLinksByRecipient FROM $_Q6jOo WHERE id=".$_6llOC[$_Qf0Ct]["Campaigns_id"];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    for($_Q6llo=0; $_Q6llo<count($_6L0f1); $_Q6llo++){
       $_6llOC[$_Qf0Ct][$_6L0f1[$_Q6llo]] = $_Q8OiJ[$_6L0f1[$_Q6llo]];
    }
    $_6llOC[$_Qf0Ct]["Name"] = $_Q8OiJ["Name"];
    $_6llOC[$_Qf0Ct]["TrackEMailOpeningsByRecipient"] = $_Q8OiJ["TrackEMailOpeningsByRecipient"];
    $_6llOC[$_Qf0Ct]["TrackLinksByRecipient"] = $_Q8OiJ["TrackLinksByRecipient"];
    # we need this later
    $_6lLOl["TrackingIPBlocking"] = $_Q8OiJ["TrackingIPBlocking"];

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
          mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          continue;
        }
        $_QJlJ0 = "DELETE FROM `".$_6llOC[$_Qf0Ct][$_6L0f1[$_Q6llo]]."` WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
    }
    // splittesttable
    $_QJlJ0 = "SELECT `MembersTableName`, `RandomMembersTableName` FROM `$_j0fti` WHERE id=$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "DROP TABLE `$_Q8OiJ[MembersTableName]`";
    mysql_query($_QJlJ0, $_Q61I1);

    $_QJlJ0 = "DROP TABLE `$_Q8OiJ[RandomMembersTableName]`";
    mysql_query($_QJlJ0, $_Q61I1);

    # don't send it again when there are no send entries
    $_QJlJ0 = "SELECT COUNT(*) FROM `$_j0fti` WHERE id<>$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if($_Q6Q1C[0] == 0 && $_6lLOl["SendScheduler"] != 'SaveOnly' ){
      $_QJlJ0 = "UPDATE `$_IooOQ` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_Iloio";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }
    mysql_free_result($_Q60l1);
    #

    $_QJlJ0 = "DELETE FROM `$_j0fti` WHERE id=$SendStatId";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_QJlJ0 = "DELETE FROM `$_6lLOl[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$SendStatId";
    mysql_query($_QJlJ0, $_Q61I1);
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
        $_6llOC[$_Qf0Ct][$key] = 0;
      }
      continue;
    }

    $_QJlJ0 = "SELECT SentCountSucc, SentCountFailed, SentCountPossiblySent, HardBouncesCount, SoftBouncesCount, UnsubscribesCount FROM ".$_6llOC[$_Qf0Ct]["CurrentSendTableName"]." WHERE id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    foreach($_Q8OiJ as $key => $_Q6ClO){
      $_6llOC[$_Qf0Ct][$key] = $_Q6ClO;
      if(!isset($_f006J[$key]))
        $_f006J[$key] = 0;
      $_f006J[$key] += $_Q6ClO;
    }
    mysql_free_result($_Q60l1);
  }

  if($_QtILf) {
    $_f006J["SentCountSucc"] = 0;
    $_f006J["SentCountFailed"] = 0;
    for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
      $_j08fl = $_6llOC[$_Qf0Ct]["RStatisticsTableName"];
      // Count things while sending
      $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." AND Send='Sent'";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_f006J["SentCountSucc"] += $_IO08Q[0];
      $_6llOC[$_Qf0Ct]["SentCountSucc"] += $_IO08Q[0];

      $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." AND Send='Failed'";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_f006J["SentCountFailed"] += $_IO08Q[0];
      $_6llOC[$_Qf0Ct]["SentCountFailed"] += $_IO08Q[0];

      $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." AND Send='PossiblySent'";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_f006J["SentCountPossiblySent"] += $_IO08Q[0];
      $_6llOC[$_Qf0Ct]["SentCountPossiblySent"] += $_IO08Q[0];
    }
  }

  // Template
  $_6L16j = $_Q6Q1C["SentDateTime"];
  if($_QtILf)
    $_6L16j = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["000675"]."&quot;";
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001860"], $_6lLOl["Name"], $_6L16j ), $_I0600, 'stat_splittesttracking', 'stat_splittesttracking_snipped.htm');

  $_QJCJi = str_replace('name="SplitTestId"', 'name="SplitTestId" value="'.$_Iloio.'"', $_QJCJi);
  $_QJCJi = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QJCJi);

  $ResponderType = "SplitTest";
  $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);

  if($_6lLOl["TrackingIPBlocking"] > 0)
    $_QJCJi = _OPR6L($_QJCJi, "<info:ipblocking>", "</info:ipblocking>", $resourcestrings[$INTERFACE_LANGUAGE]["TrackingIPBlocking"]);
   else
    $_QJCJi = _OPR6L($_QJCJi, "<info:ipblocking>", "</info:ipblocking>", "");

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<SplitTestId>", "</SplitTestId>", $_6lLOl["id"]);
  $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_6lLOl["Name"]);
  $_QJCJi = _OPR6L($_QJCJi, "<CREATEDATE>", "</CREATEDATE>", $_6lLOl["CreateDateFormated"]);

  $_QJCJi = _OPR6L($_QJCJi, "<WinnerType>", "</WinnerType>", $resourcestrings[$INTERFACE_LANGUAGE]["WinnerType".$_6lLOl["WinnerType"]]);
  if($_6lLOl["TestType"] == 'TestSendToAllMembers')
    $_6iIjI = $resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_6lLOl["TestType"]];
    else {
      $_6iIjI = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_6lLOl["TestType"]], $_6lLOl["ListPercentage"], $_6lLOl["SendAfterInterval"], $resourcestrings[$INTERFACE_LANGUAGE][$_6lLOl["SendAfterIntervalType"]."s"]);
    }
  $_QJCJi = _OPR6L($_QJCJi, "<TestType>", "</TestType>", $_6iIjI);


  if($_6lLOl["TrackingIPBlocking"] != 0)
     $_QJCJi = _OPR6L($_QJCJi, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
     else
     $_QJCJi = _OPR6L($_QJCJi, "<IPBLOCKING>", "</IPBLOCKING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QJCJi = _OPR6L($_QJCJi, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_QtILf) {
    $_QJCJi = _OP6PQ($_QJCJi, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
    $_6LItL["EndSendDateTimeFormated"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
  }
  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_f006J["RecipientsCount"]);

  // prevent division by zero
  if($_f006J["RecipientsCount"] == 0)
     $_f006J["RecipientsCount"] = 0.01;

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_f006J["SentCountSucc"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_f006J["SentCountSucc"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_f006J["SentCountFailed"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_f006J["SentCountFailed"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_f006J["SentCountPossiblySent"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_f006J["SentCountPossiblySent"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $_f006J["HardBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNTPERCENT>", "</HARDBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_f006J["HardBouncesCount"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $_f006J["SoftBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNTPERCENT>", "</SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.1f%%", $_f006J["SoftBouncesCount"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $_f006J["UnsubscribesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNTPERCENT>", "</UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.1f%%", $_f006J["UnsubscribesCount"] * 100 / $_f006J["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:START>", "</SENDING:START>", $_Q6Q1C["StartSendDateTimeFormated"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $_Q6Q1C["EndSendDateTimeFormated"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $_Q6Q1C["SendDurationFormated"]."&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE]["Hours"]);

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


  $_f0Qof = 0;
  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount FROM `".$_6llOC[$_Qf0Ct]["TrackingLinksTableName"]."` WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q8OiJ = mysql_fetch_row($_Q60l1);
      if(!isset($_Q8OiJ[0]))
         $_Q8OiJ[0] = 0;
      mysql_free_result($_Q60l1);
      $_f0Qof += $_Q8OiJ[0];
      if(!isset($_6llOC[$_Qf0Ct]["LinksClicks"]))
         $_6llOC[$_Qf0Ct]["LinksClicks"] = 0;
      $_6llOC[$_Qf0Ct]["LinksClicks"] += $_Q8OiJ[0];
    }
  }

  $_QJCJi = _OPR6L($_QJCJi, "<SUMLINKCLICKS>", "</SUMLINKCLICKS>", $_f0Qof);

  $_f0Qof = 0;
  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    $_QJlJ0 = "SELECT SUM(Clicks) AS ClicksCount FROM `".$_6llOC[$_Qf0Ct]["TrackingOpeningsTableName"]."` WHERE SendStat_id=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q8OiJ = mysql_fetch_row($_Q60l1);
      if(!isset($_Q8OiJ[0]))
         $_Q8OiJ[0] = 0;
      mysql_free_result($_Q60l1);
      $_f0Qof += $_Q8OiJ[0];
      if(!isset($_6llOC[$_Qf0Ct]["Openings"]))
         $_6llOC[$_Qf0Ct]["Openings"] = 0;
      $_6llOC[$_Qf0Ct]["Openings"] += $_Q8OiJ[0];
    }
  }

  $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSCOUNT>", "</OPENINGSCOUNT>", $_f0Qof);
  if($_6lLOl["TrackingIPBlocking"] != 0)
    $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSRATE>", "</OPENINGSRATE>", sprintf("%1.2f%%", $_f0Qof * 100 / $_f006J["RecipientsCount"]) );
    else
    $_QJCJi = _OPR6L($_QJCJi, "<OPENINGSRATE>", "</OPENINGSRATE>",  $resourcestrings[$INTERFACE_LANGUAGE]["NA"] );

  //
  // sum useragents
  $_f0I06 = array();
  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `".$_6llOC[$_Qf0Ct]["TrackingUserAgentsTableName"]."` WHERE `SendStat_id`=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)){
      $_6L8i0 = $_Q8OiJ["UserAgent"];
      if($_6L8i0 == "")
        $_6L8i0 = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownEMailClient];icon=ua/unknown.gif";
      // name=IE 8.0;icon=msie.png
      $_IJLt1 = substr($_6L8i0, 0, strpos($_6L8i0, ";"));
      $_IJLt1 = substr($_IJLt1, 5);
      $_6L8it = substr($_6L8i0, strpos_reverse($_6L8i0, ";") + 1);
      $_6L8it = substr($_6L8it, 5);

      if(!isset($_f0I06[$_IJLt1]))
        $_f0I06[$_IJLt1] = array("icon" => $_6L8it, "ClicksCount" => $_Q8OiJ["ClicksCount"]);
        else {
          $_f0I06[$_IJLt1]["ClicksCount"] += $_Q8OiJ["ClicksCount"];
        }
    }
    mysql_free_result($_Q8Oj8);
  }

  // sum OSs
  $_f0IOj = array();
  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `".$_6llOC[$_Qf0Ct]["TrackingOSsTableName"]."` WHERE `SendStat_id`=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"]." GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT 0, 20";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)){
      $_6L8i0 = $_Q8OiJ["OS"];
      if($_6L8i0 == "")
        $_6L8i0 = "name=$resourcestrings[$INTERFACE_LANGUAGE][UnknownOS];icon=ua/unknown.gif";
      // name=Windows 7;icon=windows7.png
      $_IJLt1 = substr($_6L8i0, 0, strpos($_6L8i0, ";"));
      $_IJLt1 = substr($_IJLt1, 5);
      $_6L8it = substr($_6L8i0, strpos_reverse($_6L8i0, ";") + 1);
      $_6L8it = substr($_6L8it, 5);

      if(!isset($_f0IOj[$_IJLt1]))
        $_f0IOj[$_IJLt1] = array("icon" => $_6L8it, "ClicksCount" => $_Q8OiJ["ClicksCount"]);
        else {
          $_f0IOj[$_IJLt1]["ClicksCount"] += $_Q8OiJ["ClicksCount"];
        }
    }
    mysql_free_result($_Q8Oj8);
  }

  $_QfoQo = _OP81D($_QJCJi, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>");
  $_Q6ICj = "";
  $_Q6llo = 0;
  $_6L86f = 0;
  $_II6ft = array();
  reset($_f0I06);
  foreach($_f0I06 as $_6L8i0 => $_Q6ClO){
    $_Q6ICj .= $_QfoQo;

    $_Q6ICj = _OPR6L($_Q6ICj, "<EMAILCLIENT_COUNT>", "</EMAILCLIENT_COUNT>", $_Q6ClO["ClicksCount"]."&nbsp;(<EMAILCLIENT_COUNT_PERCENT$_Q6llo></EMAILCLIENT_COUNT_PERCENT$_Q6llo>)");
    $_II6ft[] = $_Q6ClO["ClicksCount"];
    $_6L86f += $_Q6ClO["ClicksCount"];
    $_Q6llo++;
    $_Q6ICj = _OPR6L($_Q6ICj, "<EMAILCLIENT_NAME>", "</EMAILCLIENT_NAME>", $_6L8i0);
    $_Q6ICj = _OPR6L($_Q6ICj, "&lt;EMAILCLIENT_NAME&gt;", "&lt;/EMAILCLIENT_NAME&gt;", $_6L8i0);
    if(strpos($_Q6ClO["icon"], "ua/") !== false)
       $_Q6ICj = str_replace("EMAILCLIENT_ICON", "images/".$_Q6ClO["icon"], $_Q6ICj);
       else
       $_Q6ICj = str_replace("EMAILCLIENT_ICON", $_6LQ08."ua/".$_Q6ClO["icon"], $_Q6ICj);
  }
  if($_6L86f <= 0)
     $_6L86f = 0.01;
  for($_Q6llo=0; $_Q6llo<count($_II6ft); $_Q6llo++){
    $_Q6ICj = _OPR6L($_Q6ICj, "<EMAILCLIENT_COUNT_PERCENT$_Q6llo>", "</EMAILCLIENT_COUNT_PERCENT$_Q6llo>", sprintf("%1.1f%%", $_II6ft[$_Q6llo] * 100 / $_6L86f));
  }

  $_QJCJi = _OPR6L($_QJCJi, "<EMAILCLIENT_STAT>", "</EMAILCLIENT_STAT>", $_Q6ICj);
  //
  $_QfoQo = _OP81D($_QJCJi, "<OS_STAT>", "</OS_STAT>");
  $_Q6ICj = "";
  $_Q6llo = 0;
  $_6Ltfo = 0;
  $_II6ft = array();
  reset($_f0IOj);
  foreach($_f0IOj as $_ILtIQ => $_Q6ClO){
    $_Q6ICj .= $_QfoQo;

    $_Q6ICj = _OPR6L($_Q6ICj, "<OS_COUNT>", "</OS_COUNT>", $_Q6ClO["ClicksCount"]."&nbsp;(<OS_COUNT_PERCENT$_Q6llo></OS_COUNT_PERCENT$_Q6llo>)");
    $_II6ft[] = $_Q6ClO["ClicksCount"];
    $_6Ltfo += $_Q6ClO["ClicksCount"];
    $_Q6llo++;
    $_Q6ICj = _OPR6L($_Q6ICj, "<OS_NAME>", "</OS_NAME>", $_ILtIQ);
    $_Q6ICj = _OPR6L($_Q6ICj, "&lt;OS_NAME&gt;", "&lt;/OS_NAME&gt;", $_ILtIQ);
    if(strpos($_Q6ClO["icon"], "ua/") !== false)
       $_Q6ICj = str_replace("OS_ICON", "images/".$_Q6ClO["icon"], $_Q6ICj);
       else
       $_Q6ICj = str_replace("OS_ICON", $_6LQ08."os/".$_Q6ClO["icon"], $_Q6ICj);
  }
  if($_6Ltfo <= 0)
     $_6Ltfo = 0.01;
  for($_Q6llo=0; $_Q6llo<count($_II6ft); $_Q6llo++){
    $_Q6ICj = _OPR6L($_Q6ICj, "<OS_COUNT_PERCENT$_Q6llo>", "</OS_COUNT_PERCENT$_Q6llo>", sprintf("%1.1f%%", $_II6ft[$_Q6llo] * 100 / $_6Ltfo));
  }

  $_QJCJi = _OPR6L($_QJCJi, "<OS_STAT>", "</OS_STAT>", $_Q6ICj);
  //

  $_QfoQo = _OP81D($_QJCJi, "<OPENINGS_LINE>", "</OPENINGS_LINE>");
  $_Q6ICj = "";

  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    $_Q6ICj .= $_QfoQo;

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_CAMPAIGNNAME>", "</OPENINGS_CAMPAIGNNAME>", $_6llOC[$_Qf0Ct]["Name"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_RECIPIENTSCOUNT>", "</OPENINGS_RECIPIENTSCOUNT>", $_6llOC[$_Qf0Ct]["RecipientsCount"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_OPENINGSCOUNT>", "</OPENINGS_OPENINGSCOUNT>", $_6llOC[$_Qf0Ct]["Openings"]);

    if($_6llOC[$_Qf0Ct]["RecipientsCount"] == 0)
       $_6llOC[$_Qf0Ct]["RecipientsCount"] = 0.01;

    if($_6lLOl["TrackingIPBlocking"] != 0)
      $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_OPENINGSRATE>", "</OPENINGS_OPENINGSRATE>", sprintf("%1.2f%%", $_6llOC[$_Qf0Ct]["Openings"] * 100 / $_6llOC[$_Qf0Ct]["RecipientsCount"]));
      else
      $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_OPENINGSRATE>", "</OPENINGS_OPENINGSRATE>", $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_UNSUBSCRIBESCOUNT>", "</OPENINGS_UNSUBSCRIBESCOUNT>", $_6llOC[$_Qf0Ct]["UnsubscribesCount"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_SOFTBOUNCESCOUNT>", "</OPENINGS_SOFTBOUNCESCOUNT>", $_6llOC[$_Qf0Ct]["SoftBouncesCount"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_HARDBOUNCESCOUNT>", "</OPENINGS_HARDBOUNCESCOUNT>", $_6llOC[$_Qf0Ct]["HardBouncesCount"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_FAILEDCOUNT>", "</OPENINGS_FAILEDCOUNT>", $_6llOC[$_Qf0Ct]["SentCountFailed"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_SUCCCOUNT>", "</OPENINGS_SUCCCOUNT>", $_6llOC[$_Qf0Ct]["SentCountSucc"]);

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_UNSUBSCRIBESCOUNTPERCENT>", "</OPENINGS_UNSUBSCRIBESCOUNTPERCENT>", sprintf("%1.2f%%", $_6llOC[$_Qf0Ct]["UnsubscribesCount"] / $_6llOC[$_Qf0Ct]["RecipientsCount"]));
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_SOFTBOUNCESCOUNTPERCENT>", "</OPENINGS_SOFTBOUNCESCOUNTPERCENT>", sprintf("%1.2f%%", $_6llOC[$_Qf0Ct]["SoftBouncesCount"] / $_6llOC[$_Qf0Ct]["RecipientsCount"]));
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPENINGS_HARDBOUNCESCOUNTPERCENT>", "</OPENINGS_HARDBOUNCESCOUNTPERCENT>", sprintf("%1.2f%%", $_6llOC[$_Qf0Ct]["HardBouncesCount"] / $_6llOC[$_Qf0Ct]["RecipientsCount"]));

    $_f0Il6 = $_6llOC[$_Qf0Ct]["Campaigns_id"]."-".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];
    $_Q6ICj = str_replace('name="WhoHasOpenedBtn" value="-1"', 'name="WhoHasOpenedBtn" value="'.$_f0Il6.'"', $_Q6ICj);

    $_Q6ICj = str_replace("stat_campaigntracking.php?CampaignId=&SendStatId=", "stat_campaigntracking.php?CampaignId=".$_6llOC[$_Qf0Ct]["Campaigns_id"]."&SendStatId=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"], $_Q6ICj);

    if(!$_6llOC[$_Qf0Ct]["TrackEMailOpeningsByRecipient"])
       $_Q6ICj = _OP6PQ($_Q6ICj, "<IF:RECIPIENTOPENINGTRACKING>", "</IF:RECIPIENTOPENINGTRACKING>");
       else {
         $_Q6ICj = str_replace("<IF:RECIPIENTOPENINGTRACKING>", "", $_Q6ICj);
         $_Q6ICj = str_replace("</IF:RECIPIENTOPENINGTRACKING>", "", $_Q6ICj);
       }

  }

  $_QJCJi = _OPR6L($_QJCJi, "<OPENINGS_LINE>", "</OPENINGS_LINE>", $_Q6ICj);
  //

  $_QfoQo = _OP81D($_QJCJi, "<CLICKS_LINE>", "</CLICKS_LINE>");
  $_Q6ICj = "";

  for($_Qf0Ct=0; $_Qf0Ct<count($_6llOC); $_Qf0Ct++) {
    $_Q6ICj .= $_QfoQo;

    $_Q6ICj = _OPR6L($_Q6ICj, "<LINKS_CAMPAIGNNAME>", "</LINKS_CAMPAIGNNAME>", $_6llOC[$_Qf0Ct]["Name"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<LINKS_RECIPIENTSCOUNT>", "</LINKS_RECIPIENTSCOUNT>", $_6llOC[$_Qf0Ct]["RecipientsCount"]);
    $_Q6ICj = _OPR6L($_Q6ICj, "<LINKS_CLICKSCOUNT>", "</LINKS_CLICKSCOUNT>", $_6llOC[$_Qf0Ct]["LinksClicks"]);

    if($_6llOC[$_Qf0Ct]["RecipientsCount"] == 0)
       $_6llOC[$_Qf0Ct]["RecipientsCount"] = 0.01;


    if($_6lLOl["TrackingIPBlocking"] != 0)
      $_Q6ICj = _OPR6L($_Q6ICj, "<LINKS_CLICKRATE>", "</LINKS_CLICKRATE>", sprintf("%1.2f%%", $_6llOC[$_Qf0Ct]["LinksClicks"] * 100 / $_6llOC[$_Qf0Ct]["RecipientsCount"]));
      else
      $_Q6ICj = _OPR6L($_Q6ICj, "<LINKS_CLICKRATE>", "</LINKS_CLICKRATE>", $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);

    $_f0Il6 = $_6llOC[$_Qf0Ct]["Campaigns_id"]."-".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"];

    $_Q6ICj = str_replace("stat_campaigntracking.php?CampaignId=&SendStatId=", "stat_campaigntracking.php?CampaignId=".$_6llOC[$_Qf0Ct]["Campaigns_id"]."&SendStatId=".$_6llOC[$_Qf0Ct]["CampaignsSendStat_id"], $_Q6ICj);

  }

  $_QJCJi = _OPR6L($_QJCJi, "<CLICKS_LINE>", "</CLICKS_LINE>", $_Q6ICj);


  print $_QJCJi;
?>
