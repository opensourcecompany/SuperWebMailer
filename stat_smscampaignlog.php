<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
  include_once("cron_smscampaigns.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("smsout.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeViewSMSCampaignLog"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

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

  if(!isset($_I6lOO) && isset($_POST["ResponderId"]))
     $_I6lOO = intval($_POST["ResponderId"]);
     else
     if(!isset($_I6lOO) && isset($_GET["ResponderId"]))
         $_I6lOO = intval($_GET["ResponderId"]);

  if(!isset($_I6lOO)) {
    $_GET["ResponderType"] = "SMSCampaign";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_I6lOO = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_smscampaignlog.php";
    include_once("smscampaignsendstatselect.inc.php");
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


  if(isset($_GET["Result_id"]) && isset($_GET["CampaignId"]) && isset($_GET["SendStatId"]) ) {
    $_GET["Result_id"] = intval($_GET["Result_id"]);
    $_GET["CampaignId"] = intval($_GET["CampaignId"]);
    $_GET["SendStatId"] = intval($_GET["SendStatId"]);
    $_QJCJi = join("", file(_O68QF()."smscampaignlog_view_result.htm"));

    $_QJlJ0 = "SELECT $_IoCtL.Name, $_IoCtL.RStatisticsTableName, $_Q60QL.MaillistTableName FROM $_IoCtL LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoCtL.maillists_id WHERE $_IoCtL.id=$_GET[CampaignId]";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_j08fl = $_Q6Q1C["RStatisticsTableName"];
    $_QlQC8 = $_Q6Q1C["MaillistTableName"];
    mysql_free_result($_Q60l1);

    $_QJCJi = str_replace("%NAME%", "'".$_Q6Q1C["Name"]."'", $_QJCJi);

    $_QJlJ0 = "SELECT `$_j08fl`.*, DATE_FORMAT(SendDateTime, $_Q6QiO) AS SentDateTime, `$_QlQC8`.u_CellNumber FROM `$_j08fl` LEFT JOIN $_QlQC8 ON `$_j08fl`.recipients_id=`$_QlQC8`.id WHERE `$_j08fl`.id=$_GET[Result_id] AND `$_j08fl`.SendStat_id=$_GET[SendStatId]";

    $_Q60l1 = mysql_query($_QJlJ0);
    if($_Q60l1 && $_Q6Q1C = mysql_fetch_array($_Q60l1)) {
      $_QJCJi = _OPR6L($_QJCJi, "<RESULTTEXT>", "</RESULTTEXT>", $_Q6Q1C["SendResult"]);

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
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:SENTDATE>", "</LIST:SENTDATE>", $_6itQQ.$_Q6Q1C["SentDateTime"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:CELLNUMBER>", "</LIST:CELLNUMBER>", $_Q6Q1C["u_CellNumber"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:SUBJECT>", "</LIST:SUBJECT>", $_Q6Q1C["MailSubject"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:STATUS>", "</LIST:STATUS>", $resourcestrings[$INTERFACE_LANGUAGE]["MailSend".$_Q6Q1C["Send"]]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:SMSCOSTS>", "</LIST:SMSCOSTS>", sprintf("%01.2f", $_Q6Q1C["SMSCosts"])." EUR");
    }

    SetHTMLHeaders($_Q6QQL);

    print $_QJCJi;
    exit;
  }

  $_I0600 = "";

  ## Log actions

  $_I680t = !isset($_POST["LogsActions"]);
  if(!$_I680t) {
    if( isset($_POST["OneCampaignLogAction"]) && $_POST["OneCampaignLogAction"] != "" )
      $_I680t = true;
    if($_I680t) {
      if( !( isset($_POST["OneCampaignLogId"]) && $_POST["OneCampaignLogId"] != "")  )
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
       _LLDDA($_I6lOO, $SendStatId, $_6iOi1, $_6io6C);
       if(count($_6io6C) != 0)
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_6io6C);
       else
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
     }

  }

  if( isset($_POST["OneCampaignLogAction"]) && isset($_POST["OneCampaignLogId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneCampaignLogAction"] == "DeleteLogEntry") {
        $_6iOi1 = array();
        $_6iOi1[] = $_POST["OneCampaignLogId"];
        $_QtIiC = array();
        _LLR1B($_6iOi1, $_QtIiC);
        if(count($_QtIiC) != 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000672"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000671"];
      }
      if($_POST["OneCampaignLogAction"] == "SendAgain") {
        $_6iOi1 = array();
        $_6iOi1[] = $_POST["OneCampaignLogId"];
        $_6io6C = array();
        _LLDDA($_I6lOO, $SendStatId, $_6iOi1, $_6io6C);
        if(count($_6io6C) != 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_6io6C);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
      }

  }
  ## Log actions end


  $_QJlJ0 = "SELECT $_IoCtL.*, $_Q60QL.MaillistTableName FROM $_IoCtL LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoCtL.maillists_id WHERE $_IoCtL.id=$_I6lOO";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_j06O8 = $_Q6J0Q["Name"];
  $_j0fti = $_Q6J0Q["CurrentSendTableName"];
  $_j08fl = $_Q6J0Q["RStatisticsTableName"];
  $_QlQC8 = $_Q6J0Q["MaillistTableName"];
  $_I0o0o = $_Q6J0Q["maillists_id"];

  $_QJlJ0 = "SELECT SendState, StartSendDateTime, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS SentDateTime, DATE_FORMAT(StartSendDateTime, $_If0Ql) AS STARTDATE, RecipientsCount, SentCountSucc, SentCountFailed, `SentCountPossiblySent`, ";
  $_QJlJ0 .= "DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, ";
  $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration ";
  $_QJlJ0 .= " FROM $_j0fti WHERE id=$SendStatId";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QtILf = $_Q6Q1C["SendState"] == 'Sending';
  $_6L0Q0 = $_Q6Q1C["STARTDATE"];

  // Remove Send entry
  if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && !$_QtILf ) {
    $_6L0f1 = array("RStatisticsTableName");
    for($_Q6llo=0; $_Q6llo<count($_6L0f1); $_Q6llo++){
      $_QJlJ0 = "DELETE FROM `".$_Q6J0Q[$_6L0f1[$_Q6llo]]."` WHERE SendStat_id=$SendStatId";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
    }

    # don't send it again when there are no send entries
    $_QJlJ0 = "SELECT COUNT(*) FROM `$_j0fti` WHERE id<>$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    if($_Q6Q1C[0] == 0 && $_Q6J0Q["SendScheduler"] != 'SaveOnly' && $_Q6J0Q["SendScheduler"] != 'SendManually' ){
      $_QJlJ0 = "UPDATE `$_IoCtL` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_I6lOO";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
    }
    mysql_free_result($_Q60l1);
    #

    $_QJlJ0 = "DELETE FROM `$_j0fti` WHERE id=$SendStatId";
    mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    include("browsesmscampaigns.php");
    exit;
  }
  // Remove Send entry /


  if($_QtILf) {
    // Count things while sending
    $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=$SendStatId AND Send='Sent'";
    $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
    $_IO08Q = mysql_fetch_row($_ItlJl);
    mysql_free_result($_ItlJl);
    $_Q6Q1C["SentCountSucc"] = $_IO08Q[0];

    $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=$SendStatId AND Send='Failed'";
    $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
    $_IO08Q = mysql_fetch_row($_ItlJl);
    mysql_free_result($_ItlJl);
    $_Q6Q1C["SentCountFailed"] = $_IO08Q[0];

    $_QJlJ0 = "SELECT COUNT(id) FROM $_j08fl WHERE SendStat_id=$SendStatId AND Send='PossiblySent'";
    $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
    $_IO08Q = mysql_fetch_row($_ItlJl);
    mysql_free_result($_ItlJl);
    $_Q6Q1C["SentCountPossiblySent"] = $_IO08Q[0];
  }

  // Template
  $_6L16j = $_Q6Q1C["SentDateTime"];
  if($_QtILf)
    $_6L16j = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["001675"]."&quot;";
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001670"], $_j06O8, $_6L16j), $_I0600, 'stat_smscampaignlog', 'browse_smscampaignslog_snipped.htm');

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_QtILf ) {
    $_QJCJi = _OP6PQ($_QJCJi, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
  }

  if($_Q6Q1C["RecipientsCount"] == 0)
    $_Q6Q1C["RecipientsCount"] = 0.01;

  $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_j06O8 );
  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_Q6Q1C["RecipientsCount"] == 0.01 ? 0 : $_Q6Q1C["RecipientsCount"]);

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_Q6Q1C["SentCountSucc"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_Q6Q1C["SentCountSucc"] * 100 / $_Q6Q1C["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_Q6Q1C["SentCountFailed"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_Q6Q1C["SentCountFailed"] * 100 / $_Q6Q1C["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_Q6Q1C["SentCountPossiblySent"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_Q6Q1C["SentCountPossiblySent"] * 100 / $_Q6Q1C["RecipientsCount"]) );

  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:START>", "</SENDING:START>", $_Q6Q1C["StartSendDateTimeFormated"]);
  if(!$_QtILf) {
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $_Q6Q1C["EndSendDateTimeFormated"]);
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $_Q6Q1C["SendDuration"]);
  } else {
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
    $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
  }

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

  $_QJlJ0 = "SELECT {} FROM $_j08fl WHERE SendStat_id=$SendStatId AND SendDateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I);

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

  $_QJlJ0 .= " ORDER BY SendDateTime";

  $_QJCJi = _LLCEP($_QJlJ0, $_QJCJi);

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

    if(!$_QJojf["PrivilegeSMSCampaignEdit"] || !$_QJojf["PrivilegeSMSCampaignBrowse"] ) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteLogEntry");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveEntries");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }


  print $_QJCJi;

  function _LLCEP($_QJlJ0, $_Q6ICj) {
    global $UserId, $OwnerUserId, $_SESSION, $INTERFACE_LANGUAGE, $resourcestrings, $_IoCtL, $_jC1lo, $_jCQ0I, $_Q6QiO, $_I6lOO, $SendStatId;
    global $_j08fl, $_I0o0o, $_QtILf, $_Q6fio;
    $_I61Cl = array();
    $_I61Cl["CampaignId"]=$_I6lOO;
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
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', "COUNT($_j08fl.id)", $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL);
    _OAL8F($_QtjtL);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "End") )
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

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_Iijl0 = "$_j08fl.*, DATE_FORMAT(SendDateTime, $_Q6QiO) AS SentDateTime";
    $_QJlJ0 = str_replace('{}', $_Iijl0, $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
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
        $_6lifo = _LL6LE("u_CellNumber", $_I0o0o, $_Q6Q1C["recipients_id"]);
      else
        $_6lifo = $_Q6Q1C["CellNumber"];

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:CELLNUMBER>", "</LIST:CELLNUMBER>", $_6lifo);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:SUBJECT>", "</LIST:SUBJECT>", $_Q6Q1C["MailSubject"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $resourcestrings[$INTERFACE_LANGUAGE]["MailSend".$_Q6Q1C["Send"]]);

      $_Q66jQ = str_replace("Result_id=", "Result_id=".$_Q6Q1C["id"], $_Q66jQ);
      $_Q66jQ = str_replace("CampaignId=", "CampaignId=".$_I6lOO, $_Q66jQ);
      $_Q66jQ = str_replace("SendStatId=", "SendStatId=".$SendStatId, $_Q66jQ);


      if(($_Q6Q1C["Send"] == 'Failed' || $_Q6Q1C["Send"] == 'PossiblySent') && !$_QtILf && $_6lifo != "")
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
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    return $_Q6ICj;
  }

  function _LL6LE($_6ioLQ, $_I0o0o, $_jOfC1) {
   global $_Q60QL, $_Q61I1;
   $_Q6ClO = "";
   $_QJlJ0 = "SELECT MaillistTableName FROM $_Q60QL WHERE id=".intval($_I0o0o);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1) return $_Q6ClO;
   $_Q6Q1C = mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QJlJ0 = "SELECT `$_6ioLQ` FROM $_Q6Q1C[MaillistTableName] WHERE id=".intval($_jOfC1);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1) return $_Q6ClO;
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   return $_Q6Q1C[0];
  }

  function _LLR1B($_6iOi1, &$_QtIiC) {
    global $_I6lOO, $_IoCtL, $_Q61I1;

    $_QJlJ0 = "SELECT RStatisticsTableName FROM $_IoCtL WHERE id=$_I6lOO";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    for($_Q6llo=0; $_Q6llo<count($_6iOi1); $_Q6llo++) {
      $_QJlJ0 = "DELETE FROM $_Q6Q1C[RStatisticsTableName] WHERE id=".intval($_6iOi1[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "")
        $_QtIiC[] = mysql_error($_Q61I1);
    }
  }

 function _LLDDA($_I6lOO, $SendStatId, $_6iOi1, &$_6io6C) {
  global $_IoCtL, $_Q60QL, $_Q6fio, $UserId, $OwnerUserId, $_Q61I1;
  global $INTERFACE_LANGUAGE, $_jJt8t;

  $_jOI0f = new _LODEB();

  $_QJlJ0 = "SELECT $_IoCtL.*, $_Q60QL.MaillistTableName, $_Q60QL.StatisticsTableName, $_Q60QL.LocalBlocklistTableName, $_IoCtL.maillists_id FROM $_IoCtL LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoCtL.maillists_id WHERE $_IoCtL.id=$_I6lOO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_j0fti = $_Q8OiJ["CurrentSendTableName"];
    $_j08fl = $_Q8OiJ["RStatisticsTableName"];
    $_QlQC8 = $_Q8OiJ["MaillistTableName"];
    $_I0o0o = $_Q8OiJ["maillists_id"];
    $_QlIf6 = $_Q8OiJ["StatisticsTableName"];
    $_ItCCo = $_Q8OiJ["LocalBlocklistTableName"];
    $FormId = $_Q8OiJ["forms_id"];
    $_jOI0f->SMSoutUsername = $_Q8OiJ["SMSoutUsername"];
    $_jOI0f->SMSoutPassword = $_Q8OiJ["SMSoutPassword"];
    $_Q8COf = $_jOI0f->Login();
    if(!$_Q8COf){
      $_6io6C[] = "Error logging in ".$_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString;
      return false;
    }
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

  // get group ids if specified for unsubscribe link
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

  $_jf8CQ = 0;
  $_Qt6oI = $UserId;
  if($OwnerUserId != 0)
    $_Qt6oI = $OwnerUserId;

  for($_Q6llo=0; $_Q6llo<count($_6iOi1); $_Q6llo++) {
    $_6iOi1[$_Q6llo] = intval($_6iOi1[$_Q6llo]);
    $_QLitI = 0;
    $MailingListId = $_I0o0o;

    $_QJlJ0 = "SELECT * FROM $_j08fl WHERE id=$_6iOi1[$_Q6llo]";
    $_6iCJQ = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_6iCJQ) continue;
    $_6JLOL = mysql_fetch_array($_6iCJQ);
    mysql_free_result($_6iCJQ);
    if($_6JLOL["Send"] != "Failed" && $_6JLOL["Send"] != "PossiblySent") continue; // list operations, send failed only
    $_QLitI = $_6JLOL["recipients_id"];

    $_QJlJ0 = "SELECT u_EMail, u_CellNumber FROM $_QlQC8 WHERE id=$_QLitI";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_IJoII = $_Q6Q1C["u_EMail"];
      $_6lifo = $_Q6Q1C["u_CellNumber"];
    }else{
      $_6io6C[] = "member $_QLitI doesn't exists in mailinglist.";
      continue;
    }

    if(_L101P($_IJoII, $_I0o0o, $_ItCCo )) {
     $_6io6C[] = "member with cell phone number '$_6lifo' is in local black list.";
     continue;
    }
    if(_L0FRD($_IJoII)) {
     $_6io6C[] = "member with cell phone number '$_6lifo' is in global black list.";
     continue;
    }

    $_Q8COf = $_jOI0f->SendSingleSMS($_Q8OiJ["SMSSendVariant"], $_6lifo, $_Q8OiJ["SMSCampaignName"], $_6JLOL["MailSubject"]);

    // old entry
    $_QJlJ0 = "DELETE FROM $_j08fl WHERE id=$_6iOi1[$_Q6llo]";
    mysql_query($_QJlJ0, $_Q61I1);
    //$_jf8CQ--; NOT HERE!!!

    // new entry
    if($_Q8COf){
      $_jOLJ0 = $_jOI0f->SMSoutLastErrorString;
      // str_replace(",", ".", $_jOLJ0) weil anscheinend einige PHP versionen bei der Ausgabe aus dem Punkt ein Komma machen
      $_QJlJ0 = "INSERT INTO `$_Q8OiJ[RStatisticsTableName]` SET `SendStat_id`=$SendStatId, `MailSubject`="._OPQLR($_6JLOL["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QLitI, `Send`='Sent', SendResult='OK', `SMSCosts`="._OPQLR(str_replace(",", ".", $_jOLJ0));
      mysql_query($_QJlJ0, $_Q61I1);
    } else{
       $_QJlJ0 = "INSERT INTO `$_Q8OiJ[RStatisticsTableName]` SET `SendStat_id`=$SendStatId, `MailSubject`="._OPQLR($_6JLOL["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QLitI, `Send`='Failed', SendResult="._OPQLR("SMS to $_6lifo is undeliverable, Error:<br />".$_jOI0f->SMSoutLastErrorString." (".$_jOI0f->SMSoutLastErrorNo.")");
       mysql_query($_QJlJ0, $_Q61I1);
       $_6io6C[] = "SMS to $_6lifo is undeliverable, Error:<br />".$_jOI0f->SMSoutLastErrorString." (".$_jOI0f->SMSoutLastErrorNo.")";
    }

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

    $_jf8CQ++;

  } # for


#  if($_jf8CQ > 0){
#    $_QJlJ0 = "UPDATE `$_j0fti` SET `SendState`='ReSending' WHERE `id`=$SendStatId";
#    mysql_query($_QJlJ0, $_Q61I1);
#  }

 }
?>
