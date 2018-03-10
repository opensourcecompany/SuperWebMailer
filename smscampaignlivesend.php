<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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

  if (count($_POST) == 0) {
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

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_I0600 = "";

  // MailTextInfos
  $_QJlJ0 = "SELECT `$_IoCtL`.*, `$_IoCtL`.Name AS CampaignsName, `$_Q60QL`.MaillistTableName, `$_Q60QL`.MailListToGroupsTableName, `$_Q60QL`.LocalBlocklistTableName, `$_Q60QL`.id AS MailingListId, `$_Q60QL`.FormsTableName, `$_Q60QL`.StatisticsTableName, `$_Q60QL`.MailLogTableName, `$_Q60QL`.`MTAsTableName`, `$_Q60QL`.users_id ";
  $_QJlJ0 .= " FROM `$_IoCtL` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_IoCtL`.maillists_id WHERE `$_IoCtL`.id=$CampaignListId";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    $_I0600 = $commonmsgHTMLFormNotFound;
  } else {
    $_IiICC = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }

  $MailingListId = $_IiICC["MailingListId"];
  $FormId = $_IiICC["forms_id"];
  $_QLI8o = $_IiICC["FormsTableName"];
  $_Q6t6j = $_IiICC["GroupsTableName"];
  $_QlQC8 = $_IiICC["MaillistTableName"];
  $_QLI68 = $_IiICC["MailListToGroupsTableName"];
  $_QlIf6 = $_IiICC["StatisticsTableName"];
  $_ItCCo = $_IiICC["LocalBlocklistTableName"];
  $_QljIQ = $_IiICC["MailLogTableName"];

  $_IiICC["OverrideSubUnsubURL"] = "";

  // CurrentSendTableName
  $_jQlit = 0;
  $_jQll6 = 0;
  $_jI0Oo = 0;
  $_jI1Ql = 0;
  $_jI1tt = 0;
  $_jIQ0i = 0;
  $_jIQfo=0;
  if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && $_POST["CurrentSendId"] > 0) { # Send done?
    $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, ";
    $_QJlJ0 .= "DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, ";
    $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
    $_QJlJ0 .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_Q6QiO) AS SendEstEndTime ";
    $_QJlJ0 .= "FROM `$_IiICC[CurrentSendTableName]` WHERE id=$_POST[CurrentSendId]";
    }
   else {
     if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && $_POST["CurrentSendId"] == 0) // browser or mysql crash?
       unset($_POST["SendDone"]);
     $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, ";
     $_QJlJ0 .= "DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, ";
     $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
     $_QJlJ0 .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_Q6QiO) AS SendEstEndTime ";
     $_QJlJ0 .= "FROM `$_IiICC[CurrentSendTableName]` WHERE SendState<>'Done' AND SendState<>'Paused'";
    }
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q60l1) > 0) {
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_jQlit = $_Q6Q1C["LastMember_id"];
    $_jQll6 = $_Q6Q1C["id"];
    $_jI0Oo = $_Q6Q1C["RecipientsCount"];
    $_jII6j = $_Q6Q1C["StartSendDateTimeFormated"];
    $_jIjJi = $_Q6Q1C["EndSendDateTimeFormated"];
    $_jIj6l = $_Q6Q1C["SendDuration"];
    $_jIjC1 = $_Q6Q1C["SendEstEndTime"];
    $_jI1Ql = $_Q6Q1C["SentCountSucc"];
    $_jI1tt = $_Q6Q1C["SentCountFailed"];
    $_jIQ0i = $_Q6Q1C["SentCountPossiblySent"];
    $_jIQfo = $_Q6Q1C["ReportSent"];
    mysql_free_result($_Q60l1);
  } else{
    mysql_free_result($_Q60l1);

    // Current Send Table
    $_QJlJ0 = "INSERT INTO `$_IiICC[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW()";

    mysql_query($_QJlJ0, $_Q61I1);
    $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_jQll6 = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration FROM $_IiICC[CurrentSendTableName] WHERE id=$_jQll6";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_jII6j = $_Q6Q1C["StartSendDateTimeFormated"];
    $_jIjJi = $_Q6Q1C["StartSendDateTimeFormated"]; //only set var
    $_jIj6l = $_Q6Q1C["SendDuration"];
    $_jIjC1 = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
    mysql_free_result($_Q60l1);

    // Update ReSendFlag
    $_QJlJ0 = "UPDATE $_IoCtL SET ReSendFlag=0 WHERE id=$_IiICC[id]";
    mysql_query($_QJlJ0, $_Q61I1);

  }
  $_POST["CurrentSendId"] = $_jQll6;
  // CurrentSendId for Tracking and AltBrowserLink
  $_IiICC["CurrentSendId"] = $_jQll6;

  // RecipientsRow
  if($_jI0Oo == 0) {
    $_jI0Oo = _LOAFR($_IiICC, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
    mysql_query("UPDATE $_IiICC[CurrentSendTableName] SET RecipientsCount=$_jI0Oo WHERE id=$_jQll6", $_Q61I1);
  }

  $_QJlJ0 = _LOP8R($_IiICC, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  $_QJlJ0 .= " AND `$_QlQC8`.id>$_jQlit ORDER BY `$_QlQC8`.id"." LIMIT 0, $_IiICC[MaxSMSToProcess]";

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_I0600 != "") ) { # Send done or error?
    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001650"], $_IiICC["CampaignsName"]), $_I0600, 'DISABLED', 'smscampaign_live_send_done_snipped.htm');
  } else {
    // Template
    $_QJCJi = GetMainTemplate(false, $UserType, $Username, false, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001650"], $_IiICC["CampaignsName"]), $_I0600, 'DISABLED', 'smscampaign_live_send_snipped.htm');
  }

  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jI0Oo);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jI1Ql);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jI1tt);
  $_QJCJi = _OPR6L($_QJCJi, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jIQ0i);

  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:START>", "</SENDING:START>", $_jII6j);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:END>", "</SENDING:END>", $_jIjJi);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jIj6l);
  $_QJCJi = _OPR6L($_QJCJi, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jIjC1);

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_I0600 != "") ) { # Send done or error?

    print $_QJCJi;
    exit;
  }

  $_QllO8 = explode("<!--SPACER//-->", $_QJCJi);
  print $_QllO8[0];
  flush();
  $_QJCJi = $_QllO8[1];
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QJCJi = _OP6PQ($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");

  // smsout class
  $_jOI0f = new _LODEB();
  $_jOI0f->SMSoutUsername = $_IiICC["SMSoutUsername"];
  $_jOI0f->SMSoutPassword = $_IiICC["SMSoutPassword"];

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_6ooi8 = $_IiICC["MaxSMSToProcess"];
  if($_6ooi8 <= 0)
    $_6ooi8 = 1;
  $_jOo68 = 0;
  $_6oiI8 = 0;
  $_jICIf = ini_get("max_execution_time");
  $_6oil1 = strpos($_IiICC["SMSText"], "[") !== false;
  $_6oilj = $_6ooi8;

  if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
    while($_jOo68 < $_6ooi8 && ($_jIiQ8 = mysql_fetch_assoc($_Q60l1)) ) {

      // send time start
      list($_jIiLJ, $_jILot) = explode(' ', microtime());
      $_jIlQO = (float) $_jILot + (float) $_jIiLJ;

      _OPQ6J();

      $_jIiQ8["RecipientsCount"] = $_jI0Oo;
      $_jIlC0 = false;

      if(isset($errors))
        unset($errors);
      $errors = array();
      if(isset($_Ql1O8))
        unset($_Ql1O8);
      $_Ql1O8 = array();

      $_jj0JO = "";
      $errors = false;
      $_Q8COf = false;
      if(!$_jOI0f->IsLoggedIn()) {
        $_Q8COf = $_jOI0f->Login();

        if(!$_Q8COf) {
            $errors = true;
            $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001652"], $_jOI0f->SMSoutLastErrorNo, $_jOI0f->SMSoutLastErrorString);
          }

      }

      _OPQ6J();

      if(!$errors && $_jj0JO == "") {

        $_II1Ot = array();
        if( ($_jIiQ8["u_CellNumber"] = trim($_jIiQ8["u_CellNumber"])) == ""  || !_LOC1E($_jIiQ8["u_CellNumber"], $_II1Ot) ){
          $errors = true;
          if(count($_II1Ot) == 0)
            $_jj0JO = $resourcestrings[$INTERFACE_LANGUAGE]["000584"];
            else
            $_jj0JO = join(" ", $_II1Ot);
        }
      }

      $_jOiQ8 = $_IiICC["SMSText"];
      if($_6oil1)
         $_jOiQ8 = _L1ERL($_jIiQ8, $MailingListId, $_jOiQ8, "utf-8", false, array());

      if(!$errors && $_jj0JO == "") {

        _OPQ6J();

        $_jOiiI = ConvertString("utf-8", "iso-8859-1", $_jOiQ8, false);

        if($_IiICC["SMSSendVariant"] < 6)
          $_jOiLi = 160;
          else
          $_jOiLi = 1560;

        if(strlen($_jOiiI) > $_jOiLi) {
          $_jOiiI = chunk_split($_jOiiI, $_jOiLi - 1, chr(255));
          $_jOiiI = explode(chr(255), $_jOiiI);
        } else
          $_jOiiI = array($_jOiiI);



        $_jOLJ0 = 0;
        if(defined("DEMO") || defined("SimulateMailSending")) {
          $_Q8COf = true;
        }
        else {
          // SMS senden
          for($_jOLtJ=0; $_jOLtJ<count($_jOiiI); $_jOLtJ++){
            if($_jOiiI[$_jOLtJ] == "") continue;
            $_Q8COf = $_jOI0f->SendSingleSMS($_IiICC["SMSSendVariant"], $_jIiQ8["u_CellNumber"], $_IiICC["SMSCampaignName"], $_jOiiI[$_jOLtJ]);
            if($_Q8COf) {
              $_jOLJ0 += $_jOI0f->SMSoutLastErrorString;
            }
          }
        }

        if($_Q8COf) {
          $_jIlC0 = true;

          // UpdateResponderStatistics
          // str_replace(",", ".", $_jOLJ0) weil anscheinend einige PHP versionen bei der Ausgabe aus dem Punkt ein Komma machen
          $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET SendStat_id=$_jQll6, `MailSubject`="._OPQLR($_jOiQ8).", `SendDateTime`=NOW(), `recipients_id`=".$_jIiQ8["id"].", `Send`='Sent', SendResult='OK', `SMSCosts`="._OPQLR(str_replace(",", ".", $_jOLJ0));
          mysql_query($_QJlJ0, $_Q61I1);

          if(defined("DEMO") || defined("SimulateMailSending"))
            $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001651"], "DEMO, ".sprintf("%01.2f", $_jOLJ0)." EUR");
          else
            $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001651"], sprintf("%01.2f", $_jOLJ0)." EUR");


        } else{
          $_jj0JO = sprintf("Sending failed, Error code: %d, Error text: %s. ", $_jOI0f->SMSoutLastErrorNo, $_jOI0f->SMSoutLastErrorString);

          // UpdateResponderStatistics
          $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET SendStat_id=$_jQll6, `MailSubject`="._OPQLR($_jOiQ8).", `SendDateTime`=NOW(), `recipients_id`=".$_jIiQ8["id"].", `Send`='Failed', SendResult="._OPQLR("SMS is undeliverable, Error:<br />".$_jOI0f->SMSoutLastErrorString." (".$_jOI0f->SMSoutLastErrorNo.")");
          mysql_query($_QJlJ0, $_Q61I1);

        }


      } else {
        if(!$_jOI0f->IsLoggedIn())
         $_jj0JO = sprintf("Sending failed, Error code: %d, Error text: %s. ", $_jOI0f->SMSoutLastErrorNo, $_jOI0f->SMSoutLastErrorString);
         else
         $_jj0JO = sprintf("Sending failed, Error code: %d, Error text: %s. ", 9999, $_jj0JO);
        // UpdateResponderStatistics
        $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET SendStat_id=$_jQll6, `MailSubject`="._OPQLR($_jOiQ8).", `SendDateTime`=NOW(), `recipients_id`=$_jIiQ8[id], `Send`='Failed', SendResult="._OPQLR($_jj0JO);
        mysql_query($_QJlJ0, $_Q61I1);
      }

      # update last member id, script timeout
      if($_jIlC0)
         $_QJlJ0 = "`SentCountSucc`=`SentCountSucc`+1";
         else
         $_QJlJ0 = "`SentCountFailed`=`SentCountFailed`+1";
      $_QJlJ0 = "UPDATE `$_IiICC[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_jIiQ8[id], $_QJlJ0 WHERE id=$_jQll6";
      mysql_query($_QJlJ0, $_Q61I1);

      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_jIiQ8["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:CELLNUMBER>", "</LIST:CELLNUMBER>", $_jIiQ8["u_CellNumber"]);

      // send time end
      list($_jIiLJ, $_jILot) = explode(' ', microtime());
      $_jj1IC = (float) $_jILot + (float) $_jIiLJ;
      $_jOo68++;

      // calculate script timeout
      if($_6oiI8 < $_jj1IC - $_jIlQO)
        $_6oiI8 = $_jj1IC - $_jIlQO;
      if($_jICIf > 0) {
        if($_6oiI8 * $_jOo68 > $_jICIf - 1) {
          if( $_6ooi8 > intval(($_jICIf - 1) / $_6oiI8) )
             $_6ooi8 = intval(($_jICIf - 1) / $_6oiI8);
          if($_6ooi8 <= 0)
            $_6ooi8 = 1;
        }
      }


      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $_jj0JO.round($_jj1IC - $_jIlQO, 5)."s");


      print $_Q6JJJ.$_Q66jQ;
      flush();

      _OPQ6J();


    } # while($_jIiQ8 = mysql_fetch_assoc($_Q60l1))
    if($_Q60l1)
      mysql_free_result($_Q60l1);

  } /* if(mysql_num_rows($_Q60l1) > 0) */
    else {
      # it's ever called in a new request
      _OPQ6J();

      $_QJlJ0 = "UPDATE $_IiICC[CurrentSendTableName] SET EndSendDateTime=NOW(), SendState='Done', CampaignSendDone=1, ReportSent=1 WHERE id=$_jQll6";
      mysql_query($_QJlJ0, $_Q61I1);
      $_QJCJi = str_replace('name="SendDone"', 'name="SendDone" value="1"', $_QJCJi);
      $_IiICC["ReportSent"] = 0;

      # GET MTA
      $_QJlJ0 = "SELECT $_Qofoi.* FROM $_Qofoi RIGHT JOIN $_IiICC[MTAsTableName] ON $_IiICC[MTAsTableName].mtas_id=$_Qofoi.id ORDER BY $_IiICC[MTAsTableName].sortorder LIMIT 0, 1"; // only the first;
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      // we take the first in list
      $_jIfO0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      // merge text with mail send settings
      if(isset($_jIfO0["id"])) {
        unset($_jIfO0["id"]);
        unset($_jIfO0["CreateDate"]);
        unset($_jIfO0["IsDefault"]);
        unset($_jIfO0["Name"]);
      }
      $_IiICC = array_merge($_IiICC, $_jIfO0);

      _ORCL8($_IiICC, $_jQll6);
  }

  print $_QJCJi;

?>
