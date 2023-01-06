<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
  include_once("smscampaignstools.inc.php");
  include_once("cron_sendengine.inc.php");
  include_once("cron_smscampaigns.inc.php");
  include_once("smsout.inc.php");

  if (count($_POST) <= 1) {
    include_once("browsesmscampaigns.php");
    exit;
  }

  if(isset($_POST['CampaignListId'])) { // Formular speichern?
      $CampaignListId = intval($_POST['CampaignListId']);
    }
  else
    if(isset($_POST['OneCampaignListId']))
      $CampaignListId = intval($_POST['OneCampaignListId']);

  if(!isset($CampaignListId)) {
    include_once("browsesmscampaigns.php");
    exit;
  }

  $_POST['CampaignListId'] = $CampaignListId;

  if(isset($_POST["SendDone"]) && $_POST["SendDone"] == "") # Send done?
    unset($_POST["SendDone"]);

  if(isset($_POST["MaxSMSSentTime"]) && $_POST["MaxSMSSentTime"] == "") # Send done?
    unset($_POST["MaxSMSSentTime"]);

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_Itfj8 = "";

  // MailTextInfos
  $_QLfol = "SELECT `$_jJLLf`.*, `$_jJLLf`.Name AS CampaignsName, `$_QL88I`.MaillistTableName, `$_QL88I`.MailListToGroupsTableName, `$_QL88I`.LocalBlocklistTableName, `$_QL88I`.id AS MailingListId, `$_QL88I`.FormsTableName, `$_QL88I`.StatisticsTableName, `$_QL88I`.MailLogTableName, `$_QL88I`.`MTAsTableName`, `$_QL88I`.users_id ";
  $_QLfol .= " FROM `$_jJLLf` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_jJLLf`.maillists_id WHERE `$_jJLLf`.id=$CampaignListId";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_Itfj8 = $commonmsgHTMLFormNotFound;
  } else {
    $_jf6Qi = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }

  $MailingListId = $_jf6Qi["MailingListId"];
  $FormId = $_jf6Qi["forms_id"];
  $_IfJoo = $_jf6Qi["FormsTableName"];
  $_QljJi = $_jf6Qi["GroupsTableName"];
  $_I8I6o = $_jf6Qi["MaillistTableName"];
  $_IfJ66 = $_jf6Qi["MailListToGroupsTableName"];
  $_I8jjj = $_jf6Qi["StatisticsTableName"];
  $_jjj8f = $_jf6Qi["LocalBlocklistTableName"];
  $_I8jLt = $_jf6Qi["MailLogTableName"];

  $_jf6Qi["OverrideSubUnsubURL"] = "";

  // CurrentSendTableName
  $_jlOJO = 0;
  $_jlOCl = 0;
  $_jloJI = 0;
  $_jlolf = 0;
  $_jlCtf = 0;
  $_jliJi = 0;
  $_jlLQC=0;
  if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && $_POST["CurrentSendId"] > 0) { # Send done?
    $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, ";
    $_QLfol .= "DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
    $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
    $_QLfol .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_QLo60) AS SendEstEndTime ";
    $_QLfol .= "FROM `$_jf6Qi[CurrentSendTableName]` WHERE id=$_POST[CurrentSendId]";
    }
   else {
     if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && $_POST["CurrentSendId"] == 0) // browser or mysql crash?
       unset($_POST["SendDone"]);
     $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, ";
     $_QLfol .= "DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
     $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
     $_QLfol .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_QLo60) AS SendEstEndTime ";
     $_QLfol .= "FROM `$_jf6Qi[CurrentSendTableName]` WHERE SendState<>'Done' AND SendState<>'Paused'";
    }
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_QL8i1) > 0) {
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_jlOJO = $_QLO0f["LastMember_id"];
    $_jlOCl = $_QLO0f["id"];
    $_jloJI = $_QLO0f["RecipientsCount"];
    $_jlLL0 = $_QLO0f["StartSendDateTimeFormated"];
    $_jlLLO = $_QLO0f["EndSendDateTimeFormated"];
    $_jll1i = $_QLO0f["SendDuration"];
    $_jllQj = $_QLO0f["SendEstEndTime"];
    $_jlolf = $_QLO0f["SentCountSucc"];
    $_jlCtf = $_QLO0f["SentCountFailed"];
    $_jliJi = $_QLO0f["SentCountPossiblySent"];
    $_jlLQC = $_QLO0f["ReportSent"];
    mysql_free_result($_QL8i1);
  } else{
    mysql_free_result($_QL8i1);

    // Current Send Table
    $_QLfol = "INSERT INTO `$_jf6Qi[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW()";

    mysql_query($_QLfol, $_QLttI);
    $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_jlOCl = $_QLO0f[0];
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration FROM $_jf6Qi[CurrentSendTableName] WHERE id=$_jlOCl";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_jlLL0 = $_QLO0f["StartSendDateTimeFormated"];
    $_jlLLO = $_QLO0f["StartSendDateTimeFormated"]; //only set var
    $_jll1i = $_QLO0f["SendDuration"];
    $_jllQj = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
    mysql_free_result($_QL8i1);

    // Update ReSendFlag
    $_QLfol = "UPDATE $_jJLLf SET ReSendFlag=0 WHERE id=$_jf6Qi[id]";
    mysql_query($_QLfol, $_QLttI);

  }
  $_POST["CurrentSendId"] = $_jlOCl;
  // CurrentSendId for Tracking and AltBrowserLink
  $_jf6Qi["CurrentSendId"] = $_jlOCl;

  // RecipientsRow
  if($_jloJI == 0) {
    $_jloJI = _JLQEF($_jf6Qi, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
    mysql_query("UPDATE $_jf6Qi[CurrentSendTableName] SET RecipientsCount=$_jloJI WHERE id=$_jlOCl", $_QLttI);
  }

  $_QLfol = _JL1A6($_jf6Qi, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  $_QLfol .= " AND `$_I8I6o`.id>$_jlOJO ORDER BY `$_I8I6o`.id"." LIMIT 0, $_jf6Qi[MaxSMSToProcess]";

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_Itfj8 != "") ) { # Send done or error?
    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001650"], $_jf6Qi["CampaignsName"]), $_Itfj8, 'DISABLED', 'smscampaign_live_send_done_snipped.htm');
  } else {
    // Template
    $_QLJfI = GetMainTemplate(false, $UserType, $Username, false, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001650"], $_jf6Qi["CampaignsName"]), $_Itfj8, 'DISABLED', 'smscampaign_live_send_snipped.htm');
  }

  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jloJI);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jlolf);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jlCtf);
  $_QLJfI = _L81BJ($_QLJfI, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jliJi);

  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:START>", "</SENDING:START>", $_jlLL0);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:END>", "</SENDING:END>", $_jlLLO);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jll1i);
  $_QLJfI = _L81BJ($_QLJfI, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jllQj);

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_Itfj8 != "") ) { # Send done or error?

    print $_QLJfI;
    exit;
  }

  $_I016j = explode("<!--SPACER//-->", $_QLJfI);
  print $_I016j[0];
  flush();
  $_QLJfI = $_I016j[1];
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QLJfI = _L80DF($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");

  // smsout class
  $_J8f6L = new _JLJ0F();
  $_J8f6L->SMSoutUsername = $_jf6Qi["SMSoutUsername"];
  $_J8f6L->SMSoutPassword = $_jf6Qi["SMSoutPassword"];

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_8Jfoo = $_jf6Qi["MaxSMSToProcess"];
  if($_8Jfoo <= 0)
    $_8Jfoo = 1;
  $_JO1Qf = 0;
  $_8JttJ = 0;
  $_J0JLi = ini_get("max_execution_time");
  $_8Jtl0 = strpos($_jf6Qi["SMSText"], "[") !== false;
  $_8JOCJ = $_8Jfoo;

  if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
    while($_JO1Qf < $_8Jfoo && ($_j11Io = mysql_fetch_assoc($_QL8i1)) ) {

      // send time start
      list($_J0O1f, $_J0O6i) = explode(' ', microtime());
      $_J0OOt = (float) $_J0O6i + (float) $_J0O1f;

      _LRCOC();

      $_j11Io["RecipientsCount"] = $_jloJI;
      $_J0OiJ = false;

      if(isset($errors))
        unset($errors);
      $errors = array();
      if(isset($_I816i))
        unset($_I816i);
      $_I816i = array();

      $_J0COJ = "";
      $errors = false;
      $_I1o8o = false;
      if(!$_J8f6L->IsLoggedIn()) {
        $_I1o8o = $_J8f6L->Login();

        if(!$_I1o8o) {
            $errors = true;
            $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001652"], $_J8f6L->SMSoutLastErrorNo, $_J8f6L->SMSoutLastErrorString);
          }

      }

      _LRCOC();

      if(!$errors && $_J0COJ == "") {

        $_IoLOO = array();
        if( ($_j11Io["u_CellNumber"] = trim($_j11Io["u_CellNumber"])) == ""  || !_JLODC($_j11Io["u_CellNumber"], $_IoLOO) ){
          $errors = true;
          if(count($_IoLOO) == 0)
            $_J0COJ = $resourcestrings[$INTERFACE_LANGUAGE]["000584"];
            else
            $_J0COJ = join(" ", $_IoLOO);
        }
      }

      $_JOQ11 = $_jf6Qi["SMSText"];
      if($_8Jtl0)
         $_JOQ11 = _J1EBE($_j11Io, $MailingListId, $_JOQ11, $_QLo06, false, array());
         else
         $_JOQ11 = unhtmlentities($_JOQ11, $_QLo06);

      if(!$errors && $_J0COJ == "") {

        _LRCOC();

        $_JOQ8I = ConvertString($_QLo06, "iso-8859-1", $_JOQ11, false);

        if($_jf6Qi["SMSSendVariant"] < 6)
          $_JOIJl = 160;
          else
          $_JOIJl = 1560;

        if(strlen($_JOQ8I) > $_JOIJl) {
          $_JOQ8I = chunk_split($_JOQ8I, $_JOIJl - 1, chr(255));
          $_JOQ8I = explode(chr(255), $_JOQ8I);
        } else
          $_JOQ8I = array($_JOQ8I);



        $_JOj18 = 0;
        if(defined("DEMO") || defined("SimulateMailSending")) {
          $_I1o8o = true;
        }
        else {
          // SMS senden
          for($_JOj68=0; $_JOj68<count($_JOQ8I); $_JOj68++){
            if($_JOQ8I[$_JOj68] == "") continue;
            $_I1o8o = $_J8f6L->SendSingleSMS($_jf6Qi["SMSSendVariant"], $_j11Io["u_CellNumber"], $_jf6Qi["SMSCampaignName"], $_JOQ8I[$_JOj68]);
            if($_I1o8o) {
              $_JOj18 += $_J8f6L->SMSoutLastErrorString;
            }
          }
        }

        if($_I1o8o) {
          $_J0OiJ = true;

          // UpdateResponderStatistics
          // str_replace(",", ".", $_JOj18) weil anscheinend einige PHP versionen bei der Ausgabe aus dem Punkt ein Komma machen
          $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET SendStat_id=$_jlOCl, `MailSubject`="._LRAFO($_JOQ11).", `SendDateTime`=NOW(), `recipients_id`=".$_j11Io["id"].", `Send`='Sent', SendResult='OK', `SMSCosts`="._LRAFO(str_replace(",", ".", $_JOj18));
          mysql_query($_QLfol, $_QLttI);

          if(defined("DEMO") || defined("SimulateMailSending"))
            $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001651"], "DEMO, ".sprintf("%01.2f", $_JOj18)." EUR");
          else
            $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001651"], sprintf("%01.2f", $_JOj18)." EUR");


        } else{
          $_J0COJ = sprintf("Sending failed, Error code: %d, Error text: %s. ", $_J8f6L->SMSoutLastErrorNo, $_J8f6L->SMSoutLastErrorString);

          // UpdateResponderStatistics
          $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET SendStat_id=$_jlOCl, `MailSubject`="._LRAFO($_JOQ11).", `SendDateTime`=NOW(), `recipients_id`=".$_j11Io["id"].", `Send`='Failed', SendResult="._LRAFO("SMS is undeliverable, Error:<br />".$_J8f6L->SMSoutLastErrorString." (".$_J8f6L->SMSoutLastErrorNo.")");
          mysql_query($_QLfol, $_QLttI);

        }


      } else {
        if(!$_J8f6L->IsLoggedIn())
         $_J0COJ = sprintf("Sending failed, Error code: %d, Error text: %s. ", $_J8f6L->SMSoutLastErrorNo, $_J8f6L->SMSoutLastErrorString);
         else
         $_J0COJ = sprintf("Sending failed, Error code: %d, Error text: %s. ", 9999, $_J0COJ);
        // UpdateResponderStatistics
        $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET SendStat_id=$_jlOCl, `MailSubject`="._LRAFO($_JOQ11).", `SendDateTime`=NOW(), `recipients_id`=$_j11Io[id], `Send`='Failed', SendResult="._LRAFO($_J0COJ);
        mysql_query($_QLfol, $_QLttI);
      }

      # update last member id, script timeout
      if($_J0OiJ)
         $_QLfol = "`SentCountSucc`=`SentCountSucc`+1";
         else
         $_QLfol = "`SentCountFailed`=`SentCountFailed`+1";
      $_QLfol = "UPDATE `$_jf6Qi[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_j11Io[id], $_QLfol WHERE id=$_jlOCl";
      mysql_query($_QLfol, $_QLttI);

      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_j11Io["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:CELLNUMBER>", "</LIST:CELLNUMBER>", $_j11Io["u_CellNumber"]);

      // send time end
      list($_J0O1f, $_J0O6i) = explode(' ', microtime());
      $_J0i1O = (float) $_J0O6i + (float) $_J0O1f;
      $_JO1Qf++;

      // calculate script timeout
      if($_8JttJ < $_J0i1O - $_J0OOt)
        $_8JttJ = $_J0i1O - $_J0OOt;
      if($_J0JLi > 0) {
        if($_8JttJ * $_JO1Qf > $_J0JLi - 1) {
          if( $_8Jfoo > intval(($_J0JLi - 1) / $_8JttJ) )
             $_8Jfoo = intval(($_J0JLi - 1) / $_8JttJ);
          if($_8Jfoo <= 0)
            $_8Jfoo = 1;
        }
      }


      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $_J0COJ.round($_J0i1O - $_J0OOt, 5)."s");


      print $_QLl1Q.$_Ql0fO;
      flush();

      _LRCOC();


    } # while($_j11Io = mysql_fetch_assoc($_QL8i1))
    if($_QL8i1)
      mysql_free_result($_QL8i1);

  } /* if(mysql_num_rows($_QL8i1) > 0) */
    else {
      # it's ever called in a new request
      _LRCOC();

      $_QLfol = "UPDATE $_jf6Qi[CurrentSendTableName] SET EndSendDateTime=NOW(), SendState='Done', CampaignSendDone=1, ReportSent=1 WHERE id=$_jlOCl";
      mysql_query($_QLfol, $_QLttI);
      $_QLJfI = str_replace('name="SendDone"', 'name="SendDone" value="1"', $_QLJfI);
      $_jf6Qi["ReportSent"] = 0;

      # GET MTA
      $_QLfol = "SELECT $_Ijt0i.* FROM $_Ijt0i RIGHT JOIN $_jf6Qi[MTAsTableName] ON $_jf6Qi[MTAsTableName].mtas_id=$_Ijt0i.id ORDER BY $_jf6Qi[MTAsTableName].sortorder LIMIT 0, 1"; // only the first;
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      // we take the first in list
      $_J00C0 = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      // merge text with mail send settings
      if(isset($_J00C0["id"])) {
        unset($_J00C0["id"]);
        unset($_J00C0["CreateDate"]);
        unset($_J00C0["IsDefault"]);
        unset($_J00C0["Name"]);
      }
      $_jf6Qi = array_merge($_jf6Qi, $_J00C0);

      _LJ6DQ($_jf6Qi, $_jlOCl);
  }

  print $_QLJfI;

?>
