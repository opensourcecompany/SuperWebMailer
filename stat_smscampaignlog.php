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
  include_once("cron_smscampaigns.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("smsout.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeViewSMSCampaignLog"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

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

  if(!isset($_j01fj) && isset($_POST["ResponderId"]))
     $_j01fj = intval($_POST["ResponderId"]);
     else
     if(!isset($_j01fj) && isset($_GET["ResponderId"]))
         $_j01fj = intval($_GET["ResponderId"]);

  if(!isset($_j01fj)) {
    $_GET["ResponderType"] = "SMSCampaign";
    include_once("responderselect.inc.php");
    if(!isset($_POST["ResponderId"]))
       exit;
       else
       $_j01fj = intval($_POST["ResponderId"]);
  }

  if(!isset($SendStatId)) {
    $_GET["action"] = "stat_smscampaignlog.php";
    include_once("smscampaignsendstatselect.inc.php");
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


  if(isset($_GET["Result_id"]) && isset($_GET["CampaignId"]) && isset($_GET["SendStatId"]) ) {
    $_GET["Result_id"] = intval($_GET["Result_id"]);
    $_GET["CampaignId"] = intval($_GET["CampaignId"]);
    $_GET["SendStatId"] = intval($_GET["SendStatId"]);
    $_QLJfI = _JJAQE("smscampaignlog_view_result.htm");

    $_QLfol = "SELECT $_jJLLf.Name, $_jJLLf.RStatisticsTableName, $_QL88I.MaillistTableName FROM $_jJLLf LEFT JOIN $_QL88I ON $_QL88I.id=$_jJLLf.maillists_id WHERE $_jJLLf.id=$_GET[CampaignId]";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_ji080 = $_QLO0f["RStatisticsTableName"];
    $_I8I6o = $_QLO0f["MaillistTableName"];
    mysql_free_result($_QL8i1);

    $_QLJfI = str_replace("%NAME%", "'".$_QLO0f["Name"]."'", $_QLJfI);

    $_QLfol = "SELECT `$_ji080`.*, DATE_FORMAT(SendDateTime, $_QLo60) AS SentDateTime, `$_I8I6o`.u_CellNumber FROM `$_ji080` LEFT JOIN $_I8I6o ON `$_ji080`.recipients_id=`$_I8I6o`.id WHERE `$_ji080`.id=$_GET[Result_id] AND `$_ji080`.SendStat_id=$_GET[SendStatId]";

    $_QL8i1 = mysql_query($_QLfol);
    if($_QL8i1 && $_QLO0f = mysql_fetch_array($_QL8i1)) {
      $_QLJfI = _L81BJ($_QLJfI, "<RESULTTEXT>", "</RESULTTEXT>", $_QLO0f["SendResult"]);

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
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:SENTDATE>", "</LIST:SENTDATE>", $_8fQ0J.$_QLO0f["SentDateTime"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:CELLNUMBER>", "</LIST:CELLNUMBER>", $_QLO0f["u_CellNumber"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:SUBJECT>", "</LIST:SUBJECT>", htmlspecialchars(_LCRC8($_QLO0f["MailSubject"]), ENT_COMPAT, $_QLo06));
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:STATUS>", "</LIST:STATUS>", $resourcestrings[$INTERFACE_LANGUAGE]["MailSend".$_QLO0f["Send"]]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:SMSCOSTS>", "</LIST:SMSCOSTS>", sprintf("%01.2f", $_QLO0f["SMSCosts"])." EUR");
    }

    SetHTMLHeaders($_QLo06);

    print $_QLJfI;
    exit;
  }

  $_Itfj8 = "";

  ## Log actions

  $_Ilt8t = !isset($_POST["LogsActions"]);
  if(!$_Ilt8t) {
    if( isset($_POST["OneCampaignLogAction"]) && $_POST["OneCampaignLogAction"] != "" )
      $_Ilt8t = true;
    if($_Ilt8t) {
      if( !( isset($_POST["OneCampaignLogId"]) && $_POST["OneCampaignLogId"] != "")  )
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
       _JJ0FD($_j01fj, $SendStatId, $_8fIJO, $_foL0Q);
       if(count($_foL0Q) != 0)
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_foL0Q);
       else
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
     }

  }

  if( isset($_POST["OneCampaignLogAction"]) && isset($_POST["OneCampaignLogId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneCampaignLogAction"] == "DeleteLogEntry") {
        $_8fIJO = array();
        $_8fIJO[] = $_POST["OneCampaignLogId"];
        $_IQ0Cj = array();
        _JLAAL($_8fIJO, $_IQ0Cj);
        if(count($_IQ0Cj) != 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000672"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000671"];
      }
      if($_POST["OneCampaignLogAction"] == "SendAgain") {
        $_8fIJO = array();
        $_8fIJO[] = $_POST["OneCampaignLogId"];
        $_foL0Q = array();
        _JJ0FD($_j01fj, $SendStatId, $_8fIJO, $_foL0Q);
        if(count($_foL0Q) != 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000674"].join("<br />", $_foL0Q);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000673"];
      }

  }
  ## Log actions end


  $_QLfol = "SELECT $_jJLLf.*, $_QL88I.MaillistTableName FROM $_jJLLf LEFT JOIN $_QL88I ON $_QL88I.id=$_jJLLf.maillists_id WHERE $_jJLLf.id=$_j01fj";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_QLL16 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_jC6ot = $_QLL16["Name"];
  $_jClC1 = $_QLL16["CurrentSendTableName"];
  $_ji080 = $_QLL16["RStatisticsTableName"];
  $_I8I6o = $_QLL16["MaillistTableName"];
  $_IttOL = $_QLL16["maillists_id"];

  $_QLfol = "SELECT SendState, StartSendDateTime, DATE_FORMAT(StartSendDateTime, $_QLo60) AS SentDateTime, DATE_FORMAT(StartSendDateTime, $_j01CJ) AS STARTDATE, RecipientsCount, SentCountSucc, SentCountFailed, `SentCountPossiblySent`, ";
  $_QLfol .= "DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
  $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration ";
  $_QLfol .= " FROM $_jClC1 WHERE id=$SendStatId";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_IQ1ji = $_QLO0f["SendState"] == 'Sending';
  $_8ft1J = $_QLO0f["STARTDATE"];

  // Remove Send entry
  if( (isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) && !$_IQ1ji ) {
    $_8ftCj = array("RStatisticsTableName");
    for($_Qli6J=0; $_Qli6J<count($_8ftCj); $_Qli6J++){
      $_QLfol = "DELETE FROM `".$_QLL16[$_8ftCj[$_Qli6J]]."` WHERE SendStat_id=$SendStatId";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
    }

    # don't send it again when there are no send entries
    $_QLfol = "SELECT COUNT(*) FROM `$_jClC1` WHERE id<>$SendStatId";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    if($_QLO0f[0] == 0 && $_QLL16["SendScheduler"] != 'SaveOnly' && $_QLL16["SendScheduler"] != 'SendManually' ){
      $_QLfol = "UPDATE `$_jJLLf` SET `ReSendFlag`=0, `SetupLevel`=0 WHERE id=$_j01fj";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
    }
    mysql_free_result($_QL8i1);
    #

    $_QLfol = "DELETE FROM `$_jClC1` WHERE id=$SendStatId";
    mysql_query($_QLfol);
    _L8D88($_QLfol);
    include("browsesmscampaigns.php");
    exit;
  }
  // Remove Send entry /


  if($_IQ1ji) {
    // Count things while sending
    $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=$SendStatId AND Send='Sent'";
    $_jjJfo = mysql_query($_QLfol, $_QLttI);
    $_jj6L6 = mysql_fetch_row($_jjJfo);
    mysql_free_result($_jjJfo);
    $_QLO0f["SentCountSucc"] = $_jj6L6[0];

    $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=$SendStatId AND Send='Failed'";
    $_jjJfo = mysql_query($_QLfol, $_QLttI);
    $_jj6L6 = mysql_fetch_row($_jjJfo);
    mysql_free_result($_jjJfo);
    $_QLO0f["SentCountFailed"] = $_jj6L6[0];

    $_QLfol = "SELECT COUNT(id) FROM $_ji080 WHERE SendStat_id=$SendStatId AND Send='PossiblySent'";
    $_jjJfo = mysql_query($_QLfol, $_QLttI);
    $_jj6L6 = mysql_fetch_row($_jjJfo);
    mysql_free_result($_jjJfo);
    $_QLO0f["SentCountPossiblySent"] = $_jj6L6[0];
  }

  // Template
  $_8fOOf = $_QLO0f["SentDateTime"];
  if($_IQ1ji)
    $_8fOOf = "&quot;".$resourcestrings[$INTERFACE_LANGUAGE]["001675"]."&quot;";
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001670"], $_jC6ot, $_8fOOf), $_Itfj8, 'stat_smscampaignlog', 'browse_smscampaignslog_snipped.htm');

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<SENTSTATID>", "</SENTSTATID>", $SendStatId);
  if($_IQ1ji ) {
    $_QLJfI = _L80DF($_QLJfI, "<CAN_REMOVE_SENDSTATID>", "</CAN_REMOVE_SENDSTATID>");
  }

  if($_QLO0f["RecipientsCount"] == 0)
    $_QLO0f["RecipientsCount"] = 0.01;

  $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_jC6ot );
  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_QLO0f["RecipientsCount"] == 0.01 ? 0 : $_QLO0f["RecipientsCount"]);

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_QLO0f["SentCountSucc"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCCPERCENT>", "</SENTCOUNTSUCCPERCENT>", sprintf("%1.1f%%", $_QLO0f["SentCountSucc"] * 100 / $_QLO0f["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_QLO0f["SentCountFailed"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILEDPERCENT>", "</SENTCOUNTFAILEDPERCENT>", sprintf("%1.1f%%", $_QLO0f["SentCountFailed"] * 100 / $_QLO0f["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_QLO0f["SentCountPossiblySent"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENTPERCENT>", "</SENTCOUNTPOSSIBLYSENTPERCENT>", sprintf("%1.1f%%", $_QLO0f["SentCountPossiblySent"] * 100 / $_QLO0f["RecipientsCount"]) );

  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:START>", "</SENDING:START>", $_QLO0f["StartSendDateTimeFormated"]);
  if(!$_IQ1ji) {
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $_QLO0f["EndSendDateTimeFormated"]);
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $_QLO0f["SendDuration"]);
  } else {
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
    $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $resourcestrings[$INTERFACE_LANGUAGE]["000675"]);
  }

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

  $_QLfol = "SELECT {} FROM $_ji080 WHERE SendStat_id=$SendStatId AND SendDateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L);

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

  $_QLfol .= " ORDER BY SendDateTime";

  $_QLJfI = _JJ06D($_QLfol, $_QLJfI);

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

    if(!$_QLJJ6["PrivilegeSMSCampaignEdit"] || !$_QLJJ6["PrivilegeSMSCampaignBrowse"] ) {
      $_QLoli = _JJC1E($_QLoli, "DeleteLogEntry");
      $_QLoli = _JJCRD($_QLoli, "RemoveEntries");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }


  print $_QLJfI;

  function _JJ06D($_QLfol, $_QLoli) {
    global $UserId, $OwnerUserId, $_SESSION, $INTERFACE_LANGUAGE, $resourcestrings, $_jJLLf, $_JoiCQ, $_JoL0L, $_QLo60, $_j01fj, $SendStatId;
    global $_ji080, $_IttOL, $_IQ1ji, $_QlQot, $_QLo06;
    $_Il0o6 = array();
    $_Il0o6["CampaignId"]=$_j01fj;
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
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', "COUNT($_ji080.id)", $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6);
    _L8D88($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneCampaignLogId"] ) && ($_POST["OneCampaignLogId"] == "End") )
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

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_jf8JI = "$_ji080.*, DATE_FORMAT(SendDateTime, $_QLo60) AS SentDateTime";
    $_QLfol = str_replace('{}', $_jf8JI, $_QLfol);
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
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
        $_8t8Jt = _JLAOB("u_CellNumber", $_IttOL, $_QLO0f["recipients_id"]);
      else
        $_8t8Jt = $_QLO0f["CellNumber"];

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:CELLNUMBER>", "</LIST:CELLNUMBER>", $_8t8Jt);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:SUBJECT>", "</LIST:SUBJECT>", htmlspecialchars(_LCRC8($_QLO0f["MailSubject"]), ENT_COMPAT, $_QLo06));
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $resourcestrings[$INTERFACE_LANGUAGE]["MailSend".$_QLO0f["Send"]]);

      $_Ql0fO = str_replace("Result_id=", "Result_id=".$_QLO0f["id"], $_Ql0fO);
      $_Ql0fO = str_replace("CampaignId=", "CampaignId=".$_j01fj, $_Ql0fO);
      $_Ql0fO = str_replace("SendStatId=", "SendStatId=".$SendStatId, $_Ql0fO);


      if(($_QLO0f["Send"] == 'Failed' || $_QLO0f["Send"] == 'PossiblySent') && !$_IQ1ji && $_8t8Jt != "")
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
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    return $_QLoli;
  }

  function _JLAOB($_8fjoC, $_IttOL, $_Jt0lt) {
   global $_QL88I, $_QLttI;
   $_QltJO = "";
   $_QLfol = "SELECT MaillistTableName FROM $_QL88I WHERE id=".intval($_IttOL);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1) return $_QltJO;
   $_QLO0f = mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_QLfol = "SELECT `$_8fjoC` FROM $_QLO0f[MaillistTableName] WHERE id=".intval($_Jt0lt);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1) return $_QltJO;
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   return $_QLO0f[0];
  }

  function _JLAAL($_8fIJO, &$_IQ0Cj) {
    global $_j01fj, $_jJLLf, $_QLttI;

    $_QLfol = "SELECT RStatisticsTableName FROM $_jJLLf WHERE id=$_j01fj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    for($_Qli6J=0; $_Qli6J<count($_8fIJO); $_Qli6J++) {
      $_QLfol = "DELETE FROM $_QLO0f[RStatisticsTableName] WHERE id=".intval($_8fIJO[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "")
        $_IQ0Cj[] = mysql_error($_QLttI);
    }
  }

 function _JJ0FD($_j01fj, $SendStatId, $_8fIJO, &$_foL0Q) {
  global $_jJLLf, $_QL88I, $_QlQot, $UserId, $OwnerUserId, $_QLttI;
  global $INTERFACE_LANGUAGE, $_JQjt6, $_QLo06;

  $_J8f6L = new _JLJ0F();

  $_QLfol = "SELECT $_jJLLf.*, $_QL88I.MaillistTableName, $_QL88I.StatisticsTableName, $_QL88I.LocalBlocklistTableName, $_jJLLf.maillists_id FROM $_jJLLf LEFT JOIN $_QL88I ON $_QL88I.id=$_jJLLf.maillists_id WHERE $_jJLLf.id=$_j01fj";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_jClC1 = $_I1OfI["CurrentSendTableName"];
    $_ji080 = $_I1OfI["RStatisticsTableName"];
    $_I8I6o = $_I1OfI["MaillistTableName"];
    $_IttOL = $_I1OfI["maillists_id"];
    $_I8jjj = $_I1OfI["StatisticsTableName"];
    $_jjj8f = $_I1OfI["LocalBlocklistTableName"];
    $FormId = $_I1OfI["forms_id"];
    $_J8f6L->SMSoutUsername = $_I1OfI["SMSoutUsername"];
    $_J8f6L->SMSoutPassword = $_I1OfI["SMSoutPassword"];
    $_I1o8o = $_J8f6L->Login();
    if(!$_I1o8o){
      $_foL0Q[] = "Error logging in ".$_J8f6L->SMSoutLastErrorNo . " ". $_J8f6L->SMSoutLastErrorString;
      return false;
    }
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

  // get group ids if specified for unsubscribe link
  $_jt0QC = array();
  $_J0ILt = "SELECT * FROM $_I1OfI[GroupsTableName]";
  $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
  while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
    $_jt0QC[] = $_J0jCt[0];
  }
  mysql_free_result($_J0jIQ);
  if(count($_jt0QC) > 0) {
    // remove groups
    $_J0ILt = "SELECT * FROM $_I1OfI[NotInGroupsTableName]";
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

  $_JjiJl = 0;
  $_Qll8O = $UserId;
  if($OwnerUserId != 0)
    $_Qll8O = $OwnerUserId;

  for($_Qli6J=0; $_Qli6J<count($_8fIJO); $_Qli6J++) {
    $_8fIJO[$_Qli6J] = intval($_8fIJO[$_Qli6J]);
    $_IfLJj = 0;
    $MailingListId = $_IttOL;

    $_QLfol = "SELECT * FROM $_ji080 WHERE id=$_8fIJO[$_Qli6J]";
    $_8fJ6L = mysql_query($_QLfol, $_QLttI);
    if(!$_8fJ6L) continue;
    $_fifQL = mysql_fetch_array($_8fJ6L);
    mysql_free_result($_8fJ6L);
    if($_fifQL["Send"] != "Failed" && $_fifQL["Send"] != "PossiblySent") continue; // list operations, send failed only
    $_IfLJj = $_fifQL["recipients_id"];

    $_QLfol = "SELECT u_EMail, u_CellNumber FROM $_I8I6o WHERE id=$_IfLJj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_IL8oI = $_QLO0f["u_EMail"];
      $_8t8Jt = $_QLO0f["u_CellNumber"];
    }else{
      $_foL0Q[] = "member $_IfLJj doesn't exists in mailinglist.";
      continue;
    }

    if(_J18FQ($_IL8oI, $_IttOL, $_jjj8f )) {
     $_foL0Q[] = "member with cell phone number '$_8t8Jt' is in local black list.";
     continue;
    }
    if(_J18DA($_IL8oI)) {
     $_foL0Q[] = "member with cell phone number '$_8t8Jt' is in global black list.";
     continue;
    }

    $_I1o8o = $_J8f6L->SendSingleSMS($_I1OfI["SMSSendVariant"], $_8t8Jt, $_I1OfI["SMSCampaignName"], $_fifQL["MailSubject"]);

    // old entry
    $_QLfol = "DELETE FROM $_ji080 WHERE id=$_8fIJO[$_Qli6J]";
    mysql_query($_QLfol, $_QLttI);
    //$_JjiJl--; NOT HERE!!!

    // new entry
    if($_I1o8o){
      $_JOj18 = $_J8f6L->SMSoutLastErrorString;
      // str_replace(",", ".", $_JOj18) weil anscheinend einige PHP versionen bei der Ausgabe aus dem Punkt ein Komma machen
      $_QLfol = "INSERT INTO `$_I1OfI[RStatisticsTableName]` SET `SendStat_id`=$SendStatId, `MailSubject`="._LRAFO(unhtmlentities($_fifQL["MailSubject"], $_QLo06)).", `SendDateTime`=NOW(), `recipients_id`=$_IfLJj, `Send`='Sent', SendResult='OK', `SMSCosts`="._LRAFO(str_replace(",", ".", $_JOj18));
      mysql_query($_QLfol, $_QLttI);
    } else{
       $_QLfol = "INSERT INTO `$_I1OfI[RStatisticsTableName]` SET `SendStat_id`=$SendStatId, `MailSubject`="._LRAFO(unhtmlentities($_fifQL["MailSubject"], $_QLo06)).", `SendDateTime`=NOW(), `recipients_id`=$_IfLJj, `Send`='Failed', SendResult="._LRAFO("SMS to $_8t8Jt is undeliverable, Error:<br />".$_J8f6L->SMSoutLastErrorString." (".$_J8f6L->SMSoutLastErrorNo.")");
       mysql_query($_QLfol, $_QLttI);
       $_foL0Q[] = "SMS to $_8t8Jt is undeliverable, Error:<br />".$_J8f6L->SMSoutLastErrorString." (".$_J8f6L->SMSoutLastErrorNo.")";
    }

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

    $_JjiJl++;

  } # for


#  if($_JjiJl > 0){
#    $_QLfol = "UPDATE `$_jClC1` SET `SendState`='ReSending' WHERE `id`=$SendStatId";
#    mysql_query($_QLfol, $_QLttI);
#  }

 }
?>
