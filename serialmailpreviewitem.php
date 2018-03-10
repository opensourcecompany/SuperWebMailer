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
  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("replacements.inc.php");
  include_once("targetgroups.inc.php");

  $_jfoLl = 0;
  if ( isset($_GET["MailingListId"]) )
     $MailingListId = intval($_GET["MailingListId"]);
     else
     if ( isset($_POST["MailingListId"]) )
       $MailingListId = intval($_POST["MailingListId"]);

  if ( isset($_GET["FormId"]) )
     $_jfoCi = intval($_GET["FormId"]);
     else
     if ( isset($_POST["FormId"]) )
       $_jfoCi = intval($_POST["FormId"]);

  if ( isset($_GET["MailTemplate"]) )
     $MailTemplate = $_GET["MailTemplate"];
     else
     if ( isset($_POST["MailTemplate"]) )
       $MailTemplate = $_POST["MailTemplate"];

  if ( isset($_GET["CurrentMail"]) )
     $CurrentMail = intval($_GET["CurrentMail"]);
     else
     if ( isset($_POST["CurrentMail"]) )
       $CurrentMail = intval($_POST["CurrentMail"]);

  if ( isset($_GET["format"]) )
     $format = $_GET["format"];
     else
     if ( isset($_POST["format"]) )
       $format = $_POST["format"];
  if(isset($format) && ( strpos($format, " ") !== false || strpos($format, '"') !== false || strpos($format, "'") !== false) )
     $format = "text";

  if ( isset($_GET["attachments"]) )
     $attachments = $_GET["attachments"];
     else
     if ( isset($_POST["attachments"]) )
       $attachments = $_POST["attachments"];

  if ( isset($_GET["ResponderType"]) && $_GET["ResponderType"] != "" )
     $ResponderType = $_GET["ResponderType"];
     else
     if ( isset($_POST["ResponderType"]) && $_POST["ResponderType"] != "" )
       $ResponderType = $_POST["ResponderType"];

  if ( isset($_GET["ResponderId"]) && $_GET["ResponderId"] != "" )
     $ResponderId = intval($_GET["ResponderId"]);
     else
     if ( isset($_POST["ResponderId"]) && $_POST["ResponderId"] != "" )
       $ResponderId = intval($_POST["ResponderId"]);

  if ( isset($_GET["ResponderMailItemId"]) && $_GET["ResponderMailItemId"] != ""  )
     $ResponderMailItemId = intval($_GET["ResponderMailItemId"]);
     else
     if ( isset($_POST["ResponderMailItemId"]) && $_POST["ResponderMailItemId"] != "" )
       $ResponderMailItemId = intval($_POST["ResponderMailItemId"]);

  if( !isset($MailingListId) || !isset($_jfoCi) || !isset($MailTemplate) || !isset($CurrentMail) || !isset($format)  )
    exit;


  $MailTemplate = _OPQLR($MailTemplate);
  $MailTemplate = str_replace("'", "", $MailTemplate);
  $MailTemplate = str_replace('"', "", $MailTemplate);
  $format = _OPQLR($format);
  $format = str_replace("'", "", $format);
  $format = str_replace('"', "", $format);

  // ** unset blank vars
  if(empty($ResponderType))
    unset($ResponderType);
  if(empty($ResponderId))
    unset($ResponderId);
  if(empty($ResponderMailItemId) )
    unset($ResponderMailItemId);

  if ( isset($_GET["ResponderType"]) && $_GET["ResponderType"] == "" )
     unset( $_GET["ResponderType"] );
     else
     if ( isset($_POST["ResponderType"]) && $_POST["ResponderType"] == "" )
       unset( $_POST["ResponderType"] );

  if ( isset($_GET["ResponderId"]) && $_GET["ResponderId"] == "" )
     unset( $_GET["ResponderId"] );
     else
     if ( isset($_POST["ResponderId"]) && $_POST["ResponderId"] == "" )
       unset( $_POST["ResponderId"] );

  if ( isset($_GET["ResponderMailItemId"]) && $_GET["ResponderMailItemId"] == ""  )
     unset( $_GET["ResponderMailItemId"] );
     else
     if ( isset($_POST["ResponderMailItemId"]) && $_POST["ResponderMailItemId"] == "" )
       unset( $_POST["ResponderMailItemId"] );

  // ** unset blank vars /

  if(isset($ResponderType) ) {
      $_POST["ResponderType"] = $ResponderType;
      $MailTemplate = "";
    }

  if(isset($ResponderId) && $ResponderId != "")
      $_POST["ResponderId"] = $ResponderId;
  if(isset($ResponderMailItemId))
    $_POST["ResponderMailItemId"] = $ResponderMailItemId;

  $_I0600 = "";
  //
  $_QJlJ0 = "SELECT users_id, MaillistTableName, MailListToGroupsTableName, FormsTableName, GroupsTableName, LocalBlocklistTableName, forms_id, `Name` AS `MailingListName` FROM $_Q60QL WHERE id=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    $_I0600 = $commonmsgMailListNotFound;
  } else {
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }
  $_I8JQO = $_Q6Q1C["MailingListName"];
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
  $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
  if(isset($ResponderType)) {
    $_jfoLl = $_jfoCi;
    $_jfoCi = $_Q6Q1C["forms_id"];
  }

  // Responder
  if(isset($_POST["ResponderId"])) {
    if($ResponderType == "FollowUpResponder") {
      $_QJlJ0 = "SELECT FUMailsTableName, GroupsTableName AS FUResponders_GroupsTableName, NotInGroupsTableName AS FUResponders_NotInGroupsTableName, forms_id FROM $_QCLCI WHERE id=$ResponderId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
        mysql_free_result($_Q60l1);
        $_ItJIf = $_Q6Q1C["FUMailsTableName"];
        $_ItiO1 = $_Q6Q1C["FUResponders_GroupsTableName"];
        $_ItL8J = $_Q6Q1C["FUResponders_NotInGroupsTableName"];
        $_jfoLl = $_Q6Q1C["forms_id"];
      } else {
        $_I0600 = $commonmsgHTMLFormNotFound;
        return;
      }
    }
  }
  // Responder

  if(!isset($ResponderType)) {
    //
    $_QJlJ0 = "SELECT $_QLI8o.* FROM $_QLI8o WHERE $_QLI8o.id=$_jfoCi";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_jfoLl = $_jfoCi;
    }
  }
   else
   if($ResponderType == "AutoResponder" || $ResponderType == "BirthdayResponder" || $ResponderType == "EventResponder" || $ResponderType == "RSS2EMailResponder"){
    $_jj1tl = _OAP0L($ResponderType);
    $_Jfi0i = _OABJE($_jj1tl);
    if(empty($_Jfi0i) && $ResponderType == "AutoResponder"){
      $_Jfi0i = $_IQL81;
    }
    $_QJlJ0 = "SELECT * FROM `$_Jfi0i` WHERE `id`=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_j6ioL["id"] = $_jfoCi; // correct formId
      $_jfoLl = $_j6ioL["forms_id"];
      if($_jfoLl == 0) // Autoresponder
        $_jfoLl = $_jfoCi;
    }
  } else
   if($ResponderType == "FollowUpResponder"){
    // FUResponder
    $_QJlJ0 = "SELECT * FROM $_ItJIf WHERE id=$ResponderMailItemId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_j6ioL["id"] = $_jfoCi; // correct formId

      // get group ids if specified for unsubscribe link
      $_IitLf = array();
      $_jIOjL = "SELECT * FROM $_ItiO1";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IitLf[] = $_jIOio[0];
      }
      mysql_free_result($_jIOff);
      if(count($_IitLf) > 0) {
        // remove groups
        $_jIOjL = "SELECT * FROM $_ItL8J";
        $_jIOff = mysql_query($_jIOjL, $_Q61I1);
        while($_jIOio = mysql_fetch_row($_jIOff)) {
          $_IJQOL = array_search($_jIOio[0], $_IitLf);
          if($_IJQOL !== false)
             unset($_IitLf[$_IJQOL]);
        }
        mysql_free_result($_jIOff);
      }
      if(count($_IitLf) > 0)
        $_j6ioL["GroupIds"] = join(",", $_IitLf);


    }
  } else
   if($ResponderType == "Campaign"){
    $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_j6ioL["id"] = $_jfoCi; // correct formId
      $_jfoLl = $_j6ioL["forms_id"];

      // get group ids if specified for unsubscribe link
      $_IitLf = array();
      $_jIOjL = "SELECT * FROM `$_j6ioL[GroupsTableName]`";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IitLf[] = $_jIOio[0];
      }
      mysql_free_result($_jIOff);
      if(count($_IitLf) > 0) {
        // remove groups
        $_jIOjL = "SELECT * FROM `$_j6ioL[NotInGroupsTableName]`";
        $_jIOff = mysql_query($_jIOjL, $_Q61I1);
        while($_jIOio = mysql_fetch_row($_jIOff)) {
          $_IJQOL = array_search($_jIOio[0], $_IitLf);
          if($_IJQOL !== false)
             unset($_IitLf[$_IJQOL]);
        }
        mysql_free_result($_jIOff);
      }
      if(count($_IitLf) > 0)
        $_j6ioL["GroupIds"] = join(",", $_IitLf);
    }
  } else
   if($ResponderType == "SMSCampaign"){
    $_QJlJ0 = "SELECT * FROM $_IoCtL WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_j6ioL["id"] = $_jfoCi; // correct formId
      $_jfoLl = $_j6ioL["forms_id"];

      // get group ids if specified for unsubscribe link
      $_IitLf = array();
      $_jIOjL = "SELECT * FROM `$_j6ioL[GroupsTableName]`";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IitLf[] = $_jIOio[0];
      }
      mysql_free_result($_jIOff);
      if(count($_IitLf) > 0) {
        // remove groups
        $_jIOjL = "SELECT * FROM `$_j6ioL[NotInGroupsTableName]`";
        $_jIOff = mysql_query($_jIOjL, $_Q61I1);
        while($_jIOio = mysql_fetch_row($_jIOff)) {
          $_IJQOL = array_search($_jIOio[0], $_IitLf);
          if($_IJQOL !== false)
             unset($_IitLf[$_IJQOL]);
        }
        mysql_free_result($_jIOff);
      }
      if(count($_IitLf) > 0)
        $_j6ioL["GroupIds"] = join(",", $_IitLf);
    }
  }
  else
   if($ResponderType == "DistributionList"){
    $_QJlJ0 = "SELECT *, `$_QoOft`.`Name` AS `DistribListsName`, `$_QoOft`.`Description` AS `DistribListsDescription` FROM `$_QoOft` WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_j6ioL["id"] = $_jfoCi; // correct formId
      $_jfoLl = $_j6ioL["forms_id"];

      // DistributionListEntries
      $_QJlJ0 = "SELECT * FROM `$_Qoo8o` WHERE id=$ResponderMailItemId";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q8Oj8 || mysql_num_rows($_Q8Oj8) == 0) {
        $_I0600 = $commonmsgHTMLFormNotFound;
        return;
      } else {
        $_Iij08 = mysql_fetch_assoc($_Q8Oj8);

        if(substr($_Iij08["MailPlainText"], 0, 4) == "xb64"){
          $_Iij08["MailPlainText"] = base64_decode( substr($_Iij08["MailPlainText"], 4) );
        }

        if(substr($_Iij08["MailHTMLText"], 0, 4) == "xb64"){
          $_Iij08["MailHTMLText"] = base64_decode( substr($_Iij08["MailHTMLText"], 4) );
        }

        $_j6ioL["DistributionListEntryId"] = $ResponderMailItemId;

        $_j6ioL["UseInternalText"] = $_Iij08["UseInternalText"];
        $_j6ioL["ExternalTextURL"] = $_Iij08["ExternalTextURL"];

        $_j6ioL["MailFormat"] = $_Iij08["MailFormat"];
        $_j6ioL["MailPriority"] = $_Iij08["MailPriority"];
        $_j6ioL["MailEncoding"] = $_Iij08["MailEncoding"];
        $_j6ioL["MailSubject"] = $_Iij08["MailSubject"];
        $_j6ioL["OrgMailSubject"] = $_Iij08["MailSubject"];
        $_j6ioL["MailPlainText"] = $_Iij08["MailPlainText"];
        $_j6ioL["MailHTMLText"] = $_Iij08["MailHTMLText"];
        $_j6ioL["Attachments"] = $_Iij08["Attachments"];
        $_j6ioL["DistribSenderEMailAddress"] = $_Iij08["DistribSenderEMailAddress"];
        $_j6ioL["MailingListName"] = $_I8JQO;

        mysql_free_result($_Q8Oj8);
      }

      // get group ids if specified for unsubscribe link
      $_IitLf = array();
      $_jIOjL = "SELECT * FROM $_j6ioL[GroupsTableName]";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IitLf[] = $_jIOio[0];
      }
      mysql_free_result($_jIOff);
      if(count($_IitLf) > 0) {
        // remove groups
        $_jIOjL = "SELECT * FROM $_j6ioL[NotInGroupsTableName]";
        $_jIOff = mysql_query($_jIOjL, $_Q61I1);
        while($_jIOio = mysql_fetch_row($_jIOff)) {
          $_IJQOL = array_search($_jIOio[0], $_IitLf);
          if($_IJQOL !== false)
             unset($_IitLf[$_IJQOL]);
        }
        mysql_free_result($_jIOff);
      }
      if(count($_IitLf) > 0)
        $_j6ioL["GroupIds"] = join(",", $_IitLf);
    }
  }

  // MembersRecord
  $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
  $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
  $_IOQf6 = " `$_QlQC8`.IsActive=1 AND `$_QlQC8`.SubscriptionStatus<>'OptInConfirmationPending'".$_Q6JJJ;
  $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

  if( isset($ResponderType) && $ResponderType == "BirthdayResponder" ) {

    $_Iijl0 =
        'TO_DAYS(

            DATE_ADD(
           `u_Birthday`,
                INTERVAL
                  (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                    IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
           )
                  YEAR)
           )
           -
           TO_DAYS( CURRENT_DATE() )
           AS `Days_to_Birthday`';

    $_QJlJ0 = "SELECT `$_QlQC8`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge, $_Iijl0 FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, 1";
  }
  else
    if( isset($ResponderType) && $ResponderType == "Campaign" ) {

     include_once("campaignstools.inc.php");

     $_QJlJ0 = _O610A($ResponderId);
     if(strpos($_QJlJ0, "`ml_groups_id`") == 0)
       $_QJlJ0 .= " ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, 1";
       else
       $_QJlJ0 .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
    } else
     if( isset($ResponderType) && $ResponderType == "SMSCampaign" ) {

      include_once("smscampaignstools.inc.php");

      $_QJlJ0 = _LOP60($ResponderId);
      if(strpos($_QJlJ0, "`ml_groups_id`") == 0)
        $_QJlJ0 .= " ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, 1";
        else
        $_QJlJ0 .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
     } else
     if( isset($ResponderType) && $ResponderType == "DistributionList" ) {

       include_once("distribliststools.inc.php");

       $_QJlJ0 = _O8LCL($ResponderId, $_j6ioL["DistribSenderEMailAddress"]);
       if(strpos($_QJlJ0, "`ml_groups_id`") == 0)
         $_QJlJ0 .= " ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, 1";
         else
         $_QJlJ0 .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
     }
     else
       $_QJlJ0 = "SELECT `$_QlQC8`.* FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, 1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if(mysql_num_rows($_Q60l1) > 0) {
    $_IiJo8 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  } else {
    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, isset($_j6ioL[$MailTemplate."MailSubject"]) ? $_j6ioL[$MailTemplate."MailSubject"] : "" /*SMS not subject*/, $_I0600, 'DISABLED', 'blank.htm', "");
    $_QJCJi = str_replace("<body>", "<body>".$resourcestrings[$INTERFACE_LANGUAGE]["NoRecipients"], $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_QJlJ0 = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_QLI8o` WHERE id=$_jfoLl";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_QlftL = mysql_fetch_assoc($_Q60l1);
    $_Iijft = $_QlftL["OverrideSubUnsubURL"];
    $_IijO6 = $_QlftL["OverrideTrackingURL"];
    mysql_free_result($_Q60l1);
  } else{
    $_Iijft = "";
    $_IijO6 = "";
  }

  if( isset($ResponderType) && $ResponderType == "DistributionList" ) {
    if(isset($_j6ioL["DistribSenderEMailAddress"]))
      $_IiJo8["DistribSenderEMailAddress"] = $_j6ioL["DistribSenderEMailAddress"];
    if(isset($_j6ioL["DistribListsName"]))
      $_IiJo8["DistribListsName"] = $_j6ioL["DistribListsName"];
    if(isset($_j6ioL["OrgMailSubject"]))
      $_IiJo8["OrgMailSubject"] = $_j6ioL["OrgMailSubject"];
    if(isset($_j6ioL["DistribListsDescription"]))
      $_IiJo8["DistribListsDescription"] = $_j6ioL["DistribListsDescription"];
    $_IiJo8["MailingListName"] = $_I8JQO;
  }

  // Special placeholders
  $_JiiQJ = array ();
  if($MailTemplate == "OptInConfirmation") {
    // ever create new key!!
    $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);

    reset($_jJioL);
    foreach ($_jJioL as $key => $_Q6ClO) {
       $_I1L81 = "";
       if ($_Q6ClO == '[SubscribeRejectLink]') {
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088)."?Action=subscribereject&key=".$_IiJo8["IdentString"];
          }
          else
          if ($_Q6ClO == '[SubscribeConfirmationLink]') {
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088)."?Action=subscribeconfirm&key=".$_IiJo8["IdentString"];
          }
       $_JiiQJ[$_Q6ClO] = $_I1L81;
    }
  } elseif($MailTemplate == "OptOutConfirmation") {
    reset($_jJLJ6);
    // ever create new key!!
    $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);

    foreach ($_jJLJ6 as $key => $_Q6ClO) {
       $_I1L81 = "";
       if ($_Q6ClO == '[UnsubscribeRejectLink]')
          $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088)."?Action=unsubscribereject&key=".$_IiJo8["IdentString"];
          else
          if ($_Q6ClO == '[UnsubscribeConfirmationLink]')
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088)."?Action=unsubscribeconfirm&key=".$_IiJo8["IdentString"];
       $_JiiQJ[$_Q6ClO] = $_I1L81;
    }
  }
    if($MailTemplate == "EditConfirmation") {
      // ever create new key!!
      $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);

      reset($_jJLoj);
      foreach ($_jJLoj as $key => $_Q6ClO) {
         $_I1L81 = "";
         if ($_Q6ClO == '[EditRejectLink]') {
              $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088)."?Action=editreject&key=".$_IiJo8["IdentString"];
            }
            else
            if ($_Q6ClO == '[EditConfirmationLink]') {
              $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088)."?Action=editconfirm&key=".$_IiJo8["IdentString"];
            }
         $_JiiQJ[$_Q6ClO] = $_I1L81;
      }
    }
    else {

    if($OwnerUserId == 0)
      $_Ii016 = $UserId;
      else
      $_Ii016 = $OwnerUserId;
    if(isset($ResponderType) && $_jfoLl != $_jfoCi)
      $_Jill8 = sprintf("%02X", 0)."_".sprintf("%02X", $_Ii016)."_".sprintf("%02X", _OAP0L($ResponderType))."_".sprintf("%02X", $ResponderId)."_"."x".sprintf("%02X", $_jfoLl);
      else
      $_Jill8 = "";

    reset($_III0L);
    foreach ($_III0L as $key => $_Q6ClO) {
       $_I1L81 = "";
       if ($_Q6ClO == '[UnsubscribeLink]') {
          $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);
          $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?key=$_IiJo8[IdentString]";

          if(!empty($_Jill8))
            $_I1L81 .= "&rid=".$_Jill8;

          if(isset($_j6ioL["GroupIds"]))
            $_I1L81 .= "&RG=".$_j6ioL["GroupIds"];
       }
       if ($_Q6ClO == '[EditLink]') {
          $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);
          $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?key=".$_IiJo8["IdentString"]."&ML=$MailingListId&F=$_jfoCi&HTMLForm=editform";
          if(!empty($_Jill8))
            $_I1L81 .= "&rid=".$_Jill8;
       }
       $_JiiQJ[$_Q6ClO] = $_I1L81;
    }

    $_6I0ij = array();
    if(isset($ResponderType) && $ResponderType == "SMSCampaign")
      $_6I0ij = array_merge($_jQt18, $_j601Q, $_Ij18l);
    else
      $_6I0ij = array_merge($_jQt18, $_j601Q, $_Ij18l, $_QOifL);

    reset($_6I0ij);
    foreach ($_6I0ij as $key => $_Q6ClO) {
       $_I1L81 = "";
       if ($_Q6ClO == '[AltBrowserLink]') {
          $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);
          $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jJ1Li : $_jJQ66)."?key=$_IiJo8[IdentString]&rid=smp";
       }


       // Social media links, AltBrowserLink_SME only
       if ($_Q6ClO == '[AltBrowserLink_SME]') {
          $_IiJo8["IdentString"] = _OA81R($_IiJo8["IdentString"], $_IiJo8["id"], $MailingListId, $_jfoCi, $_QlQC8);
          $_JLQI1 = $_IiJo8["IdentString"];
          $_JLQI1 = explode("-", $_JLQI1);
          $_JLQI1[0] = "sme";
          $_JLQI1 = join("-", $_JLQI1);
          $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jJ1Li : $_jJQ66)."?key=".$_JLQI1;
          $_I1L81 .= "&rid=smp";
       }
       // Social media links /


       $_JiiQJ[$_Q6ClO] = $_I1L81;
    }
  }

  if(isset($ResponderType) && $ResponderType == "AutoResponder") {
   reset($_III86);
   foreach ($_III86 as $key => $_Q6ClO)
     $_JiiQJ[$_Q6ClO] = $key; // we have nothing
  }
  if(isset($ResponderType) && $ResponderType == "BirthdayResponder") {
   reset($_IjQQ8);
   foreach ($_IjQQ8 as $key => $_Q6ClO)
     $_JiiQJ[$_Q6ClO] = $_IiJo8[$key];
  }

  if(isset($ResponderType) && $ResponderType == "SMSCampaign") {   # special SMS
    $_IQC1o = "PlainText";
    $_j1CLL = 'Normal';
    $_j6ioL["MailEncoding"] = 'utf-8';
    $_IQCoo = $_j6ioL["MailEncoding"];
    $_IQojt = "SMS";
    $_IQitJ = $_j6ioL["SMSText"];
  } else {
    $_IQC1o = $_j6ioL[$MailTemplate."MailFormat"];
    $_j1CLL = $_j6ioL[$MailTemplate."MailPriority"];
    $_IQCoo = $_j6ioL[$MailTemplate."MailEncoding"];
    $_IQojt = $_j6ioL[$MailTemplate."MailSubject"];
    $_IQitJ = $_j6ioL[$MailTemplate."MailPlainText"];
    if(!empty($_j6ioL[$MailTemplate."Attachments"])) {
      $Attachments = @unserialize($_j6ioL[$MailTemplate."Attachments"]);
      if($Attachments === false)
        $Attachments = array();
    } else
      $Attachments = array();
    if(!empty($_j6ioL[$MailTemplate."PersAttachments"])) {
      $_j1i80 = @unserialize($_j6ioL[$MailTemplate."PersAttachments"]);
      if($_j1i80 === false)
        $_j1i80 = array();
    } else
      $_j1i80 = array();
    if(!defined("SWM"))
      $_j1i80 = array();
  }

  if(empty($_j6ioL["ModifiedEMailSubject"])) { // no distriblist
      $_IQojt = _L1ERL($_IiJo8, $MailingListId, $_IQojt, $_IQCoo, true, $_JiiQJ);
    }
    else{
     if(!$_j6ioL["DontModifyEMailSubjectOnReFw"])
       $_IQojt = _L1ERL($_IiJo8, $MailingListId, $_j6ioL["ModifiedEMailSubject"], $_IQCoo, true, $_JiiQJ);
       else{
          if( strpos($_IQojt, "Re: ") === 0 || strpos($_IQojt, "Aw: ") === 0 || strpos($_IQojt, "Fw: ") === 0 || strpos($_IQojt, "Wg: ") === 0 ) {
              // do nothing
              $_IQojt = _L1ERL($_IiJo8, $MailingListId, $_IQojt, $_IQCoo, true, $_JiiQJ);
            }
            else
            $_IQojt = _L1ERL($_IiJo8, $MailingListId, $_j6ioL["ModifiedEMailSubject"], $_IQCoo, true, $_JiiQJ);
       }
    }

  // Social media links, Mailsubject
  if(!empty($_JiiQJ[$_QOifL["AltBrowserLink_SME"]])){

     $_JiiQJ['[AltBrowserLink_SME_URLEncoded]'] = urlencode($_JiiQJ['[AltBrowserLink_SME]']);

     $_JiiQJ['[Mail_Subject_ISO88591]'] = $_IQojt;
     $_JIO8t = ConvertString($_IQCoo, "ISO-8859-1", $_IQojt, false);
     if($_JIO8t != "")
        $_JiiQJ['[Mail_Subject_ISO88591]'] = $_JIO8t;
     $_JiiQJ['[Mail_Subject_ISO88591_URLEncoded]'] = urlencode($_JiiQJ['[Mail_Subject_ISO88591]']);

     $_JiiQJ['[Mail_Subject_UTF8]'] = $_IQojt;
     $_JIO8t = ConvertString($_IQCoo, "UTF-8", $_IQojt, false);
     if($_JIO8t != "")
        $_JiiQJ['[Mail_Subject_UTF8]'] = $_JIO8t;
     $_JiiQJ['[Mail_Subject_UTF8_URLEncoded]'] = urlencode($_JiiQJ['[Mail_Subject_UTF8]']);
  }
  // Social media links

  if($format == "html" || $format == "multipart") {

      $_JLIjo = "";
      if(isset($_j6ioL[$MailTemplate."MailPreHeaderText"]))
        $_JLIjo = htmlspecialchars( $_j6ioL[$MailTemplate."MailPreHeaderText"], ENT_COMPAT, $_IQCoo );

      $_IQ18l = $_j6ioL[$MailTemplate."MailHTMLText"];
      $_IQ18l = _L1ERL($_IiJo8, $MailingListId, $_IQ18l, $_IQCoo, true, $_JiiQJ);

      if(!empty($_JLIjo)){
        $_JLIjo = _L1ERL($_IiJo8, $MailingListId, $_JLIjo, $_IQCoo, true, $_JiiQJ);
        //$_JjflQ = explode('<body', $_IQ18l, 2);
        $_JjflQ = preg_split("/\<body/i", $_IQ18l, 2);
        if(count($_JjflQ) > 1 && strpos($_JjflQ[1], '>') !== false){
          $_I1t0l = strpos($_JjflQ[1], '>');
          $_JjflQ[1] = substr_replace($_JjflQ[1], sprintf($_jJ88O, $_JLIjo), $_I1t0l + 1, 0);
        }
        $_JjflQ = join('<body', $_JjflQ);
        $_IQ18l = $_JjflQ;
      }

    }
    else
    $_IQ18l = "";

  if($format == "text"){
         // target groups check
         $_IQ18l = $_j6ioL[$MailTemplate."MailHTMLText"];
         $_j1L1C = array();
         _LJO8O($_IQ18l, $_j1L1C);
         if(count($_j1L1C)){

            if(isset($_j6ioL["AutoCreateTextPart"]) && !$_j6ioL["AutoCreateTextPart"])
              $_IQitJ = _L1ERL($_IiJo8, $MailingListId, $_IQitJ, $_IQCoo, false, $_JiiQJ);
              else{
               $_IQ18l = _L1ERL($_IiJo8, $MailingListId, $_IQ18l, $_IQCoo, true, $_JiiQJ);
               $_IQitJ = _ODQAB($_IQ18l, $_IQCoo);
              }

         } else {
           $_IQitJ = _L1ERL($_IiJo8, $MailingListId, $_IQitJ, $_IQCoo, false, $_JiiQJ);
         }
         $_IQ18l = "";
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_IQojt, $_I0600, 'DISABLED', 'blank.htm', "", $_j6ioL[$MailTemplate."MailEncoding"]);

  if(!empty($attachments)){
    $_Q6ICj = '<table width="100%" border="0" cellspacing="1" cellpadding="1">'.$_Q6JJJ;


    // wildcard * in PersAttachments
    $_JLI6j = count($_j1i80);
    for($_Q6llo=0; $_Q6llo<$_JLI6j; $_Q6llo++) {

      if(strpos($_j1i80[$_Q6llo], '*') === false) continue;

      $_jt8LJ = $_j1i80[$_Q6llo];
      // there should be no visiblefilename
      $_JLIOO = "";
      $_Q6i6i = strpos($_jt8LJ, ";");
      if($_Q6i6i !== false){
        $_JLIOO = trim(substr($_jt8LJ, $_Q6i6i + 1));
        $_jt8LJ = substr($_jt8LJ, 0, $_Q6i6i);
      }

      $_jt8LJ = trim(_L1ERL($_IiJo8, $MailingListId, $_jt8LJ, $_IQCoo, false, $_JiiQJ));
      $_JLILQ = dirname($_QOCJo.$_jt8LJ);
      $_QllO8 = dirname($_jt8LJ);
      if($_QllO8 == ".")
        $_QllO8 = "";
      if($_QllO8 !== "")
        $_QllO8 = $_QllO8."/";
      $_JLIlt = opendir($_JLILQ);
      $_Q6LIL = array();
      while ($_JLIlt && $_Q6lfJ = readdir($_JLIlt)) {
        if(!is_dir($_JLILQ.'/'.$_Q6lfJ) && is_readable($_JLILQ.'/'.$_Q6lfJ)){
          $_Q6LIL[] = $_QllO8.$_Q6lfJ;
        }
      }
      if($_JLIlt)
        closedir($_JLIlt);

      if(count($_Q6LIL)){
        $_j1i80[$_Q6llo] = $_Q6LIL[0];
        for($_Q8otJ = 1; $_Q8otJ < count($_Q6LIL); $_Q8otJ++)
          $_j1i80[] = $_Q6LIL[$_Q8otJ];
      }
    }

    // Build PersAttachments
    for($_Q6llo=0; $_Q6llo<count($_j1i80); $_Q6llo++) {
      $_j1i80[$_Q6llo] = trim(_L1ERL($_IiJo8, $MailingListId, $_j1i80[$_Q6llo], $_IQCoo, false, $_JiiQJ));
      $_QfC8t = $_j1i80[$_Q6llo];
      $_Q6i6i = strpos($_QfC8t, ";");
      if($_Q6i6i !== false)
        $_QfC8t = substr($_QfC8t, 0, $_Q6i6i);
      if( empty($_QfC8t) || is_dir($_QOCJo.$_QfC8t) || !is_readable($_QOCJo.$_QfC8t)) # local filename check
        $_j1i80[$_Q6llo] = 'null';
    }

    // add pers attachments
    reset($_j1i80);
    foreach($_j1i80 as $key => $_Q6ClO){
      if($_Q6ClO <> 'null')
        $Attachments[] = $_Q6ClO;
    }

    $_jt8IL = $_jjCtI."file/";
    for($_Q6llo=0; $_Q6llo<count($Attachments); $_Q6llo++) {
      $_QfC8t = $Attachments[$_Q6llo];
      $_6tOoQ = $_QfC8t;
      $_Q6i6i = strpos($_QfC8t, ";");
      if($_Q6i6i !== false){
        $_6tOoQ = substr($_QfC8t, $_Q6i6i + 2);
        $_QfC8t = substr($_QfC8t, 0, $_Q6i6i);
      }
      if(strpos($_6tOoQ, "/") !== false)
        $_6tOoQ = basename($_6tOoQ);

      $Attachments[$_Q6llo] = '<td width="50%" align="center">'.'<a href="'.$_jt8IL. utf8_encode($_QfC8t).'" target="_blank"><img src="images/attach24.gif" width="24" height="24" border="0" alt="'.utf8_encode($_QfC8t).'" /><br />'.utf8_encode($_6tOoQ).'</a>'.'</td>'.$_Q6JJJ;
    }

    $_6toOi = false;
    for($_Q6llo=0; $_Q6llo<count($Attachments); $_Q6llo++) {
      if(!$_6toOi)
        $_Q6ICj .= "<tr>".$_Q6JJJ;
      $_Qf0Ct = 0;
      while($_Q6llo<count($Attachments) && $_Qf0Ct<2) {
        $_Q6ICj .= $Attachments[$_Q6llo++];
        $_Qf0Ct++;
        if($_Qf0Ct == 2) $_Q6llo--;
      }
      while($_Qf0Ct<2){
        $_Q6ICj .= '<td width="50%">'.'&nbsp;'.'</td>'.$_Q6JJJ;
        $_Qf0Ct++;
      }
      $_Q6ICj .= "</tr>".$_Q6JJJ;
      $_6toOi = false;
    }

    if($_6toOi)
      $_Q6ICj .= "</tr>".$_Q6JJJ;

    $_Q6ICj .= '</table>'.$_Q6JJJ;

    $_QJCJi = str_replace("<body>", "<body>".$_Q6ICj, $_QJCJi);
  }
  else
    if($format == "text") {
       $_QJCJi = str_replace("utf-8", $_IQCoo, $_QJCJi);
       if(strpos($_IQitJ, "\n") !== false)
          $_II1Ot = explode("\n", $_IQitJ);
          else
          $_II1Ot = explode("\r", $_IQitJ);

       $_QJCJi = str_replace("<body>", "<body>".join("<br>", $_II1Ot), $_QJCJi);
    } else {
      $_QJCJi = $_IQ18l;
      $_QJCJi = _OB8O0("<title>", "</title>", $_QJCJi, htmlspecialchars( $_IQojt, ENT_COMPAT, $_IQCoo, true ));
    }

  $_6ttIl = '<script language="JavaScript"><!--'.$_Q6JJJ.' function FixContentsLinks() { var A = document.getElementsByTagName("a"); for(var i=0; i<A.length; i++){ if(A[i].getAttribute("href") && A[i].getAttribute("href").indexOf("#") == 0) {A[i].setAttribute("target", "_self", 0);}}}'.$_Q6JJJ.' //--></script>';

  $_QJCJi = str_replace('</head>', '<base target="_blank">'.$_6ttIl.'</head>', $_QJCJi);
  $_IQojt = htmlspecialchars( $_IQojt, ENT_COMPAT, $_IQCoo, true );
  // JavaScript problems
  $_IQojt = str_replace("'", "\'", $_IQojt);
  $_IQojt = str_replace('"', '\"', $_IQojt);
  $_QJCJi = str_replace("<body", '<body onload="top.SetMailSubject(\''.$_IQojt.'\'); FixContentsLinks();" ', $_QJCJi);

  SetHTMLHeaders($_IQCoo);

  print $_QJCJi;

?>
