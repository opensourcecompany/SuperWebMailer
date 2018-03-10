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

  $MailTemplate = "";
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
  if(strpos($MailTemplate, " ") !== false || strpos($MailTemplate, '"') !== false || strpos($MailTemplate, "'") !== false)
     unset($MailTemplate);

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

  if(!isset($MailingListId) || !isset($_jfoCi) || !isset($MailTemplate) )
    exit;

  $MailTemplate = _OPQLR($MailTemplate);
  $MailTemplate = str_replace("'", "", $MailTemplate);
  $MailTemplate = str_replace('"', "", $MailTemplate);
  $_POST["MailingListId"] = $MailingListId;
  $_POST["FormId"] = $_jfoCi;
  $_POST["MailTemplate"] = $MailTemplate;

  // ** unset blank vars
  if(isset($ResponderType) && $ResponderType == "" )
    unset($ResponderType);
  if(isset($ResponderId) && $ResponderId == "" )
    unset($ResponderId);
  if(isset($ResponderMailItemId) && $ResponderMailItemId == "" )
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

  if(isset($ResponderType)) {
      $_POST["ResponderType"] = $ResponderType;
      $MailTemplate = "";
    }
  if(isset($ResponderId))
      $_POST["ResponderId"] = $ResponderId;
  if(isset($ResponderMailItemId))
    $_POST["ResponderMailItemId"] = $ResponderMailItemId;

  $_I0600 = "";
  //
  $_QJlJ0 = "SELECT MaillistTableName, FormsTableName, LocalBlocklistTableName, forms_id FROM $_Q60QL WHERE id=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    $_I0600 = $commonmsgMailListNotFound;
  } else {
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
  }
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];

  if(isset($ResponderType)) {
    $_jfoLl = $_jfoCi;
    $_jfoCi = $_Q6Q1C["forms_id"];
  }

  // Responder
  if(isset($_POST["ResponderId"])) {
    if(isset($ResponderType) && $ResponderType == "FollowUpResponder") {
      $_QJlJ0 = "SELECT FUMailsTableName FROM $_QCLCI WHERE id=$ResponderId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
        mysql_free_result($_Q60l1);
        $_ItJIf = $_Q6Q1C["FUMailsTableName"];
      } else {
        $_I0600 = $commonmsgHTMLFormNotFound;
        return;
      }
    }
  }
  // Responder

  if(!isset($ResponderType)) {
    //

    // check there is a attachment field
    $_6tt10 = "";
    $_QLLjo = array();
    _OAJL1($_QLI8o, $_QLLjo);
    if(in_array($MailTemplate."Attachments", $_QLLjo))
      $_6tt10 = ", ".$MailTemplate."Attachments";

    $_QJlJ0 = "SELECT $MailTemplate"."MailFormat, ".$MailTemplate."MailEncoding $_6tt10 FROM $_QLI8o WHERE id=$_jfoCi";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
  } else
   if($ResponderType == "AutoResponder" || $ResponderType == "BirthdayResponder" || $ResponderType == "EventResponder" || $ResponderType == "Campaign" || $ResponderType == "RSS2EMailResponder"){
    $_jj1tl = _OAP0L($ResponderType);
    $_Jfi0i = _OABJE($_jj1tl);
    if(empty($_Jfi0i) && $ResponderType == "AutoResponder"){
      $_Jfi0i = $_IQL81;
    }
    $_QJlJ0 = "SELECT `MailFormat`, `MailEncoding`, `Attachments`";
    if($ResponderType == "Campaign")
      $_QJlJ0 .= ", `PersAttachments`";
    $_QJlJ0 .= " FROM `$_Jfi0i` WHERE `id`=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
  } else
   if($ResponderType == "FollowUpResponder"){
    // FUResponder
    $_QJlJ0 = "SELECT `MailFormat`, `MailEncoding`, `Attachments`, `PersAttachments` FROM $_ItJIf WHERE id=$ResponderMailItemId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
  } else
   if($ResponderType == "SMSCampaign"){
      $_j6ioL["MailFormat"] = "PlainText";
      $_j6ioL["MailEncoding"] = "utf-8";
  } else
   if($ResponderType == "DistributionList"){
    $_QJlJ0 = "SELECT `MailFormat`, `MailEncoding`, `Attachments`, `DistribSenderEMailAddress` FROM $_Qoo8o WHERE id=$ResponderMailItemId AND DistribList_id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_j6ioL = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
  }

  // Count members
   if(isset($ResponderType) && $ResponderType == "Campaign") {
    include_once("campaignstools.inc.php");
    $_jQIIi = ""; // dummy
    $_Q6Q1C[0] = _O6OLL($ResponderId, $_jQIIi);
   }
   else
   if(isset($ResponderType) && $ResponderType == "SMSCampaign") {
      include_once("smscampaignstools.inc.php");
      $_jQIIi = ""; // dummy
      $_Q6Q1C[0] = _LOBEC($ResponderId, $_jQIIi);
   }
   else
   if(isset($ResponderType) && $ResponderType == "DistributionList") {
      include_once("distribliststools.inc.php");
      $_jQIIi = ""; // dummy
      $_Q6Q1C[0] = _O8RPL($ResponderId, $_j6ioL["DistribSenderEMailAddress"], $_jQIIi);
   }
   else
   {
    $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
    $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
    $_IOQf6 = " `$_QlQC8`.IsActive=1 AND `$_QlQC8`.SubscriptionStatus<>'OptInConfirmationPending'".$_Q6JJJ;
    $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

    $_QJlJ0 = "SELECT COUNT(*) FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    _OAL8F($_QJlJ0);
    mysql_free_result($_Q60l1);
   }

  if(!isset($_POST["CurrentMail"]) )
     $_POST["CurrentMail"] = 0;
  $CurrentMail = intval($_POST["CurrentMail"]);

  $_6ttIl = "";
  if(isset($_POST["OnePreviewListAction"]))
    switch($_POST["OnePreviewListAction"]) {
      case "FirstMailBtn":
          $CurrentMail = 0;
          break;
      case "LastMailBtn":
          $CurrentMail = $_Q6Q1C[0] - 1;
          break;
      case "PrevMailBtn":
          $CurrentMail--;
          break;
      case "NextMailBtn":
          $CurrentMail++;
          break;
    }

  if($CurrentMail < 0)
     $CurrentMail = 0;
  if($CurrentMail > $_Q6Q1C[0])
     $CurrentMail = $_Q6Q1C[0] - 1;

  if($CurrentMail == 0) {
    $_6ttIl .= 'DisableItemCursorPointer("FirstMailBtn", false);'.$_Q6JJJ;
    $_6ttIl .= 'DisableItemCursorPointer("PrevMailBtn", false);'.$_Q6JJJ;

    $_6ttIl .= 'ChangeImage("FirstMailBtn", "images/blind16x16.gif");'.$_Q6JJJ;
    $_6ttIl .= 'ChangeImage("PrevMailBtn", "images/blind16x16.gif");'.$_Q6JJJ;

  }
  if($CurrentMail >= $_Q6Q1C[0] - 1) {
    $_6ttIl .= 'DisableItemCursorPointer("NextMailBtn", false);'.$_Q6JJJ;
    $_6ttIl .= 'DisableItemCursorPointer("LastMailBtn", false);'.$_Q6JJJ;
    $_6ttIl .= 'ChangeImage("NextMailBtn", "images/blind16x16.gif");'.$_Q6JJJ;
    $_6ttIl .= 'ChangeImage("LastMailBtn", "images/blind16x16.gif");'.$_Q6JJJ;
  }
  $_POST["CurrentMail"] = $CurrentMail;

  // Template
  if(isset($ResponderType) && $ResponderType == "SMSCampaign") {
     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000201"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].$resourcestrings[$INTERFACE_LANGUAGE]["SMSCount"], $_I0600, 'DISABLED', 'serialmailpreviewframe.htm', "", $_j6ioL[$MailTemplate."MailEncoding"]);
     $_QJCJi = str_replace('%TabCaptionEMailAsHTML%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionSMSAsPlainText"], $_QJCJi);
     $_QJCJi = str_replace('%TabCaptionEMailAsPlainText%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionSMSAsPlainText"], $_QJCJi);
    }
  else {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000200"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].$resourcestrings[$INTERFACE_LANGUAGE]["EMailCount"], $_I0600, 'DISABLED', 'serialmailpreviewframe.htm', "", $_j6ioL[$MailTemplate."MailEncoding"]);
    $_QJCJi = str_replace('%TabCaptionEMailAsHTML%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionEMailAsHTML"], $_QJCJi);
    $_QJCJi = str_replace('%TabCaptionEMailAsPlainText%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionEMailAsPlainText"], $_QJCJi);
    $_QJCJi = str_replace('%TabCaptionEMailAttachments%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionEMailAttachments"], $_QJCJi);
  }

  $_QffOf = "&MailingListId=$MailingListId&FormId=$_jfoCi&MailTemplate=$MailTemplate&CurrentMail=$CurrentMail&nocache=".md5(date("r"));

  if(isset($ResponderType)) {
    $_QffOf .= "&ResponderType=$ResponderType&ResponderId=$ResponderId";
    if(isset($ResponderMailItemId))
      $_QffOf .= "&ResponderMailItemId=$ResponderMailItemId";
  } else {
    // forms can only be created in HTML and plain text therefore HTML is multipart
    if($_j6ioL[$MailTemplate."MailFormat"] == "HTML")
      $_j6ioL[$MailTemplate."MailFormat"] = "Multipart";
  }

  if ($_j6ioL[$MailTemplate."MailFormat"] == "HTML")
    $_QJCJi = _OP6PQ($_QJCJi, "<if:TEXT>", "</if:TEXT>");
  else
    $_6ttIl .= "textiframe.src='./serialmailpreviewitem.php?format=text$_QffOf';".$_Q6JJJ;

  if($_j6ioL[$MailTemplate."MailFormat"] == "HTML" || $_j6ioL[$MailTemplate."MailFormat"] == "Multipart") {
    $_6ttIl .= "htmliframe.src='./serialmailpreviewitem.php?format=".strtolower($_j6ioL[$MailTemplate."MailFormat"])."$_QffOf';".$_Q6JJJ;
    }
    else {
      $_QJCJi = _OP6PQ($_QJCJi, "<if:HTML>", "</if:HTML>");
    }

  if(!empty($_j6ioL[$MailTemplate."Attachments"])){
    $_j6ioL[$MailTemplate."Attachments"] = @unserialize($_j6ioL[$MailTemplate."Attachments"]);
    if($_j6ioL[$MailTemplate."Attachments"] === false)
       $_j6ioL[$MailTemplate."Attachments"] = array();
  }

  if(!empty($_j6ioL[$MailTemplate."PersAttachments"])){
    $_j6ioL[$MailTemplate."PersAttachments"] = @unserialize($_j6ioL[$MailTemplate."PersAttachments"]);
    if($_j6ioL[$MailTemplate."PersAttachments"] === false)
       $_j6ioL[$MailTemplate."PersAttachments"] = array();
  }

  $_II1Ot = empty($_j6ioL[$MailTemplate."Attachments"]) || !is_array($_j6ioL[$MailTemplate."Attachments"]) || count($_j6ioL[$MailTemplate."Attachments"]) == 0;
  $_II1Ot = $_II1Ot && (empty($_j6ioL[$MailTemplate."PersAttachments"]) || !is_array($_j6ioL[$MailTemplate."PersAttachments"]) || count($_j6ioL[$MailTemplate."PersAttachments"]) == 0);

  if($_II1Ot)
     $_QJCJi = _OP6PQ($_QJCJi, "<if:ATTACHMENTS>", "</if:ATTACHMENTS>");
     else {
       $_QJCJi = str_replace("<if:ATTACHMENTS>", "", $_QJCJi);
       $_QJCJi = str_replace("</if:ATTACHMENTS>", "", $_QJCJi);
       $_6ttIl .= "attachmentsiframe.src='./serialmailpreviewitem.php?format=text&attachments=show"."$_QffOf';".$_Q6JJJ;
     }

  $_QJCJi = str_replace("<if:HTML>", "", $_QJCJi);
  $_QJCJi = str_replace("</if:HTML>", "", $_QJCJi);
  $_QJCJi = str_replace("<if:TEXT>", "", $_QJCJi);
  $_QJCJi = str_replace("</if:TEXT>", "", $_QJCJi);

  $_POST["RecipientsCount"] = $_Q6Q1C[0];
  $_QJCJi = str_replace("%RECIPIENTCOUNT%", $_Q6Q1C[0], $_QJCJi);
  $_QJCJi = str_replace("%EMAILCOUNT%", $CurrentMail + 1, $_QJCJi);

  if(isset($_POST["OnePreviewListAction"]))
    unset($_POST["OnePreviewListAction"]);

  if(isset($_POST["OnePreviewListId"]))
    unset($_POST["OnePreviewListId"]);

  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

  $_QJCJi = str_replace("//AUTO_SCRIPT_CODE_PLACEHOLDER//", $_6ttIl, $_QJCJi);

  $_QJCJi = SetHTMLCharSet($_QJCJi, $_j6ioL[$MailTemplate."MailEncoding"], true);

  $_QJCJi = str_replace("Subject:", $resourcestrings[$INTERFACE_LANGUAGE]["Subject"].":", $_QJCJi);

  print $_QJCJi;

?>
