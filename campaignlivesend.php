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

  define("CampaignLiveSending", 1);
  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("campaignstools.inc.php");
  include_once("cron_sendengine.inc.php");
  include_once("cron_campaigns.inc.php");
  include_once("mailer.php");
//  include_once("twitter.inc.php");

  if (count($_POST) <= 1) {
    include_once("browsecampaigns.php");
    exit;
  }

  if(isset($_POST['CampaignListId'])) { // Formular speichern?
      $CampaignListId = intval($_POST['CampaignListId']);
    }
  else
    if(isset($_POST['OneCampaignListId']))
      $CampaignListId = intval($_POST['OneCampaignListId']);

  if(!isset($CampaignListId)) {
    include_once("browsecampaigns.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeCampaignSending"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(empty($_QLi60)){
   $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
   $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", "Session lost, table for emailings empty.");
   print $_QLJfI;
   exit;
  }

  $_POST['CampaignListId'] = intval($CampaignListId);

  if(isset($_POST["SendDone"]) && $_POST["SendDone"] == "") # Send done?
    unset($_POST["SendDone"]);

  if(isset($_POST["MaxEMailSentTime"]) && $_POST["MaxEMailSentTime"] == "") # Send done?
    unset($_POST["MaxEMailSentTime"]);

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_jlt10 = _JOLQE("ECGListCheck");
  
  $_Itfj8 = "";

  // MailTextInfos
  $_QLfol = "SELECT `$_QLi60`.*, `$_QLi60`.Name AS CampaignsName, `$_QL88I`.MaillistTableName, `$_QL88I`.`forms_id` AS `MLForms_id`, `$_QL88I`.`SubscriptionUnsubscription`, `$_QL88I`.MailListToGroupsTableName, `$_QL88I`.LocalBlocklistTableName, `$_QL88I`.id AS MailingListId, `$_QL88I`.FormsTableName, `$_QL88I`.StatisticsTableName, `$_QL88I`.MailLogTableName, `$_QL88I`.users_id, `$_QL88I`.`ExternalBounceScript`, ";
  $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_QLi60`.SenderFromName <> '', `$_QLi60`.SenderFromName, `$_QL88I`.SenderFromName) AS SenderFromName,";
  $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_QLi60`.SenderFromAddress <> '', `$_QLi60`.SenderFromAddress, `$_QL88I`.SenderFromAddress) AS SenderFromAddress,";
  $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_QLi60`.ReplyToEMailAddress, `$_QL88I`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
  $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_QLi60`.ReturnPathEMailAddress, `$_QL88I`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
  $_QLfol .= " FROM `$_QLi60` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_QLi60`.maillists_id WHERE `$_QLi60`.id=$CampaignListId";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_Itfj8 = $commonmsgHTMLFormNotFound;
  } else {
    $_jf6Qi = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }

  $MailingListId = $_jf6Qi["MailingListId"];
  $_jlt8j = $_jf6Qi["MLForms_id"];
  $_jlO00 = $_jf6Qi["forms_id"];
  $_IfJoo = $_jf6Qi["FormsTableName"];
  $_QljJi = $_jf6Qi["GroupsTableName"];
  $_I8I6o = $_jf6Qi["MaillistTableName"];
  $_IfJ66 = $_jf6Qi["MailListToGroupsTableName"];
  $_I8jjj = $_jf6Qi["StatisticsTableName"];
  $_jjj8f = $_jf6Qi["LocalBlocklistTableName"];
  $_I8jLt = $_jf6Qi["MailLogTableName"];
  
  // ECG List not more than 5000
  if($_jlt10 And $_jf6Qi["MaxEMailsToProcess"] > 5000)
    $_jf6Qi["MaxEMailsToProcess"] = 5000;

  $_QLfol = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_IfJoo` WHERE id=$_jlO00";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_I8fol = mysql_fetch_assoc($_QL8i1);
    $_jf6Qi["OverrideSubUnsubURL"] = $_I8fol["OverrideSubUnsubURL"];
    $_jf6Qi["OverrideTrackingURL"] = $_I8fol["OverrideTrackingURL"];
    mysql_free_result($_QL8i1);
  } else{
    $_jf6Qi["OverrideSubUnsubURL"] = "";
    $_jf6Qi["OverrideTrackingURL"] = "";
  }

  // CurrentSendTableName
  $_jlOJO = 0;
  $_jlOCl = 0;
  $_jloJI = 0;
  $_jlolf = 0;
  $_jlCtf = 0;
  $_jliJi = 0;
  $_jlLQC=0;
  if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && intval($_POST["CurrentSendId"]) > 0) { # Send done?
    $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, ";
    $_QLfol .= "DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
    $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
    $_QLfol .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_QLo60) AS SendEstEndTime ";
    $_QLfol .= "FROM `$_jf6Qi[CurrentSendTableName]` WHERE id=".intval($_POST["CurrentSendId"]);
    }
   else {
     if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && intval($_POST["CurrentSendId"]) == 0) // browser or mysql crash?
       unset($_POST["SendDone"]);
     $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, ";
     $_QLfol .= "DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
     $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
     $_QLfol .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_QLo60) AS SendEstEndTime ";
     $_QLfol .= "FROM `$_jf6Qi[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND SendState<>'Done' AND SendState<>'Paused'";
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

    mysql_query("BEGIN", $_QLttI);

    // Current Send Table
    $_QLfol = "INSERT INTO `$_jf6Qi[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW(), `Campaigns_id`=$CampaignListId";

    mysql_query($_QLfol, $_QLttI);
    $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    $_jlOCl = $_QLO0f[0];
    mysql_free_result($_QL8i1);

    /*
    // Twitter Update start
    $_J0QOL = "";
    $_J01ot = "TWITTER_UPDATE_NOT_CONFIGURED";
    if($_jf6Qi["TwitterUpdate"]){
       $twitter = new _JJDPE($_jf6Qi["TwitterUsername"], $_jf6Qi["TwitterPassword"]);

       if($OwnerUserId == 0)
         $TrackingUserId = $UserId;
         else
         $TrackingUserId = $OwnerUserId;
       $ResponderTypeNum = _LPO6C("Campaign");
       $Identifier = sprintf("%02X", $_jlOCl)."_".sprintf("%02X", $TrackingUserId)."_".sprintf("%02X", $ResponderTypeNum)."_".sprintf("%02X", $_jf6Qi["id"]);
       // Twitter
       $key = sprintf("twitter-%02X-%02X", $MailingListId, $_jlO00);

       $twitterURL = $AltBrowserLinkScript."?key=$key&rid=".$Identifier;

       $Error = "";
       $twitterURL = $twitter->TwitterGetShortURL($twitterURL, $Error);
       if($twitterURL !== false) {
           $text = $_jf6Qi["MailSubject"];
           $fields = GetAllCampaignPersonalizingFields($_I8I6o);
           reset($fields);
           foreach($fields as $key => $value){
             $text = str_ireplace("[$key]", "", $text);
           }
           if($twitter->TwitterSendStatusMessage("$text\r\n".$twitterURL, $Error)){
              $_J01ot = "TWITTER_UPDATE_DONE";
            }
             else {
               $_J01ot = "TWITTER_UPDATE_FAILED";
               $_J0QOL = "TWITTER_UPDATE_POSTING_FAILED";
             }
         }
         else {
          $_J01ot = "TWITTER_UPDATE_FAILED";
          $_J0QOL = "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE";
        }
        $_POST["TwitterUpdateState"] = $_J01ot;
        $_POST["TwitterUpdateErrorText"] = $_J0QOL;

        $_QLfol = "UPDATE `$_jf6Qi[CurrentSendTableName]` SET ";

        if($_J01ot == "TWITTER_UPDATE_NOT_CONFIGURED")
           $_QLfol .= "`TwitterUpdate`='NotActivated'";
           else
           if($_J01ot == "TWITTER_UPDATE_DONE")
              $_QLfol .= "`TwitterUpdate`='Done'";
              else
              if($_J01ot == "TWITTER_UPDATE_FAILED")
                 $_QLfol .= "`TwitterUpdate`='Failed'";
        $_QLfol .= ", `TwitterUpdateErrorText`="._LRAFO($_J0QOL);
        $_QLfol .= " WHERE id=$_jlOCl";
        mysql_query($_QLfol, $_QLttI);
    }
    // Twitter Update end
    */

    // SET Current used MTAs to zero
    $_QLfol = "INSERT INTO `$_jf6Qi[CurrentUsedMTAsTableName]` SELECT 0, $_jlOCl, `mtas_id`, 0 FROM `$_jf6Qi[MTAsTableName]` WHERE `Campaigns_id`=$CampaignListId ORDER BY sortorder";
    mysql_query($_QLfol, $_QLttI);

    // Archive Table
    $_QLfol = "INSERT INTO `$_jf6Qi[ArchiveTableName]` SET SendStat_id=$_jlOCl, ";
    $_QLfol .= "MailFormat="._LRAFO($_jf6Qi["MailFormat"]).", ";
    $_QLfol .= "MailEncoding="._LRAFO($_jf6Qi["MailEncoding"]).", ";
    $_QLfol .= "MailSubject="._LRAFO($_jf6Qi["MailSubject"]).", ";
    $_QLfol .= "MailPlainText="._LRAFO($_jf6Qi["MailPlainText"]).", ";
    $_QLfol .= "MailHTMLText="._LRAFO($_jf6Qi["MailHTMLText"]).", ";
    $_QLfol .= "Attachments="._LRAFO($_jf6Qi["Attachments"]);
    mysql_query($_QLfol, $_QLttI);

    $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_jlloj = $_QLO0f[0];
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
    $_QLfol = "UPDATE $_QLi60 SET ReSendFlag=0 WHERE id=$_jf6Qi[id]";
    mysql_query($_QLfol, $_QLttI);

    mysql_query("COMMIT", $_QLttI);

  }
  $_POST["CurrentSendId"] = $_jlOCl;
  // CurrentSendId for Tracking and AltBrowserLink
  $_jf6Qi["CurrentSendId"] = $_jlOCl;

  // check all mtas_id are in CurrentUsedMTAsTableName
  $_QLfol = "SELECT DISTINCT `$_jf6Qi[MTAsTableName]`.mtas_id, `$_jf6Qi[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_jf6Qi[MTAsTableName]` LEFT JOIN `$_jf6Qi[CurrentUsedMTAsTableName]` ON `$_jf6Qi[CurrentUsedMTAsTableName]`.mtas_id = `$_jf6Qi[MTAsTableName]`.mtas_id WHERE `$_jf6Qi[CurrentUsedMTAsTableName]`.`SendStat_id`=$_jlOCl ORDER BY sortorder";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1)
    $_jllCj = mysql_num_rows($_QL8i1);
    else
    $_jllCj = 0;

  if(!$_QL8i1 || $_jllCj == 0) {
    $_Itfj8 = $commonmsgHTMLMTANotFound;
  }

  while($_jJ0JL = mysql_fetch_assoc($_QL8i1)) {
    $_QLO0f = $_jJ0JL; // save it
    if($_QLO0f["Usedmtas_id"] == "NULL") {
      $_QLfol = "INSERT INTO `$_jf6Qi[CurrentUsedMTAsTableName]` SET `SendStat_id`=$_jlOCl, `mtas_id`=$_QLO0f[mtas_id]";
      mysql_query($_QLfol, $_QLttI);
    }
  }
  mysql_free_result($_QL8i1);

  # one MTA only than we can get data and merge it directly
  if($_jllCj == 1){
    $_jf6Qi["mtas_id"] = $_QLO0f["mtas_id"];

    // MTA
    $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE `id`=$_jf6Qi[mtas_id]";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
      $_J00C0 = mysql_fetch_assoc($_I1O6j);
      mysql_free_result($_I1O6j);
    }

    // merge text with mail send settings
    if(isset($_J00C0["id"])) {
      unset($_J00C0["id"]);
      unset($_J00C0["CreateDate"]);
      unset($_J00C0["IsDefault"]);
      unset($_J00C0["Name"]);
    }
    $_jf6Qi = array_merge($_jf6Qi, $_J00C0);
  }

  // Looping protection
  $AdditionalHeaders = array();
  if(isset($_jf6Qi["AddXLoop"]) && $_jf6Qi["AddXLoop"])
     $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
  if(isset($_jf6Qi["AddListUnsubscribe"]) && $_jf6Qi["AddListUnsubscribe"])
     $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';


  // RecipientsRow
  if($_jloJI == 0) {
    $_jloJI = _LO6LA($_jf6Qi, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
    mysql_query("UPDATE $_jf6Qi[CurrentSendTableName] SET RecipientsCount=$_jloJI WHERE id=$_jlOCl", $_QLttI);
  }

  $_QLfol = _LOOCQ($_jf6Qi, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  $_QLfol .= " AND `$_I8I6o`.id>$_jlOJO ORDER BY `$_I8I6o`.id"." LIMIT 0, $_jf6Qi[MaxEMailsToProcess]";

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_Itfj8 != "") ) { # Send done or error?
    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000650"], $_jf6Qi["CampaignsName"]), $_Itfj8, 'DISABLED', 'campaign_live_send_done_snipped.htm');
  } else {
    // Template
    $_QLJfI = GetMainTemplate(false, $UserType, $Username, false, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000650"], $_jf6Qi["CampaignsName"]), $_Itfj8, 'DISABLED', 'campaign_live_send_snipped.htm');
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

  if(empty($_POST["TwitterUpdateState"])) {
    $_POST["TwitterUpdateState"] = "TWITTER_UPDATE_NOT_CONFIGURED";
  }
  if($_POST["TwitterUpdateState"] == "TWITTER_UPDATE_NOT_CONFIGURED")
     $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
  if($_POST["TwitterUpdateState"] == "TWITTER_UPDATE_DONE")
     $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
  if($_POST["TwitterUpdateState"] == "TWITTER_UPDATE_FAILED") {
     if( $_POST["TwitterUpdateErrorText"] == "TWITTER_UPDATE_POSTING_FAILED" )
        $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
        else
        if( $_POST["TwitterUpdateErrorText"] == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
            $_QLJfI = _L81BJ($_QLJfI, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);
  }

  if( !empty($_POST["SendDone"]) || ($_Itfj8 != "") ) { # Send done or error?

    if( (!$_jf6Qi["TrackLinks"] && !$_jf6Qi["TrackLinksByRecipient"]  &&
        !$_jf6Qi["TrackEMailOpenings"] && !$_jf6Qi["TrackEMailOpeningsByRecipient"]) )
        $_QLJfI = _L80DF($_QLJfI, "<if:tracking>", "</if:tracking>");

    print $_QLJfI;
    exit;
  }

  $_J0ItI = 0;
  if(isset($_POST["SUMEMailsSent"]))
    $_J0ItI = intval($_POST["SUMEMailsSent"]);
  $_J0ItJ = 0;  
  if(isset($_POST["LastEMailBreakCount"]))
    $_J0ItJ = intval($_POST["LastEMailBreakCount"]);

  if($_jf6Qi["LiveSendingBreakCount"] && $_jf6Qi["LiveSendingBreakTime"]){
    if($_J0ItI - $_J0ItJ >= $_jf6Qi["LiveSendingBreakCount"]){
      $_I016j = explode("<--IF:BREAK-->", $_QLJfI);
      print $_I016j[0];
      flush();
      $_QLJfI = $_I016j[1];
      $_I016j = explode("<--/IF:BREAK-->", $_QLJfI);
      $_QLJfI = $_I016j[0];
      $_IilfC = substr($_I016j[1], strpos($_I016j[1], '</form>'));
      

      $_J0ItJ = $_J0ItI;
      $_QLJfI = str_replace('name="SUMEMailsSent"', 'name="SUMEMailsSent" value="'.$_J0ItI.'"', $_QLJfI);
      $_QLJfI = str_replace('name="LastEMailBreakCount"', 'name="LastEMailBreakCount" value="'.$_J0ItJ.'"', $_QLJfI);
      $_QLJfI = str_replace('name="LiveSendingBreakTime"', 'name="LiveSendingBreakTime" value="'.$_jf6Qi["LiveSendingBreakTime"].'"', $_QLJfI);
      print $_QLJfI . $_IilfC;
      exit;
    }
  }  
  
  $_QLJfI = _L80DF($_QLJfI, "<--IF:BREAK-->", "<--/IF:BREAK-->");
    
  $_I016j = explode("<!--SPACER//-->", $_QLJfI);
  print $_I016j[0];
  flush();

  $_QLJfI = $_I016j[1];
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QLJfI = _L80DF($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");

  // get group ids if specified for unsubscribe link
  $_jt0QC = array();
  $_J0ILt = "SELECT `ml_groups_id` FROM `$_jf6Qi[GroupsTableName]` WHERE `Campaigns_id`=$CampaignListId";
  $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
  while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
    $_jt0QC[] = $_J0jCt[0];
  }
  mysql_free_result($_J0jIQ);
  if(count($_jt0QC) > 0) {
    // remove groups
    $_J0ILt = "SELECT `ml_groups_id` FROM `$_jf6Qi[NotInGroupsTableName]` WHERE `Campaigns_id`=$CampaignListId";
    $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
    while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
      $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
      if($_Iiloo !== false)
         unset($_jt0QC[$_Iiloo]);
    }
    mysql_free_result($_J0jIQ);
  }
  if(count($_jt0QC) > 0)
    $_jf6Qi["GroupIds"] = join(",", $_jt0QC);

  // target groups support
  // save time and check it once at start up so _LEJE8() must NOT do this
  if($_jf6Qi["MailFormat"] == "HTML" || $_jf6Qi["MailFormat"] == "Multipart"){
    $_jf6Qi["TargetGroupsWithEmbeddedFilesIncluded"] = _JJ8DO($_jf6Qi["MailHTMLText"]);

    $_jLtli = array();
    if(!$_jf6Qi["TargetGroupsWithEmbeddedFilesIncluded"]){
      _JJREP($_jf6Qi["MailHTMLText"], $_jLtli, 1);
    }
    $_jf6Qi["TargetGroupsInHTMLPartIncluded"] = $_jf6Qi["TargetGroupsWithEmbeddedFilesIncluded"] || count($_jLtli) > 0;
  }
  // target groups support /
  
  // mail class
  $_j10IJ = new _LEFO8(mtCampaignEMail);
  // no ECGList check
  $_j10IJ->DisableECGListCheck();

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_IojOt = $_jf6Qi["MaxEMailsToProcess"];
  if($_IojOt <= 0)
    $_IojOt = 1;
  if( $_jf6Qi["LiveSendingBreakCount"] && $_jf6Qi["LiveSendingBreakTime"] && $_IojOt > $_jf6Qi["LiveSendingBreakCount"])  
     $_IojOt = $_jf6Qi["LiveSendingBreakCount"];
  $_J0J6C = 0;
  $_jl886 = 0;
  $_J0JLi = ini_get("max_execution_time");

  if($_jlt10){
    if($_jf6Qi["SleepInMailSendingLoop"] < 10)
      $_jf6Qi["SleepInMailSendingLoop"] = 10; // ever break, otherwise server gives error on too many requests
    $_J06J0 = 0;
    _LRCOC();
    $_J06Ji = array();
    while($_J06J0 < $_IojOt && ($_j11Io = mysql_fetch_assoc($_QL8i1)) ) {
      $_J06Ji[] = array("email" => $_j11Io["u_EMail"]/*, "id" => $_QLO0f["id"]*/);
      $_J06J0++;
    }
    
    $_J0fIj = array();
    $_J08Q1 = "";
    $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1); 
    if(!$_J0t0L) // request failed, is ever in ECG-liste
      $_J0fIj = $_J06Ji;
    unset($_J06Ji); 
    mysql_data_seek($_QL8i1, 0);
  }
  
  if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
    while($_J0J6C < $_IojOt && ($_j11Io = mysql_fetch_assoc($_QL8i1)) ) {

      if(!isset($SubjectGenerator)) // set hole Subject
        $SubjectGenerator = new SubjectGenerator($_jf6Qi["MailSubject"]);
      $_jf6Qi["MailSubject"] = $SubjectGenerator->_LEEPA($_J0J6C + $_jlolf + $_jlCtf + $_jliJi);  

      // send time start
      list($_J0O1f, $_J0O6i) = explode(' ', microtime());
      $_J0OOt = (float) $_J0O6i + (float) $_J0O1f;

      _LRCOC();

      mysql_query("BEGIN", $_QLttI);

#$debug_current_mta = 0;
      if($_jllCj > 1){
        $_J00C0 = _LO8R8($_jf6Qi["CurrentUsedMTAsTableName"], $_jf6Qi["MTAsTableName"], $_jlOCl);
        // merge text with mail send settings
        if(isset($_J00C0["id"])) {
#$debug_current_mta = $_J00C0["id"];
          unset($_J00C0["id"]);
          unset($_J00C0["CreateDate"]);
          unset($_J00C0["IsDefault"]);
          unset($_J00C0["Name"]);
        }
        $_jf6Qi = array_merge($_jf6Qi, $_J00C0);
      }


      $_j11Io["RecipientsCount"] = $_jloJI;
      $_J0OiJ = false;

      if(isset($errors))
        unset($errors);
      $errors = array();
      if(isset($_I816i))
        unset($_I816i);
      $_I816i = array();
      $_j108i = "";
      $_j10O1 = "";
      $_J0o1o = false;

      //ECGList
      $_J0olI = false;
      if($_jlt10){
        $_J0olI = array_search($_j11Io["u_EMail"], array_column($_J0fIj, 'email')) !== false;
      }

      // Create Mail
      $_J0COJ = "";
      if($_J0olI)
        $_J0COJ = "Recipient is in ECG-Liste<br /><br />";
        else
          if(!_LEJE8($_j10IJ, $_j108i, $_j10O1, !$_jf6Qi["Caching"], $_jf6Qi, $_j11Io, $MailingListId, $_jlt8j, $_jlO00, $errors, $_I816i, $AdditionalHeaders, $CampaignListId, "Campaign") ) {
            $_J0COJ = "Email was not createable (".join($_QLl1Q, $_I816i).").<br /><br />";
          }

      _LRCOC();

      if($_J0COJ == "") {

        # Demo version
        if(defined("DEMO") || defined("SimulateMailSending") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()) )
          $_j10IJ->Sendvariant = "null";

        // send email
        $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);

        unset($_j108i);
        unset($_j10O1);
        _LRCOC();
        
        $_J0Ci0 = _LC6CP( ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false) );

        if($_I1o8o) {
          $_J0COJ = $resourcestrings[$INTERFACE_LANGUAGE]["000651"];
          $_J0OiJ = true;

          $_j10IJ->_LF0QR($_I8jLt, $_j11Io["id"], $_J0Ci0);

          // update last email sent datetime
          if($_j10IJ->Sendvariant == "smtpmx")
            $_QLfol = "UPDATE `$_I8I6o` SET `LastEMailSent`=NOW(), `BounceStatus`='', `SoftbounceCount`=0, `LastChangeDate`=`LastChangeDate` WHERE id=$_j11Io[id]";
            else
            $_QLfol = "UPDATE `$_I8I6o` SET `LastEMailSent`=NOW(), `LastChangeDate`=`LastChangeDate` WHERE `id`=$_j11Io[id]";
          mysql_query($_QLfol, $_QLttI);

          if(!empty($_j11Io['BounceStatus'])) {
              $_QLfol = "DELETE FROM `$_I8jjj` WHERE `Action`='Bounced' AND `Member_id`=$_j11Io[id]";
              mysql_query($_QLfol, $_QLttI);
          }

          // UpdateResponderStatistics
          $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET `SendStat_id`=$_jlOCl, `MailSubject`="._LRAFO( $_J0Ci0 ).", `SendDateTime`=NOW(), `recipients_id`=$_j11Io[id], `Send`='Sent', `SendResult`='OK'";
          mysql_query($_QLfol, $_QLttI);

          mysql_query("COMMIT", $_QLttI);
          mysql_query("BEGIN", $_QLttI);


        } else{
          $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], $_j10IJ->errors["errorcode"], $_j10IJ->errors["errortext"]);

          if( _LBOL6($_j10IJ->errors["errorcode"], $_j10IJ->errors["errortext"], $_J00C0["Type"]) ) {

           if($_j10IJ->errors["errorcode"] < 9000){ # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
             _LLF1C($_I8I6o, $_I8jjj, $_j11Io["id"], true, false, $_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"], !empty($_jf6Qi["ExternalBounceScript"]) ? $_jf6Qi["ExternalBounceScript"] : "");
             $_J0o1o = true;
           }

           // UpdateResponderStatistics
           $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET `SendStat_id`=$_jlOCl, `MailSubject`="._LRAFO( $_J0Ci0 ).", `SendDateTime`=NOW(), `recipients_id`=$_j11Io[id], `Send`='Failed', `SendResult`="._LRAFO("mail to $_j11Io[u_EMail] permanently undeliverable, Error: ".$_j10IJ->errors["errortext"]);
           mysql_query($_QLfol, $_QLttI);
          } else{

           if($_j10IJ->errors["errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _LLF1C($_I8I6o, $_I8jjj, $_j11Io["id"], false, true, $_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"], !empty($_jf6Qi["ExternalBounceScript"]) ? $_jf6Qi["ExternalBounceScript"] : "");

            // UpdateResponderStatistics
            $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET `SendStat_id`=$_jlOCl, `MailSubject`="._LRAFO($_J0Ci0).", `SendDateTime`=NOW(), `recipients_id`=$_j11Io[id], `Send`='Failed', `SendResult`="._LRAFO("mail to $_j11Io[u_EMail] temporarily undeliverable, Error: ".$_j10IJ->errors["errortext"]);
            mysql_query($_QLfol, $_QLttI);
          }

          mysql_query("COMMIT", $_QLttI);
          mysql_query("BEGIN", $_QLttI);

        }
      } else {
        $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], 9999, $_J0COJ);
        // UpdateResponderStatistics
        $_QLfol = "INSERT INTO `$_jf6Qi[RStatisticsTableName]` SET `SendStat_id`=$_jlOCl, `MailSubject`="._LRAFO(_LC6CP(ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false))).", `SendDateTime`=NOW(), `recipients_id`=$_j11Io[id], `Send`='Failed', `SendResult`="._LRAFO($_J0COJ);
        mysql_query($_QLfol, $_QLttI);
      }

      # update last member id, script timeout
      if($_J0OiJ)
         $_QLfol = "`SentCountSucc`=`SentCountSucc`+1";
         else
         $_QLfol = "`SentCountFailed`=`SentCountFailed`+1";
      if($_J0o1o)  // special for live sending cron_sendengine doesn't sets this
         $_QLfol .= ", `HardBouncesCount`=`HardBouncesCount`+1";

      $_QLfol = "UPDATE `$_jf6Qi[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `LastMember_id`=$_j11Io[id], $_QLfol WHERE id=$_jlOCl";
      mysql_query($_QLfol, $_QLttI);

      mysql_query("COMMIT", $_QLttI);

      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_j11Io["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $_j11Io["u_EMail"]);

      // send time end
      list($_J0O1f, $_J0O6i) = explode(' ', microtime());
      $_J0i1O = (float) $_J0O6i + (float) $_J0O1f;
      $_J0J6C++;
      $_J0ItI++;

      // calculate script timeout
      if($_jl886 < $_J0i1O - $_J0OOt)
        $_jl886 = $_J0i1O - $_J0OOt;
      if($_J0JLi > 0) {
        if($_jl886 * $_J0J6C > $_J0JLi - 1) {
          if( $_IojOt > intval(($_J0JLi - 1) / $_jl886) )
             $_IojOt = intval(($_J0JLi - 1) / $_jl886);
          if($_IojOt <= 0)
            $_IojOt = 1;
        }
      }

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $_J0COJ.round($_J0i1O - $_J0OOt, 5)."s");


      print $_QLl1Q.$_Ql0fO;
      flush();

      _LRCOC();

      if( isset($_jf6Qi["SleepInMailSendingLoop"]) && $_jf6Qi["SleepInMailSendingLoop"] > 0 )
        if(function_exists('usleep'))
           usleep($_jf6Qi["SleepInMailSendingLoop"] * 1000);  // mikrosekunden
           else
           sleep( (int) ($_jf6Qi["SleepInMailSendingLoop"] / 1000) );  // sekunden

    } # while($_j11Io = mysql_fetch_assoc($_QL8i1))
    if($_QL8i1)
      mysql_free_result($_QL8i1);

  } /* if(mysql_num_rows($_QL8i1) > 0) */
    else {
      # it's ever called in a new request
      _LRCOC();

      $_QLfol = "UPDATE `$_jf6Qi[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `SendState`='Done', `CampaignSendDone`=1, `ReportSent`=1 WHERE id=$_jlOCl";
      mysql_query($_QLfol, $_QLttI);
      $_QLfol = "UPDATE `$_QLi60` SET `LastSentDateTime`=NOW() WHERE `id`=$_jf6Qi[id]";
      mysql_query($_QLfol, $_QLttI);
      $_QLJfI = str_replace('name="SendDone"', 'name="SendDone" value="1"', $_QLJfI);
      $_jf6Qi["ReportSent"] = 0;

      # GET MTA
      if($_jllCj > 1){
        $_J00C0 = _LO8R8($_jf6Qi["CurrentUsedMTAsTableName"], $_jf6Qi["MTAsTableName"], $_jlOCl);
        // merge text with mail send settings
        if(isset($_J00C0["id"])) {
          unset($_J00C0["id"]);
          unset($_J00C0["CreateDate"]);
          unset($_J00C0["IsDefault"]);
          unset($_J00C0["Name"]);
        }
        $_jf6Qi = array_merge($_jf6Qi, $_J00C0);
      }

      _LLR0D($_jf6Qi, $_jlOCl);
  }

  $_QLJfI = str_replace('name="SUMEMailsSent"', 'name="SUMEMailsSent" value="'.$_J0ItI.'"', $_QLJfI);
  $_QLJfI = str_replace('name="LastEMailBreakCount"', 'name="LastEMailBreakCount" value="'.$_J0ItJ.'"', $_QLJfI);
  
  print $_QLJfI;
  flush();

?>
