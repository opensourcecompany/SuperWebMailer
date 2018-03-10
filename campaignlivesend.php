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

  define("CampaignLiveSending", 1);
  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("campaignstools.inc.php");
  include_once("cron_sendengine.inc.php");
  include_once("cron_campaigns.inc.php");
  include_once("mailer.php");
//  include_once("twitter.inc.php");

  if (count($_POST) == 0) {
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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeCampaignSending"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(empty($_Q6jOo)){
   $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
   $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", "Session lost, table for emailings empty.");
   print $_QJCJi;
   exit;
  }

  $_POST['CampaignListId'] = intval($CampaignListId);

  if(isset($_POST["SendDone"]) && $_POST["SendDone"] == "") # Send done?
    unset($_POST["SendDone"]);

  if(isset($_POST["MaxEMailSentTime"]) && $_POST["MaxEMailSentTime"] == "") # Send done?
    unset($_POST["MaxEMailSentTime"]);

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_I0600 = "";

  // MailTextInfos
  $_QJlJ0 = "SELECT `$_Q6jOo`.*, `$_Q6jOo`.Name AS CampaignsName, `$_Q60QL`.MaillistTableName, `$_Q60QL`.`forms_id` AS `MLForms_id`, `$_Q60QL`.`SubscriptionUnsubscription`, `$_Q60QL`.MailListToGroupsTableName, `$_Q60QL`.LocalBlocklistTableName, `$_Q60QL`.id AS MailingListId, `$_Q60QL`.FormsTableName, `$_Q60QL`.StatisticsTableName, `$_Q60QL`.MailLogTableName, `$_Q60QL`.users_id, `$_Q60QL`.`ExternalBounceScript`, ";
  $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_Q6jOo`.SenderFromName <> '', `$_Q6jOo`.SenderFromName, `$_Q60QL`.SenderFromName) AS SenderFromName,";
  $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_Q6jOo`.SenderFromAddress <> '', `$_Q6jOo`.SenderFromAddress, `$_Q60QL`.SenderFromAddress) AS SenderFromAddress,";
  $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_Q6jOo`.ReplyToEMailAddress, `$_Q60QL`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
  $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_Q6jOo`.ReturnPathEMailAddress, `$_Q60QL`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
  $_QJlJ0 .= " FROM `$_Q6jOo` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_Q6jOo`.maillists_id WHERE `$_Q6jOo`.id=$CampaignListId";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    $_I0600 = $commonmsgHTMLFormNotFound;
  } else {
    $_IiICC = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }

  $MailingListId = $_IiICC["MailingListId"];
  $_jQLfQ = $_IiICC["MLForms_id"];
  $_jQlQ6 = $_IiICC["forms_id"];
  $_QLI8o = $_IiICC["FormsTableName"];
  $_Q6t6j = $_IiICC["GroupsTableName"];
  $_QlQC8 = $_IiICC["MaillistTableName"];
  $_QLI68 = $_IiICC["MailListToGroupsTableName"];
  $_QlIf6 = $_IiICC["StatisticsTableName"];
  $_ItCCo = $_IiICC["LocalBlocklistTableName"];
  $_QljIQ = $_IiICC["MailLogTableName"];

  $_QJlJ0 = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_QLI8o` WHERE id=$_jQlQ6";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_QlftL = mysql_fetch_assoc($_Q60l1);
    $_IiICC["OverrideSubUnsubURL"] = $_QlftL["OverrideSubUnsubURL"];
    $_IiICC["OverrideTrackingURL"] = $_QlftL["OverrideTrackingURL"];
    mysql_free_result($_Q60l1);
  } else{
    $_IiICC["OverrideSubUnsubURL"] = "";
    $_IiICC["OverrideTrackingURL"] = "";
  }

  // CurrentSendTableName
  $_jQlit = 0;
  $_jQll6 = 0;
  $_jI0Oo = 0;
  $_jI1Ql = 0;
  $_jI1tt = 0;
  $_jIQ0i = 0;
  $_jIQfo=0;
  if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && intval($_POST["CurrentSendId"]) > 0) { # Send done?
    $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, ";
    $_QJlJ0 .= "DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, ";
    $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
    $_QJlJ0 .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_Q6QiO) AS SendEstEndTime ";
    $_QJlJ0 .= "FROM `$_IiICC[CurrentSendTableName]` WHERE id=".intval($_POST["CurrentSendId"]);
    }
   else {
     if(isset($_POST["SendDone"]) && $_POST["SendDone"] != "" && isset($_POST["CurrentSendId"]) && intval($_POST["CurrentSendId"]) == 0) // browser or mysql crash?
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

    mysql_query("BEGIN", $_Q61I1);

    // Current Send Table
    $_QJlJ0 = "INSERT INTO `$_IiICC[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW()";

    mysql_query($_QJlJ0, $_Q61I1);
    $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    $_jQll6 = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);

    /*
    // Twitter Update start
    $_jItfj = "";
    $_jI8C8 = "TWITTER_UPDATE_NOT_CONFIGURED";
    if($_IiICC["TwitterUpdate"]){
       $twitter = new _LJPOA($_IiICC["TwitterUsername"], $_IiICC["TwitterPassword"]);

       if($OwnerUserId == 0)
         $TrackingUserId = $UserId;
         else
         $TrackingUserId = $OwnerUserId;
       $ResponderTypeNum = _OAP0L("Campaign");
       $Identifier = sprintf("%02X", $_jQll6)."_".sprintf("%02X", $TrackingUserId)."_".sprintf("%02X", $ResponderTypeNum)."_".sprintf("%02X", $_IiICC["id"]);
       // Twitter
       $key = sprintf("twitter-%02X-%02X", $MailingListId, $_jQlQ6);

       $twitterURL = $AltBrowserLinkScript."?key=$key&rid=".$Identifier;

       $Error = "";
       $twitterURL = $twitter->TwitterGetShortURL($twitterURL, $Error);
       if($twitterURL !== false) {
           $text = $_IiICC["MailSubject"];
           $fields = GetAllCampaignPersonalizingFields($_QlQC8);
           reset($fields);
           foreach($fields as $key => $value){
             $text = str_ireplace("[$key]", "", $text);
           }
           if($twitter->TwitterSendStatusMessage("$text\r\n".$twitterURL, $Error)){
              $_jI8C8 = "TWITTER_UPDATE_DONE";
            }
             else {
               $_jI8C8 = "TWITTER_UPDATE_FAILED";
               $_jItfj = "TWITTER_UPDATE_POSTING_FAILED";
             }
         }
         else {
          $_jI8C8 = "TWITTER_UPDATE_FAILED";
          $_jItfj = "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE";
        }
        $_POST["TwitterUpdateState"] = $_jI8C8;
        $_POST["TwitterUpdateErrorText"] = $_jItfj;

        $_QJlJ0 = "UPDATE `$_IiICC[CurrentSendTableName]` SET ";

        if($_jI8C8 == "TWITTER_UPDATE_NOT_CONFIGURED")
           $_QJlJ0 .= "`TwitterUpdate`='NotActivated'";
           else
           if($_jI8C8 == "TWITTER_UPDATE_DONE")
              $_QJlJ0 .= "`TwitterUpdate`='Done'";
              else
              if($_jI8C8 == "TWITTER_UPDATE_FAILED")
                 $_QJlJ0 .= "`TwitterUpdate`='Failed'";
        $_QJlJ0 .= ", `TwitterUpdateErrorText`="._OPQLR($_jItfj);
        $_QJlJ0 .= " WHERE id=$_jQll6";
        mysql_query($_QJlJ0, $_Q61I1);
    }
    // Twitter Update end
    */

    // SET Current used MTAs to zero
    $_QJlJ0 = "INSERT INTO `$_IiICC[CurrentUsedMTAsTableName]` SELECT 0, $_jQll6, `mtas_id`, 0 FROM `$_IiICC[MTAsTableName]` ORDER BY sortorder";
    mysql_query($_QJlJ0, $_Q61I1);

    // Archive Table
    $_QJlJ0 = "INSERT INTO `$_IiICC[ArchiveTableName]` SET SendStat_id=$_jQll6, ";
    $_QJlJ0 .= "MailFormat="._OPQLR($_IiICC["MailFormat"]).", ";
    $_QJlJ0 .= "MailEncoding="._OPQLR($_IiICC["MailEncoding"]).", ";
    $_QJlJ0 .= "MailSubject="._OPQLR($_IiICC["MailSubject"]).", ";
    $_QJlJ0 .= "MailPlainText="._OPQLR($_IiICC["MailPlainText"]).", ";
    $_QJlJ0 .= "MailHTMLText="._OPQLR($_IiICC["MailHTMLText"]).", ";
    $_QJlJ0 .= "Attachments="._OPQLR($_IiICC["Attachments"]);
    mysql_query($_QJlJ0, $_Q61I1);

    $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_jIJC0 = $_Q6Q1C[0];
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
    $_QJlJ0 = "UPDATE $_Q6jOo SET ReSendFlag=0 WHERE id=$_IiICC[id]";
    mysql_query($_QJlJ0, $_Q61I1);

    mysql_query("COMMIT", $_Q61I1);

  }
  $_POST["CurrentSendId"] = $_jQll6;
  // CurrentSendId for Tracking and AltBrowserLink
  $_IiICC["CurrentSendId"] = $_jQll6;

  // check all mtas_id are in CurrentUsedMTAsTableName
  $_QJlJ0 = "SELECT `$_IiICC[MTAsTableName]`.mtas_id, `$_IiICC[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_IiICC[MTAsTableName]` LEFT JOIN `$_IiICC[CurrentUsedMTAsTableName]` ON `$_IiICC[CurrentUsedMTAsTableName]`.mtas_id = `$_IiICC[MTAsTableName]`.mtas_id WHERE `$_IiICC[CurrentUsedMTAsTableName]`.`SendStat_id`=$_jQll6 ORDER BY sortorder";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1)
    $_jI6Jo = mysql_num_rows($_Q60l1);
    else
    $_jI6Jo = 0;

  if(!$_Q60l1 || $_jI6Jo == 0) {
    $_I0600 = $commonmsgHTMLMTANotFound;
  }

  while($_IOolj = mysql_fetch_assoc($_Q60l1)) {
    $_Q6Q1C = $_IOolj; // save it
    if($_Q6Q1C["Usedmtas_id"] == "NULL") {
      $_QJlJ0 = "INSERT INTO `$_IiICC[CurrentUsedMTAsTableName]` SET `SendStat_id`=$_jQll6, `mtas_id`=$_Q6Q1C[mtas_id]";
      mysql_query($_QJlJ0, $_Q61I1);
    }
  }
  mysql_free_result($_Q60l1);

  # one MTA only than we can get data and merge it directly
  if($_jI6Jo == 1){
    $_IiICC["mtas_id"] = $_Q6Q1C["mtas_id"];

    // MTA
    $_QJlJ0 = "SELECT * FROM `$_Qofoi` WHERE `id`=$_IiICC[mtas_id]";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
      $_jIfO0 = mysql_fetch_assoc($_Q8Oj8);
      mysql_free_result($_Q8Oj8);
    }

    // merge text with mail send settings
    if(isset($_jIfO0["id"])) {
      unset($_jIfO0["id"]);
      unset($_jIfO0["CreateDate"]);
      unset($_jIfO0["IsDefault"]);
      unset($_jIfO0["Name"]);
    }
    $_IiICC = array_merge($_IiICC, $_jIfO0);
  }

  // Looping protection
  $AdditionalHeaders = array();
  if(isset($_IiICC["AddXLoop"]) && $_IiICC["AddXLoop"])
     $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
  if(isset($_IiICC["AddListUnsubscribe"]) && $_IiICC["AddListUnsubscribe"])
     $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';


  // RecipientsRow
  if($_jI0Oo == 0) {
    $_jI0Oo = _O6QAL($_IiICC, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
    mysql_query("UPDATE $_IiICC[CurrentSendTableName] SET RecipientsCount=$_jI0Oo WHERE id=$_jQll6", $_Q61I1);
  }

  $_QJlJ0 = _O61RO($_IiICC, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  $_QJlJ0 .= " AND `$_QlQC8`.id>$_jQlit ORDER BY `$_QlQC8`.id"." LIMIT 0, $_IiICC[MaxEMailsToProcess]";

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_I0600 != "") ) { # Send done or error?
    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000650"], $_IiICC["CampaignsName"]), $_I0600, 'DISABLED', 'campaign_live_send_done_snipped.htm');
  } else {
    // Template
    $_QJCJi = GetMainTemplate(false, $UserType, $Username, false, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000650"], $_IiICC["CampaignsName"]), $_I0600, 'DISABLED', 'campaign_live_send_snipped.htm');
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

  if(empty($_POST["TwitterUpdateState"])) {
    $_POST["TwitterUpdateState"] = "TWITTER_UPDATE_NOT_CONFIGURED";
  }
  if($_POST["TwitterUpdateState"] == "TWITTER_UPDATE_NOT_CONFIGURED")
     $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
  if($_POST["TwitterUpdateState"] == "TWITTER_UPDATE_DONE")
     $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
  if($_POST["TwitterUpdateState"] == "TWITTER_UPDATE_FAILED") {
     if( $_POST["TwitterUpdateErrorText"] == "TWITTER_UPDATE_POSTING_FAILED" )
        $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
        else
        if( $_POST["TwitterUpdateErrorText"] == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
            $_QJCJi = _OPR6L($_QJCJi, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);
  }

  if( (isset($_POST["SendDone"]) && $_POST["SendDone"] != "") || ($_I0600 != "") ) { # Send done or error?

    if( (!$_IiICC["TrackLinks"] && !$_IiICC["TrackLinksByRecipient"]  &&
        !$_IiICC["TrackEMailOpenings"] && !$_IiICC["TrackEMailOpeningsByRecipient"]) )
        $_QJCJi = _OP6PQ($_QJCJi, "<if:tracking>", "</if:tracking>");

    print $_QJCJi;
    exit;
  }

  $_QllO8 = explode("<!--SPACER//-->", $_QJCJi);
  print $_QllO8[0];
  flush();
  $_QJCJi = $_QllO8[1];
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QJCJi = _OP6PQ($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");

  // get group ids if specified for unsubscribe link
  $_IitLf = array();
  $_jIOjL = "SELECT * FROM `$_IiICC[GroupsTableName]`";
  $_jIOff = mysql_query($_jIOjL, $_Q61I1);
  while($_jIOio = mysql_fetch_row($_jIOff)) {
    $_IitLf[] = $_jIOio[0];
  }
  mysql_free_result($_jIOff);
  if(count($_IitLf) > 0) {
    // remove groups
    $_jIOjL = "SELECT * FROM `$_IiICC[NotInGroupsTableName]`";
    $_jIOff = mysql_query($_jIOjL, $_Q61I1);
    while($_jIOio = mysql_fetch_row($_jIOff)) {
      $_IJQOL = array_search($_jIOio[0], $_IitLf);
      if($_IJQOL !== false)
         unset($_IitLf[$_IJQOL]);
    }
    mysql_free_result($_jIOff);
  }
  if(count($_IitLf) > 0)
    $_IiICC["GroupIds"] = join(",", $_IitLf);

  // target groups support
  // save time and check it once at start up so _OED01() must NOT do this
  if($_IiICC["MailFormat"] == "HTML" || $_IiICC["MailFormat"] == "Multipart"){
    $_IiICC["TargetGroupsWithEmbeddedFilesIncluded"] = _LJOAD($_IiICC["MailHTMLText"]);

    $_j1L1C = array();
    if(!$_IiICC["TargetGroupsWithEmbeddedFilesIncluded"]){
      _LJO8O($_IiICC["MailHTMLText"], $_j1L1C, 1);
    }
    $_IiICC["TargetGroupsInHTMLPartIncluded"] = $_IiICC["TargetGroupsWithEmbeddedFilesIncluded"] || count($_j1L1C) > 0;
  }
  // target groups support /

  // mail class
  $_IiJit = new _OF0EE(mtCampaignEMail);

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_IQjC0 = $_IiICC["MaxEMailsToProcess"];
  if($_IQjC0 <= 0)
    $_IQjC0 = 1;
  $_jIojl = 0;
  $_jQiCt = 0;
  $_jICIf = ini_get("max_execution_time");

  if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
    while($_jIojl < $_IQjC0 && ($_jIiQ8 = mysql_fetch_assoc($_Q60l1)) ) {

      // send time start
      list($_jIiLJ, $_jILot) = explode(' ', microtime());
      $_jIlQO = (float) $_jILot + (float) $_jIiLJ;

      _OPQ6J();

      mysql_query("BEGIN", $_Q61I1);

#$debug_current_mta = 0;
      if($_jI6Jo > 1){
        $_jIfO0 = _O6LLO($_IiICC["CurrentUsedMTAsTableName"], $_IiICC["MTAsTableName"], $_jQll6);
        // merge text with mail send settings
        if(isset($_jIfO0["id"])) {
#$debug_current_mta = $_jIfO0["id"];
          unset($_jIfO0["id"]);
          unset($_jIfO0["CreateDate"]);
          unset($_jIfO0["IsDefault"]);
          unset($_jIfO0["Name"]);
        }
        $_IiICC = array_merge($_IiICC, $_jIfO0);
      }


      $_jIiQ8["RecipientsCount"] = $_jI0Oo;
      $_jIlC0 = false;

      if(isset($errors))
        unset($errors);
      $errors = array();
      if(isset($_Ql1O8))
        unset($_Ql1O8);
      $_Ql1O8 = array();
      $_Ii6QI = "";
      $_Ii6lO = "";
      $_jIlLQ = false;

      // Create Mail
      $_jj0JO = "";
      if(!_OED01($_IiJit, $_Ii6QI, $_Ii6lO, !$_IiICC["Caching"], $_IiICC, $_jIiQ8, $MailingListId, $_jQLfQ, $_jQlQ6, $errors, $_Ql1O8, $AdditionalHeaders, $CampaignListId, "Campaign") ) {
        $_jj0JO = "Email was not createable (".join($_Q6JJJ, $_Ql1O8)."), it was removed from out queue.<br /><br />";
      }

      _OPQ6J();

      if($_jj0JO == "") {

        # Demo version
        if(defined("DEMO") || defined("SimulateMailSending") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()) )
          $_IiJit->Sendvariant = "null";

        // send email
        $_Q8COf = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);

        unset($_Ii6QI);
        unset($_Ii6lO);
        _OPQ6J();

        if($_Q8COf) {
          $_jj0JO = $resourcestrings[$INTERFACE_LANGUAGE]["000651"];
          $_jIlC0 = true;

          $_IiJit->_OF0FL($_QljIQ, $_jIiQ8["id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false));

          // update last email sent datetime
          if($_IiJit->Sendvariant == "smtpmx")
            $_QJlJ0 = "UPDATE `$_QlQC8` SET `LastEMailSent`=NOW(), `BounceStatus`='', `SoftbounceCount`=0, `HardbounceCount`=0, `LastChangeDate`=`LastChangeDate` WHERE id=$_jIiQ8[id]";
            else
            $_QJlJ0 = "UPDATE `$_QlQC8` SET `LastEMailSent`=NOW(), `LastChangeDate`=`LastChangeDate` WHERE `id`=$_jIiQ8[id]";
          mysql_query($_QJlJ0, $_Q61I1);

          if(!empty($_jIiQ8['BounceStatus'])) {
              $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE `Action`='Bounced' AND `Member_id`=$_jIiQ8[id]";
              mysql_query($_QJlJ0, $_Q61I1);
          }

          // UpdateResponderStatistics
          $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET `SendStat_id`=$_jQll6, `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=$_jIiQ8[id], `Send`='Sent', `SendResult`='OK'";
          mysql_query($_QJlJ0, $_Q61I1);

          mysql_query("COMMIT", $_Q61I1);
          mysql_query("BEGIN", $_Q61I1);


        } else{
          $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], $_IiJit->errors["errorcode"], $_IiJit->errors["errortext"]);

          if( _ORPER($_IiJit->errors["errorcode"], $_IiJit->errors["errortext"], $_jIfO0["Type"]) ) {

           if($_IiJit->errors["errorcode"] < 9000){ # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
             _ORALQ($_QlQC8, $_QlIf6, $_jIiQ8["id"], true, false, $_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"], !empty($_IiICC["ExternalBounceScript"]) ? $_IiICC["ExternalBounceScript"] : "");
             $_jIlLQ = true;
           }

           // UpdateResponderStatistics
           $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET `SendStat_id`=$_jQll6, `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=$_jIiQ8[id], `Send`='Failed', `SendResult`="._OPQLR("mail to $_jIiQ8[u_EMail] permanently undeliverable, Error: ".$_IiJit->errors["errortext"]);
           mysql_query($_QJlJ0, $_Q61I1);
          } else{

           if($_IiJit->errors["errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _ORALQ($_QlQC8, $_QlIf6, $_jIiQ8["id"], false, true, $_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"], !empty($_IiICC["ExternalBounceScript"]) ? $_IiICC["ExternalBounceScript"] : "");

            // UpdateResponderStatistics
            $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET `SendStat_id`=$_jQll6, `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=$_jIiQ8[id], `Send`='Failed', `SendResult`="._OPQLR("mail to $_jIiQ8[u_EMail] temporarily undeliverable, Error: ".$_IiJit->errors["errortext"]);
            mysql_query($_QJlJ0, $_Q61I1);
          }

          mysql_query("COMMIT", $_Q61I1);
          mysql_query("BEGIN", $_Q61I1);

        }
      } else {
        $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000652"], 9999, $_jj0JO);
        // UpdateResponderStatistics
        $_QJlJ0 = "INSERT INTO `$_IiICC[RStatisticsTableName]` SET `SendStat_id`=$_jQll6, `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=$_jIiQ8[id], `Send`='Failed', `SendResult`="._OPQLR($_jj0JO);
        mysql_query($_QJlJ0, $_Q61I1);
      }

      # update last member id, script timeout
      if($_jIlC0)
         $_QJlJ0 = "`SentCountSucc`=`SentCountSucc`+1";
         else
         $_QJlJ0 = "`SentCountFailed`=`SentCountFailed`+1";
      if($_jIlLQ)
         $_QJlJ0 .= ", `HardBouncesCount`=`HardBouncesCount`+1";

      $_QJlJ0 = "UPDATE `$_IiICC[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `LastMember_id`=$_jIiQ8[id], $_QJlJ0 WHERE id=$_jQll6";
      mysql_query($_QJlJ0, $_Q61I1);

      mysql_query("COMMIT", $_Q61I1);

      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_jIiQ8["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", $_jIiQ8["u_EMail"]);

      // send time end
      list($_jIiLJ, $_jILot) = explode(' ', microtime());
      $_jj1IC = (float) $_jILot + (float) $_jIiLJ;
      $_jIojl++;

      // calculate script timeout
      if($_jQiCt < $_jj1IC - $_jIlQO)
        $_jQiCt = $_jj1IC - $_jIlQO;
      if($_jICIf > 0) {
        if($_jQiCt * $_jIojl > $_jICIf - 1) {
          if( $_IQjC0 > intval(($_jICIf - 1) / $_jQiCt) )
             $_IQjC0 = intval(($_jICIf - 1) / $_jQiCt);
          if($_IQjC0 <= 0)
            $_IQjC0 = 1;
        }
      }

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $_jj0JO.round($_jj1IC - $_jIlQO, 5)."s");


      print $_Q6JJJ.$_Q66jQ;
      flush();

      _OPQ6J();

      if( isset($_IiICC["SleepInMailSendingLoop"]) && $_IiICC["SleepInMailSendingLoop"] > 0 )
        if(function_exists('usleep'))
           usleep($_IiICC["SleepInMailSendingLoop"] * 1000);  // mikrosekunden
           else
           sleep( (int) ($_IiICC["SleepInMailSendingLoop"] / 1000) );  // sekunden

    } # while($_jIiQ8 = mysql_fetch_assoc($_Q60l1))
    if($_Q60l1)
      mysql_free_result($_Q60l1);

  } /* if(mysql_num_rows($_Q60l1) > 0) */
    else {
      # it's ever called in a new request
      _OPQ6J();

      $_QJlJ0 = "UPDATE `$_IiICC[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `SendState`='Done', `CampaignSendDone`=1, `ReportSent`=1 WHERE id=$_jQll6";
      mysql_query($_QJlJ0, $_Q61I1);
      $_QJCJi = str_replace('name="SendDone"', 'name="SendDone" value="1"', $_QJCJi);
      $_IiICC["ReportSent"] = 0;

      # GET MTA
      if($_jI6Jo > 1){
        $_jIfO0 = _O6LLO($_IiICC["CurrentUsedMTAsTableName"], $_IiICC["MTAsTableName"], $_jQll6);
        // merge text with mail send settings
        if(isset($_jIfO0["id"])) {
          unset($_jIfO0["id"]);
          unset($_jIfO0["CreateDate"]);
          unset($_jIfO0["IsDefault"]);
          unset($_jIfO0["Name"]);
        }
        $_IiICC = array_merge($_IiICC, $_jIfO0);
      }

      _ORO1F($_IiICC, $_jQll6);
  }

  print $_QJCJi;

?>
